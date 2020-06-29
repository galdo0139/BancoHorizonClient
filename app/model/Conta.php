<?php

class Conta{
    private $idConta;
    private $idCliente;
    private $cpf;
    private $tipo;
    private $numContrato;
    private $senhaConta;
    private $possuiCartao;
    private $dataCadastro;
    private $taxaMensal;
    private $numConta;
    private $agConta;
    private $saldo;

    //carrega os dados da conta do usuário para o dashboard
    public function __construct(){
        
        $conn = DbConn::getConn();
        $query = "SELECT * FROM conta where idConta =:conta";
        $prepare = $conn->prepare($query);
        $prepare->bindValue(":conta", $_SESSION['userId']);
        $prepare->execute();
        if ($prepare->rowCount()) {
            $resultado = $prepare->fetch();
        }

        $this->idConta = $resultado['idConta'];
        $this->idCliente = $resultado['idCliente'];
        $this->cpf = $resultado['cpf'];
        $this->tipo = $resultado['tipo'];
        $this->numContrato = $resultado['numContrato'];
        $this->possuiCartao = $resultado['possuiCartao'];
        $this->dataCadastro = $resultado['dataCadastro'];
        $this->taxaMensal = $resultado['taxaMensal'];
        $this->numConta = $resultado['numConta'];
        $this->agConta = $resultado['agConta'];
        $this->saldo = $resultado['saldo'];
    }

    public function transferir($dados){
        $conn = DbConn::getConn();
        $query = "SELECT * FROM conta where idConta =:conta";
        $prepare = $conn->prepare($query);
        $prepare->bindValue(":conta", $_SESSION['userId']);
        $prepare->execute();
        if ($prepare->rowCount()) {
            $resultado = $prepare->fetch();
        }
    }
   
    public function pagamento(Type $var = null)
    {
        # code...
    }

    public function gerarBoleto(Type $var = null)
    {
        # code...
    }

    public function extrato(Type $var = null)
    {
        # code...
    }

    public function cartaoCredito(Type $var = null)
    {
        # code...
    }



    //getters e setters da classe
    public function getIdConta(){
        return $this->idConta;
    }
    public function getIdCliente(){
        return $this->idCliente;
    }
    public function getCpf(){
        return $this->cpf;
    }
    public function getTipo(){
        return $this->tipo;
    }
    public function getNumContrato(){
        return $this->numContrato;
    }
    public function getPossuiCartao(){
        return $this->possuiCartao;
    }
    public function getDataCadastro(){
        return $this->dataCadastro;
    }
    public function getTaxaMensal(){
        return $this->taxaMensal;
    }
    public function getNumConta(){
        return $this->numConta;
    }
    public function getAgConta(){
        return $this->agConta;
    }
    public function getSaldo(){
        return $this->saldo;
    }



    public function setIdConta($value){
        $this->idConta = $value;
    }
    public function setIdCliente($value){
        $this->idCliente = $value;
    }
    public function setCpf($value){
        $this->cpf = $value;
    }
    public function setTipo($value){
        $this->tipo = $value;
    }
    public function setNumContrato($value){
        $this->numContrato = $value;
    }
    public function setPossuiCartao($value){
        $this->possuiCartao = $value;
    }
    public function setDataCadastro($value){
        $this->dataCadastro = $value;
    }
    public function setTaxaMensal($value){
        $this->taxaMensal = $value;
    }
    public function setNumConta($value){
        $this->numConta = $value;
    }
    public function setAgConta($value){
        $this->agConta = $value;
    }
    public function setSaldo($value){
        $this->saldo = $value;
    }

}