<?php
require_once 'conn.php';

header('Content-Type: application/json');

$statusFilter = $_GET['status'] ?? 'Pending';
$includePast = isset($_GET['include_past']) && $_GET['include_past'] === 'true';

$sql = "SELECT
            a.appID,
            a.service,
            a.name AS clientName,
            a.phone,
            a.email,
            a.appointment_date,
            a.status,
            p.petName
        FROM
            appointments a
        JOIN
            pets p ON a.petID = p.petID
        WHERE 1=1";

$params = [];

if ($statusFilter !== 'All') {
    $sql .= " AND a.status = ?";
    $params[] = $statusFilter;
}

if (!$includePast) {
    if ($statusFilter === 'Pending' || $statusFilter === 'Approved') {
         $sql .= " AND a.appointment_date >= CURDATE()";
    }
}

$sql .= " ORDER BY a.appointment_date ASC, a.appID ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'appointments' => $appointments]);

} catch (\PDOException $e) {
    error_log("Error fetching appointments: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching appointments: ' . $e->getMessage()]);
}
?>