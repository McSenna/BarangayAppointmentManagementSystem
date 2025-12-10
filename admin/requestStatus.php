<?php 

include_once '../connection.php';

try {

    $residence_id = $con->real_escape_string($_POST['residence_id']);
    $purpose = $con->real_escape_string(strtoupper($_POST['purpose']));
    $certificate_id = $con->real_escape_string($_POST['certificate_id']);

    // CORRECT FIELD (old field 'edit_date_issued' removed)
    $date_issued = $con->real_escape_string($_POST['date_issued']);

    // Auto-generate date_expired: +3 days
    $issued_date_obj = new DateTime($date_issued);
    $issued_date_obj->modify('+3 days');
    $date_expired = $issued_date_obj->format('m/d/Y');

    $message = $con->real_escape_string($_POST['message']);
    $status = 'ACCEPTED';

    // UPDATE certificate request
    $sql_update_request = "
        UPDATE certificate_request 
        SET 
            date_issued = ?, 
            date_expired = ?, 
            status = ?, 
            purpose = ?,  
            message = ?
        WHERE id = ? 
        AND residence_id = ?
    ";

    $stmt_update = $con->prepare($sql_update_request) or die($con->error);
    $stmt_update->bind_param(
        'sssssss',
        $date_issued,
        $date_expired,
        $status,
        $purpose,
        $message,
        $certificate_id,
        $residence_id
    );

    $stmt_update->execute();
    $stmt_update->close();

    // Logs
    $date_activity = date("j-n-Y g:i A");
    $status_activity_log = "updated";

    $message_activity =  
        "ADMIN ACCEPTED CERTIFICATE REQUEST - $residence_id | PURPOSE $purpose | " .
        "DATE ISSUED $date_issued | DATE EXPIRED $date_expired";

    $sql_logs = "INSERT INTO activity_log (`message`, `date`, `status`) VALUES (?,?,?)";
    $stmt_logs = $con->prepare($sql_logs);
    $stmt_logs->bind_param('sss', $message_activity, $date_activity, $status_activity_log);
    $stmt_logs->execute();
    $stmt_logs->close();

} catch (Exception $e) {
    echo $e->getMessage();
}

?>
