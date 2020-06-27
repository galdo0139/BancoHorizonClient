<?php
class Core{
	private $controller;

	public function __construct($url){
		$this->controller = ucfirst($url."Controller");
		


		if (!class_exists($this->controller)) {
			$this->controller = "ErroController";
		} 
	}

	public function renderController(){
		$acao = 'index';
		ob_start();
		$returnController  = call_user_func_array(array($this->controller, $acao), array());
		$render = ob_get_contents();
		ob_end_clean();

		$dados = ["content" => $render, "title" => $returnController['title'], "css" => $returnController['css'], "js" => $returnController['js']];
		return $dados;
	}
}