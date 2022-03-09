<?php
include_once 'config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['password']) || empty($_POST['email'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Forbidden']);
    die();
}

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);
$userData = $user->findByEmail($_POST['email']);

if (empty($userData) || !password_verify($_POST['password'], $userData['password'])) {
    echo json_encode(['success' => false, 'error' => 'Undefined user or password']);
    die();
}

$jwt = Jwt::encodeByData($userData);
echo json_encode(['success' => true, 'jwt' => $jwt]);

