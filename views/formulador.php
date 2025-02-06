<?php include '../dao/functions_dao.php'; 
    try {
        $data = getInitInfo($connection);
    
        if (isset($data['error'])) {
            echo "Error: " . $formulaData['error'];
        } else {
            $allresult = $data['allresult'];
            $compounds = $data['compounds'];
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
  <title>Formulador Mineralia</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../styles/estilos.css">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="../js/scripts.js" defer></script>
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
                <h2 class="heading">Datos de contacto</h2>
                <form id="form-contacto">
                    <div class="form-group">
                        <div class="controls">
                            <input type="text" id="nombre" class="floatLabel" name="nombre" required>
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="controls">
                            <input type="text" id="empresa" class="floatLabel" name="empresa" required>
                            <label for="empresa">Nombre de la empresa</label>
                        </div>
                        <div class="controls">
                            <input type="text" id="correo" class="floatLabel" name="correo" required>
                            <label for="correo">Correo</label>
                        </div>
                        <div class="controls">
                            <input type="text" id="telefono" class="floatLabel" name="telefono" required>
                            <label for="telefono">Teléfono</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                    <h2 class="heading">Productos</h2>
                    <form id="form-productos" onsubmit="event.preventDefault(); enviarDatos();">
                        <table class="table-products">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>DOSIS KG/HA</th>
                                </tr>
                            </thead>
                            <tbody class="products">
                                <?php
                                    $allresult = array_map(function ($item) {
                                        return [
                                            'Name' => htmlspecialchars($item['Name']),
                                            'Value' => htmlspecialchars($item['Value']),
                                            'ProductID' => htmlspecialchars($item['ProductID']),
                                            'Symbol' => htmlspecialchars($item['Symbol']),
                                        ];
                                    }, $allresult);
                                    
                                    $compounds = array_map(function ($item) {
                                        return [
                                            'Symbol' => htmlspecialchars($item['Symbol']),
                                        ];
                                    }, $compounds);

                                    $nombresVistos = [];
                                    function filtrarDuplicados($producto) {
                                        global $nombresVistos;
                                        if (in_array($producto['Name'], $nombresVistos)) {
                                            return false;
                                        } else {
                                            $nombresVistos[] = $producto['Name'];
                                            return true;
                                        }
                                    }
                                    $productosFiltrados = array_filter($allresult, 'filtrarDuplicados');
                                ?>
                                <input type="hidden" name="allresult" value="<?php echo htmlspecialchars(json_encode($allresult)); ?>">
                                <input type="hidden" name="compounds" value="<?php echo htmlspecialchars(json_encode($compounds)); ?>">
                                <?php foreach ($productosFiltrados as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                        <td><input type="number" class="form-control" name="dosis_kg[<?php echo htmlspecialchars($row['Name']); ?>]"></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="total-row">
                                    <td><strong>Total Dosis</strong></td>
                                    <td id="total-dosis" style="text-align: center">0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
            </div>
        </div>
       
        <div class="row sticky-bottom background_components">
            <div class="col-12">
                <h2 class="heading">Componentes</h2>
                <div class="table-responsive sumatoria_componentes" id="sumatoria_componentes"> <!-- Contenedor responsivo para la tabla -->
                    <table id="sumatoria_componentes" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th> </th>
                                <?php foreach ($compounds as $compound): ?>
                                    <th><?php echo htmlspecialchars($compound['Symbol']); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>% EN 100 KG</strong></td>
                                <?php foreach ($compounds as $compound): ?>
                                    <td>0.00</td>
                                <?php endforeach; ?>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 text-center">
                <button type="submit" id="enviar-btn" form="data-form" class="btn-custome">Confirmar y Enviar</button>         
            </div>
        </div>
        </br>
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

