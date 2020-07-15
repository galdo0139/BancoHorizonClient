<?php

class Extrato{
    private $conta;
    private $contaDestino;
    private $valor;
    private $operacao;
    private $descricao;
    private $categoria;
    private $dataOperacao;
    private $codigoBoleto;
    

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


    public function transferencia($transf, $conta){
        $contaTransf = new Conta(0, $transf->getNumContaTransf(), $transf->getAgTransf());
        $consulta = $contaTransf->consultarNome();
        
        $this->valor = $transf->getValorTransf();
        $this->operacao = 2;
        $this->descricao = "TED enviada no valor de ". Money::real($this->valor). 
        " para  ".$consulta[0] . " ". $transf->getAgTransf(). " | " .$transf->getNumContaTransf();
        $this->categoria = "Transferência";
        $this->registrar();

        
        $this->descricao = "TED recebida no valor de ". Money::real($this->valor). 
        " de ".$_SESSION['userName'] . " ". $conta->getAgConta(). " | " .$conta->getNumConta();
        $this->registrar($contaTransf->getIdConta());
    }

    

    
    public function pagamento($boleto, $conta){
        
        $this->valor = $boleto->getTotal();
        $this->operacao = 3;
        $this->descricao = "Pagamento de boleto para ". $boleto->getNomeBeneficiado(). " no valor de ". $boleto->getTotalMoeda();
        $this->categoria = "Pagamento";
        $this->codigoBoleto = $boleto->getNumBoleto();

        var_dump($this);

        $this->registrar();
        $this->descricao = "Recebimento de boleto no valor de ". Money::real($this->valor);
        $this->registrar($boleto->getIdConta());
    }
    

    public function registrar($contaDestino = 0){
        $conn = DbConn::getConn();
        $query = "INSERT INTO extrato (idConta, valor, operacao, descricao, categoria, dataOperacao, codigoBoleto) 
                    VALUES (:conta, :valor, :operacao, :descricao, :categoria, :dataOperacao, :codigoBoleto)";
        $prepare = $conn->prepare($query);

        if($contaDestino != 0){
            $prepare->bindValue(":conta", $contaDestino);
        }else{
            
            $prepare->bindValue(":conta", $this->conta);
        }

        if($this->operacao == 3){
            $prepare->bindValue(":codigoBoleto", $this->codigoBoleto);
        }else{
            $prepare->bindValue(":codigoBoleto", "null");
        }
        $prepare->bindValue(":descricao", $this->descricao);
        $prepare->bindValue(":valor", $this->valor);
        $prepare->bindValue(":operacao", $this->operacao);
        $prepare->bindValue(":categoria", $this->categoria);
        $prepare->bindValue(":dataOperacao", $this->dataOperacao);
        $r = $prepare->execute();
    }

    
}