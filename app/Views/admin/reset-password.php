<?php $token = $_GET['token'] ?? old('token'); ?>
<section class="auth-card admin-card">
    <span class="pill subtle">Nova senha</span>
    <h1>Redefinir senha</h1>
    <form method="post" class="form-grid">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= h($token) ?>">
        <label>
            <span>Nova senha</span>
            <div class="password-field">
                <input type="password" name="password" data-password-input required>
                <button class="password-toggle" type="button" data-password-toggle aria-label="Mostrar senha">👁</button>
            </div>
        </label>
        <label>
            <span>Confirmar nova senha</span>
            <div class="password-field">
                <input type="password" name="password_confirmation" data-password-input required>
                <button class="password-toggle" type="button" data-password-toggle aria-label="Mostrar senha">👁</button>
            </div>
        </label>
        <button class="button button-success" type="submit">Salvar nova senha</button>
    </form>
    <div style="margin-top: 16px;">
        <a href="/admin/login"
            style="display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 18px; border-radius: 999px; border: 1px solid rgba(56, 189, 248, 0.35); background: rgba(14, 165, 233, 0.12); color: #38bdf8; font-weight: 700; text-decoration: none;">
            Voltar ao login
        </a>
    </div>
</section>

<script>
    (() => {
        document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
            const input = toggle.parentElement?.querySelector('[data-password-input]');

            if (!input) {
                return;
            }

            toggle.addEventListener('click', () => {
                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                toggle.setAttribute('aria-label', showing ? 'Mostrar senha' : 'Ocultar senha');
                toggle.textContent = showing ? '👁' : '🙈';
            });
        });
    })();
</script>
