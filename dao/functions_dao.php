<?php include 'conexion.php';

function getFormulaData($connection, $id) {
    $data = [];

    try {
        // Obtener datos de la fórmula
        $query = "SELECT cf.CustomerFormulaID, c.Name, c.Company, c.Email, c.Telephone, cf.DosageKg 
                  FROM Customer_Formula cf 
                  INNER JOIN Customer c ON c.CustomerID = cf.CustomerID 
                  WHERE cf.CustomerFormulaID = :CustomerFormulaID";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':CustomerFormulaID', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data['formula'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener productos
        $queryProducto = "SELECT p.Name, fp.CustomValue 
                          FROM Formula_Product fp 
                          INNER JOIN Customer_Formula cf ON cf.CustomerFormulaID = fp.CustomerFormulaID 
                          INNER JOIN Product p ON p.ProductID = fp.ProductComponentID 
                          WHERE fp.CustomerFormulaID = :CustomerFormulaID";
        $stmtProd = $connection->prepare($queryProducto);
        $stmtProd->bindParam(':CustomerFormulaID', $id, PDO::PARAM_INT);
        $stmtProd->execute();
        $data['products'] = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Manejar el error
        $data['error'] = $e->getMessage();
    }

    return $data;
}

function getFormulaList($connection){
    $formulas =[];
    try {
        $query = "SELECT cf.CustomerFormulaID, c.Name, c.Company, c.Email, c.Telephone, cf.DosageKg 
                  FROM Customer_Formula cf 
                  Inner join Customer c ON c.CustomerID  = cf.CustomerID";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $formulas['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
        $formulas['error'] = $e->getMessage();
    }
   
    return $formulas;
}

function getInitInfo($connection){
    $data = [];
    try {
        $getProductComponent = "SELECT pc.ProductID, p.Name, pc.Value, c.Symbol FROM `Product_Component` pc INNER JOIN Product p ON pc.ProductID = p.ProductID INNER JOIN Compound c ON pc.CompoundID = c.CompoundID";
        $getcomponents = "SELECT c.Name, c.Symbol FROM `Compound` c";
        $statement_components = $connection->prepare($getcomponents); 
        $statement = $connection->prepare($getProductComponent);
        $statement_components->execute();
        $statement->execute();

        $data['allresult'] = $statement->fetchAll(PDO::FETCH_ASSOC);
        $data['compounds'] = $statement_components->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $data['error'] = $e->getMessage();
    }
    
    return $data;
}

?>