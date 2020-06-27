<?php
require_once "app/model/Autenticacao.php";
session_start();


$aut = new Autenticacao("");

$autenticado = $aut->getAutenticacao();

if ($autenticado) {
    if ($_GET['logout'] == 'sair') {
        unset($_SESSION['conta']);
        unset($aut);
        echo "desloguei";
        //$aut->logout($aut);
    }else{
        header("location: conta");
    }    
}

$tpl = file_get_contents("app/view/template.html");

$view = file_get_contents("app/view/home.html");
    
$tpl = str_replace("{{conteudo}}", $view, $tpl);
$tpl = str_replace("{{titulo}}", "Acessar", $tpl);
    
echo $tpl;
var_dump($_SESSION['conta']);
var_dump($aut);
