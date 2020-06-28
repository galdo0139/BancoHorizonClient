<?php
class LoginController{
    public static function index()
    {
        //uso da classe do twig para renderizar as minhas views
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('home.html');
        echo $template->render();
    }

    public static function check(){
        $usuario = new Usuario();
            
        try {
            if(isset($_POST['conta']) && isset($_POST['agencia']) && isset($_POST['senha'])){
                $usuario->setConta($_POST['conta']);
                $usuario->setAgencia($_POST['agencia']);
                $usuario->setSenha($_POST['senha']);
                $r = $usuario->autenticar();
               
                header("Location: /gigaBankClient/conta");
            }else{
                header("Location: /gigaBankClient");
            }
            
        } catch (\Throwable $th) {
            header("Location: /gigaBankClient");
        }
        
    }

    public static function logout(){
        unset($_SESSION['userId']);
        unset($_SESSION['userName']);
        header("Location: /gigaBankClient");
    }
}