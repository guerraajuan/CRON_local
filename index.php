<?php
    require 'conexion.php';

    //echo phpinfo();

    $db = new Database();
    $con = $db->conectar();

    // $comando =$con->query("SELECT id, solicitud, lista FROM solicitud  ORDER BY id ASC");
    // $comando->execute();
    // $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    $lista = 1;

    $comando =$con->prepare("SELECT * FROM resultado WHERE id_transaccion =1  ORDER BY id ASC");
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <title>TEST</title>
</head>
<body class="py-3">
    <main class="container">
            <div class="row">
                <div class="col">
                    <h4>Solicitudes</h4>
                    <a href="nuevo.php" class="btn btn-primary float-right" >Nuevo</a>
                </div>

                <div class="row py-3">
                    <div class="col">
                        <table class="table table-border">
                            <thead>
                                <th>ID</th>
                                <th>ID TRANSACCION</th>
                                <th>ESTADO</th>
                                <th>RESULTADO</th>
                                <th></th>
                            </thead>

                            <tbody>
                                <?php foreach($resultado as $row) {?>
                                    <tr>
                                        <td><?php echo $row['id'];  ?></td>
                                        <td><?php echo $row['id_transaccion'];  ?></td>
                                        <td><?php echo $row['estado'];  ?></td>
                                        <td><?php echo $row['resultado']; $res = $row['resultado'];  ?></td>
                                         
                                    </tr>
                                <?php }?>

                            </tbody>

                        </table>
                        
                        <p>
                            <?php 
                            //echo  $res;

                            $datos = explode(chr(14), $res);
                            $titulo = $datos[42];
                            $max_giro_titulo = $datos[43];
                            $max_giro_monto = $datos[44];
                            $disponible_titulo = $datos[45];
                            $disponible_monto = $datos[47];
                            $total_texto = $datos[48];
                            $total_monto = $datos[50];
                            $total_monto = substr($total_monto, 0, -1);
                            $total_monto = substr($total_monto, 0, -1);
                            $total_monto = substr($total_monto, 0, -1);
                            $total_monto = substr($total_monto, 0, -1);

                            // echo '<pre>';
                            // print_r($datos);
                            // echo '</pre>';

                            
                            // echo  $titulo."</br></br>";
                            // echo  substr($max_giro_titulo,1)."             ".substr($max_giro_monto,1).'</br>';
                            // echo  substr($disponible_titulo,1)."             ".substr($disponible_monto,1).'</br>';
                            // echo  substr($total_texto,1)."             ".substr($total_monto,1).'</br>';

                             ?>
                        </p>

                        <table >
                            <thead>
                                <th><?php echo $titulo;  ?></th>
                                <th></th>
                                <th></th>
                            </thead>

                            <tbody>
                                    <tr>
                                        <td class="font-weight-bold"><?php echo substr($max_giro_titulo,1)  ?></td>
                                        <td><?php echo '&nbsp &nbsp';  ?></td>
                                        <td><?php echo substr($max_giro_monto,1);  ?></td>
                                         
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold"><?php echo substr($disponible_titulo,1)  ?></td>
                                        <td><?php echo '&nbsp &nbsp';  ?></td>
                                        <td><?php echo substr($disponible_monto,1);  ?></td>
                                         
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold"><?php echo substr($total_texto,1)  ?></td>
                                        <td><?php echo '&nbsp &nbsp';  ?></td>
                                        <td><?php echo substr($total_monto,1);  ?></td>
                                         
                                    </tr>
    

                            </tbody>

                        </table>
                        
                    </div>
                </div>

            </div>
    



    </main>  

    
    
</body>
</html>