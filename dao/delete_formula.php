<?php include 'conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $query = "DELETE FROM Customer_Formula WHERE CustomerFormulaID = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../views/list_formula.php?mensaje=Registro eliminado correctamente");
            exit;
        } else {
            echo "Error al eliminar el registro.";
        }
    }
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
