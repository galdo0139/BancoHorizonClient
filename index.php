<?php
//var_dump($_GET);
//echo "<hr>";

require_once('app/core/core.php');

//require de todas as controllers do projeto
require_once('app/controller/HomeController.php');
require_once('app/controller/ErroController.php');
require_once('app/controller/PageController.php');



$url = explode('/', $_GET['url']);
$urlLast = end($url);

//trás o conteúdo de uma view
$core = new Core($urlLast);
$preView = $core->renderController();

//template geral do site
$template = file_get_contents('app/template/tpl.html');

$render = str_replace('{{Título}}', $preView['title'], $template);
$render = str_replace('{{conteudo}}', $preView['content'], $render);


echo $render;
?>