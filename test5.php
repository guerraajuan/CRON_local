<?php
    $from = "";
    $rut= "";
    $dv = "";
    $tarjeta = "";
    $estado = "";
    $fecha_1 ="";
    $monto_1 ="";
    $fecha_2 ="";
    $monto_2 ="";
    $fecha_3 ="";
    $monto_3 ="";
    $fecha_4 ="";
    $monto_4 ="";
    $monto="";
    $cuotas="";
    


    if(isset($_REQUEST["from"])) $from = $_REQUEST["from"];
    if(isset($_REQUEST["rut"])) $rut = $_REQUEST["rut"];
    if(isset($_REQUEST["dv"])) $dv = $_REQUEST["dv"];

    if(isset($_REQUEST["tarjeta"])) $tarjeta = $_REQUEST["tarjeta"];
    if(isset($_REQUEST["estado"])) $estado = $_REQUEST["estado"];

    if(isset($_REQUEST["fecha_1"])) $fecha_1 = $_REQUEST["fecha_1"];
    if(isset($_REQUEST["fecha_2"])) $fecha_2 = $_REQUEST["fecha_2"];
    if(isset($_REQUEST["fecha_3"])) $fecha_3 = $_REQUEST["fecha_3"];
    if(isset($_REQUEST["fecha_4"])) $fecha_4 = $_REQUEST["fecha_4"];

    if(isset($_REQUEST["monto_1"])) $monto_1 = $_REQUEST["monto_1"];
    if(isset($_REQUEST["monto_2"])) $monto_2 = $_REQUEST["monto_2"];
    if(isset($_REQUEST["monto_3"])) $monto_3 = $_REQUEST["monto_3"];
    if(isset($_REQUEST["monto_4"])) $monto_4 = $_REQUEST["monto_4"];

    if(isset($_REQUEST["monto"])) $monto = $_REQUEST["monto"];
    if(isset($_REQUEST["cuotas"])) $cuotas = $_REQUEST["cuotas"];

    // echo $from.'</br>';
    // echo $cta1.'</br>';
    // echo $cta2.'</br>';
    // echo $cta3.'</br>';
    // echo $ncta1.'</br>';
    // echo $ncta2.'</br>';
    // echo $ncta3.'</br>';
    // echo $cta1_codigo.'</br>';
    // echo $cta2_codigo.'</br>';
    // echo $cta3_codigo.'</br>';
  
?>

<table>
    <thead>
        <tr>
            <th class="text-center"  style="color:#3c763d; padding-bottom:60px;" colspan="2" >
                <h3 class="poppins" style="color:#3c763d;"> 
                CUANDO LE GUSTARIA </br> COMENZAR A PAGAR </br> MONTO:$ xxxxx </br> XX CUOTAS DE
                </h3>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:50%;">
                <div class="teaser with_border rounded text-center pt-0 m-0" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color2 text-left m-0 p-0">
                        <a  onclick="javascript:get_seleccion();">
                            <?php echo $fecha_1; ?> </br>
                            <?php echo $monto_1; ?>
                        </a>
                    </h4>
                </div>
            </td>
            <td  style="width:50%;" >
                <div class="teaser with_border rounded text-center" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color3 text-right">
                    <a style="color:white;" href="#">SIN PRODUCTO </a>
                    </h4>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                <div class="teaser with_border rounded text-center pt-0 m-0" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color2 text-left m-0 p-0">
                        <a  onclick="javascript:get_seleccion();">
                            <?php echo $fecha_2; ?> </br>
                            <?php echo $monto_2; ?>
                        </a>
                    </h4>
                </div>
            </td>
            <td  style="width:50%;" >
                <div class="teaser with_border rounded text-center" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color3 text-right">
                    <a style="color:white;" href="#">SIN PRODUCTO </a>
                    </h4>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                <div class="teaser with_border rounded text-center pt-0 m-0" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color2 text-left m-0 p-0">
                        <a  onclick="javascript:get_seleccion();">
                            <?php echo $fecha_3; ?> </br>
                            <?php echo $monto_3; ?>
                        </a>
                    </h4>
                </div>
            </td>
            <td  style="width:50%;" >
                <div class="teaser with_border rounded text-center" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color3 text-right">
                    <a style="color:white;" href="#">SIN PRODUCTO </a>
                    </h4>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                <div class="teaser with_border rounded text-center pt-0 m-0" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color2 text-left m-0 p-0">
                        <a  onclick="javascript:get_seleccion();">
                            <?php echo $fecha_4; ?> </br>
                            <?php echo $monto_4; ?>
                        </a>
                    </h4>
                </div>
            </td>
            <td  style="width:50%;" >
                <div class="teaser with_border rounded text-center" style="cursor: pointer;" onclick="">
                    <h4 class="poppins hover-color3 text-right">
                    <a style="color:white;" href="#">VOLVER </a>
                    </h4>
                </div>
            </td>
        </tr>



    </tbody>
</table>



