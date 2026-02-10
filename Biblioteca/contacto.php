<!DOCTYPE html>
<html lang="en">

<head>
    <title>Biblioteca Essence</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="img/icon.jpeg">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
</head>

<body>
    <!-- Header -->
    <?php 
    include ("template/menu.php");
    ?>

    <!-- Comentarios -->
    <div class="container">
        <br>
        <h2>Su Opinión Es Importante</h2>
        <form action="guardar_comentario.php" method="POST">
            <div class="form-group">
                <label for="nombre">Su nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Su nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Su correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" placeholder="Su correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto del comentario" required>
            </div>
            <div class="form-group">
                <label for="comentario">Comentario</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Escribe tu comentario" required></textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-success">Enviar</button>
        </form>

        <h3>Comentarios recibidos</h3>
        <?php
        include 'Base de Datos/conexion2.php';
        $db = new DBConnection();
        $conn = $db->getConnection();

        $sql = "SELECT nombre, comentario, fecha FROM comentarios ORDER BY fecha DESC";
        $stmt = $conn->query($sql);
        $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($comentarios) > 0) {
            foreach ($comentarios as $row) {
                echo "<div class='card my-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . " (" . $row['fecha'] . ")</h5>";
                echo "<p class='card-text'>" . htmlspecialchars($row['comentario']) . "</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "No hay comentarios aun.";
        }
        ?>
    </div>
    <!-- Start Footer -->
    <?php 
    include ("template/footer.php");
    ?>
</body>

</html>