<?php
                    $query =$con->prepare("INSERT INTO solicitud (solicitud, lista) VALUES (1,0)");
                    $respuesta = $query->execute();
                    //$id = $con->lastInsertId();
                ?>



<?php
                    $query =$con->prepare("SELECT * FROM resultado WHERE id_transaccion =5");
                    $mostrar = '';

                    while(true){
                        
                        $query->execute();
                        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

                        if(count($resultado)){
                            $datos = explode(chr(14), $resultado);
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



                            $mostrar= '<table >
                                            <thead>
                                                <th>'. $titulo.' </td></th>
                                                <th></th>
                                                <th></th>
                                            </thead>

                                            <tbody>
                                                    <tr>
                                                        <td class="font-weight-bold"> '. substr($max_giro_titulo,1).' </td>
                                                        <td>< "&nbsp &nbsp" ></td>
                                                        <td> '. substr($max_giro_monto,1).' </td>                                     
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">'. substr($disponible_titulo,1).' </td>
                                                        <td> "&nbsp &nbsp"  </td>
                                                        <td> '. substr($disponible_monto,1).' </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold"> '. substr($total_texto,1).' </td>
                                                        <td> "&nbsp &nbsp" </td>
                                                        <td> '. substr($total_monto,1).' </td>
                                                    </tr>
                                            </tbody>
                                        </table>';

                            break(1);
 
                        }
                        echo 'dentro';
                    }
                    echo 'sali';
                ?>




$('#titulo').text(<?php echo $titulo; ?>);
                //  $('#max_giro_titulo').text(<?php echo "'".substr($max_giro_titulo,1)."'"; ?>);
                //  $('#max_giro_monto').text(<?php echosubstr($max_giro_monto,1); ?>);
                //  $('#disponible_titulo').text(<?php echo substr($disponible_titulo,1); ?>);
                //  $('#disponible_monto').text(<?php echo substr($disponible_monto,1); ?>);
                //  $('#total_titulo').text(<?php echo substr($total_texto,1); ?>);
                //  $('#total_monto').text(<?php echo substr($total_monto,1) ; ?>);
                $('#recibo').show() 




                // else if(paso == 3){
        //     let estado = $('#estado').val();
        //     if(estado == 1){
        //         $('#mensaje').hide()
        //         $('#titulo_consulta').text(<?php echo "'".preg_replace("/[\r\n|\n|\r]+/", " ", $titulo)."'"; ?>);
        //         $('#max_giro_titulo').text(<?php echo "'".substr($max_giro_titulo,1)."'"; ?>);
        //         $('#max_giro_monto').text(<?php echo "'".preg_replace("/[\r\n|\n|\r]+/", " ",substr($max_giro_monto,1))."'"; ?>);
        //         $('#disponible_titulo').text(<?php echo "'".substr($disponible_titulo,1)."'"; ?>);
        //         $('#disponible_monto').text(<?php echo "'".preg_replace("/[\r\n|\n|\r]+/", " ",substr($disponible_monto,1))."'"; ?>);
        //         $('#total_titulo').text(<?php echo "'".substr($total_titulo,1)."'"; ?>);
        //         $('#total_monto').text(<?php echo "'".preg_replace("/[\r\n|\n|\r]+/", " ",substr($total_monto,1))."'"; ?>);
        //         $('#recibo').show()
        //     }
        //     else{
        //         $('#mensaje').hide()
        //         $('#titulo_consulta').text('No se pudo completar la transaccion, valide los datos');
        //         $('#recibo').show()
        //     }
        //     setTimeout(goto_paso, 7000);
        // }