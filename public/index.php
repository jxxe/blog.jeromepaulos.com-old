<?php

require_once '../private/App/functions.php';

header_remove('X-Powered-By');
session_start(['name' => 'session']);

if(should_cache()) {
    $cached_html = apcu_fetch($_SERVER['REQUEST_URI']);
    if($cached_html) exit($cached_html);
}

require_once '../vendor/autoload.php';

use Blog\App\ConfigParser;
use Blog\App\Paths;
use Bramus\Router\Router;

ConfigParser::load(Paths::CONFIG);
date_default_timezone_set($_ENV['TIME_ZONE']);

$router = new Router();

$router->get('/', 'Blog\Controllers\HomeController::index');
$router->get('/posts/{slug}', '\Blog\Controllers\SingleController::index');
$router->post('/posts/{slug}', '\Blog\Controllers\SingleController::POST_comment');

$router->get('/posts', '\Blog\Controllers\ArchiveController::index');
$router->get('/tutorials', '\Blog\Controllers\TutorialArchiveController::index');

$router->get('/login', '\Blog\Controllers\AuthController::login_page');
$router->post('/login', '\Blog\Controllers\AuthController::POST_login');
$router->get('/logout', '\Blog\Controllers\AuthController::logout');

$router->before('GET|POST', '/admin(\/.*|)', '\Blog\Controllers\AuthController::guard');

$router->match('GET|POST', '/admin', '\Blog\Controllers\AdminController::index');
$router->post('/admin/upload', '\Blog\Controllers\ImageUploadController::POST_image');
$router->get('/admin/{slug}', '\Blog\Controllers\AdminController::edit_post');
$router->post('/admin/{slug}', '\Blog\Controllers\AdminController::POST_save_post');

$router->set404('\Blog\App\RespondWith::error_page');

try {
    ob_start();
    $router->run();
    $html = ob_get_clean();
    if(should_cache()) apcu_add($_SERVER['REQUEST_URI'], $html, 60*30);
    echo $html;
} catch(Exception) {
    ob_end_clean();
    view('error', code: 500);
}