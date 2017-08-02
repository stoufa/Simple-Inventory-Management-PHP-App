<?php
class Client {
	
	//attributs
	private $_id = null;
	private $_nom = null;
	private $_message = null;
	public static $NO_CLIENT = 1;
	private static $_options = array(
		'id' => 0,
		'nom' => 1
	);
	
	//constructeur
	public function Client($nom) {
		$this->_nom = $nom;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getNom() { return $this->_nom; }
	public function getMessage() {
		//pour que le message s'affiche une seule fois on le réinitialise 
		$str = $this->_message;
		$this->_message = '';
		return $str;
	}
	
	//setters
	public function setId($value) { $this->_id = $value; }
	public function setNom($value) { $this->_nom = $value; }
public function setMessage($value) { $this->_message = $value; }
	
	public function estValide() {
		$this->setMessage('');
		$taille = strlen($this->_nom);
		$nom = ($taille > 0) && ($taille <= 100);
		if(!$nom) {
			if(!$taille) {
				$this->_message .= '<br/>nom ne doit pas être vide!';
			}
			if($taille > 100) {
				$this->_message .= '<br/>nom ne doit pas dépasser 100 caractéres!';
			}
		}
		if(empty($this->_message)) {
			$this->setMessage('Client valide');
		}
		return $nom;
		//le nom doit avoir 100 caractéres au max
	}
	
	public static function nb() {
		return DB_Manager::getNbRows(self::getTableName());
	}
	
	public static function pasDelements() {
		return !self::nb();
	}

	public static function loadOptions() {
		return self::$_options;
	}
	
	public static function ajouter(Client $c) {
		$cols = self::getCols();
		$vals = array(
			$c->getNom()
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
	}
	
	public static function modifier(Client $c) {
		$id = $c->getId();
		$cols = self::getCols();
		$vals = array(
			$c->getNom()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
	}
	
	public static function supprimer(Client $c) {
		$id = $c->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Client $c) {
		//le client existe s'il existe dans la base
		$nom = $c->getNom();
		$condition = "nom = '$nom'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_CLIENT; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Client($row['nom']);
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_CLIENT; }
		$c = new Client($row['nom']);
		$c->setId($id);
		return $c;
	}
	
	public static function getCols() {
		return array('nom');
	}
	
	public static function getTableName() {
		return 'clients';
	}

}