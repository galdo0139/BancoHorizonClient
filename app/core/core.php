<?php
class Core{
	private $controller;
	private $metodo = 'index';
	private $parametros;


	private $url;
	private $usuario;


	public function __construct(){
		$this->usuario = $_SESSION['userId'] ?? null;
	}


	//define qual sera a controller e o metodo a ser chamado 
	public function start($url){
		if(isset($url['url'])){
			//define a controller
			$this->url = explode('/', $url['url']);
			$this->controller = ucfirst($this->url[0])."Controller";
			array_shift($this->url);

			//define o método da controller
			if(isset($this->url[0]) && $this->url[0] != ''){
				$this->metodo = $this->url[0];
				array_shift($this->url);

				//define parametros pra este método
				if(isset($this->url[0]) && $this->url != ''){
					$this->parametros = $this->url;
				}
			}
		}else{
			//exception que chama a home do site quando nenhuma pagina interna é acessada
			$this->controller = "LoginController";
			$this->metodo = "index";
			$this->parametros = "";
			
		}

		//verifica autenticação e redireciona pra home se falhar
		if ($this->usuario) {
			//paginas permitidas
			$paginas = ['ContaController', 'LoginController', 'PagamentoController', 'TransferenciaController'];
			if (!isset($this->controller) && !in_array($this->controller, $paginas)) {
				$this->controller = "ContaController";
				$this->metodo = "redirect";
			}
		}else{
			//pagina de redirect quando acessa sem sessão iniciada
			$paginas = ['LoginController'];
			if (!isset($this->controller) || !in_array($this->controller, $paginas)) {
				$this->controller = "LoginController";
				$this->metodo = "check";
			}
			$this->parametros = "tentou acessar sem sessao";

		}
		
		//chama uma view de erro 404 caso nenhuma controller seja encontrada (tratamento de links incorretos)
		if (!class_exists($this->controller) || !method_exists($this->controller,$this->metodo)) {
			$this->controller = "ErroController";
			$this->metodo = "index";
			$this->parametros = "";
		} 
		//chama a controller
		call_user_func_array(array($this->controller, $this->metodo), array($this->parametros));
	}
}