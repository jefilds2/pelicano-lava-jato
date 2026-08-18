<?php

declare(strict_types=1);

return [
    'app_url' => 'http://localhost:8080',
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'pelicano_lava_jato',
        'user' => 'pelicano_app',
        'pass' => '',
    ],
    'mail' => [
        'enabled' => false,
        'from_name' => 'Pelicano Lava-Jato JF',
        'from_address' => 'no-reply@example.com',
    ],
    'smtp' => [
        'host' => 'smtp.example.com',
        'port' => 587,
        'user' => '',
        'pass' => '',
        'secure' => 'tls',
    ],
];
