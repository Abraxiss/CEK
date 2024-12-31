<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería Dinámica</title>
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #475569;
            --background: #f8fafc;
            --card: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--background);
            color: var(--secondary);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .gallery-item {
            background: var(--card);
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease;
            position: relative;
        }

        .gallery-item:hover {
            transform: translateY(-4px);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-content {
            padding: 1rem;
        }

        .btn {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.25rem;
        }

        .gallery-actions {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .gallery-actions button {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
        }

        .gallery-actions button:hover {
            background: rgba(255, 255, 255, 1);
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Galería de Imágenes</h1>
            <button class="btn" onclick="showUploadModal()">Subir Imagen</button>
        </header>

        <div class="gallery-grid" id="galleryGrid">
<?php
require 'db_connection.php';

$query = "SELECT * FROM registro_galeria";
$stmt = $pdo->prepare($query);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
?>
        </div>
    </div>

    <!-- Modal de subida -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <h2>Subir Nueva Imagen</h2>
            <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="imageFile">Seleccionar Imagen</label>
                    <input type="file" id="imageFile" name="imageFile" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="imageDesc">Descripción</label>
                    <textarea id="imageDesc" name="imageDesc" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Subir</button>
                    <button type="button" class="btn" onclick="hideUploadModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function fetchGallery() {
            const response = await fetch('fetch_gallery.php');
            const galleryData = await response.json();
            renderGallery(galleryData);
        }

        function renderGallery(galleryData) {
            const galleryGrid = document.getElementById('galleryGrid');
            galleryGrid.innerHTML = '';
            galleryData.forEach(item => {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'gallery-item';
                galleryItem.innerHTML = `
                    <img class="gallery-image" src="${item.imagen_ruta}" alt="Imagen">
                    <div class="gallery-content">
                        <p>${item.descripcion}</p>
                    </div>
                    <div class="gallery-actions">
                        <button onclick="deleteImage(${item.id})">Eliminar</button>
                    </div>
                `;
                galleryGrid.appendChild(galleryItem);
            });
        }

        function showUploadModal() {
            document.getElementById('uploadModal').classList.add('active');
        }

        function hideUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
        }

        async function deleteImage(id) {
            if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
                try {
                    const response = await fetch('delete_image.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${id}`
                    });

                    if (response.ok) {
                        alert("Imagen eliminada correctamente.");
                        fetchGallery(); // Recargar la galería
                    } else {
                        const error = await response.json();
                        alert(`Error: ${error.message}`);
                    }
                } catch (error) {
                    console.error("Error eliminando la imagen:", error);
                    alert("Hubo un problema al intentar eliminar la imagen.");
                }
            }
        }

        // Inicializa la galería al cargar la página
        fetchGallery();
    </script>
</body>
</html>
