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
    $host    = "10.1.112.150";
    $port    = 16118;
//////////////////////////////////////////////////////////////////////////////////////////////////////

    // //RESPUESTAS
    // $message229 =  chr(00).chr(15).'22'.chr(28).'211000000'.chr(28).chr(28).'9';

    // $message22b =  chr(00).chr(15).'22'.chr(28).'211000000'.chr(28).chr(28).'B';

    // $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP ) or die("Could not create socket\n");
    // // connect to server
    // $result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");


    $comando =$con->query("SELECT id, solicitud, lista,rut,dv,tarjeta,cuenta,co_cuenta, monto_giro, monto_deposito FROM solicitud WHERE lista =0  ORDER BY id ASC");

    echo'inicio espera'."\n";
            
    while(true){
        $comando->execute();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        if(count($resultado)){
            //echo'con solicitud';
            foreach ($resultado as $row) {
                $id = $row['id'];
                $solicitud = $row['solicitud'];
                $lista = $row['lista']; //estado
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

            $url = 'http://172.20.249.243:9080/Switch_ATM?id='.$id.'&solicitud='.$solicitud.'&estado='.$lista.'&rut='.$rut.'&dv='.$dv.'&monto_giro='.$monto_giro.'&monto_deposito='.$monto_deposito.'&tarjeta='.$tarjeta.'&cuenta='.$cuenta.'&co_cuenta='.$co_cuenta;
		    $consultaSB =  file_get_contents($url);
		    //$resp = json_decode($consultaSB);
            $validacion = $consultaSB[0];
            if($validacion == '1'){
                echo 'voy a respuesta virtual'."\n";
                echo $consultaSB;

            }
            else{
                echo 'voy a con la respuesta real'."\n";
                $array = json_decode($consultaSB, true);
                //print_r($array);
                $cajero= $array['cajero'];
                $saldo_total= $array['saldo_total'];
                echo $cajero."\n";
                echo $saldo_total."\n";
            }
           

        }


        sleep(.01);
    }
                
    


?>


