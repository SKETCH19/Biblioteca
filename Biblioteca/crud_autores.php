<?php
include 'Base de Datos/conexion2.php';

$db = new DBConnection();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_autor = $_POST['id_autor'] ?? null;
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = substr($_POST['estado'], 0, 2); // Limitar a 2 caracteres
    $pais = $_POST['pais'];
    $cod_postal = $_POST['cod_postal'];
    $photo_path = $_POST['photo_path'] ?? '';

    if (empty($id_autor)) {
        // Generar un nuevo id_autor único
        $stmt = $conn->query("SELECT MAX(CAST(SUBSTR(id_autor, 2) AS INTEGER)) as max_id FROM autores");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_id = $result['max_id'] + 1;
        $id_autor = 'A' . str_pad($new_id, 10, '0', STR_PAD_LEFT);

        // Añadir nuevo autor
        $sql = "INSERT INTO autores (id_autor, nombre, apellido, telefono, direccion, ciudad, estado, pais, cod_postal, photo_path) 
            VALUES (:id_autor, :nombre, :apellido, :telefono, :direccion, :ciudad, :estado, :pais, :cod_postal, :photo_path)";
    } else {
        // Actualizar autor existente
        $sql = "UPDATE autores SET nombre = :nombre, apellido = :apellido, telefono = :telefono, 
            direccion = :direccion, ciudad = :ciudad, estado = :estado, pais = :pais, 
            cod_postal = :cod_postal, photo_path = :photo_path WHERE id_autor = :id_autor";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_autor', $id_autor);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':ciudad', $ciudad);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':pais', $pais);
    $stmt->bindParam(':cod_postal', $cod_postal);
    $stmt->bindParam(':photo_path', $photo_path);

    if ($stmt->execute()) {
        header('Location: autores.php');
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} elseif (isset($_GET['id'])) {
    // Editar autor existente
    $id_autor = $_GET['id'];
    $sql = "SELECT * FROM autores WHERE id_autor = :id_autor";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_autor', $id_autor);
    $stmt->execute();
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Añadir nuevo autor
    $autor = [
        'id_autor' => '',
        'nombre' => '',
        'apellido' => '',
        'telefono' => '',
        'direccion' => '',
        'ciudad' => '',
        'estado' => '',
        'pais' => '',
        'cod_postal' => '',
        'photo_path' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="img/icon.jpeg" type="image/jpeg">
    <title><?= isset($_GET['id']) ? 'Editar' : 'Añadir' ?> Autor - Biblioteca Essence</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <?php include ("template/menu.php"); ?>
    <!-- Close Header -->
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4 pb-2 border-bottom"><?= isset($_GET['id']) ? 'Editar' : 'Añadir' ?> Autor</h2>
        <form action="crud_autores.php" method="post">
            <input type="hidden" name="id_autor" value="<?= $autor['id_autor'] ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= $autor['nombre'] ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?= $autor['apellido'] ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?= $autor['telefono'] ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?= $autor['direccion'] ?>" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="<?= $autor['ciudad'] ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" name="estado" class="form-control" value="<?= $autor['estado'] ?>" required>
            </div>
            <div class="form-group">
                <label for="pais">País</label>
                <input type="text" name="pais" class="form-control" value="<?= $autor['pais'] ?>" required>
            </div>
            <div class="form-group">
                <label for="cod_postal">Código Postal</label>
                <input type="text" name="cod_postal" class="form-control" value="<?= $autor['cod_postal'] ?>" required>
                <br>
            </div>
            <div class="form-group">
                <label for="photo_path">Imagen (ruta)</label>
                <input type="text" name="photo_path" class="form-control" value="<?= $autor['photo_path'] ?>" placeholder="assets/img/authors/author-1.svg">
                <br>
            </div>
            <button type="submit" class="btn btn-success"><?= isset($_GET['id']) ? 'Actualizar' : 'Añadir' ?> Autor</button>
        </form>
        <br><br>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Start Footer -->
    <?php include ("template/footer.php"); ?>
</body>
</html>