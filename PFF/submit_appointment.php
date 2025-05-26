<?php
require_once 'connection.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['service'] ?? '';
    $ownerName = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $appointmentDate = $_POST['date'] ?? '';
    $petID = $_POST['petID'] ?? null;
    $userID = $_POST['userID'] ?? null;

    if (empty($service) || empty($ownerName) || empty($phone) || empty($email) || empty($appointmentDate) || empty($petID) || empty($userID)) {
        $response['message'] = 'All fields are required. Missing data.';
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $appointmentDate)) {
        $response['message'] = 'Invalid date format. Please use YYYY-MM-DD.';
        echo json_encode($response);
        exit();
    }

    $conn->beginTransaction();

    try {
        $stmt_app = $conn->prepare("INSERT INTO appointments (service, name, phone, email, appointment_date, petID, userID, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $status = 'Pending';
        $stmt_app->execute([$service, $ownerName, $phone, $email, $appointmentDate, $petID, $userID, $status]);

        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Appointment scheduled successfully!';

    } catch (\PDOException $e) {
        $conn->rollBack();
        $response['message'] = "Error scheduling appointment: " . $e->getMessage();
        error_log("Appointment submission PDO error: " . $e->getMessage());
    }

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>