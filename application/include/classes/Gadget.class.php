<?php
class Gadget {

	//attributs
	private $_id = null;
	private $_idArticle = null;
	private $_idGamme = null;
	private $_nom = null;
	private $_designation = null;
	private $_quantite = null;
	private $_message = null;
	public static $NO_GADGET = 1;
	private static $_options = array(
		'id' => 0,
		'id_article' => 1,
		'id_gamme' => 2,
		'nom' => 3,
		'designation' => 4,
		'quantite' => 5
	);
	
	//constructeur
	public function Gadget($idArticle, $idGamme, $nom, $designation, $quantite) {
		$this->_idArticle = $idArticle;
		$this->_idGamme = $idGamme;
		$this->_nom = $nom;
		$this->_designation = $designation;
		$this->_quantite = $quantite;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getIdArticle() { return $this->_idArticle; }
	public function getIdGamme() { return $this->_idGamme; }
	public function getNom() { return $this->_nom; }
	public function getDesignation() { return $this->_designation; }
	public function getQuantite() { return $this->_quantite; }
	public function getMessage() {
		//pour que le message s'affiche une seule fois on le réinitialise 
		$str = $this->_message;
		$this->_message = '';
		return $str;
	}
	
	//setters
	public function setId($value) { $this->_id = $value; }
	public function setIdArticle($value) { $this->_idArticle = $value; }
	public function setIdGamme($value) { $this->_idGamme = $value; }
	public function setNom($value) { $this->_nom = $value; }
	public function setDesignation($value) { $this->_designation = $value; }
	public function setQuantite($value) { $this->_quantite = $value; }
	public function setMessage($value) { $this->_message = $value; }
	
	public function estValide() {
		$this->setMessage('');
		$lenNom = strlen($this->getNom());
		$nom = ($lenNom > 0) && ($lenNom <= 100);
		if(!$nom) {
			if(!$lenNom) {
				$this->_message .= '<br/>nom ne doit pas être vide!';
			}
			if($lenNom > 100) {
				$this->_message .= '<br/>nom ne doit pas dépasser 100 caractéres!';
			}
		}
		$lenDesignation = strlen($this->getDesignation());
		$designation = ($lenDesignation > 0) && ($lenDesignation <= 100);
		if(!$designation) {
			if(!$lenDesignation) {
				$this->_message .= '<br/>designation ne doit pas être vide!';
			}
			if($lenDesignation > 100) {
				$this->_message .= '<br/>designation ne doit pas dépasser 100 caractéres!';
			}
		}
		$quantite = $this->getQuantite() >= 0;
		if(!$quantite) {
			$this->_message .= '<br/>quantité doit être positive!';
		}
		if(empty($this->_message)) {
			$this->setMessage('Gadget valide');
		}
		return $nom && $designation && $quantite;
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
	
	public static function ajouter(Gadget $g) {
		$cols = self::getCols();
		$vals = array(
			$g->getIdArticle(),
			$g->getIdGamme(),
			$g->getNom(),
			$g->getDesignation(),
			$g->getQuantite()
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
	}
	
	public static function modifier(Gadget $g) {
		$id = $g->getId();
		$cols = self::getCols();
		$vals = array(
			$g->getIdArticle(),
			$g->getIdGamme(),
			$g->getNom(),
			$g->getDesignation(),
			$g->getQuantite()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
	}
	
	public static function supprimer(Gadget $g) {
		$id = $g->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Gadget $g) {
		//le gadget existe s'il existe dans la base
		//i.e: son (id_article, id_gamme, nom) existe
		$idArticle = $g->getIdArticle();
		$idGamme = $g->getIdGamme();
		$nom = $g->getNom();
		$condition = "id_article = '$idArticle' AND id_gamme = '$idGamme' AND nom = '$nom'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_GADGET; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Gadget($row['id_article'], $row['id_gamme'], $row['nom'], $row['designation'], $row['quantite']);
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_GADGET; }
		$g = new Gadget($row['id_article'], $row['id_gamme'], $row['nom'], $row['designation'], $row['quantite']);
		$g->setId($id);
		return $g;
	}
	
	public static function getCols() {
		return array('id_article', 'id_gamme', 'nom', 'designation', 'quantite');
	}
	
	public static function getTableName() {
		return 'gadgets';
	}

	public static function getSommeReception($id) {
		$condition = "id_gadget = '$id'";
		$receptions = DB_Manager::select(Reception::getTableName(), $condition);
		if($receptions == DB_Manager::$NO_RESULTS) { return 0; }	//aucune réception
		$somme = 0;
		foreach ($receptions as $reception) {
			$somme += $reception['quantite'];
		}
		return $somme;
	}

	public static function getSommeLivraison($id) {
		$condition = "id_gadget = '$id'";
		$livraisons = DB_Manager::select(Livraison::getTableName(), $condition);
		if($livraisons == DB_Manager::$NO_RESULTS) { return 0; }	//aucune livraison
		$somme = 0;
		foreach ($livraisons as $livraison) {
			$somme += $livraison['quantite'];
		}
		return $somme;
	}
}