<?php

class ContaController{

    //carrega o dashboard da conta ao fazer login
    public static function index(){
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();


        $conta = new Conta();
        
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('dashboard.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta - Banco Horizon',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }



    public static function FunctionName(Type $var = null)
    {
        header("location: /BancoHorizonclient");
    }


     //===================================== PAGAMENTOS ========================================================
     public static function pagamento(Type $var = null)
     {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();

        
        $conta = new Conta();
        
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('pagamento.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Pagamentos - Banco Horizon',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
     }

    // ================================== TRANSFERÊNCIAS ====================================================
    public static function transferencia(){
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();


        $conta = new Conta();
        
        
        
        //checa se uma transferência foi solicitada
        if(isset($_POST['valor']) && isset($_POST['agencia']) && isset($_POST['conta']) && isset($_POST['banco']) && isset($_POST['tipoConta'])){
            //chama o método transferir ao solicitar essa operação
            $res = $conta->transferir($_POST, $conta);

            if($res){
                //registro no extrato
                $extrato = new Extrato();

                $descricao = "Transferência TED para ". $conta->getAgTransf(). " | " .$conta->getNumContaTransf();
                $extrato->transferencia($conta->getValorTransf(), $descricao);
                
                //renderiza a view de sucesso na transferencia
                $conteudo = $twig->render('transferencia/sucesso.html', ['transf'=> $conta->getValorTransf(), 'saldo'=>$conta->getSaldo()]);
                unset($POST);
            }else{
                //transferência mal sucedida
                $conteudo = $twig->render('transferencia/erro.html', ['saldo'=>"ERRO"]);
            }
        }else{
            //carrega o conteúdo da view e modfica as variáves
            $conteudo = $twig->render('transferencia/transferencia.html', ['saldo'=>$conta->getSaldo()]);
           
        }

        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Transferência - Banco Horizon',
                                         'conteudo'=>$conteudo,
                                         'menu'=>$menu,
                                         'css'=>'/BancoHorizonClient/public/dashboard.css']);
        
    }

   

    //=============================================== BOLETOS ================================================
    public static function boleto(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();


        $conta = new Conta();
        //$conta->gerarBoleto(); 
        
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('boleto.html', ['saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Gerar Boleto - Banco Horizon',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    //================================================ EXTRATO ============================================
    public static function extrato(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();


        $conta = new Conta();
        $extrato = new Extrato();
        $dados = $extrato->verExtrato();

        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('extrato.html', ['saldo'=>$conta->getSaldo(), 'queryResult'=> $dados]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Extrato - Banco Horizon',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    //============================================= CARTÕES ==================================================
    public static function cartao(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);
        //carrega um objeto twig configurado
        $twig = TwigConfig::loader();


        $conta = new Conta();

        
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('cartao.html', ['saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Meus Cartões - Banco Horizon',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    //
   /* public function FunctionName(Type $var = null)
    {
        # code...
    }*/
}