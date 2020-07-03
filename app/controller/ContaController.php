<?php
use Twig\Extra\Intl\IntlExtension;

class ContaController{

    //carrega o dashboard da conta ao fazer login
    public static function index(){
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        //var_dump($conta);

        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);

        $twig->addExtension(new IntlExtension());
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('dashboard.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    public static function FunctionName(Type $var = null)
    {
        header("location: /BancoHorizonclient");
    }
     //
     public static function pagamento(Type $var = null)
     {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        //var_dump($conta);

        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);

        $twig->addExtension(new IntlExtension());
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('pagamento.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
     }

    // ================================== TRANSFERÊNCIAS ====================================================
    public static function transferencia(){
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        

        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        
        //adiciona a extensão de formatação de números ao twig
        $twig->addExtension(new IntlExtension());
        
        //checa se uma transferência foi solicitada
        if(isset($_POST['valor']) && isset($_POST['agencia']) && isset($_POST['conta']) && isset($_POST['banco']) && isset($_POST['tipoConta'])){
            //chama o método transferir ao solicitar essa operação
            $res = $conta->transferir($_POST, $conta);

            if($res){
                //transferencia bem sucedida
               $conteudo = $twig->render('transferencia/sucesso.html', ['transf'=> $conta->getValorTransf(), 'saldo'=>$conta->getSaldo()]);
            }else{
                //transferência mau sucedida
                $conteudo = $twig->render('transferencia/erro.html', ['saldo'=>"ERRO"]);
            }
        }else{
            //carrega o conteúdo da view e modfica as variáves
            $conteudo = $twig->render('transferencia.html', ['saldo'=>$conta->getSaldo()]);
           
        }

        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                         'conteudo'=>$conteudo,
                                         'menu'=>$menu,
                                         'css'=>'/BancoHorizonClient/public/dashboard.css']);
        
    }

   

    //=============================================== BOLETOS ================================================
    public static function boleto(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        //$conta->gerarBoleto(); 
        
        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);

        $twig->addExtension(new IntlExtension());
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('boleto.html', ['saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    //================================================ EXTRATO ============================================
    public static function extrato(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        //var_dump($conta);

        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);

        $twig->addExtension(new IntlExtension());
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('extrato.html', ['saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/BancoHorizonClient/public/dashboard.css']);
    }

    //
    public static function cartao(Type $var = null)
    {
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        //var_dump($conta);

        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);

        $twig->addExtension(new IntlExtension());
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('cartao.html', ['saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
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