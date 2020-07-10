<?php

class Boleto{
    private $dataAtual;
    private $numBoleto;
    private $idBoleto;
    private $idConta;
    private $dataEmissao;
    private $vencimento;
    private $dataVencimento;
    private $diasAtraso;
    private $cobrarJuros;
    private $dataDesconto;
    
    private $multaAtraso;

    private $valorDesconto;
    private $valorDescontoMoeda;
    
    
    private $valor;
    private $valorMoeda;
    
    private $total;
    private $totalMoeda;
    private $valorPagamento;
    private $statusPagamento;
    private $resultado;
    

    // =========================================== Cadastra um novo boleto ============================================
    public function gerarBoleto($valor, $vencimento){   
        $this->dataAtual = date("Y-m-d");
        if (strlen($valor)>0 && strlen($vencimento)>0) {
        
            $this->valor = abs(filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION));
            $this->vencimento = filter_var($vencimento, FILTER_SANITIZE_SPECIAL_CHARS);
            $dataVencimento = explode('-',$this->vencimento);
            $dataVencimento = checkdate($dataVencimento[1], $dataVencimento[2], $dataVencimento[0]); 
            
            //gera o número do boleto
            $hora = date("H-i-s");
            $random = rand(1, 999999);
            while (strlen($random) < 6){
                $random = "0" . $random;
            }

            $random2 = rand(1, 99999);
            while (strlen($random2) < 5){
                $random2 = "0" . $random2;
            }
            $this->numBoleto = "00139." .  $random . "." . str_replace("-","",$this->dataAtual).".".str_replace("-","",$hora) . ".$random2-8"; 
            
        
            if ($dataVencimento) {
                $conn = DbConn::getConn();
            
                $query = "INSERT INTO boleto (idConta, valor, dataEmissao, dataVencimento, cobrarJuros, statusPagamento, codigoBoleto) 
                        VALUES (:idConta, :valor, :dataEmissao, :dataVencimento, :cobrarJuros, :statusPagamento, :codigoBoleto)";

                
                $prepare = $conn->prepare($query);

                $prepare->bindValue(":idConta", $_SESSION['userId']);
                $prepare->bindValue(":valor", $this->valor);
                $prepare->bindValue(":dataEmissao", $this->dataAtual);
                $prepare->bindValue(":dataVencimento", $this->vencimento);
                $prepare->bindValue(":cobrarJuros", 0);
                $prepare->bindValue(":statusPagamento", 0);
                $prepare->bindValue(":codigoBoleto", $this->numBoleto);
                $res = $prepare->execute();
                
                
                //$mpdf = new \Mpdf\Mpdf();
                //$mpdf->WriteHTML('<h1>Hello world!</h1>');
                //$mpdf->Output();
                
                $this->resultado = true;
            }else {
                $this->resultado = false;
            }
        }else{
            $this->resultado = false;
        }

