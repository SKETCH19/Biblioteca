<?php
include 'Base de Datos/conexion2.php';

$id_autor = $_GET['id'];
$db = new DBConnection();
$conn = $db->getConnection();

$sql = "DELETE FROM autores WHERE id_autor = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_autor]);

header('Location: autores.php');
exit;
?>
