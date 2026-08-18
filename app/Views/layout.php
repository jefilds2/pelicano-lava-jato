<?php
$pageTitle = $pageTitle ?? $title ?? 'Pelicano Lava-Jato JF';
$metaDescription = $metaDescription ?? 'Estética automotiva e lava-jato em Guanhães - MG.';
$flashes = pull_flashes();
$bodyClass = $bodyClass ?? '';
$extraHead = $extraHead ?? '';
$extraScripts = $extraScripts ?? '';
$useBaseCss = $useBaseCss ?? true;
$favicon = asset('img/logo.png');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <meta name="description" content="<?= h($metaDescription) ?>">
    <link rel="icon" href="<?= h($favicon) ?>" type="image/png">
    <?php if ($useBaseCss): ?>
        <link rel="stylesheet" href="<?= h(asset('css/app.css')) ?>">
    <?php endif; ?>
    <?= $extraHead ?>
</head>
<body class="<?= h($bodyClass) ?>">
<?php if (str_starts_with($_SERVER['REQUEST_URI'] ?? '/', '/admin')): ?>
    <?php require __DIR__ . '/partials/admin-shell.php'; ?>
<?php else: ?>
    <?php if ($flashes): ?>
        <div class="flash-stack">
            <?php foreach ($flashes as $flash): ?>
                <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
<?php endif; ?>
<?php if ($useBaseCss): ?>
    <script src="<?= h(asset('js/app.js')) ?>"></script>
<?php else: ?>
    <script src="<?= h(asset('js/app.js')) ?>"></script>
<?php endif; ?>
<?= $extraScripts ?>
</body>
</html>
