<?php
$flashes = pull_flashes();
$favicon = asset('img/logo.png');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title ?? 'Acesso administrativo') ?></title>
    <link rel="icon" href="<?= h($favicon) ?>" type="image/png">
    <link rel="stylesheet" href="<?= h(asset('css/app.css')) ?>">
</head>
<body class="auth-page admin-theme">
    <main class="auth-shell">
        <a href="/" class="auth-brand" aria-label="Pelicano Lava-Jato JF - início">
            <img src="<?= h(asset('img/logo.png')) ?>" alt="Logo do Pelicano Lava-Jato JF">
        </a>
        <?php if ($flashes): ?>
            <div class="flash-stack flash-stack-inline">
                <?php foreach ($flashes as $flash): ?>
                    <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>
</body>
</html>
