<?php

require_once 'conn.php';

header('Content-Type: application/json');

error_log("update_appointment.php received a POST request.");
error_log("Raw POST data received: " . print_r($_POST, true));

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appID = filter_input(INPUT_POST, 'editAppID', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'editStatus', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($appID && $status && count($_POST) <= 2) { 
        $allowedStatuses = ['Pending', 'Approved', 'Cancelled', 'Completed'];
        if (!in_array($status, $allowedStatuses)) {
            $response['message'] = 'Invalid status provided.';
            echo json_encode($response);
            exit();
        }

        try {
            $stmt_update_status = $conn->prepare(
                "UPDATE appointments
                 SET status = ?
                 WHERE appID = ?"
            );
            $stmt_update_status->execute([$status, $appID]);

            if ($stmt_update_status->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Appointment status updated successfully!';
            } else {
                $response['message'] = 'No changes made or appointment not found.';
            }

        } catch (\PDOException $e) {
            error_log("Error updating appointment status for appID {$appID}: " . $e->getMessage());
            $response['message'] = "Database error during status update: " . $e->getMessage();
        } catch (Exception $e) {
            error_log("General error updating appointment status for appID {$appID}: " . $e->getMessage());
            $response['message'] = "An unexpected error occurred during status update: " . $e->getMessage();
        }

    } else {
        // --- ORIGINAL LOGIC FOR FULL UPDATE (keep this if you have an edit form) ---
        // If more data is present, assume a full update is intended
        $clientName = filter_input(INPUT_POST, 'editClientName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'editClientEmail', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'editClientPhone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $petName = filter_input(INPUT_POST, 'editPetName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $service = filter_input(INPUT_POST, 'editService', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $appointmentDate = filter_input(INPUT_POST, 'editAppointmentDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$appID || !$clientName || !$email || !$phone || !$petName || !$service || !$appointmentDate || !$status) {
            $response['message'] = 'Missing or invalid data for full update.'; // Differentiate this message
            echo json_encode($response);
            exit();
        }

        if (!$email) {
            $response['message'] = 'Invalid email format.';
            echo json_encode($response);
            exit();
        }

        $allowedStatuses = ['Pending', 'Approved', 'Cancelled', 'Completed'];
        if (!in_array($status, $allowedStatuses)) {
            $response['message'] = 'Invalid status provided.';
            echo json_encode($response);
            exit();
        }

        try {
            $conn->beginTransaction();

            $stmt_get_petid = $conn->prepare("SELECT petID FROM appointments WHERE appID = ?");
            $stmt_get_petid->execute([$appID]);
            $result_petid = $stmt_get_petid->fetch(PDO::FETCH_ASSOC);

            if (!$result_petid) {
                $conn->rollBack();
                $response['message'] = 'Appointment not found for petID retrieval.';
                echo json_encode($response);
                exit();
            }
            $currentPetID = $result_petid['petID'];

            $stmt_update_pet = $conn->prepare("UPDATE pets SET petName = ? WHERE petID = ?");
            $stmt_update_pet->execute([$petName, $currentPetID]);

            $stmt_update_appointment = $conn->prepare(
                "UPDATE appointments
                 SET name = ?, email = ?, phone = ?, service = ?, appointment_date = ?, status = ?
                 WHERE appID = ?"
            );
            $stmt_update_appointment->execute([
                $clientName,
                $email,
                $phone,
                $service,
                $appointmentDate,
                $status,
                $appID
            ]);

            $conn->commit();

            if ($stmt_update_appointment->rowCount() > 0 || $stmt_update_pet->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Appointment and pet details updated successfully!';
            } else {
                $response['message'] = 'No changes made or appointment not found.';
            }

        } catch (\PDOException $e) {
            $conn->rollBack();
            error_log("Error updating appointment appID {$appID}: " . $e->getMessage());
            $response['message'] = "Database error during full update: " . $e->getMessage(); // Differentiate this message
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("General error updating appointment appID {$appID}: " . $e->getMessage());
            $response['message'] = "An unexpected error occurred during full update: " . $e->getMessage(); // Differentiate this message
        }
    }

} else {
    $response['message'] = 'Invalid request method. This endpoint only accepts POST requests.';
}

echo json_encode($response);
exit();
?>