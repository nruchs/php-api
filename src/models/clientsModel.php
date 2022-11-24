<?php
class clientsModel{
    public $conexao;
    public function __construct(){
        $this->conexao = new mysqli('localhost','root','','api');
        mysqli_set_charset($this->conexao,'utf8');
    }

    public function getClients($id=null){
        $where = ($id == null) ? "" : " WHERE id='$id'";
        $clients=[];
        $sql="SELECT * FROM clients ".$where;
        $records = mysqli_query($this->conexao,$sql);
        while($row = mysqli_fetch_assoc($records)){
            array_push($clients,$row);
        }
        return $clients;
    }

    public function saveClients($name,$city,$cpf){
        $valida = $this->validateClients($name,$city,$cpf);
        $resultado=['error','Um cliente semelhante já está registrado'];
        if(count($valida)==0){
            $sql="INSERT INTO clients(name,city,cpf) VALUES('$name','$city','$cpf')";
            mysqli_query($this->conexao,$sql);
            $resultado=['success','Cliente adicionado'];
        }
        return $resultado;
    }

    public function updateClients($id,$name,$city,$cpf){
        $existe= $this->getClients($id);
        $resultado=['error','Não existe um cliente com o ID '.$id];
        if(count($existe)>0){
            $valida = $this->validateClients($name,$city,$cpf);
            $resultado=['error','Um cliente semelhante já está registrado'];
            if(count($valida)==0){
                $sql="UPDATE clients SET name='$name',city='$city',cpf='$cpf' WHERE id='$id' ";
                mysqli_query($this->conexao,$sql);
                $resultado=['success','Cliente atualizado'];
            }
        }
        return $resultado;
    }
    
    public function deleteClients($id){
        $valida = $this->getClients($id);
        $resultado=['error','Não existe um cliente com o ID '.$id];
        if(count($valida)>0){
            $sql="DELETE FROM clients WHERE id='$id' ";
            mysqli_query($this->conexao,$sql);
            $resultado=['success','Cliente excluído'];
        }
        return $resultado;
    }
    
    public function validateClients($name,$city,$cpf){
        $clients=[];
        $sql="SELECT * FROM clients WHERE name='$name' AND city='$city' AND cpf='$cpf' ";
        $records = mysqli_query($this->conexao,$sql);
        while($row = mysqli_fetch_assoc($records)){
            array_push($clients,$row);
        }
        return $clients;
    }
}