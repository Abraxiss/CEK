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
            cursor: pointer;
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
            background: rgba(0, 0, 0, 0.8);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
        }

        .modal-content img {
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: white;
            color: var(--secondary);
            border: none;
            font-size: 1.5rem;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
        }

        .close-modal:hover {
            background: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Galería de Imágenes</h1>
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

    <div class="modal" id="imageModal">
        <button class="close-modal" onclick="hideModal()">&times;</button>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Imagen">
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
                `;
                galleryItem.addEventListener('click', () => showModal(item.imagen_ruta));
                galleryGrid.appendChild(galleryItem);
            });
        }

        function showModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.add('active');
        }

        function hideModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active');
        }

        // Inicializa la galería al cargar la página
        fetchGallery();
    </script>


<!-- End of  Section galeria-->

    <style>


        .parallax-section {
            position: relative;
            height: 400px;
            background-image: url('house-6115719_1280.jpg'); /* Cambia esta URL por la imagen de fondo deseada */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .parallax-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin: 0;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .parallax-section p {
            font-size: 1.5rem;
            margin: 10px 0;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .parallax-section a {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-size: 1.2rem;
            color: #fff;
            background: #ff6e42;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            transition: background 0.3s ease;
        }

        .parallax-section a:hover {
            background: #e05532;
        }
    </style>
    <section class="parallax-section">

        <a href="../index.html">Regresar</a>
    </section>


<!-- fin of  Section galeria-->





</body>
</html>
