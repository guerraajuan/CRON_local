<?php



$tarjeta = '11'.chr(28).'211000000'.chr(28).chr(28).'CB800888'.chr(28).'15'.chr(28).';5224681010111223=24032068880000110000?'.chr(28).chr(28).'H       '.chr(28).'00000000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28).'116.138.361-9 PRUEBA166 SATP166'.chr(28).chr(28).'5CAM000C5A0852246810101112235F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F260830F20A8553A546039F2701809F33036040209F34034203009F3602007C950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704AE0DA8C99F3501149F2103094538'.chr(28).chr(28);
$cuenta = '11'.chr(28).'211000000'.chr(28).chr(28).'1136D589'.chr(28).'15'.chr(28).';5487423576914829=24032068880000110000?'.chr(28).chr(28).'H       '.chr(28).'00000000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28).'14.183.012-3 CARLOS VALDIVIESO D.'.chr(28).chr(28).'5CAM000C5A0854874235769148295F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F260830F20A8553A546039F2701809F33036040209F34034203009F3602007C950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704AE0DA8C99F3501149F2103094538'.chr(28).chr(28);

//echo strlen($cuenta);

//$onlyconsonants = str_replace($vowels, chr(12), "");
//echo strlen($cuenta);
// return false;

// $test2 ="24505000749'.chr(15).'BO$50.000   '.chr(15).'CK06'.chr(15).'EC30/03/2023'.chr(15).'FC$9.285    '.chr(15).'HC30/04/2023'.chr(15).'IC$9.571    '.chr(15).'KC30/05/2023'.chr(15).'LC$9.857    '.chr(15).'NC30/06/2023'.chr(15).'OC$10.143";
// $monto_giro = '';
// $monto_giro = str_pad($monto_giro, 2, "0", STR_PAD_LEFT);
// $cuenta='I';
// $oc ='I'.$cuenta.'      ';


// echo $oc;
// echo'aaa';
// $resr1 = explode(chr(15), $test);
// $resr2 = explode(chr(15), $test2);
// echo '<pre>';
// var_dump($resr1);
// echo '</pre>';

// echo '<pre>';
// var_dump($resr2);
// echo '</pre>';
// $fecha_1 =substr($resr1[3],2);
// $monto_1 = substr($resr1[4],2);

// $fecha_2 =substr($resr1[5],2);
// $monto_2 = substr($resr1[6],2);

// $fecha_3 =substr($resr1[7],2);
// $monto_3 = substr($resr1[8],2);

// $fecha_4 =substr($resr1[9],2);
// $monto_4 = substr($resr1[10],2);



//     echo $fecha_1.'</br>';
//     echo $monto_1.'</br>';
//     echo $fecha_2.'</br>';
//     echo $monto_2.'</br>';
//     echo $fecha_3.'</br>';
//     echo $monto_3.'</br>';
//     echo $fecha_4.'</br>';
//     echo $monto_4.'</br>';




//echo strlen($mensaje_deposito);



function rut( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}





$res1='1001ATM-512269949916000021270-501-000010000000-20230323-123119-000045-Z2-173800-NE-152-BUS-          11-00000000        0000000000000000-000150001300000000791-Banco Falabella Chile';

  


  //$datos1 = explode(chr(28), $res1);
$datos1 = explode(chr(29), $res1);
if(array_key_exists(1,  $datos1)){
  $res_2 = $datos1[1];
  echo $res_2.'</br>';
  $res_2 = substr($res_2, 67);
  $res_2 = substr($res_2, 0, -90);  
  $codigo_erorr = substr($res_2, 0, -18); 
  echo $codigo_erorr;
  //echo $res_2;
} 
 //$datos1 = explode(chr(14), $res1);
// $datos1 = explode(chr(14), $res3);
// $datos2 = explode(chr(14), $res2);

// echo '<pre>';
// var_dump($datos1);
// echo '</pre>';







                        
                        // echo '<pre>';
                        // var_dump($respuesta_server);
                        // echo '</pre>';
// echo'--------------------------------------------------------';
// echo '<pre>';
// var_dump($datos2);
// echo '</pre>';
// echo'--------------------------------------------------------';
// echo '<pre>';
// var_dump($datos4);
// echo '</pre>';

// $n_operacion = intval(preg_replace('/[^0-9]+/', '', $datos1[16]), 10); 
// $n_operacion = substr($n_operacion, 0,5);
// echo $n_operacion.'\n'; // resultado: 102030


// $n_tarjeta = preg_replace("/[\r\n|\n|\r]+/", " ",substr($datos1[36], 23,18));
// echo $n_tarjeta;

// $n_tarjeta =  trim (preg_replace("/[\r\n|\n|\r]+/", " ",strval($datos1[16])));
// $n_tarjeta = substr($n_tarjeta, 24,19);
// echo $n_tarjeta;
// //--------------------------------------------------------------------------------
// $fecha =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[3]),1));
// $fecha= substr($fecha, -10);
// echo $fecha;
//-----------------------------------------------------------------------
//--------------------------------------------------------------------------------
// $fecha_cont =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[5]),1));
// echo $fecha_cont;
//--------------------------------------------------------------------------------

