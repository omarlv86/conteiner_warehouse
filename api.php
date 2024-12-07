<?php
include 'db.php';

function jsonResponse($data, $status = 200)
{
    http_response_code($status);
    header('Content-type: application/json');
    echo json_encode($data);
}


try {
    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                    if($_POST['param'] == 'getInventario'){
                        $sql = "SELECT id, numero_contenedor, tamano, estatus, activo FROM contenedor";
                        $result = $conn->query($sql);
                        $array_containers = [];
                        if ($result->num_rows > 0) {
                            while ($item = mysqli_fetch_array($result)) {
                                array_push($array_containers, [
                                    'id' => $item['id'],
                                    'numero_contenedor' => $item['numero_contenedor'],
                                    'tamano' => $item['tamano'],
                                    'estatus' => $item['estatus'],
                                    'activo' => $item['activo']
                                ]); 
                
                            }
                        }
                        $conn->close();
                        return jsonResponse(['status' =>  200, 'data' => $array_containers, 'msg' => 'Obteniendo inventario']);
                    }
                
                
            case 'GET':
                return jsonResponse(['status' =>  200, 'data' => [], 'msg' => 'Metodo GET success']);

        }
    }
} catch (Exception $e) {
    echo $e;
    return jsonResponse([
        'status' => 'error',
        'error' => $e->getMessage()
    ], 500);
}
?>