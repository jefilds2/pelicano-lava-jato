<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title ?? 'Impressão') ?></title>
    <link rel="stylesheet" href="<?= h(asset('css/app.css')) ?>">
</head>
<body class="print-page">
<?= $content ?>
</body>
</html>
