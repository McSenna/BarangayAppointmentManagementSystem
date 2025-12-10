<?php
include_once '../connection.php';

try {

    // Read form values
    $user_id        = $con->real_escape_string($_POST['user_id']);
    $purpose        = $con->real_escape_string($_POST['purpose']);
    $selected_date  = $con->real_escape_string($_POST['date']);   // date_request
    $selected_time  = $con->real_escape_string($_POST['time']);   // time_request
    $document_type  = $con->real_escape_string($_POST['document_type']);

    // Generate unique ID
    date_default_timezone_set('Asia/Manila');
    $now = new DateTime();

    // Auto date issued (current system date/time)
    $date_issued = $now->format("m/d/Y g:i A");

    // Date expired = date issued + 3 days
    $expire_date = clone $now;
    $expire_date->modify("+3 days");
    $date_expired = $expire_date->format("m/d/Y g:i A");

    // Combine resident date + time
    $datetime_request = date("Y-m-d H:i", strtotime("$selected_date $selected_time"));

    // System-generated unique ID
    $uniqid = uniqid(mt_rand() . $now->format("mdYHisv") . rand());

    // Default status
    $status = "PENDING";

    // Insert into table
    $sql = "INSERT INTO `certificate_request`
            (`id`, `residence_id`, `certificate_type`, `purpose`,
             `date_request`, `time_request`, `datetime_request`,
             `date_issued`, `date_expired`, `status`)
            VALUES (?,?,?,?,?,?,?,?,?,?)";

    $stmt = $con->prepare($sql) or die($con->error);
    $stmt->bind_param(
        'ssssssssss',
        $uniqid,
        $user_id,
        $document_type,
        $purpose,
        $selected_date,
        $selected_time,
        $datetime_request,
        $date_issued,
        $date_expired,
        $status
    );

    $stmt->execute();
    $stmt->close();

    // Log message
    $log_message = "RESIDENT - $user_id submitted a certificate request ($document_type)";

    $date_activity = date("m/d/Y g:i A");
    $log_status = "create";

    $sql_log = "INSERT INTO activity_log (`message`, `date`, `status`) VALUES (?,?,?)";
    $stmt_log = $con->prepare($sql_log);
    $stmt_log->bind_param("sss", $log_message, $date_activity, $log_status);
    $stmt_log->execute();
    $stmt_log->close();

} catch (Exception $e) {
    echo $e->getMessage();
}
?>
