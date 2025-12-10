CREATE TABLE document_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    purpose VARCHAR(255) NOT NULL,
    request_date DATE NOT NULL,
    request_time TIME NOT NULL,

    document_type VARCHAR(100) NOT NULL,

    status VARCHAR(50) DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

<?php
include 'config.php'; // your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id        = $_POST['user_id'];
    $purpose        = $_POST['purpose'];
    $request_date   = $_POST['date'];
    $request_time   = $_POST['time'];
    $document_type  = $_POST['document_type'];

    // Prepare and execute insert
    $stmt = $conn->prepare("
        INSERT INTO document_requests 
        (user_id, purpose, request_date, request_time, document_type) 
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issss", 
        $user_id, 
        $purpose, 
        $request_date, 
        $request_time, 
        $document_type
    );

    if ($stmt->execute()) {
        echo "Document request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
