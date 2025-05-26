<?php
require_once 'connection.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;

    if ($appointment_id > 0) {
        try {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE appID = ?");
            $stmt->execute([$appointment_id]);

            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Appointment cancelled successfully!';
            } else {
                $response['message'] = 'Appointment not found or already cancelled.';
            }

        } catch (\PDOException $e) {
            $response['message'] = "Error cancelling appointment: " . $e->getMessage();
            error_log("Appointment cancellation PDO error: " . $e->getMessage());
        }
    } else {
        $response['message'] = 'Invalid appointment ID provided.';
    }
} else {
    $response['message'] = 'Invalid request method. This script only accepts POST requests.';
}

echo json_encode($response);
exit();
?>