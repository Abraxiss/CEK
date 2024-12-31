<?php
require 'db_connection.php';

$query = "SELECT * FROM registro_galeria";
$stmt = $pdo->prepare($query);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
