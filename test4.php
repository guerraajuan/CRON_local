
<?php

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


$query =$con->prepare("SELECT * FROM resultado WHERE id =341");

$respuesta = $query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			if(count($resultado)){

				foreach($resultado as $row){
					$res = $row['resultado']; 
					$estado = $row['estado'];
					$res_2 = $row['resultado_2'];
				}
            }
            $datos = explode(chr(14), $res);
            $datos2 = explode(chr(14), $res_2);

             //substr(strval($datos2[4]),1);

            $n_operacion_titulo = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[14])),1) ;
            $pos_16 = preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[16])) ;
            $n_operacion_n = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[16])),0,5) ;
            $comprobante_titulo = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[16])),6,29) ;
            
            $pos_17 = preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[17])) ;
            $rut_titulo = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[16])),36,11) ;
            $rut = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[17])),1,12) ;
            $producto = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[17])),13,37) ;
            $n_cta_titulo = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[17])),50,11) ;

            $pos_42 = preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[42])) ;

            $n_cta = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[19])),0,19) ;
            $monto_titulo = substr(preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[19])),20,5) ;

            $pos_42 = preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos[42])) ;
            $pos_42=str_replace(chr(12), " ", $pos_42);
            $monto=substr($pos_42,2);

            $pos_2 = preg_replace("/[\r\n|\n|\r]+/", " ", strval($datos2[2])) ;
            $gracias = substr(str_replace(chr(12), " ", $pos_2),1);


            //echo $monto.'<br><br>';
            echo $n_operacion_titulo.' ';
            echo $n_operacion_n.'<br>';
            echo $comprobante_titulo.'<br>';
            echo $rut_titulo.'  ';
            echo $rut.'<br>';
            echo $producto.'<br>';
            echo $n_cta_titulo.' ';
            echo $n_cta.'<br>';
            echo $monto_titulo.'  ';
            echo $monto.'<br><br><br>';



            echo $gracias.'<br><br><br>';









?>
