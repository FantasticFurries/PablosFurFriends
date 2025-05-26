<?php
require_once 'conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appID = filter_input(INPUT_POST, 'appID', FILTER_VALIDATE_INT);

    if (!$appID) {
        $response['message'] = 'Invalid Appointment ID provided.';
        echo json_encode($response);
        exit();
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("DELETE FROM appointments WHERE appID = ?");
        $stmt->execute([$appID]);

        if ($stmt->rowCount() > 0) {
            $conn->commit();
            $response['success'] = true;
            $response['message'] = 'Appointment deleted successfully!';
        } else {
            $conn->rollBack();
            $response['message'] = 'Appointment not found or could not be deleted.';
        }

    } catch (\PDOException $e) {
        $conn->rollBack();
        error_log("Appointment deletion PDO error for appID {$appID}: " . $e->getMessage());
        $response['message'] = "Error deleting appointment: " . $e->getMessage();
    }

} else {
    $response['message'] = 'Invalid request method. This endpoint only accepts POST requests.';
}

echo json_encode($response);
exit();