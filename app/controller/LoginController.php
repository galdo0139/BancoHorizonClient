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

    public static function check($erro){
        $usuario = new Usuario();
            
        try {
            if(isset($_POST['conta']) && isset($_POST['agencia']) && isset($_POST['senha'])){
                $usuario->setConta($_POST['conta']);
                $usuario->setAgencia($_POST['agencia']);
                $usuario->setSenha($_POST['senha']);
                $r = $usuario->autenticar();
               
                header("Location: /BancoHorizonClient/conta");
            }else{
                header("Location: /BancoHorizonClient?erro=$erro");
            }
            
        } catch (\Throwable $th) {
           header("Location: /BancoHorizonClient?erro=$erro");
        }
        
    }

    public static function logout(){
        unset($_SESSION['userId']);
        unset($_SESSION['userName']);
        header("Location: /BancoHorizonClient/");
    }
}