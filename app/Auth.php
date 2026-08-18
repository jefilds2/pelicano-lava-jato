<?php

declare(strict_types=1);

final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $stmt = db()->prepare('SELECT * FROM admins WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return false;
        }

        $_SESSION['admin_id'] = (int) $admin['id'];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_id']);
        session_regenerate_id(true);
    }

    public static function createResetToken(string $email): ?string
    {
        $stmt = db()->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            return null;
        }

        $token = bin2hex(random_bytes(24));

        $insert = db()->prepare(
            'INSERT INTO password_reset_tokens (admin_id, token, expires_at)
             VALUES (:admin_id, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))'
        );
        $insert->execute([
            'admin_id' => $admin['id'],
            'token' => $token,
        ]);

        return $token;
    }

    public static function resetPassword(string $token, string $password): bool
    {
        $stmt = db()->prepare(
            'SELECT * FROM password_reset_tokens WHERE token = :token AND used_at IS NULL AND expires_at >= NOW() LIMIT 1'
        );
        $stmt->execute(['token' => $token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            return false;
        }

        db()->beginTransaction();

        try {
            $updateAdmin = db()->prepare('UPDATE admins SET password_hash = :hash WHERE id = :id');
            $updateAdmin->execute([
                'hash' => password_hash($password, PASSWORD_DEFAULT),
                'id' => $reset['admin_id'],
            ]);

            $markToken = db()->prepare('UPDATE password_reset_tokens SET used_at = NOW() WHERE id = :id');
            $markToken->execute(['id' => $reset['id']]);

            db()->commit();
            return true;
        } catch (Throwable $exception) {
            db()->rollBack();
            throw $exception;
        }
    }
}
