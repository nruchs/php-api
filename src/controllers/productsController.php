<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');

require_once '../models/productsModel.php';

$productsModel= new productsModel();
switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $resposta = (!isset($_GET['id'])) ? $productsModel->getProducts() : $productsModel->getProducts($_GET['id']);
        echo json_encode($resposta);
    break;

    case 'POST':
        $_POST= json_decode(file_get_contents('php://input',true));
        if(!isset($_POST->name) || is_null($_POST->name) || empty(trim($_POST->name)) || strlen($_POST->name) > 80){
            $resposta= ['error','O nome do produto não deve estar vazio e não deve ter mais de 80 caracteres'];
        }
        else if(!isset($_POST->description) || is_null($_POST->description) || empty(trim($_POST->description)) || strlen($_POST->name) > 150){
            $resposta= ['error','A descrição do produto não deve estar vazia e não deve ter mais de 150 caracteres'];
        }
        else if(!isset($_POST->price) || is_null($_POST->price) || empty(trim($_POST->price)) || !is_numeric($_POST->price) || strlen($_POST->price) > 12){
            $resposta= ['error','O preço do produto não deve estar vazio, deve ser do tipo numérico e não ter mais de 12 caracteres'];
        }
        else{
            $resposta = $productsModel->saveProducts($_POST->name,$_POST->description,$_POST->price);
        }
        echo json_encode($resposta);
    break;

    case 'PUT':
        $_PUT= json_decode(file_get_contents('php://input',true));
        if(!isset($_PUT->id) || is_null($_PUT->id) || empty(trim($_PUT->id))){
            $resposta= ['error','O ID do produto não deve estar vazio'];
        }
        else if(!isset($_PUT->name) || is_null($_PUT->name) || empty(trim($_PUT->name)) || strlen($_PUT->name) > 80){
            $resposta= ['error','O nome do produto não deve estar vazio e não deve ter mais de 80 caracteres'];
        }
        else if(!isset($_PUT->description) || is_null($_PUT->description) || empty(trim($_PUT->description)) || strlen($_PUT->description) > 150){
            $resposta= ['error','A descrição do produto não deve estar vazia e não deve ter mais de 150 caracteres'];
        }
        else if(!isset($_PUT->price) || is_null($_PUT->price) || empty(trim($_PUT->price)) || !is_numeric($_PUT->price) || strlen($_PUT->price) > 12){
            $resposta= ['error','O preço do produto não deve estar vazio, deve ser do tipo numérico e não ter mais de 12 caracteres'];
        }
        else{
            $resposta = $productsModel->updateProducts($_PUT->id,$_PUT->name,$_PUT->description,$_PUT->price);
        }
        echo json_encode($resposta);
    break;

    case 'DELETE';
        $_DELETE= json_decode(file_get_contents('php://input',true));
        if(!isset($_DELETE->id) || is_null($_DELETE->id) || empty(trim($_DELETE->id))){
            $resposta= ['error','Informe o ID do produto desejado para exclusão'];
        }
        else{
            $resposta = $productsModel->deleteProducts($_DELETE->id);
        }
        echo json_encode($resposta);
    break;
}