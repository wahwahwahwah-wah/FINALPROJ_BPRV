<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];

    $stmt = $db->prepare("SELECT ImageURL FROM pets WHERE PetID = ?");
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $stmt->close();

    $stmt = $db->prepare("DELETE FROM pets WHERE PetID = ?");
    $stmt->bind_param("i", $pet_id);
    
    if ($stmt->execute()) {
        if ($pet && !empty($pet['ImageURL']) && file_exists($pet['ImageURL'])) {
            unlink($pet['ImageURL']);
        }
        header("location: dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $db->error;
    }
    $stmt->close();
    $db->close();
}
?>