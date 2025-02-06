<?php  include '../dao/functions_dao.php';
try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $formulaData = getFormulaData($connection, $id);

        if (isset($formulaData['error'])) {
            echo "Error: " . $formulaData['error'];
        } else {
            $formula = $formulaData['formula'];
            $products = $formulaData['products'];
    
            $totalDosage = 0;
            foreach ($products as $product) {
                $totalDosage += $product['CustomValue'];
            }
    
            if (!$formula) {
                die("No se encontró la fórmula con el ID especificado.");
            }
        }
    } else {
        die("ID no proporcionado.");
    }
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Fórmula</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/estilos.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
</head>
<body>
    <header>
        <nav class="navbar navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="https://llanofertil.com/">
                <img src="../assets/logo-full-01.png" alt="" width="165" height="50">
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="heading">Datos del cliente</h2>
                <table cellspacing="0" cellpadding="10" >
                    <tr>
                        <th style="color: white;">Nombre del Cliente</th>
                        <td><?= htmlspecialchars($formula['Name']) ?></td>
                    </tr>
                    <tr>
                        <th style="color: white;">Empresa</th>
                        <td><?= htmlspecialchars($formula['Company']) ?></td>
                    </tr>
                    <tr>
                        <th style="color: white;">Correo</th>
                        <td><?= htmlspecialchars($formula['Email']) ?></td>
                    </tr>
                    <tr>
                        <th style="color: white;">Telefono</th>
                        <td><?= htmlspecialchars($formula['Telephone']) ?></td>
                    </tr>
                </table>

                <h2 class="heading">Detalle del Pedido</h2>
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <th>Producto</th>
                        <th>DOSIS KG/HA</th>
                    </thead>
                    <tbody>
                        <?php if (count($products) > 0): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['Name']) ?></td>
                                    <td><?= htmlspecialchars($product['CustomValue']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">No hay productos disponibles.</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="total-row">
                            <td><strong>Total Dosis</strong></td>
                            <td id="total-dosis"><?= htmlspecialchars($totalDosage) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                <a href="list_formula.php" class="btn-custome">Regresar al Listado</a>
                            </td>
                            <td>
                                <a href="export_excel.php?id=<?= htmlspecialchars($formula['CustomerFormulaID']) ?>" class="btn-custome">Exportar a Excel</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer class="footer-bg-green ">
        <div class="row">
            <div class="d-grid gap-2">
                <button class="btn btn-link" type="button">Términos y Condiciones</button>
                <button class="btn btn-link" type="button">Aviso de Privacidad</button>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <img src="../assets/home-02-150x150.png" alt="" width="150" height="150">
            </div>
        </div>
    </footer>
</body>
</html>
