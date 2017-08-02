<?php
class Article {
	
	//attributs
	private $_id = null;
	private $_idGamme = null;
	private $_nom = null;
	private $_message = null;
	public static $NO_ARTICLE = 1;
	private static $_options = array(
		'id' => 0,
		'id_gamme' => 1,
		'nom' => 2
	);
	
	//constructeur
	public function Article($idGamme, $nom) {
		$this->_idGamme = $idGamme;
		$this->_nom = $nom;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getIdGamme() { return $this->_idGamme; }
	public function getNom() { return $this->_nom; }
	public function getMessage() {
		//pour que le message s'affiche une seule fois on le réinitialise 
		$str = $this->_message;
		$this->_message = '';
		return $str;
	}
	
	//setters
	public function setId($value) { $this->_id = $value; }
	public function setIdGamme($value) { $this->_idGamme = $value; }
	public function setNom($value) { $this->_nom = $value; }
	public function setMessage($value) { $this->_message = $value; }
	
	public function estValide() {
		$this->setMessage('');
		$taille = strlen($this->_nom);
		$nom = ($taille > 0) && ($taille <= 50);
		if(!$nom) {
			if(!$taille) {
				$this->_message .= '<br/>nom ne doit pas être vide!';
			}
			if($taille > 50) {
				$this->_message .= '<br/>nom ne doit pas dépasser 50 caractéres!';
			}
		}
		if(empty($this->_message)) {
			$this->setMessage('Article valide');
		}
		return $nom;
		//le nom doit avoir 50 caractéres au max
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
	
	public static function ajouter(Article $a) {
		$cols = self::getCols();
		$vals = array(
			$a->getIdGamme(),
			$a->getNom()
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
	}
	
	public static function modifier(Article $a) {
		$id = $a->getId();
		$cols = self::getCols();
		$vals = array(
			$a->getIdGamme(),
			$a->getNom()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
	}
	
	public static function supprimer(Article $a) {
		$id = $a->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Article $a) {
		//l'article existe s'il existe dans la base
		$idGamme = $a->getIdGamme();
		$nom = $a->getNom();
		$condition = "id_gamme = '$idGamme' AND nom = '$nom'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_ARTICLE; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Article($row['id_gamme'], $row['nom']);
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_ARTICLE; }
		$a = new Article($row['id_gamme'], $row['nom']);
		$a->setId($id);
		return $a;
	}
	
	public static function getCols() {
		return array('id_gamme', 'nom');
	}
	
	public static function getTableName() {
		return 'articles';
	}
}