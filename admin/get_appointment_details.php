<?php
require_once 'conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'appointment' => null];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $appID = filter_input(INPUT_GET, 'appID', FILTER_VALIDATE_INT);

    if (!$appID) {
        $response['message'] = 'Invalid Appointment ID provided.';
        echo json_encode($response);
        exit();
    }

    try {
        // Corrected SQL query with JOIN to get petName
        $stmt = $conn->prepare(
            "SELECT 
                a.appID, 
                a.service, 
                a.name AS clientName,  -- Alias 'name' to 'clientName' for consistency with JS
                a.phone, 
                a.email, 
                a.appointment_date, 
                p.petName,             -- Get petName from the pets table
                a.status,
                a.petID,               -- Include petID if needed for the form later
                a.userID               -- Include userID if needed for the form later
             FROM appointments a
             JOIN pets p ON a.petID = p.petID
             WHERE a.appID = ?"
        );
        
        $stmt->execute([$appID]);
        
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment) {
            $response['success'] = true;
            $response['appointment'] = $appointment;
        } else {
            $response['message'] = 'Appointment not found.';
        }

    } catch (\PDOException $e) {
        error_log("Error fetching appointment details for appID {$appID}: " . $e->getMessage());
        $response['message'] = "Database error: " . $e->getMessage();
    }

} else {
    $response['message'] = 'Invalid request method. This endpoint only accepts GET requests.';
}

echo json_encode($response);
exit();
?>