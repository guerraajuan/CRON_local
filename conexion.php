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



?>