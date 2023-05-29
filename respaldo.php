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
    $host    = "10.1.112.150";
    $port    = 16118;
//////////////////////////////////////////////////////////////////////////////////////////////////////

    //RESPUESTAS
    $message229 =  chr(00).chr(15).'22'.chr(28).'211000000'.chr(28).chr(28).'9';

    $message22b =  chr(00).chr(15).'22'.chr(28).'211000000'.chr(28).chr(28).'B';

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP ) or die("Could not create socket\n");
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
            $sent=  socket_write($socket, $message7, strlen($message7)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='3'){
            echo  "aqui 3\n" ;
            $sent=  socket_write($socket, $message3, strlen($message3)) or die("Could not send data to server\n");
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
            $messagea45 = chr(00).chr(22).'23'.chr(28).'211000000'.chr(28).chr(28).'3'.chr(28).$kcv;
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
            
            
            $messagea42 = chr(00).chr(22).'23'.chr(28).'211000000'.chr(28).chr(28).'3'.chr(28).$kcv;
            //echo '</br>';
            //echo 'Mensaje enviado:'.$messagea42."\n";
            //echo 'largo: '.strlen($messagea42)."\n";
          
            $sent=  socket_write($socket, $messagea42, strlen($messagea42)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='1'){
            
            echo  "aqui 1\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
            //AQUI SE DEBE QUEDAR CON LA CONEXION ESTABLECIDA Y ESPERAR EJECUTAR TRANSACCIONES

            $comando =$con->query("SELECT id, solicitud, lista, monto_giro FROM solicitud WHERE lista =0  ORDER BY id ASC");
            
            while(true){
                $comando->execute();
                $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
                if(count($resultado)){
                    foreach ($resultado as $row) {
                        $id = $row['id'];
                        $solicitud = $row['solicitud'];
                        $lista = $row['lista'];
                        $monto_giro = $row['monto_giro'];
                    }
                    $monto_giro = str_pad($monto_giro, 8, "0", STR_PAD_LEFT);
                    $query =$con->prepare("UPDATE solicitud SET lista =1 WHERE id = ?  ");
                    $respuesta = $query->execute(array($id));

                    
                    if($solicitud == 1){

                        //CONSULTA
                        $mensaje = chr(00).chr(400).'11'.chr(28).'211000000'.chr(28).chr(28).'37698581'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'IA A AB'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26081A4A53B2C7BC9DB49F2701809F33036040209F34030201009F360202779F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F3704613B1205';

                       // $mensaje = chr(00).chr(422).'11'.chr(28).'211000000'.chr(28).chr(28).'28694D81'.chr(28).'02'.chr(28).';5331870042506332=27032260000012450051?'.chr(28).chr(28).'AA A AC'.chr(28).'00000000'.chr(28).'?419;>47?9<?:?:;'.chr(28).'050014734196'.chr(28).chr(28).'118.562.390-4 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0853318700425063325F340100820239008407A00000000410109C01019F1A0201529F10120114A00001220000000000000000000000FF9F2608130EC9523F3637979F2701809F33036040209F34034203009F360200AE950580000480009F02060000000000009F03060000000000005F2A0201529A032212199F3704ED7CB0C09F350114';

                       $mensaje = chr(00).chr(393). '11'.chr(28).'211000000'.chr(28).chr(28).'0F696081'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'II A ABC'.chr(28).'0010000'.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26083DE3985D5022C66C9F2701809F33036040209F34030201009F360202739F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F370436190E3D';

                        $sent=  socket_write($socket, $mensaje, strlen($mensaje)) or die("Could not send data to server\n");
                         echo "mensaje enviado:".$sent."\n";
     
                        $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                        echo  "Respuesta del Servidor: " .$result."\n" ;
                        $datos1 = explode(chr(28), $result);
                         if(count($datos1)){

     
                        //     if(array_key_exists(6,  $datos1)){
                        //         //respuesta con posible valor correcto
                        //         $validacion = $datos1[6];
                        //         $validacion2 = explode(chr(14), $validacion);
                        //          //echo  "Dato a validar: " .count($validacion2)."\n" ;
                         
                        //         if(count($validacion2)<2){
                        //             echo  "guardo en base de datos respuesta de error\n" ;
             
                        //             $mensaje = 'No se pudo completar la transaccion, valide los datos';
                        //             $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                        //             $respuesta = $query->execute(array($id,$mensaje));
                                     
                        //          }
                        //         else{
                        //             echo  "guardo en base de datos respues satisfactoria\n" ;
             
                        //             $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                        //             $respuesta = $query->execute(array($id,$validacion));
                        //         }
                        //     }else{
                        //         //ya sabemos que no hay posibilidad de respuesta correcta
                        //         echo  "guardo en base de datos respues satisfactoria\n" ;
         
                        //         $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                        //         $respuesta = $query->execute(array($id,$validacion));
                        //     }

                            echo  "guardo en base de datos respuesta de error\n" ;
             
                            $mensaje = 'No se pudo completar la transaccion, valide los datos';
                            $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                            $respuesta = $query->execute(array($id,$mensaje));

                            echo  "respondo transaccion ok\n" ;
                            $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";

                            
                            $mensaje2parte = chr(00).chr(103).'11'.chr(28).'211000000'.chr(28).chr(28).'2F694E81'.chr(28).'03'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).chr(28);
                            //$mensaje2parte = chr(00).chr(103).'11'.chr(28).'211000000'.chr(28).chr(28).'0B69D380'.chr(28).'07'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'0010000'.chr(28).'71?1673198?0::9?'.chr(28).chr(28);

                          

                            echo  "solicito segunda parte\n" ;
                            $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";
                            sleep(.5);

                            $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                            echo  "Respuesta del Servidor: " .$result2."\n" ;

                            echo  "respondo transaccion ok\n" ;
                            $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";
                            
                        }
                        


                    }
                    else if($solicitud == 2){
                        //GIRO
                        //$num =  $numero_aleatorio = rand(2,7);

                        $mensaje = chr(00).chr(393). '11'.chr(28).'211000000'.chr(28).chr(28).'0F696081'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'II A ABC'.chr(28).$monto_giro.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'116.921.214-7 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26083DE3985D5022C66C9F2701809F33036040209F34030201009F360202739F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F370436190E3D';

                        //$mensaje = chr(00).chr(425). '11'.chr(28).'211000000'.chr(28).chr(28).'0C690781'.chr(28).'12'.chr(28).';5331870042506332=27032260000012450051?'.chr(28).chr(28).'AI AFACC'.chr(28).$monto_giro.chr(28).'?419;>47?9<?:?:;'.chr(28).'050014734196'.chr(28).chr(28).'118.079.592-8 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0853318700425063325F340100820239008407A00000000410109C01019F1A0201529F10120114A00001220000000000000000000000FF9F26082BEC6E6B9C5C05B79F2701809F33036040209F34034203009F360200A8950580000480009F02060000000000009F03060000000000005F2A0201529A032212199F3704585FE48F9F350114';

                        //echo "GIRO ".$mensaje."\n";

                        $sent=  socket_write($socket, $mensaje, strlen($mensaje)) or die("Could not send data to server\n");
                        echo "mensaje enviado:".$sent."\n";
    
                        $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                        echo  "Respuesta del Servidor: " .$result."\n" ;
                        $datos1 = explode(chr(28), $result);

             

                        if(count($datos1)){

                            if(array_key_exists(6,  $datos1)){
                                //respuesta con posible valor correcto
                                $validacion = $datos1[6];
                                $validacion2 = explode(chr(14), $validacion);
                                 //echo  "Dato a validar: " .count($validacion2)."\n" ;

                                //  echo "<pre>";
                                //  print_r($validacion2);
                                //  echo "</pre>";
                         
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
                                }
                            }else{
                                //ya sabemos que no hay posibilidad de respuesta correcta
                                echo  "guardo en base de datos respues satisfactoria\n" ;
         
                                $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,1,?)");
                                $respuesta = $query->execute(array($id,$validacion));
                            }
                            
                            sleep(.3);
                            $message22b =  chr(00).chr(15).'22'.chr(28).'211000000'.chr(28).chr(28).'B';
                            $message12e = chr(00).chr(51).'12'.chr(28).'211000000'.chr(28).chr(28).'E000010000000000'.chr(28).'00000000'.chr(28).'00'.chr(28).'10200000';

                            // echo  "respondo transaccion 12e\n" ;
                            // $sent=  socket_write($socket, $message12e, strlen($message12e)) or die("Could not send data to server\n"); 
                            // echo "mensaje enviado:".$sent."\n";

                            
                            //$message22botro = chr(00).chr(77).'22'.chr(28).'211000000'.chr(28).chr(28).'B'.chr(28).'000000000100000000000009900048000590002700001000010000100001';

                            echo  "respondo transaccion ok\n" ;
                            $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                            echo "mensaje enviado:".$sent."\n";

                                
                                // $mensaje2parte = chr(00).chr(103).'11'.chr(28).'211000000'.chr(28).chr(28).'24696181'.chr(28).'03'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'CCCCCCCC'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).chr(28);

                                // echo  "solicito segunda parte\n" ;
                                // $sent=  socket_write($socket, $mensaje2parte, strlen($mensaje2parte)) or die("Could not send data to server\n"); 
                                // echo "mensaje enviado:".$sent."\n";
                                // sleep(.5);

                                // $result2 = socket_read ($socket, 1024) or die("Could not read server response\n");
                                // echo  "Respuesta del Servidor: " .$result2."\n" ;

                                // echo  "respondo transaccion ok\n" ;
                                // $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                                // echo "mensaje enviado:".$sent."\n";
                            
                            
                        }
                        
                    }
                    else if($solicitud == 3){
                        //DEPOSITO
                       
                        //$mensaje = chr(00).chr(145).'11'.chr(28).'211000000'.chr(28).chr(28).'AC375284'.chr(28).'11'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'IB AB'.chr(28).chr(28).chr(28).'0000000000000001010013469450500'.chr(28).chr(28).'00000000000000000000000000000'.chr(28).chr(28).'w0701';

                        $mensaje = chr(00).chr(127).'11'.chr(28).'211000000'.chr(28).chr(28).'AC375284'.chr(28).'1<'.chr(28).';4097673861485332=0'.chr(28).chr(28).'IB AB'.chr(28).chr(28).chr(28).'0000000000000001010013469450500'.chr(28).chr(28).'00000000000000000000000000000'.chr(28).chr(28).'w0701';

                        $mensaje_deposito = chr(00).chr(125).'11'.chr(28).'211000000'.chr(28).chr(28).'AC375284'.chr(28).'11'.chr(28).';4097673861485332=0'.chr(28).chr(28).'IB AB '.chr(28).chr(28).chr(28).'0000000000000001010013469450500'.chr(28).chr(28).'00000000000000000000000000000'.chr(28).chr(28).'w0410';


                        $sent=  socket_write($socket, $mensaje_deposito, strlen($mensaje_deposito)) or die("Could not send data to server\n");
                        echo "mensaje enviado:".$sent."\n";
    
                        $result = socket_read ($socket, 2048) or die("Could not read server response\n");
                        echo  "Respuesta del Servidor: " .$result."\n" ;
                        $datos1 = explode(chr(28), $result);


                        echo  "respondo transaccion ok\n" ;
                        $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                        echo "mensaje enviado:".$sent."\n";

                        $mensaje = 'guardo algo por ahora';
                        $query =$con->prepare("INSERT INTO resultado (id_transaccion, estado, resultado) VALUES (?,0,?)");
                        $respuesta = $query->execute(array($id,$mensaje));
 
                        
                    }
                    
                }
                
            }
        }









       sleep(.01);
    }


?>


