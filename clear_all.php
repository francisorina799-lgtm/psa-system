<?php
// clear_all.php
// Wipes the entire manifest so the guard can start fresh.
// (Deletes all appointment rows. Switch the DELETE below to an UPDATE
//  if you'd rather keep history and just hide them — see note at bottom.)
require __DIR__ . '/db.php';
header('Content-Type: application/json');

// Simple confirmation flag from the frontend (matches confirmClearAll() in the dashboard)
$confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';

if ($confirm !== 'yes') {
    echo json_encode(['status' => 'error', 'message' => 'Missing confirmation']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM appointments");
    $stmt->execute();

    echo json_encode([
        'status'        => 'success',
        'cleared_count' => $stmt->affected_rows
    ]);

    $stmt->close();
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Clear failed: ' . $e->getMessage()]);
}

/*
 * NOTE: If you want to keep a record of cleared appointments instead of
 * permanently deleting them, replace the DELETE above with:
 *
 *   UPDATE appointments SET status = 'Cleared' WHERE status != 'Cleared'
 *
 * get_appointments.php would then need: WHERE status NOT IN ('Cancelled','Cleared')
 */
?>