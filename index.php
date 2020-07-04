<?php
//configurações gerais de timezone e charset, aplicado a todo o projeto
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');


//requisita o core do projeto
require_once 'app/core/core.php';
//requisita o autoloader do composer
require_once 'vendor/autoload.php';



//======================== Ver como colocar esses requires na autoloader ==========================================
//require das models do projeto
require_once 'lib/DbConn.php';
require_once 'lib/TwigConfig.php';
require_once 'app/model/Usuario.php';
require_once 'app/model/Conta.php';
require_once 'app/model/Extrato.php';


//require de todas as controllers do projeto
require_once "app/controller/LoginController.php";
require_once "app/controller/ContaController.php";
//require_once('app/controller/HomeController.php');
require_once "app/controller/ErroController.php";
//require_once('app/controller/PageController.php');
//==================================================================================================================



session_start();

$core = new Core();

//trás o conteúdo de uma view através do core
$core->start($_GET);

?>