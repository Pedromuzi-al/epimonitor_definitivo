<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Este arquivo permite emular a funcionalidade "mod_rewrite" do Apache usando o
// servidor web PHP integrado. Isto oferece uma forma conveniente de testar uma aplicação
// Laravel sem ter instalado um software "real" de servidor web aqui.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
