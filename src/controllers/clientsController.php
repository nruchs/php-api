<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');

require_once '../models/clientsModel.php';

$clientsModel= new clientsModel();
switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $resposta = (!isset($_GET['id'])) ? $clientsModel->getClients() : $clientsModel->getClients($_GET['id']);
        echo json_encode($resposta);
    break;

    case 'POST':
        $_POST= json_decode(file_get_contents('php://input',true));
        if(!isset($_POST->name) || is_null($_POST->name) || empty(trim($_POST->name)) || strlen($_POST->name) > 80){
            $resposta= ['error','O nome do cliente não deve estar vazio e não deve ter mais de 80 caracteres'];
        }
        else if(!isset($_POST->city) || is_null($_POST->city) || empty(trim($_POST->city)) || strlen($_POST->name) > 150){
            $resposta= ['error','A cidade do cliente não deve estar vazia e não deve ter mais de 150 caracteres'];
        }
        else if(!isset($_POST->cpf) || is_null($_POST->cpf) || empty(trim($_POST->cpf)) || !is_numeric($_POST->cpf) || strlen($_POST->cpf) > 11){
            $resposta= ['error','O CPF do cliente não deve estar vazio, deve ser do tipo numérico e não ter mais de 11 caracteres'];
        }
        else{
            $resposta = $clientsModel->saveClients($_POST->name,$_POST->city,$_POST->cpf);
        }
        echo json_encode($resposta);
    break;

    case 'PUT':
        $_PUT= json_decode(file_get_contents('php://input',true));
        if(!isset($_PUT->id) || is_null($_PUT->id) || empty(trim($_PUT->id))){
            $resposta= ['error','O ID do cliente não deve estar vazio'];
        }
        else if(!isset($_PUT->name) || is_null($_PUT->name) || empty(trim($_PUT->name)) || strlen($_PUT->name) > 80){
            $resposta= ['error','O nome do cliente não deve estar vazio e não deve ter mais de 80 caracteres'];
        }
        else if(!isset($_PUT->city) || is_null($_PUT->city) || empty(trim($_PUT->city)) || strlen($_PUT->city) > 150){
            $resposta= ['error','A cidade do cliente não deve estar vazia e não deve ter mais de 150 caracteres'];
        }
        else if(!isset($_PUT->cpf) || is_null($_PUT->cpf) || empty(trim($_PUT->cpf)) || !is_numeric($_PUT->cpf) || strlen($_PUT->cpf) > 11){
            $resposta= ['error','O CPF do cliente não deve estar vazio, deve ser do tipo numérico e não ter mais de 11 caracteres'];
        }
        else{
            $resposta = $clientsModel->updateClients($_PUT->id,$_PUT->name,$_PUT->city,$_PUT->cpf);
        }
        echo json_encode($resposta);
    break;

    case 'DELETE';
        $_DELETE= json_decode(file_get_contents('php://input',true));
        if(!isset($_DELETE->id) || is_null($_DELETE->id) || empty(trim($_DELETE->id))){
            $resposta= ['error','Informe o ID do cliente desejado para exclusão'];
        }
        else{
            $resposta = $clientsModel->deleteClients($_DELETE->id);
        }
        echo json_encode($resposta);
    break;
}