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
    $port    = 17900;
//////////////////////////////////////////////////////////////////////////////////////////////////////

    //RESPUESTAS
    $message229 =  chr(00).chr(15).'22'.chr(28).'900000000'.chr(28).chr(28).'9';

    $message22b =  chr(00).chr(15).'22'.chr(28).'900000000'.chr(28).chr(28).'B';

    $messagep21 =  chr(00).chr(17).'12'.chr(28).'900000000'.chr(28).chr(28).'P21';
    $messager004 =  chr(00).chr(18).'12'.chr(28).'900000000'.chr(28).chr(28).'R004';
    $messager104 =  chr(00).chr(18).'12'.chr(28).'900000000'.chr(28).chr(28).'R104';
    $messager009 =  chr(00).chr(18).'12'.chr(28).'900000000'.chr(28).chr(28).'R009';
    $messagep20 =  chr(00).chr(17).'12'.chr(28).'900000000'.chr(28).chr(28).'P20';

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
            $messagea45 = chr(00).chr(22).'23'.chr(28).'900000000'.chr(28).chr(28).'3'.chr(28).$kcv;
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
            
            
            $messagea42 = chr(00).chr(22).'23'.chr(28).'900000000'.chr(28).chr(28).'3'.chr(28).$kcv;
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

            echo  "envio p21\n" ;
            $sent=  socket_write($socket, $messagep21, strlen($messagep21)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";

            echo  "envio r004\n" ;
            $sent=  socket_write($socket, $messager004, strlen($messager004)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";

            echo  "envio r104\n" ;
            $sent=  socket_write($socket, $messager104, strlen($messager104)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";

            echo  "envio r009\n" ;
            $sent=  socket_write($socket, $messager009, strlen($messager009)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";

            echo  "envio p20\n" ;
            $sent=  socket_write($socket, $messagep20, strlen($messagep20)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";


            while (true) {
                $result = socket_read ($socket, 1024) or die("Could not read server response\n");
                echo  "Respuesta del Servidor: " .$result."\n" ;
                $datos_1 = explode(chr(28), $result);
        
                if($datos_1[3] =='2'){
                    echo  "envio 229\n" ;
                    $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
                    echo "mensaje enviado:".$sent."\n";
                }
                else if($datos_1[3] =='4'){
                    echo  "envio contadores\n" ;
                    $mensaje_contadores = '22'.chr(28).'900000000'.chr(28).chr(28).'F'.chr(28).'2056300000100015500062001040021000001000010000100001000000000000000000000000000000000000000000000000000000000000';

                    $largo_mensaje_contadores = strlen($mensaje_contadores);

                    $mensaje_contadores = chr(00).chr($largo_mensaje_contadores).$mensaje_contadores;

                    echo  "envio contadores\n" ;
                    $sent=  socket_write($socket, $mensaje_contadores, strlen($mensaje_contadores)) or die("Could not send data to server\n"); 
                    echo "mensaje enviado:".$sent."\n";
                }
                else if($datos_1[3] =='7'){
                    echo  "envio contadores\n" ;
                    $mensaje_7 = '22'.chr(28).'900000000'.chr(28).chr(28).'F'.chr(28).'10000'.chr(28).'0000000000000000000000'.chr(28).'00'.chr(28).'00011011000000022220000'.chr(28).'011110011111'.chr(28).'040201'.chr(28).'G531-0283';

                    $largo_mensaje_7 = strlen($mensaje_7);

                    $mensaje_7 = chr(00).chr($largo_mensaje_7).$mensaje_7;

                    echo  "envio contadores\n" ;
                    $sent=  socket_write($socket, $mensaje_7, strlen($mensaje_7)) or die("Could not send data to server\n"); 
                    echo "mensaje enviado:".$sent."\n";
                }

            }

            

            // $result = socket_read ($socket, 1024) or die("Could not read server response\n");
            // echo  "Respuesta del Servidor: " .$result."\n" ;

            // echo  "envio 229\n" ;
            // $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            // echo "mensaje enviado:".$sent."\n";

            // $result = socket_read ($socket, 1024) or die("Could not read server response\n");
            // echo  "Respuesta del Servidor: " .$result."\n" ;


            $mensaje_7 = '22'.chr(28).'900000000'.chr(28).chr(28).'F'.chr(28).'10000'.chr(28).'0000000000000000000000'.chr(28).'00'.chr(28).'00011011000000022220000'.chr(28).'011110011111'.chr(28).'040201'.chr(28).'G531-0283';

            // $largo_mensaje_contadores = strlen($mensaje_contadores);

            // $mensaje_contadores = chr(00).chr($largo_mensaje_contadores).$mensaje_contadores;

            // echo  "envio contadores\n" ;
            // $sent=  socket_write($socket, $mensaje_contadores, strlen($mensaje_contadores)) or die("Could not send data to server\n"); 
            // echo "mensaje enviado:".$sent."\n";

            // $result = socket_read ($socket, 1024) or die("Could not read server response\n");
            // echo  "Respuesta del Servidor: " .$result."\n" ;

            // echo  "envio 229\n" ;
            // $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            // echo "mensaje enviado:".$sent."\n";
                        

          


  
        }

    }


?>


