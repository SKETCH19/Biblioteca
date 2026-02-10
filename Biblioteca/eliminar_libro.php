<?php
include('Base de Datos/conexion3.php');

$db = new DBConnection1();
$conn = $db->getConnection();

$id_titulo = $_GET['id'];
$sql = "DELETE FROM titulos WHERE id_titulo = :id_titulo";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_titulo', $id_titulo);

if ($stmt->execute()) {
    header('Location: libros.php');
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}

$conn = null;
?>
