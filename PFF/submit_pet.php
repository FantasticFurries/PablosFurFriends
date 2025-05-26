<?php
require_once 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'petID' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petName = $_POST['petName'] ?? '';
    $ownerID = $_POST['userID'] ?? null;

    if (empty($petName) || empty($ownerID)) {
        $response['message'] = 'Pet name and User ID are required.';
        echo json_encode($response);
        exit();
    }

    $ownerID = (int)$ownerID;

    try {
        $stmt_check = $conn->prepare("SELECT petID FROM pets WHERE petName = ? AND ownerID = ?");
        $stmt_check->execute([$petName, $ownerID]);
        $existingPetID = $stmt_check->fetchColumn();

        if ($existingPetID) {
            $response['success'] = true;
            $response['message'] = 'Pet already registered.';
            $response['petID'] = $existingPetID;
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO pets (petName, ownerID) VALUES (?, ?)");
            $stmt_insert->execute([$petName, $ownerID]);

            $response['success'] = true;
            $response['message'] = 'Pet registered successfully.';
            $response['petID'] = $conn->lastInsertId();
        }
    } catch (\PDOException $e) {
        $response['message'] = "Database error: " . $e->getMessage();
        error_log("Pet submission PDO error: " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>