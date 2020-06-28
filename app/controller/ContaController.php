<?php
use Twig\Extra\Intl\IntlExtension;

class ContaController{
    public static function index(){
        

        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);

        $conta = new Conta();
        var_dump($conta);

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
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta', 'conteudo'=>$conteudo]);
    }


    public static function transferencia(){
        $pos = strpos($_SESSION['userName']," ");
        $nome = substr($_SESSION['userName'], 0,$pos);



        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        
        //carrega o conteúdo da view e modfica as variáves
        $conteudo = $twig->render('transferencia.html', ['nome'=>$nome]);
        
        //adiciona o conteúdo da página na template
        echo $twig->render('template.html', ['titulo'=> 'Minha Conta', 'conteudo'=>$conteudo]);
    }
}