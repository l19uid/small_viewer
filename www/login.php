<?php
require_once "../bootstrap/bootstrap.php";
session_start();

$username = "";
$password = "";
$loginSuccess = false;
$hash = "";

readPost();
$loginSuccess = login();

if ($loginSuccess) {
    header("Location: index.php");
    exit;
} else {
    $_SESSION['loginError'] = "Špatné jméno nebo heslo";
    header("Location: index.php");
    exit;
}

function readPost(): void
{
    global $username, $hash;

    $username = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
    $hash = hash('sha256', $password);
}

function login(): bool
{
    global $username, $hash;

    if(!$username || !$hash)
        return false;

    $employee = Employee::findLoginHash($username, $hash);

    if (!$employee)
        return false;

    $_SESSION['employee'] = $employee;

    return true;
}