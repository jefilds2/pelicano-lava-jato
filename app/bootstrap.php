<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(env('SESSION_NAME', 'pelicano_session'));
    session_start();
}

date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Mailer.php';
