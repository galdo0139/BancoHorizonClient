<?php
use Twig\Extra\Intl\IntlExtension;
class TwigConfig{

    public static function loader(Type $var = null)
    {   
        //loader que acessa a pasta das views no app
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        //gera cache das views
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        
        //adiciona a extensão de formatação de números ao twig
        $twig->addExtension(new IntlExtension());

        return $twig;
    }
}