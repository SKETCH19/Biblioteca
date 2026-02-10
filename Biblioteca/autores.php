<?php
include 'Base de Datos/conexion2.php';

$db = new DBConnection();
$conn = $db->getConnection();
$sql = "SELECT * FROM autores";
$stmt = $conn->query($sql);
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Autores - Biblioteca Essence</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <?php include ("template/menu.php"); ?>
    <!-- Close Header -->
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4 pb-2 border-bottom">Lista de Autores</h2>
        <a href="crud_autores.php" class="btn btn-success mb-3">Añadir Autor</a>
        <div class="row">
            <?php foreach ($autores as $autor): ?>
                <?php
                    $photoPath = !empty($autor['photo_path']) ? $autor['photo_path'] : 'assets/img/authors/author-default.svg';
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?= htmlspecialchars($photoPath) ?>" class="card-img-top" alt="Autor">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?></h5>
                            <a href="crud_autores.php?id=<?= $autor['id_autor'] ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                            <a href="eliminar_autor.php?id=<?= $autor['id_autor'] ?>" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Start Footer -->
    <?php include ("template/footer.php"); ?>
</body>
</html>
