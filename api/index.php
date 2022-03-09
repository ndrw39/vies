<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

if (empty($_GET['method'])) {
    http_response_code(403);
    echo json_encode(['success' => true, 'error' => 'empty parameter "method"']);
    die();
}

echo call_user_func(['\\Api\\Controllers\\' . $_GET['method'], 'index'], (object)$_REQUEST);


?>