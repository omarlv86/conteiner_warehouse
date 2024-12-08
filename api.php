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
                        $sql = "SELECT id, numero_contenedor, tamano, flujo, activo FROM contenedor";
                        $result = $conn->query($sql);
                        $array_containers = [];
                        if ($result->num_rows > 0) {
                            while ($item = mysqli_fetch_array($result)) {
                                array_push($array_containers, [
                                    'id' => $item['id'],
                                    'numero_contenedor' => $item['numero_contenedor'],
                                    'tamano' => $item['tamano'],
                                    'flujo' => $item['flujo'],
                                    'activo' => $item['activo']
                                ]); 
                
                            }
                        }
                        $conn->close();
                        return jsonResponse(['status' =>  200, 'data' => $array_containers, 'msg' => 'Obteniendo inventario']);
                    }
                    if($_POST['param'] == 'insertarContenedor'){    
                        $conn->begin_transaction();
                        try{
                            $contenedores = $_POST['contenedores'];
                            $numero_economico = $_POST['numero_economico'];
                            $placas = $_POST['placas_unidad'];
                            $nombre_conductor = $_POST['nombre_conductor'];
                            $flujo = $_POST['flujo'];
                            $today = date('Y-m-d H:i:s');

                            $sqlInsertarCamion= "INSERT INTO camion (numero_placas, nombre_conductor, numero_economico) VALUES ('".$placas."', '".$nombre_conductor."', $numero_economico)";
                            if ($conn->query($sqlInsertarCamion) === TRUE) {
                                $idCamion = $conn->insert_id;
                            }

                            foreach ($contenedores as $key => $value) {
                                $sqlValidarContenedor = "SELECT contenedor.idcontenedor, numero, tipo_movimiento FROM contenedor 
                                                         LEFT JOIN movimiento ON contenedor.idcontenedor = movimiento.id_contenedor
                                                         WHERE contenedor.numero = '".$value['numero']."'
                                                         ORDER BY fecha_movimiento DESC
                                                         LIMIT 1;";
                                $resultValidContenedor = $conn->query($sqlValidarContenedor);
                                if($resultValidContenedor->num_rows == 0){
                                    $sqlInsertarContenedor = "INSERT INTO contenedor (numero, tamano) VALUES ('".$value['numero']."', '".$value['tamano']."')";
                                    if ($conn->query($sqlInsertarContenedor) === TRUE) {
                                        $idContenedor = $conn->insert_id;
                                    }
                                    $sqlInsertarMovimiento = "INSERT INTO movimiento (fecha_movimiento, tipo_movimiento, id_contenedor, id_camion) VALUES ('$today', '$flujo', $idContenedor, $idCamion)";
                                    $conn->query($sqlInsertarMovimiento);
                                    
                                }else{
                                    $row = $resultValidContenedor->fetch_assoc();

                                    if($row['tipo_movimiento'] == "entrada"){
                                        return jsonResponse(['status' =>  200, 'data' => [], 'msg' => 'El contenedor '.$value['numero'].' ya existe dentro del almacen...']);
                                    }else{
                                        $idContenedor = $row['idcontenedor'];
                                        $sqlInsertarMovimiento = "INSERT INTO movimiento (fecha_movimiento, tipo_movimiento, id_contenedor, id_camion) VALUES ('$today', '$flujo', $idContenedor, $idCamion)";
                                        $conn->query($sqlInsertarMovimiento);
                                    }
                                }
                            }
                            $conn->commit();
                            return jsonResponse(['status' =>  200, 'msg' => 'Registro correcto...']);


                        } catch (Exception $e) {
                            // Si hubo un error, se hace rollback
                            $mysqli->rollback();
                            echo "Error: " . $e->getMessage();
                            return jsonResponse(['status' =>  500, 'error' => $e->getMessage()]);
                        }
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