//--------------------------------------------------------------------------------
// $hora =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[4]),1));
// echo $hora;
// //------------------------------------------------------------------------------

//--------------------------------------------------------------------------------
// $cajero =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[6]),1));
// echo $cajero;
//-------------------------------------------------------------------------------

// $nombre_op= preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-Z\s]+/u', '', $datos1[16]));
// $nombre_op = substr($nombre_op, 0,-18);
// echo $nombre_op;
//--------------------------------------------------------------------------------
// $n_operacion = intval(preg_replace('/[^0-9]+/', '', $datos1[16]), 10); 
// $n_operacion = substr($n_operacion, 0,5);
// echo $n_operacion; 
//--------------------------------------------------------------------------------

//--------------------------------------------------------------------------------
// $ncuenta =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[17]),1));
// $ncuenta = substr($ncuenta, 0,18);
// echo $ncuenta;
//---------------------

//--------------------------------------------------------------------------------
// $n_cuenta =trim( preg_replace("/[\r\n|\n|\r]+/", " ",substr($datos1[37], 2,18)));
// echo $n_cuenta;
//---------------------


// //--------------------------------------------------------------------------------
// $operacion = trim(preg_replace("/[^a-zA-Z]+/", " ",strval($datos1[17]))); 
// echo $operacion;
//----------------------------------------------------------------------------

// //--------------------------------------------------------------------------------
// $max_giro =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[21]),1));
// echo $max_giro;
//----------------------------------------------------------------------------

// $monto_op =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[20]),1));
// echo $monto_op;

// // //--------------------------------------------------------------------------------
// $disponible =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[22]),1));
// echo $disponible;
// // //----------------------------------------------------------------------------

// // //--------------------------------------------------------------------------------
// $total =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[50]),1));
// //total0 = trim($total);
// $total =  str_replace(chr(12),"",$total);
// //$total = substr($total, 0, -1);
// echo $total ;
// // // //----------------------------------------------------------------------------


// // //--------------------------------------------------------------------------------
// $car_1 =  preg_replace("/[\r\n|\n|\r]+/", " ",strval($datos1[3]));
// $car_1_array = explode(' ', $car_1);
// print_r($car_1_array);
// if(array_key_exists(3,  $car_1_array)){
//   $car_1 = $car_1_array[1];
// }
// else{
//   $car_1=substr($car_1_array[0], -1);  
// }

// echo $car_1 ;
// // // //----------------------------------------------------------------------------

// // //--------------------------------------------------------------------------------
// $car_2 =  preg_replace("/[\r\n|\n|\r]+/", " ",strval($datos1[5]));
// $car_2_array = explode(' ', $car_2);
// $car_2 = $car_2_array[1];
// echo $car_2 ;
// // // //----------------------------------------------------------------------------



// // //--------------------------------------------------------------------------------
// $fec_cap =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[14]),1));
// $fec_cap = substr($fec_cap, 0, -1);
// echo $fec_cap;
// // // //----------------------------------------------------------------------------








// $disp_cred_consumo=  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos2[6]),1));
// echo $disp_cred_consumo.'</br>'; // resultado: 102030

// $disp_compras=  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos2[8]),1));
// echo $disp_compras.'</br>'; // resultado: 102030






//  $rut_op = intval(preg_replace('/[^0-9]+/', '', $datos1[16]), 10); 
//  $rut_op = substr($rut_op, 5);
//  echo $rut_op; // resultado: 102030


// $nombre= preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-Z\s]+/u', '', $datos4[38]));--------------
// $nombre = substr($nombre, 0,-18);
// echo $nombre;

// $monto_op=  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos4[42]),1));--------------
// echo $monto_op; // resultado: 102030

// $cuotas_op=  preg_replace("/[\r\n|\n|\r]+/", " ",$datos4[43]);-----
// $cuotas_op = substr($cuotas_op,1);
// echo $cuotas_op; // resultado: 102030


// $max_giro =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos2[51]),1));
// echo $max_giro; // resultado: 102030


// $disponible =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos2[54]),1));
// echo $disponible; // resultado: 102030





// $saldo_total =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[6]),1));
// echo trim($saldo_total= str_replace(chr(12), " ", $saldo_total));

// $giros_realizados = trim( preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[18]),1)));
// echo $giros_realizados;

// $intereses = trim( preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[21]),1)));
// echo $intereses;

// $reajuste = trim( preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[24]),1)));
// echo $reajuste= str_replace(chr(12), " ", $reajuste);

// $saldo_total =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[6]),1));
// echo $saldo_total= trim($saldo_total= str_replace(chr(12), " ", $saldo_total));



// if(array_key_exists(3,  $datos1)){
//   $saldo_total =  preg_replace("/[\r\n|\n|\r]+/", " ",substr(strval($datos1[3]),1));
//   $saldo_total= trim($saldo_total= str_replace(chr(12), " ", $saldo_total));
//   echo $saldo_total;
// }

?>


