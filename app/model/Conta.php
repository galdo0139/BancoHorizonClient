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

    private $valorTransf;
    private $agTransf;
    private $numContaTransf;
    private $tipoContaTransf;
    private $bancoTransf;
    
    
    
    
    //carrega os dados da conta do usuário
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
   
    public function pagamento(Type $var = null)
    {
        # code...
    }

    public function gerarBoleto(Type $var = null)
    {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();
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
    public function getValorTransf(){
        return $this->valorTransf;
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
    public function setValorTransf($value){
        $this->valorTransf = $value;
    }


}