        return $this->resultado;
    }

    // ====================================== Procura e carrega no objeto os dados de um boleto cadastrado ======================
    public function procuraBoleto($numBoleto){
        //SANITIZAR ISSO AQUI
        $this->numBoleto = $numBoleto;


        $conn = DbConn::getConn();
        $query = "SELECT * FROM boleto WHERE codigoBoleto = :codigoBoleto";
        $prepare = $conn->prepare($query);
        $rows = $prepare->bindValue(":codigoBoleto", $this->numBoleto);
        $res = $prepare->execute();
       
        $resposta = $prepare->fetch();
        if ($resposta != false){      
            foreach ($resposta as $key => $value) {
                if (is_numeric($key)) {
                    unset($resposta[$key]); //limpa os indices numéricos do resultado
                }
            }


            $this->idBoleto = $resposta['idBoleto'];
            $this->idConta = $resposta['idConta'];
            $this->dataEmissao = $resposta['dataEmissao'];
            $this->vencimento = new DateTime($resposta['dataVencimento']);
            $this->dataVencimento = $resposta['dataVencimento'];
            $this->dataDesconto = new DateTime($resposta['dataDesconto']);
            $this->dataAtual = new DateTime();

            $this->cobrarJuros = $resposta['cobrarJuros'];
            $this->valor = $resposta['valor'];
            $this->valorDesconto = $resposta['valorDesconto'];



            $this->resultado = true;
        } else {
            $this->resultado = false;
        }
    }

    // =================================== Calcula o valor a pagar do boleto ========================
    public function calcularBoleto(){
        
        //MULTA POR ATRASO
        if ($this->cobrarJuros && ($this->vencimento < $this->dataAtual)) {
           
           //calcula os dias atrasados
           $calcAtraso = (date_diff($this->vencimento, $this->dataAtual));
           $this->diasAtraso = (string) $calcAtraso->d;
           
           //calcula a multa por atraso (juros simples de 0.8% ao dia)
           $multa = $this->valor * $this->diasAtraso * 0.008;
      
           $this->valorMoeda = Money::real($this->valor);
           $this->multaAtraso = Money::real($multa);
           $this->valorDescontoMoeda = Money::real(0);
           $this->total = $this->valor + $multa;
           $this->totalMoeda = Money::realSemSifrao($this->valor + $multa);
       
       
       //DESCONTO
       }else if ($this->valorDesconto > 0 && $this->dataDesconto > $this->dataAtual) {
           //calcula o desconto
           $this->valorMoeda = Money::real($this->valor);
           $this->multaAtraso = Money::real(0);
           $this->valorDescontoMoeda = Money::real($this->valorDesconto);
           $this->total = $this->valor - $this->valorDesconto;
           $this->totalMoeda = Money::realSemSifrao($this->valor - $this->valorDesconto);

       //DEFAULT
       }else{
           $this->valorMoeda = Money::real($this->valor);
           $this->multaAtraso = Money::real(0);
           $this->valorDescontoMoeda = Money::real(0);
           $this->total = $this->valor;
           $this->totalMoeda = Money::realSemSifrao($this->valor);
       }
    }



    //realiza o pagamento de um boleto e registra no extrato
    public function pagarBoleto($conta){
       
        $contaBeneficiario = new Conta($this->getIdConta());
        //$contaBeneficiario = new Conta($boleto->getIdConta());
        var_dump($contaBeneficiario);
        $conn = DbConn::getConn();
        
        
        $query = "UPDATE conta 
        SET saldo = saldo + :novoSaldo 
        WHERE tipo =:tipo && numConta = :numConta 
        AND agConta = :agConta";
        $prepare = $conn->prepare($query);
        
        $prepare->bindValue(":novoSaldo", $this->total);
        $prepare->bindValue(":tipo", $contaBeneficiario->getTipo());
        $prepare->bindValue(":numConta", $contaBeneficiario->getNumConta());
        $prepare->bindValue(":agConta", $contaBeneficiario->getAgConta());
        $prepare->execute();

        //em caso de sucesso na transferência, retira o saldo da conta que realizou a operação
        if ($prepare->rowCount() > 0) {
            $query = "UPDATE conta 
            SET saldo = saldo - :novoSaldo 
            WHERE tipo =:tipo && numConta = :numConta 
            AND agConta = :agConta";
            $prepare = $conn->prepare($query);
            
            $prepare->bindValue(":novoSaldo", $this->total);
            $prepare->bindValue(":tipo", $conta->getTipo());
            $prepare->bindValue(":numConta", $conta->getNumConta());
            $prepare->bindValue(":agConta", $conta->getAgConta());
            $prepare->execute();

        
        }
        $ret = ($prepare->rowCount() > 0)? true: false; 
        return $ret;
    }

    public function toJson(Type $var = null){
        $objectArray = [];
        foreach($this as $key => $value) {
            $objectArray[$key] = $value;
        }
    
        return json_encode($objectArray);
    }

    

    
	public function getIdConta() {
		return $this->idConta;
	}

	public function setIdConta($idConta) {
		$this->idConta = $idConta;
    }
    

    public function getValor(){
        return $this->valor;
    }

    public function getDataAtual(){
        return $this->dataAtual;
    }

    public function getVencimento(){
        return $this->vencimento;
    }

    public function getNumBoleto(){
        return $this->numBoleto;
    }

    public function getResultado(){
        return $this->resultado;
    }


    public function setValor($value){
        $this->valor = $value;
    }

    public function setDataAtual($value){
        $this->dataAtual = $value;
    }

    public function setVencimento($value){
        $this->vencimento = $value;
    }

    public function setNumBoleto($value){
        $this->numBoleto = $value;
    }

    public function setResultado($value){
        $this->resultado = $value;
    }

    
}