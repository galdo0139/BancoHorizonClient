<?php 

class Autenticacao{
    private $autenticacao;
    private $db;
    //autentica um usuário que já esteja logado corretamente ou que esteja fazendo login no momento
    public function __construct($db){
        $this->db = $db;

        if(isset($_SESSION['conta'])){
            $this->autenticacao = true; 
            echo "aqui";   
        }else if(isset($_POST['agencia']) && isset($_POST['conta']) && isset($_POST['senha'])){
            $conta = 1;
            $ag = $_POST['agencia'];
            $conta = $_POST['conta'];
            $senha = $_POST['senha'];

            $res = $db->query("select * from conta where numConta = '$conta' && agConta = '$ag' && senhaConta = '$senha'");
            if($res->num_rows>0){
                $_SESSION['conta'] = $conta;
                $this->autenticacao = true;
            }else{
                $this->autenticacao = false;
            }            
        }else{
            //echo('malandrinho');
            $this->autenticacao = false;
        }
    }

    public function logOut($obj){
        unset($_SESSION);
        unset($obj);
    }

    //retorna o estado da autenticação
    public function getAutenticacao(){
        return $this->autenticacao;
    }

    //debug de conexão com o banco
    public function getDb(){
        return $this->db;
    }
}




