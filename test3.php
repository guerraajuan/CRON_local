    <?php

        function strToHex($string){
            $hex = '';
            for ($i=0; $i<strlen($string); $i++){
                $ord = ord($string[$i]);
                $hexCode = dechex($ord);
                $hex .= substr('0'.$hexCode, -2);
            }
            return strToUpper($hex);
        }
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
            $encrypted = bin2hex(openssl_encrypt($plaintext, $algorithm, $key,OPENSSL_RAW_DATA));
            return strtoupper(substr($encrypted,0,6));
        }
        function Descifrar($valor){
            $cifrados = openssl_get_cipher_methods();
            $plaintext = $valor.'8EE0FBD1F4CEF8A1';
            $plaintext = hex2bin($plaintext);
            $key = hex2bin('875FA0CA18B2F68100E266099C3500E8');
            $algorithm = $cifrados[99];
            $decryptedData = bin2hex(openssl_decrypt($plaintext, $algorithm, $key,OPENSSL_RAW_DATA )); 
            return strtoupper($decryptedData);
        }


        // $componente1 = Descifrar('FD7E687DEA80107F');
        // echo $componente1;
        // echo '</br>';
        // $componente2 =  Descifrar('3FE657C36A6CCD53');
        // echo $componente2;
        // echo '</br>';
        // $resul= $componente1.$componente2;
        // echo $resul;
        // echo '</br>';
         $kcv = GetKCV('E4AA828398F63873075F479891244841');
        // echo '</br>';
         echo $kcv;
        
        


        // $cifrados = openssl_get_cipher_methods();
        // echo '<pre>';
        // var_dump($cifrados);
        // echo '</pre>';
    //     $plaintext = hex2bin("F7374AAB70EC3BFE");
    //     $key = hex2bin('875FA0CA18B2F68100E266099C3500E8');
    //     $algorithm = $cifrados[99];

    //     $secret_iv1 = openssl_cipher_iv_length($algorithm);
    //     $secret_iv = bin2hex($secret_iv1);
    //    // $iv = 
    //     //$iv = '91646F7423CF8D59';
    //     $iv = openssl_cipher_iv_length($algorithm);
    //     $encrypted = bin2hex(openssl_encrypt($plaintext, $algorithm, $key,OPENSSL_RAW_DATA));
    //     echo strtoupper($encrypted);

   
       
      
       
        


    
    





    ?>