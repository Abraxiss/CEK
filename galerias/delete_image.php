<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Aquí realiza la consulta para eliminar la imagen de la base de datos
    $query = "DELETE FROM registro_galeria WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Imagen eliminada correctamente."]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error eliminando la imagen."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido."]);
}
?>
