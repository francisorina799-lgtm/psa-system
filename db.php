<?php
/**
 * db.php — Single, unified database connection.
 * EVERY backend file must include THIS file (not db_connect.php).
 * If you still have a separate db_connect.php anywhere, delete it —
 * having two connection files pointing at two different databases
 * is the classic reason "nothing was saving."
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "psa_appointment_db";   // must match schema.sql

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}
?>