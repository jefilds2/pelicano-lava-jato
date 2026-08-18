<?php

declare(strict_types=1);

function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return (string) $value;
}

function config(): array
{
    static $config;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'app_name' => 'Pelicano Lava-Jato JF',
        'app_url' => env('APP_URL', 'http://localhost:8080'),
        'db' => [
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'name' => env('DB_NAME', 'pelicano_lava_jato'),
            'user' => env('DB_USER', ''),
            'pass' => env('DB_PASS', ''),
        ],
        'mail' => [
            'enabled' => env('SMTP_ENABLED', '0') === '1',
            'from_name' => env('MAIL_FROM_NAME', 'Pelicano Lava-Jato JF'),
            'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@pelicanolava-jato.local'),
        ],
        'smtp' => [
            'host' => env('SMTP_HOST', 'smtp.gmail.com'),
            'port' => (int) env('SMTP_PORT', '587'),
            'user' => env('SMTP_USER', ''),
            'pass' => env('SMTP_PASS', ''),
            'secure' => env('SMTP_SECURE', 'tls'),
        ],
    ];

    $configFile = dirname(__DIR__) . '/config.php';

    if (is_file($configFile)) {
        $fileConfig = require $configFile;

        if (is_array($fileConfig)) {
            $config = array_replace_recursive($config, $fileConfig);
        }
    }

    return $config;
}

function db(): PDO
{
    return Database::connection();
}

function asset(string $path): string
{
    $relativePath = ltrim($path, '/');
    $url = '/public/assets/' . $relativePath;
    $absolutePath = dirname(__DIR__) . '/public/assets/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (is_file($absolutePath)) {
        return $url . '?v=' . filemtime($absolutePath);
    }

    return $url;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = compact('type', 'message');
}

function pull_flashes(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);

    return $messages;
}

function store_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . h(csrf_token()) . '">';
}

function verify_csrf(): void
{
    if (request_method() !== 'POST') {
        return;
    }

    $token = $_POST['_csrf'] ?? '';
    $sessionToken = $_SESSION['_csrf'] ?? '';

    if ($sessionToken === '' || $token === '' || !hash_equals($sessionToken, $token)) {
        flash('error', 'Sua sessão expirou. Atualize a página e envie o formulário novamente.');

        $redirectTo = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        redirect($redirectTo);
    }
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('error', 'Faça login para acessar o painel.');
        redirect('/admin/login');
    }
}

function render(string $view, array $data = [], string $layout = 'layout'): void
{
    extract($data, EXTR_SKIP);
    $viewPath = __DIR__ . '/Views/' . $view . '.php';
    $layoutPath = __DIR__ . '/Views/' . $layout . '.php';

    ob_start();
    require $viewPath;
    $content = ob_get_clean();

    require $layoutPath;
}

function slug_phone(string $value): string
{
    return preg_replace('/\D+/', '', $value) ?? '';
}

function format_money(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function normalize_money_input(string $value): float
{
    $normalized = preg_replace('/[^\d,.-]/u', '', trim($value)) ?? '';
    $normalized = str_replace('.', '', $normalized);
    $normalized = str_replace(',', '.', $normalized);

    return round((float) $normalized, 2);
}

function format_date_br(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '-';
    }

    $timestamp = strtotime($value);

    if ($timestamp === false) {
        return (string) $value;
    }

    return date('d/m/Y', $timestamp);
}

function format_datetime_br(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '-';
    }

    $timestamp = strtotime($value);

    if ($timestamp === false) {
        return (string) $value;
    }

    return date('d/m/Y H:i', $timestamp);
}

function company_data(): array
{
    $stmt = db()->query('SELECT * FROM company LIMIT 1');
    return $stmt->fetch() ?: [];
}

function services_data(): array
{
    $services = db()->query('SELECT * FROM services WHERE active = 1 ORDER BY id ASC')->fetchAll();

    if ($services === []) {
        return default_services_catalog();
    }

    return $services;
}

