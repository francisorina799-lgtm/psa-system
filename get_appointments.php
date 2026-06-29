<?php
// get_appointments.php
require __DIR__ . '/db.php';
header('Content-Type: application/json');

$sql = "
    SELECT
        id,
        trn,
        hash_code        AS refNumber,
        full_name        AS name,
        email,
        mobile_number    AS mobile,
        purpose,
        appointment_date AS date,
        DATE_FORMAT(appointment_date, '%M %e, %Y') AS dateLabel,
        appointment_time AS time,
        outlet_name      AS outlet,
        outlet_address   AS address,
        status
    FROM appointments
    WHERE status != 'Cancelled'
    ORDER BY appointment_date ASC, appointment_time ASC
";

try {
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Query failed: ' . $e->getMessage()
    ]);
}
?>