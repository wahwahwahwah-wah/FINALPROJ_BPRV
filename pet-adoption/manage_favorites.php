<?php
session_start();

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

if (isset($_GET['id'])) {
    $pet_id = (int)$_GET['id'];

    if (in_array($pet_id, $_SESSION['favorites'])) {
        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$pet_id]);
    } else {
        $_SESSION['favorites'][] = $pet_id;
    }
}

$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $redirect_url);
exit();
?>