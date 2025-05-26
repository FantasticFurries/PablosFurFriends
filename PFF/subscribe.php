<?php
session_start();
require_once('connection.php');

if (isset($_POST['subscribe'])) {
    try {
        // Sanitize and validate email
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['message'] = "Please enter a valid email address";
            $_SESSION['message_type'] = "error";
        } else {
            // Check if email exists using PDO prepared statement
            $check_query = "SELECT id FROM mail_subscribers WHERE email = :email";
            $stmt = $conn->prepare($check_query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "This email is already subscribed";
                $_SESSION['message_type'] = "error";
            } else {
                // Insert new subscriber using PDO prepared statement
                $insert_query = "INSERT INTO mail_subscribers (email, subscribed_at) VALUES (:email, NOW())";
                $stmt = $conn->prepare($insert_query);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Thank you for subscribing!";
                    $_SESSION['message_type'] = "success";
                    
                    // Optional: Send confirmation email
                    // mail($email, "Subscription Confirmation", "Thank you for subscribing!");
                } else {
                    $_SESSION['message'] = "Subscription failed. Please try again later.";
                    $_SESSION['message_type'] = "error";
                    error_log("PDO Error: " . implode(" ", $stmt->errorInfo()));
                }
            }
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error. Please try again later.";
        $_SESSION['message_type'] = "error";
        error_log("PDO Exception: " . $e->getMessage());
    }
    
    // Redirect to prevent form resubmission
    header("Location: products.php"); // Changed to products.php since this is your products page
    exit();
}
?>