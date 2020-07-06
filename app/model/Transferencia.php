<?php

class Transferencia{
    private $valorTransf;
    private $agTransf;
    private $numContaTransf;
    private $tipoContaTransf;
    private $bancoTransf;
        
    public function transferir($dados, $conta){
        //filtra os dados recebidos do formulário
        $this->valorTransf = str_replace(',','.',$dados['valor']);
        $this->valorTransf =  abs(filter_var($this->valorTransf, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
        $this->agTransf = filter_var($dados['agencia'], FILTER_SANITIZE_NUMBER_INT);
        $this->numContaTransf = filter_var($dados['conta'], FILTER_SANITIZE_NUMBER_INT);
        $this->tipoContaTransf = filter_var($dados['tipoConta'], FILTER_SANITIZE_NUMBER_INT);

        //arrumar isso
        $this->bancoTransf = filter_var($dados['banco'], FILTER_SANITIZE_NUMBER_INT);
            
        

        $conn = DbConn::getConn();
        
        //adiciona o saldo a uma conta existente
        $query = "UPDATE conta SET saldo = saldo + :novoSaldo WHERE tipo =:tipo && numConta = :numConta && agConta = :agConta";
        $prepare = $conn->prepare($query);
        
        $prepare->bindValue(":novoSaldo", $this->valorTransf);
        $prepare->bindValue(":tipo", $this->tipoContaTransf);
        $prepare->bindValue(":numConta", $this->numContaTransf);
        $prepare->bindValue(":agConta", $this->agTransf);
        $prepare->execute();

        //em caso de sucesso na transferência, retira o saldo da conta que realizou a operação
        if ($prepare->rowCount() > 0) {
            $query = "UPDATE conta SET saldo = saldo - :novoSaldo WHERE tipo =:tipo && numConta = :numConta && agConta = :agConta";
            $prepare = $conn->prepare($query);
            
            $prepare->bindValue(":novoSaldo", $this->valorTransf);
            $prepare->bindValue(":tipo", $conta->getTipo());
            $prepare->bindValue(":numConta", $conta->getNumConta());
            $prepare->bindValue(":agConta", $conta->getAgConta());
            $prepare->execute();

        
        }
        $ret = ($prepare->rowCount() > 0)? true: false; 
        return $ret;
    }

    public function getValorTransf(){
        return $this->valorTransf;
    }
    public function getAgTransf(){
        return $this->agTransf;
    }
    public function getNumContaTransf(){
        return $this->numContaTransf;
    }
    public function getTipoContaTransf(){
        return $this->tipoContaTransf;
    }
    public function getBancoTransf(){
        return $this->bancoTransf;
    }
    
    public function setValorTransf($value){
        $this->valorTransf = $value;
    }
}