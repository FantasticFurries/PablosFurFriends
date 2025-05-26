<?phprequire_once 'conn.php';

$timeRange = $_POST['timeRange'] ?? 'all';

try {
    $query = "";
    
    switch ($timeRange) {
        case 'monthly':
            $currentMonth = date('m');
            $currentYear = date('Y');
            $query = "SELECT COUNT(DISTINCT p.petID) as count 
                     FROM pets p
                     JOIN appointments a ON p.petID = a.petID
                     WHERE a.status = 'Completed'
                     AND MONTH(a.appointment_date) = :month 
                     AND YEAR(a.appointment_date) = :year";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':month', $currentMonth, PDO::PARAM_INT);
            $stmt->bindParam(':year', $currentYear, PDO::PARAM_INT);
            break;
            
        case 'yearly':
            $currentYear = date('Y');
            $query = "SELECT COUNT(DISTINCT p.petID) as count 
                     FROM pets p
                     JOIN appointments a ON p.petID = a.petID
                     WHERE a.status = 'Completed'
                     AND YEAR(a.appointment_date) = :year";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':year', $currentYear, PDO::PARAM_INT);
            break;
            
        case 'all':
        default:
            $query = "SELECT COUNT(DISTINCT p.petID) as count 
                     FROM pets p
                     JOIN appointments a ON p.petID = a.petID
                     WHERE a.status = 'Completed'";
            $stmt = $conn->prepare($query);
            break;
    }
    
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'count' => $result['count']
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>