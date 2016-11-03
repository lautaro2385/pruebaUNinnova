<?php
/**
 * Description of phpAPI
 *
 * @author lautaro
 */
require_once "Calcula.php";
class phpAPI {   
    public function API(){
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            $phpcalcula = new Calcula();
            $num1word = $_GET['numberone'];
            $num2word = $_GET['numbertwo'];
            $num1 = $phpcalcula->wordsToNumber($num1word);
            $num2 = $phpcalcula->wordsToNumber($num2word);
            $result = $phpcalcula->numberToWord($num1+$num2);
            $response = array("result" => $result);  
            echo json_encode($response,JSON_PRETTY_PRINT);
            break;     
        case 'POST'://inserta
            echo 'POST';
            break;                
        case 'PUT'://actualiza
            echo 'PUT';
            break;      
        case 'DELETE'://elimina
            echo 'DELETE';
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
        
    }//end class
}