function default_services_catalog(): array
{
    return [
        ['id' => 1, 'name' => 'Lavagem Simples', 'description' => 'Lavagem externa com produtos de qualidade para manter seu carro sempre limpo.', 'base_price' => 60.00, 'active' => 1],
        ['id' => 2, 'name' => 'Lavagem Detalhada', 'description' => 'Lavagem completa com atenção a cada detalhe do seu veículo.', 'base_price' => 85.00, 'active' => 1],
        ['id' => 3, 'name' => 'Limpeza Interna', 'description' => 'Limpeza completa do interior do veículo, deixando tudo impecável.', 'base_price' => 85.00, 'active' => 1],
        ['id' => 4, 'name' => 'Limpeza Externa', 'description' => 'Lavagem externa detalhada com proteção e brilho para sua lataria.', 'base_price' => 70.00, 'active' => 1],
        ['id' => 5, 'name' => 'Higienização de Bancos', 'description' => 'Remoção de manchas e bactérias, deixando os bancos como novos.', 'base_price' => 120.00, 'active' => 1],
        ['id' => 6, 'name' => 'Limpeza de Teto', 'description' => 'Limpeza profunda do teto do veículo, removendo marcas e odores.', 'base_price' => 75.00, 'active' => 1],
        ['id' => 7, 'name' => 'Limpeza de Motor', 'description' => 'Limpeza segura do compartimento do motor, preservando componentes.', 'base_price' => 110.00, 'active' => 1],
        ['id' => 8, 'name' => 'Limpeza de Chassi', 'description' => 'Limpeza completa do chassi e subsolo, prevenindo corrosão.', 'base_price' => 130.00, 'active' => 1],
        ['id' => 9, 'name' => 'Enceramento Técnico', 'description' => 'Proteção e brilho duradouros para a pintura do seu veículo.', 'base_price' => 95.00, 'active' => 1],
        ['id' => 10, 'name' => 'Polimento Automotivo', 'description' => 'Remoção de riscos e restauração do brilho original da pintura.', 'base_price' => 180.00, 'active' => 1],
        ['id' => 11, 'name' => 'Polimento de Faróis', 'description' => 'Restauração da transparência dos faróis para melhor visibilidade.', 'base_price' => 95.00, 'active' => 1],
        ['id' => 12, 'name' => 'Hidratação de Couro', 'description' => 'Hidratação profunda para manter o couro macio e protegido.', 'base_price' => 90.00, 'active' => 1],
        ['id' => 13, 'name' => 'Lavagem de Carpete Veicular', 'description' => 'Lavagem profunda do carpete automotivo para remover sujeira, odores e resíduos acumulados.', 'base_price' => 140.00, 'active' => 1],
        ['id' => 14, 'name' => 'Remoção de Adesivos', 'description' => 'Remoção cuidadosa de adesivos e resíduos de cola sem agredir a pintura do veículo.', 'base_price' => 50.00, 'active' => 1],
    ];
}

function ensure_default_services_catalog(): void
{
    $rows = db()->query('SELECT id, name FROM services ORDER BY id ASC')->fetchAll();
    $existingIds = array_map(static fn(array $row): int => (int) $row['id'], $rows);
    $missingServices = array_values(array_filter(
        default_services_catalog(),
        static fn(array $service): bool => !in_array((int) $service['id'], $existingIds, true)
    ));

    if ($missingServices === []) {
        return;
    }

    db()->beginTransaction();

    try {
        $stmt = db()->prepare(
            'INSERT INTO services (id, name, description, base_price, active)
             VALUES (:id, :name, :description, :base_price, :active)'
        );

        foreach ($missingServices as $service) {
            $stmt->execute($service);
        }

        db()->commit();
    } catch (Throwable $exception) {
        db()->rollBack();
        throw $exception;
    }
}

function site_media(): array
{
    return [
        'hero_video' => '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/video%20capa.mp4',
        'hero_images' => [
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/lavagem-carro-guanhaes.avif',
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/estetica-automotiva-guanhaes.avif',
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/lavagem-detalhada-carro-guanhaes.avif',
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/polimento-automotivo-guanhaes.avif',
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/higienizacao-automotiva-guanhaes.avif',
            '/Imagem%20e%20V%C3%ADdeos%20veiculos%20de%20site/lava-jato-pelicano-guanhaes.avif',
        ],
        'real_videos' => [
            '/Imagens%20e%20V%C3%ADdeos%20Reais%20do%20lava-jato/antes%20e%20depois%20da%20lavagem.mp4',
            '/Imagens%20e%20V%C3%ADdeos%20Reais%20do%20lava-jato/Antes%20e%20Depois%20lavagem%20motor.mp4',
            '/Imagens%20e%20V%C3%ADdeos%20Reais%20do%20lava-jato/Carro%20Limpo%20top.mp4',
        ],
    ];
}
