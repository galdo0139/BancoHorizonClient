<?php 

class Usuario{

    private $conta;
    private $agencia;
    private $senha;
    //autentica um usuário que já esteja logado corretamente ou que esteja fazendo login no momento

    public function logOut($obj){
        unset($_SESSION);
        unset($obj);
    }

    public function autenticar(){
        $conn = DbConn::getConn();

        $query = 'SELECT * from conta WHERE numConta=:numConta && agConta=:agConta';
        $clientQuery = 'SELECT * from cliente WHERE idCliente=:idCliente';
        
        $preparePDO = $conn->prepare($query);
        $preparePDO->bindValue(':numConta', $this->conta);
        $preparePDO->bindValue(':agConta', $this->agencia);
        $preparePDO->execute();

        if($preparePDO->rowCount()){
            $result = $preparePDO->fetch();
            //verifica se a senha inserida é correspondente a senha cadastrada e HASHad no DB
            if(password_verify($this->senha, $result['senhaConta'])){
                //logado com sucesso
                $_SESSION['userId'] = $result['idConta'];
                

                $preparePDO = $conn->prepare($clientQuery);
                $preparePDO->bindValue(':idCliente', $result['idCliente']);
                $preparePDO->execute();
                $result = $preparePDO->fetch();

                $_SESSION['userName'] = $result['nome'];

                
            }else{
                //senha incorreta
                throw new Exception("Erro de autenticação");
            }
        }else{
            //erro usuário não encontrado
            throw new Exception("Erro de autenticação");
        }
        
        
    }


    //retorna o estado da autenticação
    public function getAutenticacao(){
        return $this->autenticacao;
    }

    //debug de conexão com o banco
    public function getDb(){
        return $this->db;
    }


    //getters e setters da classe
    public function setConta($conta){
        $this->conta = $conta;
    }
    
    public function setSenha($senha){
        $this->senha = $senha;
    }
    
    public function setAgencia($agencia){
        $this->agencia = $agencia;
    }

    public function getConta(){
        return $this->conta;
    }
    
    public function getSenha(){
        return $this->senha;
    }
    
    public function getAgencia(){
        return  $this->agencia;
    }
}




