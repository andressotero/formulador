<?php include '../dao/functions_dao.php';
try {
    $data = getFormulaList($connection);

    if (isset($data['error'])) {
        echo "Error: " . $formulaData['error'];
    } else {
        $formulas =  $data['data'];
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
    <title>Listado de Clientes</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/estilos.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/list_formula.js"></script>
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
                <h2 class="heading">Listado de Clientes</h2>
                <?php if (isset($_GET['mensaje'])): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Registro eliminado!',
                            text: '<?php echo htmlspecialchars($_GET['mensaje'], ENT_QUOTES, 'UTF-8'); ?>',  
                        }).then(() => {
                            window.location.href = "list_formula.php";
                        });
                    </script>
                <?php endif; ?>


                <table border="1" cellspacing="0" cellpadding="10" id="tabla-clientes">
                    <thead>
                        <tr>
                            <th><input type="text" id="filtro-nombre" placeholder="Filtrar por Nombre"></th>
                            <th><input type="text" id="filtro-empresa" placeholder="Filtrar por Empresa"></th>
                            <th><input type="text" id="filtro-correo" placeholder="Filtrar por Correo"></th>
                            <th><input type="text" id="filtro-telefono" placeholder="Filtrar por Teléfono"></th>
                            <th style="text-align: center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($formulas) > 0): ?>
                            <?php foreach ($formulas as $formula): ?>
                                <tr>
                                    <td><?= htmlspecialchars($formula['Name']) ?></td>
                                    <td><?= htmlspecialchars($formula['Company']) ?></td>
                                    <td><?= htmlspecialchars($formula['Email']) ?></td>
                                    <td><?= htmlspecialchars($formula['Telephone']) ?></td>
                                    <td style="text-align: center">
                                        <form action="detail_formula.php" method="GET" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($formula['CustomerFormulaID']) ?>">
                                            <button type="submit">Ver Detalle</button>
                                        </form>
                                        <form class="deleteForm" method="POST" action="../dao/delete_formula.php" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($formula['CustomerFormulaID']) ?>">
                                            <button type="button" class="btn-eliminar" >Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay datos disponibles.</td>
                            </tr>
                        <?php endif; ?>
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
