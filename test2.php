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

    /* Permitir al script esperar para conexiones. */
    /* Activar el volcado de salida implícito, así veremos lo que estamos obteniendo mientras llega. */
    $host    = "10.1.112.150";
    //$port    = 16120;
    $port    = 16113;

    //RESPUESTAS
    //$message229 =  chr(00).chr(15).'22'.chr(28).'213000000'.chr(28).chr(28).'9';
    $message229 =  chr(00).chr(15).'22'.chr(28).'206000000'.chr(28).chr(28).'9';

    $message22b =   chr(00).chr(15).'22'.chr(28).'206000000'.chr(28).chr(28).'B';

    // $message22c1 = chr(00).chr(15).'22'.chr(28).'206000000'.chr(28).chr(28).'C'.chr(28).'1';
    // $messagep21 = '12'.chr(28).'213000000'.chr(28).chr(28).'P21';
    // $messager004 = '12'.chr(28).'213000000'.chr(28).chr(28).'R004';
    // $messager009 = '12'.chr(28).'213000000'.chr(28).chr(28).'R009';
    // $messagep20 = '12'.chr(28).'213000000'.chr(28).chr(28).'P20';
    // $messageb006 = '12'.chr(28).'213000000'.chr(28).chr(28).'B0006';
    // $message233 = '23'.chr(28).'213000000'.chr(28).chr(28).'3';


   // $messagea45 = chr(00).chr(22).'23'.chr(28).'213000000'.chr(28).chr(28).'3'.chr(28).'32B815';
    //$messagea45 = chr(00).chr(22).'23'.chr(28).'206000000'.chr(28).chr(28).'3'.chr(28).'32B815';
   // $messagea42 = chr(00).chr(22).'23'.chr(28).'213000000'.chr(28).chr(28).'3'.chr(28).'32B815';
    //$messagea42 = chr(00).chr(22).'23'.chr(28).'206000000'.chr(28).chr(28).'3'.chr(28).'32B815';


    $messagea32 = '23'.chr(28).'213000000'.chr(28).chr(28).'3'.chr(28).'9F2722';
    $messagea14 = '22'.chr(28).'213000000'.chr(28).chr(28).'F'.chr(28).'2977000016540009900049000590002700001000010000100001000000000000000000000000000000000000000000000000000000000000'.chr(29).chr(29).chr(29).chr(29).chr(29).chr(29).'00112000330025900371'.chr(29).chr(29).chr(29).chr(29).chr(29).'000000000100000';
    $message3 = '610007';

    //$message7 = chr(01).chr(43).'22'.chr(28).'213000000'.chr(28).chr(28).'F'.chr(28).'10000'.chr(28).'0000000000000000000000'.chr(28).'357F000A01000780000000C7000000010200007F7F00'.chr(28).'00011011000000022220000'.chr(28).'011110011111'.chr(28).'040201'.chr(28).'G531-0283';
    $message7 = chr(01).chr(43).'22'.chr(28).'206000000'.chr(28).chr(28).'F'.chr(28).'10000'.chr(28).'0000000000000000000000'.chr(28).'357F000A01000780000000C7000000010200007F7F00'.chr(28).'00011011000000022220000'.chr(28).'011110011111'.chr(28).'040201'.chr(28).'G531-0283';


    $message1 = chr(00).chr(15).'11'.chr(28).'206000000'.chr(28).chr(28).'2B30DD7E'.chr(28).'11'.chr(28).';4097673715411864=?'.chr(28).chr(28).'IA   AB '.chr(28).chr(28).chr(28).'010013469450'.chr(28).chr(28).'117.770.221-8 Elba Lazo Matta'.chr(28).chr(28).'7E6AA4EB';
    $test = 'TEST';

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
        else if($datos[3] =='1'){
            echo  "aqui 1\n" ;
            $sent=  socket_write($socket, $message229, strlen($message229)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
            $mensaje = chr(00).chr(400).'11'.chr(28).'206000000'.chr(28).chr(28).'37698581'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'IA A AB'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'118.562.390-4 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26081A4A53B2C7BC9DB49F2701809F33036040209F34030201009F360202779F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F3704613B1205'; 
            $sent=  socket_write($socket, $mensaje, strlen($mensaje)) or die("Could not send data to server\n");
            echo "mensaje enviado:".$sent."\n";

            $result = socket_read ($socket, 1024) or die("Could not read server response\n");
            echo  "Respuesta del Servidor: " .$result."\n" ;
            $datos1 = explode(chr(28), $result);
            print_r($datos1);
            if($datos1[0] =='4 '){
                echo  "aqui 4\n" ;
                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                echo "mensaje enviado:".$sent."\n";
            }
            else if($datos1[3] == '175'){
                echo  "aqui 175\n" ;
                $sent=  socket_write($socket, $message22b, strlen($message22b)) or die("Could not send data to server\n"); 
                echo "mensaje enviado:".$sent."\n";

                $mensaje = chr(00).chr(400).'11'.chr(28).'206000000'.chr(28).chr(28).'37698581'.chr(28).'16'.chr(28).';4097673861485332=25012260000012150051?'.chr(28).chr(28).'IA A AB'.chr(28).'00000000'.chr(28).'71?1673198?0::9?'.chr(28).'010013166819'.chr(28).chr(28).'118.562.390-4 Elba Lazo Matta'.chr(28).chr(28).'5CAM000C5A0840976738614853325F340100820238008407A00000000310109C01019F1A0201529F100706021203A0A8019F26081A4A53B2C7BC9DB49F2701809F33036040209F34030201009F360202779F530100950580800480009F02060000000000009F03060000000000005F2A0201529A032212199F3704613B1205'; 
                $sent=  socket_write($socket, $mensaje, strlen($mensaje)) or die("Could not send data to server\n");
                echo "mensaje enviado:".$sent."\n";
                
                $result = socket_read ($socket, 1024) or die("Could not read server response\n");
                echo  "Respuesta del Servidor: " .$result."\n" ;
                $datos1 = explode(chr(28), $result);

            }
            else{
                echo  "no entro\n" ;
            }

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
            echo  "aqui 45\n" ; 

            $key_all= $datos[4];
            echo  "llave completa: ".$key_all."\n";

            $llave = GetLlave_sin030($key_all);
            echo "llave sin 030: ".  $llave."\n"; 

            $llave_hex = GetLlaveHex($llave); 
            echo "llave en hex: ". $llave_hex."\n"; 

            $lado1 = GetLado1($llave_hex); 
            echo "lado 1: ". $lado1."\n";

            $lado2 = GetLado2($llave_hex);
            echo  "lado 2: ". $lado2."\n";

            $componente1 = Descifrar($lado1);
            echo 'Componente 1: '.$componente1."\n";
            
            $componente2 =  Descifrar($lado2);
            echo 'Componente 2: '.$componente2."\n";

            $resultado = $componente1.$componente2;
            $kcv = GetKCV($resultado);
            echo 'KCV: '. $kcv."\n";
            $messagea45 = chr(00).chr(22).'23'.chr(28).'206000000'.chr(28).chr(28).'3'.chr(28).$kcv;
            echo 'Mensaje enviado:'.$messagea45."\n";
            echo 'largo: '.strlen($messagea45)."\n";

            $sent=  socket_write($socket, $messagea45, strlen($messagea45)) or die("Could not send data to server\n");   
            echo "mensaje enviado:".$sent."\n";
        }
        else if($datos[3] =='42'){
            echo  "aqui 42\n" ;
            $key_all= $datos[4];
            echo  "llave completa: ".$key_all."\n";

            $llave = GetLlave_sin030($key_all);
            echo "llave sin 030: ".  $llave."\n"; 

            $llave_hex = GetLlaveHex($llave); 
            echo "llave en hex: ". $llave_hex."\n"; 

            $lado1 = GetLado1($llave_hex); 
            echo "lado 1: ". $lado1."\n";

            $lado2 = GetLado2($llave_hex);
            echo  "lado 2: ". $lado2."\n";

            $componente1 = Descifrar($lado1);
            echo 'Componente 1: '.$componente1."\n";
           
            $componente2 =  Descifrar($lado2);
            echo 'Componente 2: '.$componente2."\n";

            $resultado = $componente1.$componente2;
            $kcv = GetKCV($resultado);
            echo 'KCV: '. $kcv."\n";
            
            
            $messagea42 = chr(00).chr(22).'23'.chr(28).'206000000'.chr(28).chr(28).'3'.chr(28).$kcv;
            echo '</br>';
            echo 'Mensaje enviado:'.$messagea42."\n";
            echo 'largo: '.strlen($messagea42)."\n";
          
            $sent=  socket_write($socket, $messagea42, strlen($messagea42)) or die("Could not send data to server\n"); 
            echo "mensaje enviado:".$sent."\n";
        }
       

    }


?>





