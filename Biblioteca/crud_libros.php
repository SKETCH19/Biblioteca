<?php
include 'Base de Datos/conexion3.php';

$db = new DBConnection1();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_titulo = $_POST['id_titulo'];
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $id_pub = $_POST['id_pub'];
    $precio = $_POST['precio'];
    $avance = $_POST['avance'];
    $total_ventas = $_POST['total_ventas'];
    $notas = $_POST['notas'];
    $fecha_pub = $_POST['fecha_pub'];
    $contrato = $_POST['contrato'];
    $image_path = $_POST['image_path'] ?? '';

    if (empty($id_titulo)) {
        // Generar un nuevo id_titulo único
        $stmt = $conn->query("SELECT MAX(CAST(SUBSTR(id_titulo, 2) AS INTEGER)) as max_id FROM titulos");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_id = $result['max_id'] + 1;
        $id_titulo = 'T' . str_pad($new_id, 5, '0', STR_PAD_LEFT);

        // Añadir nuevo libro
        $sql = "INSERT INTO titulos (id_titulo, titulo, tipo, id_pub, precio, avance, total_ventas, notas, fecha_pub, contrato, image_path)
            VALUES (:id_titulo, :titulo, :tipo, :id_pub, :precio, :avance, :total_ventas, :notas, :fecha_pub, :contrato, :image_path)";
    } else {
        // Actualizar libro existente
        $sql = "UPDATE titulos SET titulo=:titulo, tipo=:tipo, id_pub=:id_pub, precio=:precio, avance=:avance, total_ventas=:total_ventas, notas=:notas, fecha_pub=:fecha_pub, contrato=:contrato, image_path=:image_path WHERE id_titulo=:id_titulo";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_titulo', $id_titulo);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':id_pub', $id_pub);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':avance', $avance);
    $stmt->bindParam(':total_ventas', $total_ventas);
    $stmt->bindParam(':notas', $notas);
    $stmt->bindParam(':fecha_pub', $fecha_pub);
    $stmt->bindParam(':contrato', $contrato);
    $stmt->bindParam(':image_path', $image_path);

    if ($stmt->execute()) {
        header('Location: libros.php');
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} elseif (isset($_GET['id'])) {
    // Editar libro existente
    $id_titulo = $_GET['id'];
    $sql = "SELECT * FROM titulos WHERE id_titulo = :id_titulo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_titulo', $id_titulo);
    $stmt->execute();
    $titulo = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Añadir nuevo libro
    $titulo = [
        'id_titulo' => '',
        'titulo' => '',
        'tipo' => '',
        'id_pub' => '',
        'precio' => '',
        'avance' => '',
        'total_ventas' => '',
        'notas' => '',
        'fecha_pub' => '',
        'contrato' => '',
        'image_path' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="assets/img/icon.jpeg" type="image/jpeg">
    <title><?= isset($_GET['id']) ? 'Editar' : 'Añadir' ?> Libro - Biblioteca Essence</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Header -->
    <?php include ("template/menu.php"); ?>
    <!-- Close Header -->
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4 pb-2 border-bottom"><?= isset($_GET['id']) ? 'Editar' : 'Añadir' ?> Libro</h2>
        <form method="post" action="crud_libros.php">
            <input type="hidden" name="id_titulo" value="<?= $titulo['id_titulo'] ?>">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $titulo['titulo'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?= $titulo['tipo'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_pub" class="form-label">ID Publicador</label>
                <input type="text" class="form-control" id="id_pub" name="id_pub" value="<?= $titulo['id_pub'] ?>" maxlength="4" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?= $titulo['precio'] ?>">
            </div>
            <div class="mb-3">
                <label for="avance" class="form-label">Avance</label>
                <input type="number" step="0.01" class="form-control" id="avance" name="avance" value="<?= $titulo['avance'] ?>">
            </div>
            <div class="mb-3">
                <label for="total_ventas" class="form-label">Total Ventas</label>
                <input type="number" class="form-control" id="total_ventas" name="total_ventas" value="<?= $titulo['total_ventas'] ?>">
            </div>
            <div class="mb-3">
                <label for="notas" class="form-label">Notas</label>
                <textarea class="form-control" id="notas" name="notas" required><?= $titulo['notas'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha_pub" class="form-label">Fecha de Publicación</label>
                <input type="datetime-local" class="form-control" id="fecha_pub" name="fecha_pub" value="<?= date('Y-m-d\TH:i', strtotime($titulo['fecha_pub'])) ?>" required>
            </div>
            <div class="mb-3">
                <label for="contrato" class="form-label">Contrato</label>
                <input type="text" class="form-control" id="contrato" name="contrato" value="<?= $titulo['contrato'] ?>" maxlength="1" required>
            </div>
            <div class="mb-3">
                <label for="image_path" class="form-label">Imagen (ruta)</label>
                <input type="text" class="form-control" id="image_path" name="image_path" value="<?= $titulo['image_path'] ?>" placeholder="assets/img/books/book-1.svg">
            </div>
            <button type="submit" class="btn btn-success"><?= isset($_GET['id']) ? 'Actualizar' : 'Añadir' ?> Libro</button>
            <br><br>
        </form>
    </div>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Start Footer -->
    <?php include ("template/footer.php"); ?>
    <!-- Close Footer -->
</body>
</html>