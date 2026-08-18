<?php

declare(strict_types=1);

final class Mailer
{
    public static function sendResetLink(string $toEmail, string $resetLink): void
    {
        $config = config();
        $subject = 'Redefinição de senha - Pelicano Lava-Jato JF';
        $body = implode("\r\n", [
            'Olá,',
            '',
            'Recebemos uma solicitação para redefinir a senha do painel administrativo.',
            'Use o link abaixo para criar uma nova senha:',
            $resetLink,
            '',
            'Se você não solicitou essa alteração, ignore este e-mail.',
        ]);

        self::send(
            $toEmail,
            $subject,
            $body,
            $config['mail']['from_address'],
            $config['mail']['from_name']
        );
    }

    public static function send(string $toEmail, string $subject, string $body, string $fromEmail, string $fromName): void
    {
        $config = config();
        $smtp = $config['smtp'];

        if (!$config['mail']['enabled']) {
            throw new RuntimeException('SMTP desativado.');
        }

        $socket = @stream_socket_client(
            'tcp://' . $smtp['host'] . ':' . $smtp['port'],
            $errorNumber,
            $errorMessage,
            20
        );

        if (!$socket) {
            throw new RuntimeException('Falha ao conectar no SMTP: ' . $errorMessage);
        }

        stream_set_timeout($socket, 20);

        try {
            self::expect($socket, [220]);
            self::command($socket, 'EHLO pelicano.local', [250]);

            if ($smtp['secure'] === 'tls') {
                self::command($socket, 'STARTTLS', [220]);

                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new RuntimeException('Falha ao iniciar TLS com o SMTP.');
                }

                self::command($socket, 'EHLO pelicano.local', [250]);
            }

            self::command($socket, 'AUTH LOGIN', [334]);
            self::command($socket, base64_encode($smtp['user']), [334]);
            self::command($socket, base64_encode($smtp['pass']), [235]);
            self::command($socket, 'MAIL FROM:<' . $fromEmail . '>', [250]);
            self::command($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
            self::command($socket, 'DATA', [354]);

            $headers = [
                'From: ' . self::formatAddress($fromName, $fromEmail),
                'To: ' . $toEmail,
                'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=',
                'MIME-Version: 1.0',
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
            ];

            $message = implode("\r\n", $headers) . "\r\n\r\n" . self::escapeBody($body) . "\r\n.";
            self::command($socket, $message, [250]);
            self::command($socket, 'QUIT', [221]);
        } finally {
            fclose($socket);
        }
    }

    private static function command($socket, string $command, array $expectedCodes): string
    {
        fwrite($socket, $command . "\r\n");

        return self::expect($socket, $expectedCodes);
    }

    private static function expect($socket, array $expectedCodes): string
    {
        $response = '';

        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;

            if (strlen($line) >= 4 && $line[3] === ' ') {
                break;
            }
        }

        $code = (int) substr($response, 0, 3);

        if (!in_array($code, $expectedCodes, true)) {
            throw new RuntimeException('Resposta SMTP inesperada: ' . trim($response));
        }

        return $response;
    }

    private static function formatAddress(string $name, string $email): string
    {
        return sprintf('=?UTF-8?B?%s?= <%s>', base64_encode($name), $email);
    }

    private static function escapeBody(string $body): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $body);
        $normalized = preg_replace('/^\./m', '..', $normalized) ?? $normalized;

        return str_replace("\n", "\r\n", $normalized);
    }
}
