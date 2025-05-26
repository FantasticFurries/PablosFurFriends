<?php

require_once 'conn.php';

$year = $_POST['year'] ?? date('Y');

try {
    $query = "SELECT 
              MONTHNAME(STR_TO_DATE(CONCAT(:year, '-', MONTH(appointment_date), '-01'), '%Y-%m-%d')) as month, 
              COUNT(*) as count 
              FROM appointments 
              WHERE status = 'Completed' 
              AND YEAR(appointment_date) = :year
              GROUP BY MONTH(appointment_date) 
              ORDER BY MONTH(appointment_date)";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ensure we always return an array, even if empty
    if (empty($monthlyData)) {
        $monthlyData = [];
    }
    
    echo json_encode([
        'success' => true,
        'monthlyData' => $monthlyData
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>