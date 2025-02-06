
<?php include '../dao/conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'] ?? '';
        $empresa = $_POST['empresa'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $sumaDosis = $_POST['sumaDosis'] ?? '';
        $dosis_kg = isset($_POST['dosis_kg']) ? json_decode($_POST['dosis_kg'], true) : [];

        
        if (!$nombre || !$empresa || !$correo || !$telefono || !$sumaDosis) {
            throw new Exception('Faltan datos obligatorios. Recuerda agregar los datos de contacto y los productos requeridos');
        }

        $queryUsuario = "INSERT INTO Customer (name, company, email, telephone) VALUES (:nombre, :empresa, :correo, :telefono)";
        $stmtUsuario = $connection->prepare($queryUsuario);
        $stmtUsuario->execute([
            ':nombre' => $nombre,
            ':empresa' => $empresa,
            ':correo' => $correo,
            ':telefono' => $telefono
        ]);

        $usuarioId = $connection->lastInsertId();

        $queryProducto = "INSERT INTO Customer_Formula (CustomerID, DosageKg, FormulaName) VALUES (:usuario_id, :dosis_kg, :nombre_producto)";
        $stmtProducto = $connection->prepare($queryProducto);
        
        $stmtProducto->execute([
            ':usuario_id' => $usuarioId,
            ':dosis_kg' => $sumaDosis,
            ':nombre_producto' =>  $empresa
        ]);
        
        $customerFormulaId = $connection->lastInsertId();

        if (!empty($dosis_kg)) {
            $queryFormula = "INSERT INTO Formula_Product (CustomerFormulaID, ProductComponentID, CustomValue) VALUES (:customerFormulaID, :productComponentID, :customValue)";
            $stmtFormula = $connection->prepare($queryFormula);
            
            foreach ($dosis_kg as $item) {
                $stmtFormula->execute([
                    ':customerFormulaID' => $customerFormulaId,
                    ':productComponentID' => $item['Name'],
                    ':customValue' => $item['Value']
                ]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se recibieron datos.']);
        }
        echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente.']);
    } else {
        throw new Exception('Método no permitido.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
