<?php
require "user.php";
require "exceldoc.php";
session_start();
if(array_key_exists('registerBtn', $_POST)) {
    $user = new user($_POST['email'], $_POST['password']);

    try {
        $user->validate();
    } catch (Exception $e) {
        redirectToHome($e->getMessage());
    }

    $user->register();
} else if (array_key_exists('loginBtn', $_POST)) {
    $user = new user($_POST['email'], $_POST['password']);
    try {
        $user->login();
    } catch (Exception $e) {
        redirectToHome($e->getMessage());
    }

} else if (array_key_exists('logOutBtn', $_POST)) {
    unset($_SESSION['userId']);
    unset($_SESSION['userEmail']);
} else if(array_key_exists('userExportExcelBtn', $_POST)) {
    $doc = new exceldoc();
    $user = new user(null, null);
    try {
        $user->getUser($_SESSION['userId']);
    } catch (Exception $e){
        redirectToHome($e->getMessage());
    }
    $usersList = $user->getAllUsers();
    $doc->exportUsers($usersList);
}
redirectToHome();
function redirectToHome($errorMessage = '') {
    if (!empty($errorMessage)) {
        $_SESSION['errorMessage'] = $errorMessage;
    }

    header('Location: http://' . $_SERVER['HTTP_HOST']);
    exit;
}
