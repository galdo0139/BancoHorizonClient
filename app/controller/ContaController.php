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
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
    }

    public static function FunctionName(Type $var = null)
    {
        header("location: /gigabankclient");
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
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
     }

    //carrega a página pra fazer transferências
    public static function transferencia(){
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
        $conteudo = $twig->render('transferencia.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
    }

   

    //
    public static function boleto(Type $var = null)
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
        $conteudo = $twig->render('boleto.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
    }

    //
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
        $conteudo = $twig->render('extrato.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
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
        $conteudo = $twig->render('cartao.html', ['nome'=>$nome, 'saldo'=>$conta->getSaldo()]);
        $menu = $twig->render('sideMenu.html');
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta',
                                             'conteudo'=>$conteudo,
                                             'menu'=>$menu,
                                             'css'=>'/gigaBankClient/public/dashboard.css']);
    }

    //
   /* public function FunctionName(Type $var = null)
    {
        # code...
    }*/
}