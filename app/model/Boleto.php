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
    private $multaAtraso;
    private $cobrarJuros;
    private $dataDesconto;
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

    // ====================================== Procura e exibe os dados de um boleto cadastrado ======================
    public function procuraBoleto($numBoleto){
        //SANITIZAR ISSO AQUI
        $this->numBoleto = $numBoleto;

        $conn = DbConn::getConn();
            
        $query = "SELECT * FROM boleto WHERE 
        idConta = :idConta && codigoBoleto = :codigoBoleto";

        
        $prepare = $conn->prepare($query);

        $rows = $prepare->bindValue(":idConta", $_SESSION['userId']);
        $prepare->bindValue(":codigoBoleto", $this->numBoleto);
        $res = $prepare->execute();
        if ($res) {
            while($row = $prepare->fetch()) {
                $resposta = $row;
            }
            foreach ($resposta as $key => $value) {
                if (is_numeric($key)) {
                    unset($resposta[$key]);
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
            $resposta['diasAtraso'] = (string) 0;

            $calcAtraso = (date_diff($this->vencimento, $this->dataAtual));

            
            
            
            
            //MULTA POR ATRASO
            if ($this->cobrarJuros && ($this->vencimento < $this->dataAtual)) {
               
                 //calcula os dias atrasados
                $resposta['diasAtraso'] = (string) $calcAtraso->d;
                //calcula a multa por atraso (juros simples de 0.8% ao dia)
                $multa = $resposta['valor'] * $resposta['diasAtraso'] * 0.008;
           
                $this->valorMoeda = Money::real($this->valor);
                $this->multaAtraso = Money::real($multa);
                $this->valorDescontoMoeda = Money::real(0);
                $this->total = Money::realSemSifrao($this->valor + $multa);
                $this->totalMoeda = Money::realSemSifrao($this->valor + $multa);
            
            
            //DESCONTO
            }else if ($resposta['valorDesconto'] > 0 && $this->dataDesconto > $this->dataAtual) {
                //calcula o desconto
                $this->valorMoeda = Money::real($this->valor);
                $this->multaAtraso = Money::real(0);
                $this->valorDescontoMoeda = Money::real($this->valorDesconto);
                $this->total = Money::realSemSifrao($this->valor);
                $this->totalMoeda = Money::realSemSifrao($this->valor);

            //DEFAULT
            }else{
                $this->valorMoeda = Money::real($this->valor);
                $this->multaAtraso = Money::real(0);
                $this->valorDescontoMoeda = Money::real(0);
                $this->total = Money::realSemSifrao($this->valor);
                $this->totalMoeda = Money::realSemSifrao($this->valor);
            }
        } else {
            $resposta = false;
        }
        
        
        return $this;

    }

    public function toJson(Type $var = null){
        $objectArray = [];
        foreach($this as $key => $value) {
            $objectArray[$key] = $value;
        }
    
        return json_encode($objectArray);
    }

    public function jsonSerialize() {
        return $this;
    }


    public function pagarBoleto($var = null){
        # code...
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