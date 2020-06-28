<?php

//requisita o core do meu projeto
require_once 'app/core/core.php';
require_once 'vendor/autoload.php';

//require das models do projeto
require_once 'lib/DbConn.php';
require_once 'app/model/Usuario.php';
require_once 'app/model/Conta.php';

//require de todas as controllers do projeto
require_once "app/controller/LoginController.php";
require_once "app/controller/ContaController.php";
//require_once('app/controller/HomeController.php');
require_once'app/controller/ErroController.php';
//require_once('app/controller/PageController.php');


session_start();



//trás o conteúdo de uma view
$core = new Core();
$core->start($_GET);


//$preView = $core->renderController();

//template geral do site
$template = file_get_contents('app/view/template.html');

//$render = str_replace('{{Título}}', $preView['title'], $template);
//$render = str_replace('{{conteudo}}', $preView['content'], $render);


//echo $render;
?>