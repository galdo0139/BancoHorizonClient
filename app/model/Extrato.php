<?php

class Extrato{
    private $conta;
    private $contaDestino;
    private $valor;
    private $operacao;
    private $descricao;
    private $categoria;
    private $dataOperacao;
    

    public function __construct(){
        $this->conta = $_SESSION['userId'];
        $this->dataOperacao = date("Y-m-d H:i:s");

    }

    public function verExtrato(Type $var = null){
        $conn = DbConn::getConn();
        $query = "SELECT * FROM extrato WHERE idConta = {$_SESSION['userId']} ORDER BY dataOperacao DESC";
        $prepare = $conn->prepare($query);
        $r = $prepare->execute();
        $a = false;
        while($row = $prepare->fetch()) {
            $a[] = $row;
        }
        return $a;
    }

    public function deposito($valor, $desc){
        $this->valor = $valor;
        $this->operacao = 1;
        $this->descricao = $desc;
        $this->categoria = "Depósito";
        $this->registrar();
    }


    public function transferencia($transf){
        $contaTransf = new Conta(0, $transf->getNumContaTransf(), $transf->getAgTransf());
        $consulta = $contaTransf->consultarNome();
        
        $this->valor = $transf->getValorTransf();
        $this->operacao = 2;
        $this->descricao = "TED enviada no valor de ". Money::real($this->valor). 
        " para  ".$consulta[0] . " ". $transf->getAgTransf(). " | " .$transf->getNumContaTransf();
        $this->categoria = "Transferência";


        $this->registrar();


        $this->descricao = "TED recebida no valor de ". Money::real($this->valor). 
        " de ".$_SESSION['userName'] . " ". $transf->getAgTransf(). " | " .$transf->getNumContaTransf();
        $this->registrar($contaTransf->getIdConta());
    }

    

    
    public function pagamento($valor, $desc){
        /*$this->valor = $valor;
        $this->operacao = 3;
        $this->descricao = $desc;
        $this->categoria = "Pagamento";


        $this->registrar();

        $contaTransf = new Conta(0, $transf->getNumContaTransf(), $transf->getAgTransf());
        $consulta = $contaTransf->consultarNome();
        
        $this->valor = $transf->getValorTransf();
        $this->operacao = 2;
        $this->descricao = "TED enviada no valor de ". Money::real($this->valor). 
        " para  ".$consulta[0] . " ". $transf->getAgTransf(). " | " .$transf->getNumContaTransf();
        $this->categoria = "Transferência";


        $this->registrar();


        $this->descricao = "TED recebida no valor de ". Money::real($this->valor). 
        " de ".$_SESSION['userName'] . " ". $transf->getAgTransf(). " | " .$transf->getNumContaTransf();
        $this->registrar($contaTransf->getIdConta());*/
    }
    

    public function registrar($contaDestino = 0){
        $conn = DbConn::getConn();
        $query = "INSERT INTO extrato (idConta, valor, operacao, descricao, categoria, dataOperacao) 
                    VALUES (:conta, :valor, :operacao, :descricao, :categoria, :dataOperacao)";
        $prepare = $conn->prepare($query);

        if($contaDestino != 0){
            $prepare->bindValue(":conta", $contaDestino);
        }else{
            
            $prepare->bindValue(":conta", $this->conta);
        }

        $prepare->bindValue(":descricao", $this->descricao);
        $prepare->bindValue(":valor", $this->valor);
        $prepare->bindValue(":operacao", $this->operacao);
        $prepare->bindValue(":categoria", $this->categoria);
        $prepare->bindValue(":dataOperacao", $this->dataOperacao);
        $r = $prepare->execute();
    }

    
}