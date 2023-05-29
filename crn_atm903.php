<?php
    function GetLlaveHex($llave){
        $contador =0;
        $dato = '';
        $llave_hex='';
        for ($i=0; $i<strlen($llave); $i++){
            $dato .= $llave[$i];

            $contador++;
            if($contador == 3){
                if(strlen(dechex($dato))==1) $llave_hex.= '0'.dechex($dato);
                else  $llave_hex.= dechex($dato);
                $contador =0;
                $dato='';
            }
        }
        return strtoupper($llave_hex);
    }
    function GetLado1($llaveHex){
        $lado1 = substr($llaveHex, 0, 16);
        return $lado1;

    }
    function GetLado2($llaveHex){
        $lado1 = substr($llaveHex, 16, 16);
        return $lado1;
    }
    function GetLlave_sin030($llaveCompleta){
        $llave_sin = substr($llaveCompleta, 3, 48);
        return $llave_sin;
    }
    function GetKCV($llave){
        $cifrados = openssl_get_cipher_methods();
        $plaintext = hex2bin("00000000000000000000000000000000");
        $key = hex2bin($llave);
        $algorithm = $cifrados[99];
        $encrypted = bin2hex(openssl_encrypt($plaintext, "des-ede", $key,OPENSSL_RAW_DATA));
        return strtoupper(substr($encrypted,0,6));
    }
    function Descifrar($valor){
        $cifrados2 = openssl_get_cipher_methods();
        $plaintext = $valor.'8EE0FBD1F4CEF8A1';
        $plaintext = hex2bin($plaintext);
        $key = hex2bin('875FA0CA18B2F68100E266099C3500E8');
        $algorithm = $cifrados2[99];
        $decryptedData = bin2hex(openssl_decrypt($plaintext, "des-ede", $key,OPENSSL_RAW_DATA )); 
        return strtoupper($decryptedData);
    }
    function rut_format( $rut ) {
        return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
    }

    class Database{
        private $hostname = 'localhost'; 
        private $database = 'prueba'; 
        private $username = 'root'; 
        private $password = '123456'; 
        private $charset = 'utf8'; 

        function conectar(){
            try{
                $conexion = "mysql:host=".$this->hostname.";dbname=".$this->database.";charset=".$this->charset;
                $opciones =[
                    PDO::ATTR_ERRMODE => PDO:: ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                $pdo = new PDO($conexion,$this->username,$this->password,$opciones);
                return $pdo;
            }
            catch(PDOException $e){
                echo 'ERROR CONEXION: '. $e->getMessage();
                exit;
            }
        }
    }

    $db = new Database();
    $con = $db->conectar();

    $comando =$con->query("SELECT id, solicitud, lista FROM solicitud  ORDER BY id ASC");

/////////SERVIDOR Y PUERETO////////////////////////////////////////////////////////////////////////////
    $host    = "10.1.112.150"; //QA
    //$host    = "10.140.0.243";  //DESARROLLO
    $port    = 17903;
//////////////////////////////////////////////////////////////////////////////////////////////////////

    //RESPUESTAS
    $message229 =  chr(00).chr(15).'22'.chr(28).'903000000'.chr(28).chr(28).'9';

    $message22b =  chr(00).chr(15).'22'.chr(28).'903000000'.chr(28).chr(28).'B';

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP ) or die("Could not create socket\n");

    // if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
    //     echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
    // }

    // connect to server
    $result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");

   

    
    while (true) {
        $result = socket_read ($socket, 1024) or die("Could not read server response\n");
        echo  "Respuesta del Servidor: " .$result."\n" ;
        $datos = explode(chr(28), $result);
        //print_r($datos);
        if($datos[3] =='2'){
            echo  "aqui 2\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
            echo 'largo: '.strlen($message229)."\n";
        }
        else if($datos[3] =='1A'){
            echo  "aqui 1A\n" ;         
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n");   
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='7'){
            echo  "aqui 7\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='3'){
            echo  "aqui 3\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n");
            echo "mensaje enviado:".$sent."\n"; 
        } 
        else if($datos[3] =='11' || $datos[3] =='12' || $datos[3] =='13'||$datos[3] =='14' || $datos[3] =='15' || $datos[3] =='16' || $datos[3] =='1A' || $datos[3] =='1B' || $datos[3] =='1C'||$datos[3] =='1D' || $datos[3] =='1E' || $datos[3] =='1F'  || $datos[3] =='1G' || $datos[3] =='1I'){
            echo  "aqui 11...\n" ;         
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n");  
            echo "mensaje enviado:".$sent."\n";
        } 
        else if($datos[2] =='1'|| $datos[2] =='2' || $datos[2] =='4'|| $datos[2] =='5'){
            echo  "aqui 8\n" ;         
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n");   
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='45'){
            //echo  "aqui 45\n" ; 

            $key_all= $datos[4];
            //echo  "llave completa: ".$key_all."\n";

            $llave = GetLlave_sin030($key_all);
            //echo "llave sin 030: ".  $llave."\n"; 

            $llave_hex = GetLlaveHex($llave); 
            //echo "llave en hex: ". $llave_hex."\n"; 

            $lado1 = GetLado1($llave_hex); 
            //echo "lado 1: ". $lado1."\n";

            $lado2 = GetLado2($llave_hex);
            //echo  "lado 2: ". $lado2."\n";

            $componente1 = Descifrar($lado1);
            //echo 'Componente 1: '.$componente1."\n";
            
            $componente2 =  Descifrar($lado2);
            //echo 'Componente 2: '.$componente2."\n";

            $resultado = $componente1.$componente2;
            $kcv = GetKCV($resultado);
            //echo 'KCV: '. $kcv."\n";
            $messagea45 = chr(00).chr(22).'23'.chr(28).'903000000'.chr(28).chr(28).'3'.chr(28).$kcv;
            //echo 'Mensaje enviado:'.$messagea45."\n";
            //echo 'largo: '.strlen($messagea45)."\n";

            $sent=  socket_write($socket, $messagea45, strlen($messagea45)) or die("Could not send data to server\n");   
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='42'){
            //echo  "aqui 42\n" ;
            $key_all= $datos[4];
            //echo  "llave completa: ".$key_all."\n";

            $llave = GetLlave_sin030($key_all);
            //echo "llave sin 030: ".  $llave."\n"; 

            $llave_hex = GetLlaveHex($llave); 
            //echo "llave en hex: ". $llave_hex."\n"; 

            $lado1 = GetLado1($llave_hex); 
            //echo "lado 1: ". $lado1."\n";

            $lado2 = GetLado2($llave_hex);
            //echo  "lado 2: ". $lado2."\n";

            $componente1 = Descifrar($lado1);
            //echo 'Componente 1: '.$componente1."\n";
           
            $componente2 =  Descifrar($lado2);
            //echo 'Componente 2: '.$componente2."\n";

            $resultado = $componente1.$componente2;
            $kcv = GetKCV($resultado);
            //echo 'KCV: '. $kcv."\n";
            
            
            $messagea42 = chr(00).chr(22).'23'.chr(28).'903000000'.chr(28).chr(28).'3'.chr(28).$kcv;
            //echo '</br>';
            //echo 'Mensaje enviado:'.$messagea42."\n";
            //echo 'largo: '.strlen($messagea42)."\n";
          
            $sent=  socket_write($socket, $messagea42, strlen($messagea42)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='1'){
            
            echo  "espero transacion\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
            //AQUI SE DEBE QUEDAR CON LA CONEXION ESTABLECIDA Y ESPERAR EJECUTAR TRANSACCIONES

            $comando =$con->query("SELECT id, solicitud, lista,rut,dv,tarjeta,cuenta,co_cuenta, monto_giro, monto_deposito FROM solicitud WHERE lista =0 AND atm = 501  ORDER BY id ASC");
            
            while(true){
                $comando->execute();
                $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
                if(count($resultado)){
                    foreach ($resultado as $row) {
                        $id = $row['id'];
                        $solicitud = $row['solicitud'];
                        $lista = $row['lista'];
                        $rut = $row['rut'];
                        $dv = $row['dv'];
                        $monto_giro = $row['monto_giro'];
                        $monto_deposito = $row['monto_deposito'];
                        $tarjeta = $row['tarjeta'];
                        $cuenta = $row['cuenta'];
                        $co_cuenta = $row['co_cuenta'];
                    }
                    $rut_full = $rut.$dv;
                    $rut_full = rut_format($rut_full);
                    $monto_giro = str_pad($monto_giro, 8, "0", STR_PAD_LEFT);

                    $query =$con->prepare("UPDATE solicitud SET lista =1 WHERE id = ?  ");
                    $respuesta = $query->execute(array($id));

                    $id_resultado=0;
                    if($solicitud == 1){
                   
                        $oc_ab ='IA   AB '; //cuenta corriente
                        $oc_ac ='AA   AC '; //cuenta vista
                        $oc_ad ='HA   AD '; //cuenta ahorro
                        $oc='IA   AB '; //CUENTA CORRIENTE POR DEFECTO EN CASO DE QUE NO VENGA LA CUENTA
                        if($co_cuenta == 'AB') $oc =$oc_ab;
                        else if($co_cuenta == 'AC')$oc =$oc_ac;
                        else if($co_cuenta == 'AD')$oc =$oc_ad;
                        //CONSULTA
                        //$mensaje = chr(00).chr(400).'11'.chr(28).'903000000'.chr(28).chr(28).'37698581'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'IA A AB'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26081A4A53B2C7BC9DB49F2701809F33036040209F34030201009F360202779F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F3704613B1205';
                         
                        if($rut_full != '' && $tarjeta != '' && $cuenta != ''){

                            $consulta_saldo = '11'.chr(28).'903000000'.chr(28).chr(28).'37698581'.chr(28).'11'.chr(28).';'.$tarjeta.'=?'.chr(28).chr(28).$oc.chr(28).chr(28).chr(28).$cuenta.chr(28).chr(28).'1'.$rut_full.' Elba Lazo Matta'.chr(28).chr(28).chr(28);

                            $largo_msj_consulta_saldo = strlen($consulta_saldo);
    
                            $consulta_saldo = chr(00).chr($largo_msj_consulta_saldo).$consulta_saldo;
    
                            
                            $sent=  socket_write($socket, $consulta_saldo, strlen($consulta_saldo)) or die("Could not send data to server\n");
                             echo "mensaje enviado:".$sent."\n";
                             echo "mensaje enviado:".$consulta_saldo."\n";
         
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $datos1 = explode(chr(28), $result);

                            if(array_key_exists(6,  $datos1)){
                                //respuesta con posible valor correcto
                                $validacion = $datos1[6];
                                $validacion2 = explode(chr(14), $validacion);
                                 //echo  "Dato a validar: " .count($validacion2)."\n" ;
                            
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
                
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                 }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
                
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
                 
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                                $id_resultado = $con->lastInsertId();
                            }
    
                            if($datos1[3] == 'A79'){
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $mensaje2parte = chr(00).chr(68).'11'.chr(28).'903000000'.chr(28).chr(28).'2F694E81'.chr(28).'12'.chr(28).';'.$tarjeta.'=?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).chr(28).chr(28).chr(28);
         
                              
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos2 = explode(chr(28), $result2);
                                // echo '<pre>';
                                // var_dump($datos2);
                                // echo '</pre>';
                                if(array_key_exists(6,  $datos2) && $id_resultado != 0){
                                    $resultado_2 = $datos2[6]; //guardo la parte del comprobante que me interesa
                                    echo  "Actualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
                            }
                            else{
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                            }

                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }
                    }
                    else if($solicitud == 2){

                        $oc_ab ='II   ABC'; //cuenta corriente
                        $oc_ac ='AI   ACC'; //cuenta vista
                        $oc_ad ='HI   ADC'; //cuenta ahorro
                        $oc='II   ABC'; //CUENTA CORRIENTE POR DEFECTO EN CASO DE QUE NO VENGA LA CUENTA
                        if($co_cuenta == 'AB') $oc =$oc_ab;
                        else if($co_cuenta == 'AC')$oc =$oc_ac;
                        else if($co_cuenta == 'AD')$oc =$oc_ad;


                        //GIRO
                        //$mensaje_giro = chr(00).chr(401). '11'.chr(28).'903000000'.chr(28).chr(28).'0F696081'.chr(28).'11'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'II A ABC'.chr(28).$monto_giro.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26083DE3985D5022C66C9F2701809F33036040209F34030201009F360202739F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F370436190E3D';

                        if($rut_full != '' && $tarjeta != '' && $cuenta != '' && $monto_giro != ''){

                            $mensaje_giro ='11'.chr(28).'903000000'.chr(28).chr(28).'1436BD88'.chr(28).'13'.chr(28).';'.$tarjeta.'=?'.chr(28).chr(28).$oc.chr(28).$monto_giro.chr(28).chr(28).$cuenta.chr(28).chr(28).'1'.$rut_full.' Elba Lazo Matta'.chr(28).chr(28).chr(28);

                            $largo_msj_giro = strlen($mensaje_giro);
                            $mensaje_giro = chr(00).chr($largo_msj_giro).$mensaje_giro;
    
                            echo  "solicitud de Giro: " .$mensaje_giro."\n" ;
                            $sent=  socket_write($socket, $mensaje_giro, strlen($mensaje_giro)) or die("Could not send data to server\n");
                             echo "mensaje enviado:".$sent."\n";
         
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $datos2 = explode(chr(28), $result);
    
    
                            if(array_key_exists(6,  $datos2)){
                                //respuesta con posible valor correcto
                                $validacion = $datos2[6];
                                $validacion2 = explode(chr(14), $validacion);
                         
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
             
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                     
                                 }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
             
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
             
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                                $id_resultado = $con->lastInsertId();
                            }
    
                            if($datos2[3] == 'A79'){
    
                                //echo  "dentro de A79\n" ;
    
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                //$mensaje2parte = chr(00).chr(103).'11'.chr(28).'903000000'.chr(28).chr(28).'24696181'.chr(28).'03'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'CCCCCCCC'.chr(28).$monto_giro.chr(28).'71?1673198?0::9?'.chr(28).chr(28);
    
                                $mensaje2parte = chr(00).chr(68).'11'.chr(28).'903000000'.chr(28).chr(28).'24696181'.chr(28).'14'.chr(28).';4097673861485332=?'.chr(28).chr(28).'CCCCCCCC'.chr(28).$monto_giro.chr(28).chr(28).chr(28).chr(28);
    
                                // $mensaje2parte = chr(00).chr(68).'11'.chr(28).'903000000'.chr(28).chr(28).'2F694E81'.chr(28).'12'.chr(28).';4097672961726702=?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).chr(28).chr(28).chr(28);
        
                            
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                               
                                
                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos2 = explode(chr(28), $result2);
                                if(array_key_exists(6,  $datos2) && $id_resultado != 0){
                                    $resultado_2 = $datos2[6];
                                    $validacion2 = explode(chr(14), $resultado_2);
                                    // echo '<pre>';
                                    // var_dump($validacion2);
                                    // echo '</pre>';
    
                                    echo  "Ayctualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }
    
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                            }
                            else{
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                            }

                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }

                    }
                    else if($solicitud == 3){

                        $oc_ab ='IB   AB '; //cuenta corriente
                        $oc_ac ='IIAI    '; //cuenta vista
                        $oc_ad ='HB   AD '; //cuenta ahorro
                        $oc='IB   AB '; //CUENTA CORRIENTE POR DEFECTO EN CASO DE QUE NO VENGA LA CUENTA
                        if($co_cuenta == 'AB'){
                            $oc =$oc_ab;
                            $cuenta = '0000000000000001'.$cuenta.'500';
                        } 
                        else if($co_cuenta == 'AC'){
                            $oc =$oc_ac;
                            $cuenta = '0000000000000001'.$cuenta.'500';
                        }
                        else if($co_cuenta == 'AD'){
                            $oc =$oc_ad;
                            //$tarjeta = '603142'.$cuenta;
                            $cuenta = '0000000000000080'.$cuenta.'500';
                        }

                        //DEPOSITO 
                        //$mensaje_deposito = chr(00).chr($largo_mensaje_deposito).'11'.chr(28).'903000000'.chr(28).chr(28).'AC375284'.chr(28).'11'.chr(28).';4097673715411864=0'.chr(28).chr(28).'IB   AB '.chr(28).chr(28).chr(28).'0000000000000001010013469450500'.chr(28).chr(28).'117.770.221-8 Elba Lazo Matta'.chr(28).chr(28).$monto_deposito.chr(28);

                        if($rut_full != '' && $tarjeta != '' && $cuenta != '' && $monto_deposito != ''){
                            //funcinando
                            $mensaje_deposito = '11'.chr(28).'903000000'.chr(28).chr(28).'AC375284'.chr(28).'11'.chr(28).';'.$tarjeta.'=0'.chr(28).chr(28).$oc.chr(28).chr(28).chr(28).$cuenta.chr(28).chr(28).'1'.$rut_full.' Elba Lazo Matta'.chr(28).chr(28).$monto_deposito.chr(28);                                                            

                            $largo_msj_deposito = strlen($mensaje_deposito);
                            $mensaje_deposito = chr(00).chr($largo_msj_deposito).$mensaje_deposito;
                            

                            echo "mensaje enviado:".$mensaje_deposito."\n";
                            $sent=  socket_write($socket, $mensaje_deposito, strlen($mensaje_deposito)) or die("Could not send data to server\n");
                            echo "mensaje enviado:".$sent."\n";
                            //echo "mensaje enviado:".$mensaje_deposito."\n";
        
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $datos2 = explode(chr(28), $result);

                            if(array_key_exists(6,  $datos2)){
                                //respuesta con posible valor correcto
                                $validacion = $datos2[6];
                                $validacion2 = explode(chr(14), $validacion);
                                
                                // echo '<pre>';
                                // var_dump($validacion2);
                                // echo '</pre>';

                        
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
            
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$mensaje));
                                    
                                }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
            
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
            
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$mensaje));
                            }

                            echo  "respondo transaccion ok\n" ;
                            $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";


                            if($datos2[3] == 'A79'){ //EXISTE SEGUNDA PARTE DEL COMPROBANTE


                                $mensaje2parte = chr(00).chr(83).'11'.chr(28).'903000000'.chr(28).chr(28).'AC374184'.chr(28).'12'.chr(28).';4097672961726702=0'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).chr(28);
                            
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";


                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos3 = explode(chr(28), $result2);


                                if(array_key_exists(6,  $datos3) && $id_resultado != 0){
                                    $resultado_2 = $datos3[6];
                                    $validacion4 = explode(chr(14), $resultado_2);

                                    echo  "Ayctualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }

                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
                            }
                            
                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }



                    }
                    else if($solicitud == 4){
                        //CONSULTA DE SALDO TARJETA DE CREDITO
                     

                        if($rut_full != '' && $tarjeta != ''){

                        
                            
                            $consulta_saldo = chr(00).chr(443).'11'.chr(28).'903000000'.chr(28).chr(28).'1136D589'.chr(28).'15'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'H       '.chr(28).'00000000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28).'1'.$rut_full.' NOMBRE_MIGRA APELLIDO_MIGR'.chr(28).chr(28).'5CAM000C5A08'.$tarjeta.'5F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F260830F20A8553A546039F2701809F33036040209F34034203009F3602007C950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704AE0DA8C99F3501149F2103094538'.chr(28).chr(28);

                            // $consulta_saldo = chr(00).chr(442).'11'.chr(28).'903000000'.chr(28).chr(28).'1136D589'.chr(28).'15'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'H       '.chr(28).'00000000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28).'14.183.012-3 NOMBRE_MIGRA APELLIDO_MIGR'.chr(28).chr(28).'5CAM000C5A08'.$tarjeta.'5F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F260830F20A8553A546039F2701809F33036040209F34034203009F3602007C950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704AE0DA8C99F3501149F2103094538'.chr(28).chr(28);

                            echo "mensaje enviado:".$consulta_saldo."\n";
                            $sent=  socket_write($socket, $consulta_saldo, strlen($consulta_saldo)) or die("Could not send data to server\n");
                            echo "mensaje enviado:".$sent."\n";
        
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $respuesta_server = explode(chr(28), $result);

                        
                            if(array_key_exists(6,  $respuesta_server)){
                                //respuesta con posible valor correcto
                                $validacion = $respuesta_server[6];
                                $validacion2 = explode(chr(14), $validacion);
                                //echo  "Dato a validar: " .count($validacion2)."\n" ;
                        
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
            
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
            
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
            
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                                $id_resultado = $con->lastInsertId();
                            }

                            if($respuesta_server[3] == 'A79'){
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";

                                $mensaje2parte = chr(00).chr(104).'11'.chr(28).'903000000'.chr(28).chr(28).'1536D689'.chr(28).'16'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28);

                                //$mensaje2parte = chr(00).chr(104).'11'.chr(28).'903000000'.chr(28).chr(28).'CE800988'.chr(28).'16'.chr(28).';'.$tarjeta.'=24122069270100110000?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).'47535?35;;16=7<4'.chr(28).chr(28).chr(28);
    
                        
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";

                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos2 = explode(chr(28), $result2);
                                // echo '<pre>';
                                // var_dump($datos2);
                                // echo '</pre>';
                                if(array_key_exists(6,  $datos2) && $id_resultado != 0){
                                    $resultado_2 = $datos2[6]; //guardo la parte del comprobante que me interesa
                                    echo  "Actualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";


                            }
                            else{
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
                            }

                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }

                    }
                    else if($solicitud == 5){
                         //AVANCE CMR PRIMER PASO
                        $co_cuenta = str_pad($co_cuenta, 2, "0", STR_PAD_LEFT);
                       

                        //$mensaje_giro = chr(00).chr(401). '11'.chr(28).'903000000'.chr(28).chr(28).'0F696081'.chr(28).'11'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'II A ABC'.chr(28).$monto_giro.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26083DE3985D5022C66C9F2701809F33036040209F34030201009F360202739F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F370436190E3D';

                        //$mensaje_1_avance = chr(00).chr(439).'11'.chr(28).'903000000'.chr(28).chr(28).'1136388A'.chr(28).'13'.chr(28).';5487423576914829=24032068880000110000?'.chr(28).chr(28).'I       '.chr(28).'00005000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).'03'.chr(28).'16.895.241-7 NOMBRE_MIGRA APELLIDO_MIGR'.chr(28).chr(28).'5CAM000C5A0854874235769148295F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F2608DA05257429A8BF519F2701809F33036040209F34034203009F3602007E950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704EEC4E88D9F3501149F2103095159'.chr(28).chr(28);;

                        if($rut_full != '' && $tarjeta != '' && $cuenta != '' && $co_cuenta != '' && $monto_giro != ''){

                            $mensaje_1_avance = '11'.chr(28).'903000000'.chr(28).chr(28).'1136388A'.chr(28).'13'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'I       '.chr(28).$monto_giro.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).$co_cuenta.chr(28).'1'.$rut_full.' '.$cuenta.chr(28).chr(28).'5CAM000C5A08'.$tarjeta.'5F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F2608DA05257429A8BF519F2701809F33036040209F34034203009F3602007E950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F3704EEC4E88D9F3501149F2103095159'.chr(28).chr(28);

                            $largo_msj_1_avance = strlen($mensaje_1_avance)+7;
    
                            $mensaje_1_avance = chr(00).chr($largo_msj_1_avance).$mensaje_1_avance;
         
    
                            echo "mensaje enviado:".$mensaje_1_avance."\n";
                            $sent=  socket_write($socket, $mensaje_1_avance, strlen($mensaje_1_avance)) or die("Could not send data to server\n");
                             echo "mensaje enviado:".$sent."\n";
         
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $respuesta_server = explode(chr(28), $result);
    
   
    
                            if(array_key_exists(5,  $respuesta_server) ){
                                $respuesta_fechas = explode(chr(15), $respuesta_server[5]);
                                if(count($respuesta_fechas)<2){
                                    //respuesta de rechazo de la transaccion
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$respuesta_server[6]));
                                    $id_resultado = $con->lastInsertId();
                                }
                                else{
                                    //respuesta con fechas y montos
                                    echo  "guardo en base de datos fechas y montos\n" ;
                                    $info = $respuesta_server[5];
                 
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$info));
                                    $id_resultado = $con->lastInsertId();
                                }
    
                            }else{
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$respuesta_server));
                                $id_resultado = $con->lastInsertId();
    
                            }
    
                            echo  "respondo transaccion ok\n" ;
                            $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";

                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }

      
                        


                    }
                    else if($solicitud == 6){
                        $co_cuenta = str_pad($co_cuenta, 2, "0", STR_PAD_LEFT);

                        if($rut_full != '' && $tarjeta != '' && $cuenta != '' && $co_cuenta != '' && $monto_giro != '' && $monto_deposito != ''){

                            $mensaje_2_avance = '11'.chr(28).'903000000'.chr(28).chr(28).'F6354A8A'.chr(28).'15'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'II      '.chr(28).$monto_giro.chr(28).'0>1?875<2?7>47::'.chr(28).$cuenta.chr(28).$co_cuenta.chr(28).'1'.$rut_full.' '.$monto_deposito.chr(28).chr(28).'5CAM000C5A08'.$tarjeta.'5F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F26080F31807BE2B5567C9F2701809F33036040209F34034203009F3602007F950580000480009F02060000000000009F03060000000000005F2A0201529A032302109F370470F1E6B59F3501149F2103095211'.chr(28).chr(28);



                        
                            $largo_msj_2_avance = strlen($mensaje_2_avance)+7;
    
                            $mensaje_2_avance = chr(00).chr($largo_msj_2_avance).$mensaje_2_avance;
         
    
                            echo "mensaje enviado:".$mensaje_2_avance."\n";
                            $sent=  socket_write($socket, $mensaje_2_avance, strlen($mensaje_2_avance)) or die("Could not send data to server\n");
                             echo "mensaje enviado:".$sent."\n";
         
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $respuesta_server = explode(chr(28), $result);
    
                            
                            // echo '<pre>';
                            // var_dump($respuesta_server);
                            // echo '</pre>';
    
                            if(array_key_exists(6,  $respuesta_server)){
                                //respuesta con posible valor correcto
                                $validacion = $respuesta_server[6];
                                $validacion2 = explode(chr(14), $validacion);
                                 //echo  "Dato a validar: " .count($validacion2)."\n" ;
                            
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
                
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                 }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
                
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
                 
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                                $id_resultado = $con->lastInsertId();
                            }
    
                            if($respuesta_server[3] == 'A79'){
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $mensaje2parte = chr(00).chr(104).'11'.chr(28).'903000000'.chr(28).chr(28).'12364B8A'.chr(28).'16'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00005000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28);
         
                              
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos2 = explode(chr(28), $result2);
                                // echo '<pre>';
                                // var_dump($datos2);
                                // echo '</pre>';
                                if(array_key_exists(6,  $datos2) && $id_resultado != 0){
                                    $resultado_2 = $datos2[6]; //guardo la parte del comprobante que me interesa
                                    echo  "Actualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
    
                            }
                            else{
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                            }
    
                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }

                    }
                    else if($solicitud == 7){
                        //SUPERAVANCE CMR
                        $monto_giro = str_pad($monto_giro, 8, "0", STR_PAD_LEFT);

                        if($rut_full != '' && $tarjeta != '' && $cuenta != '' && $co_cuenta != '' && $monto_giro != '' && $monto_deposito != ''){
                            $mensaje_super_avance = '11'.chr(28).'903000000'.chr(28).chr(28).'B91FD48D'.chr(28).'11'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'A       '.chr(28).$monto_giro.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).$co_cuenta.chr(28).'1'.$rut_full.' '.$cuenta.chr(28).chr(28).'5CAM000C5A08'.$tarjeta.'5F340100820239008407A00000000410109C01019F1A0201529F10120110A00001220000000000000000000000FF9F260885F1AC519496FADB9F2701809F33036040209F34034203009F360200A6950580000480009F02060000001200009F03060000001200005F2A0201529A032303069F3704F638142F9F3501149F2103104918'.chr(28).chr(28);
                        
                      
                            $largo_super_avance = strlen($mensaje_super_avance)+7;
    
                            $mensaje_super_avance = chr(00).chr($largo_super_avance).$mensaje_super_avance;
         
    
                            echo "mensaje enviado:".$mensaje_super_avance."\n";
                            $sent=  socket_write($socket, $mensaje_super_avance, strlen($mensaje_super_avance)) or die("Could not send data to server\n");
                             echo "mensaje enviado:".$sent."\n";
         
                            $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result."\n" ;
                            $respuesta_server = explode(chr(28), $result);
    
                            
                            // echo '<pre>';
                            // var_dump($respuesta_server);
                            // echo '</pre>';
    
                            if(array_key_exists(6,  $respuesta_server)){
                                //respuesta con posible valor correcto
                                $validacion = $respuesta_server[6];
                                $validacion2 = explode(chr(14), $validacion);
                                 //echo  "Dato a validar: " .count($validacion2)."\n" ;
                            
                                if(count($validacion2)<2){
                                    echo  "guardo en base de datos respuesta de error\n" ;
                
                                    $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                 }
                                else{
                                    echo  "guardo en base de datos respues satisfactoria\n" ;
                
                                    $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                    $respuesta = $query->execute(array($id,$validacion));
                                    $id_resultado = $con->lastInsertId();
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respuesta de error\n" ;
                 
                                $mensaje = 'No se pudo completar la transaccion, valide los datos';
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                                $id_resultado = $con->lastInsertId();
                            }
    
                            if($respuesta_server[3] == 'A79'){
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $mensaje2parte = chr(00).chr(104).'11'.chr(28).'903000000'.chr(28).chr(28).'12364B8A'.chr(28).'16'.chr(28).';'.$tarjeta.'=24032068880000110000?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00005000'.chr(28).'0>1?875<2?7>47::'.chr(28).chr(28).chr(28);
         
                              
                                echo  "solicito segunda parte\n" ;
                                $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                                $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                echo  "Respuesta del Servidor: " .$result2."\n" ;
                                $datos2 = explode(chr(28), $result2);
                                // echo '<pre>';
                                // var_dump($datos2);
                                // echo '</pre>';
                                if(array_key_exists(6,  $datos2) && $id_resultado != 0){
                                    $resultado_2 = $datos2[6]; //guardo la parte del comprobante que me interesa
                                    echo  "Actualizo tabla resultado y guardo resultado_2\n" ;
                                    $query =$con->prepare("UPDATE resultado SET resultado_2 = ? WHERE id = ?");
                                    $respuesta = $query->execute(array($resultado_2,$id_resultado));
                                }
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
    
                            }
                            else{
                                echo  "respondo transaccion ok\n" ;
                                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                echo "mensaje enviado:".$sent."\n";
    
                            }

                        }
                        else{
                            echo  "Faltan datos para hacer la transaccion:" ;
                            $validacion = 'Faltan datos para hacer la transaccion';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$validacion));
                            $id_resultado = $con->lastInsertId();

                        }







                    }


                }
                
            }
        }

    }


?>


