<?php
require_once "../bootstrap/bootstrap.php";

$username = "";
$password = "";
$loginSuccess = false;

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
    global $username, $password;

    $username = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
}

function login(): bool
{
    global $username, $password;

    $employees = Employee::all();
    foreach ($employees as $employee) {
        if ($employee->username === $username && $employee->password === $password) {
            $_SESSION['employee'] = $employee;
            return true;
        }
    }

    return false;
}