<?php
include_once 'config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    empty($_POST['countryCode']) ||
    empty($_POST['vatNumber']) ||
    empty($_POST['jwt'])) {
    echo json_encode(['success' => false, 'error' => 'error response']);
    die();
}

$userData = Jwt::decode($_POST['jwt']);
if (empty($userData)) {
    echo json_encode(['success' => false, 'error' => 'Error jwt token']);
    die();
}
$vies = new Vies();
$check = $vies->checkVat($_POST['countryCode'], $_POST['vatNumber']);
if ($check->valid === true) {
    echo json_encode(['success' => true, 'valid' => true]);
    $database = new Database();
    $db = $database->getConnection();
    $user = new Users($db);
    $user->updatePhoneUser($userData->data->id, $_POST['vatNumber']);
    die();
}

echo json_encode(['success' => true, 'valid' => false]);