<?php

class Boleto {
    private $valor;
    private $dataAtual;
    private $vencimento;
    private $numBoleto;
    private $resultado;

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

    public function procuraBoleto($numBoleto){
        $this->numBoleto = $numBoleto;

        $conn = DbConn::getConn();
            
        $query = "SELECT * FROM boleto WHERE 
        idConta = :idConta && codigoBoleto = :codigoBoleto";

        
        $prepare = $conn->prepare($query);

        $prepare->bindValue(":idConta", $_SESSION['userId']);
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
        } else {
            $resposta = false;
        }
        
        
        return $resposta;

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