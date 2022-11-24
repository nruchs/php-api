<?php
class productsModel{
    public $conexao;
    public function __construct(){
        $this->conexao = new mysqli('localhost','root','','api');
        mysqli_set_charset($this->conexao,'utf8');
    }

    public function getProducts($id=null){
        $where = ($id == null) ? "" : " WHERE id='$id'";
        $products=[];
        $sql="SELECT * FROM products ".$where;
        $records = mysqli_query($this->conexao,$sql);
        while($row = mysqli_fetch_assoc($records)){
            array_push($products,$row);
        }
        return $products;
    }

    public function saveProducts($name,$description,$price){
        $valida = $this->validateProducts($name,$description,$price);
        $resultado=['error','Um produto semelhante já está registrado'];
        if(count($valida)==0){
            $sql="INSERT INTO products(name,description,price) VALUES('$name','$description','$price')";
            mysqli_query($this->conexao,$sql);
            $resultado=['success','Produto adicionado'];
        }
        return $resultado;
    }

    public function updateProducts($id,$name,$description,$price){
        $existe= $this->getProducts($id);
        $resultado=['error','Não existe um produto com o ID '.$id];
        if(count($existe)>0){
            $valida = $this->validateProducts($name,$description,$price);
            $resultado=['error','Um produto semelhante já está registrado'];
            if(count($valida)==0){
                $sql="UPDATE products SET name='$name',description='$description',price='$price' WHERE id='$id' ";
                mysqli_query($this->conexao,$sql);
                $resultado=['success','Produto atualizado'];
            }
        }
        return $resultado;
    }
    
    public function deleteProducts($id){
        $valida = $this->getProducts($id);
        $resultado=['error','Não existe um produto com o ID '.$id];
        if(count($valida)>0){
            $sql="DELETE FROM products WHERE id='$id' ";
            mysqli_query($this->conexao,$sql);
            $resultado=['success','Produto excluído'];
        }
        return $resultado;
    }
    
    public function validateProducts($name,$description,$price){
        $products=[];
        $sql="SELECT * FROM products WHERE name='$name' AND description='$description' AND price='$price' ";
        $records = mysqli_query($this->conexao,$sql);
        while($row = mysqli_fetch_assoc($records)){
            array_push($products,$row);
        }
        return $products;
    }
}