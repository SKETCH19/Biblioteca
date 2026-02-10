<?php
include 'Base de Datos/conexion3.php';

$db = new DBConnection1();
$conn = $db->getConnection();
$sql = "SELECT * FROM titulos";
$stmt = $conn->query($sql);
$titulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libros - Biblioteca Essence</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <?php include ("template/menu.php"); ?>
    <!-- Close Header -->
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4 pb-2 border-bottom">Lista de Libros</h2>
        <a href="crud_libros.php" class="btn btn-success mb-3">Añadir Libro</a>
        <div class="row">
            <?php foreach ($titulos as $titulo): ?>
                <?php
                    $imagePath = !empty($titulo['image_path']) ? $titulo['image_path'] : 'assets/img/books/book-default.svg';
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?= htmlspecialchars($imagePath) ?>" class="card-img-top" alt="Portada">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($titulo['titulo']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($titulo['notas']) ?></p>
                            <a href="crud_libros.php?id=<?= $titulo['id_titulo'] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="eliminar_libro.php?id=<?= $titulo['id_titulo'] ?>" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Start Footer -->
    <?php include ("template/footer.php"); ?>
    <!-- Close Footer -->
</body>
</html>
