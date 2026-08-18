<?php $flashes = pull_flashes(); ?>
<div class="admin-app admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-top">
            <a class="admin-brand" href="/admin/dashboard">
                <span class="brand-mark">
                    <img src="<?= h(asset('img/logo.png')) ?>" alt="Logo do Pelicano Lava-Jato JF">
                </span>
                <span class="brand-copy">
                    <strong>Pelicano</strong>
                    <em>Admin</em>
                </span>
            </a>
            <button class="menu-toggle admin-menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-label="Abrir menu">
                ☰
            </button>
        </div>
        <div class="admin-sidebar-panel" data-menu>
            <p class="admin-sidebar-copy">Painel do cliente para organizar agendamentos, emitir OS e atualizar os dados exibidos no site.</p>
            <nav class="admin-nav">
            <a class="nav-dashboard" href="/admin/dashboard">Dashboard</a>
            <a class="nav-schedules" href="/admin/agendamentos">Agendamentos e OS</a>
            <a class="nav-clients" href="/admin/clientes">Clientes</a>
            <a class="nav-company" href="/admin/empresa">Dados da empresa</a>
            <a class="nav-site" href="/" target="_blank" rel="noreferrer">Ver site</a>
            <a class="nav-logout" href="/admin/logout">Sair</a>
            </nav>
        </div>
    </aside>
    <div class="admin-main">
        <?php if ($flashes): ?>
            <div class="flash-stack">
                <?php foreach ($flashes as $flash): ?>
                    <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>
