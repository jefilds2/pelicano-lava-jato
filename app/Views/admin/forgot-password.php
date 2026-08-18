<section class="auth-card admin-card">
    <span class="pill subtle">Recuperação de acesso</span>
    <h1>Gerar link de redefinição</h1>
    <p>Em ambiente local, o sistema exibe o link de redefinição em tela enquanto o SMTP não estiver configurado.</p>
    <form method="post" class="form-grid">
        <?= csrf_field() ?>
        <label>
            <span>E-mail cadastrado</span>
            <input type="email" name="email" value="<?= h(old('email')) ?>" required>
        </label>
        <button class="button button-success" type="submit">Gerar link</button>
    </form>
    <div style="margin-top: 16px;">
        <a href="/admin/login"
            style="display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 18px; border-radius: 999px; border: 1px solid rgba(56, 189, 248, 0.35); background: rgba(14, 165, 233, 0.12); color: #38bdf8; font-weight: 700; text-decoration: none;">
            Voltar ao login
        </a>
    </div>
</section>
