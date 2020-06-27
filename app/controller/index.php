<?php
require_once "../app/model/Autenticacao.php";
require_once "../app/model/DbConn.php";

session_start();
var_dump($_SESSION['conta']);
var_dump($autenticado);

$db = new DbConn();

$db = $db->getDb();
$aut = new Autenticacao($db);

$autenticado = $aut->getAutenticacao();


if($autenticado){
    $tpl = file_get_contents("../app/view/template.html");

    $view = file_get_contents("../app/view/conta.html");

    $tpl = str_replace("{{conteudo}}", $view, $tpl);
    $tpl = str_replace("{{titulo}}", "Minha Conta", $tpl);
}else{
    $tpl = "Você não ta logado";
}
echo $tpl;

