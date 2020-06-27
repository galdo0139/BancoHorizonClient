<?php 

/* classe que seta a conexão com o banco de dados */
abstract class dbConn{
	/*dados do banco que desejamos nos conectar*/
	private $server = "localhost";
	private $banco = "gigaBank";
	private $user = "root";
	private $senha = "";

	private $db;


	public static function __construct(){  
	
	}

	//
	public static function getDB(){
		if(self::$db){
			self::$db = new PDO("mysql: host=$server, dbname=$banco, $user, $senha");
		}

		return self::$db;
	}

	function encerrarDB(){
		//return $this->db;
	}
}
