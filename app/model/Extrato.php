<?php

class Extrato{
    private $conta;
    private $valor;
    private $operacao;
    private $descricao;
    private $categoria;
    private $dataOperacao;
    

    public function __construct(){
        $this->conta = $_SESSION['userId'];
        $this->dataOperacao = date("Y-m-d H:i:s");

    }

    public function verExtrato(Type $var = null)
    {
        $conn = DbConn::getConn();
        $query = "SELECT * FROM extrato WHERE idConta = {$_SESSION['userId']}";
        $prepare = $conn->prepare($query);
        $r = $prepare->execute();

        while($row = $prepare->fetch()) {
            //$resultado = $prepare->fetch();
            //var_dump($prepare->rowCount());
            $a[] = $row;
            //return $resultado;
        }
        return $a;
       
        
    }
    public function transferencia($valor, $desc){
        $this->valor = $valor;
        $this->operacao = 2;
        $this->descricao = $desc;
        $this->categoria = "Transferência";


        $conn = DbConn::getConn();

        $query = "INSERT INTO extrato (idConta, valor, operacao, descricao, categoria, dataOperacao) 
                  VALUES (:conta, :valor, :operacao, :descricao, :categoria, :dataOperacao)";
        $prepare = $conn->prepare($query);
        $prepare->bindValue(":conta", $this->conta);
        $prepare->bindValue(":valor", $this->valor);
        $prepare->bindValue(":operacao", $this->operacao);
        $prepare->bindValue(":descricao", $this->descricao);
        $prepare->bindValue(":categoria", $this->categoria);
        $prepare->bindValue(":dataOperacao", $this->dataOperacao);
        $r = $prepare->execute();
        var_dump($r);
    }
}