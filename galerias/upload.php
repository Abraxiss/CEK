<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['imageDesc'];
    $targetDir = "uploads/";
    $fileName = basename($_FILES["imageFile"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Verifica si la carpeta de destino existe; si no, la crea
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $targetFilePath)) {
        $query = "INSERT INTO registro_galeria (imagen_nombre, imagen_ruta, descripcion, usuario_id) VALUES (:imagen_nombre, :imagen_ruta, :descripcion, :usuario_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':imagen_nombre', $fileName);
        $stmt->bindParam(':imagen_ruta', $targetFilePath);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindValue(':usuario_id', 1); // Cambiar según la lógica de autenticación
        $stmt->execute();

        header("Location: galeria.php");
        exit();
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
