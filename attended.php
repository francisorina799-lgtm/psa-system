<?php

include 'db.php';

$ref = $_POST['ref'];

$stmt = $conn->prepare("
UPDATE appointments
SET status='Attended'
WHERE ref_number=?
");

$stmt->bind_param("s",$ref);
$stmt->execute();

echo "success";

?>