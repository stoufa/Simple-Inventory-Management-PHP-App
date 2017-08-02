<?php
session_start();
class Utilisateur {
	//attributs
	private $_id = null;
	private $_nom = null;
	private $_prenom = null;
	private $_login = null;
	private $_pw = null;
	private $_type = null;
	private $_lpp = null;
	public static $NO_USER = 1;	//retourné si aucun utilisateur est connécté
	private static $_options = array(
		'id' => 0,
		'nom' => 1,
		'prenom' => 2,
		'login' => 3,
		'pw' => 4,
		'type' => 5
	);

	//constructeur
	public function Utilisateur($nom, $prenom, $login, $pw, $type, $lpp) {
		$this->_nom = $nom;
		$this->_prenom = $prenom;
		$this->_login = $login;
		$this->_pw = $pw;
		$this->_type = $type;
		$this->_lpp = $lpp;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getNom() { return $this->_nom; }
	public function getPrenom() { return $this->_prenom; }
	public function getLogin() { return $this->_login; }
	public function getPw() { return $this->_pw; }
	public function getType() { return $this->_type; }
	public function getLpp() { return $this->_lpp; }

	//setters
	public function setId($value) { $this->_id = $value; }
	public function setNom($value) { $this->_nom = $value; }
	public function setPrenom($value) { $this->_prenom = $value; }
	public function setLogin($value) { $this->_login = $value; }
	public function setPw($value) { $this->_pw = $value; }
	public function setType($value) { $this->_type = $value; }
	public function setLpp($value) { $this->_lpp = $value; }
	
	public static function nb() {
		return DB_Manager::getNbRows(self::getTableName());
	}
	
	public static function pasDelements() {
		return !self::nb();
	}

	public static function loadOptions() {
		return self::$_options;
	}
	
	public static function ajouter(Utilisateur $u) {
		$cols = self::getCols();
		$vals = array(
			$u->getNom(),
			$u->getPrenom(),
			$u->getLogin(),
			$u->getPw(),
			$u->getType(),
			$u->getLpp()
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
	}
	
	public static function modifier(Utilisateur $u) {
		$id = $u->getId();
		$cols = self::getCols();
		$vals = array(
			$u->getNom(),
			$u->getPrenom(),
			$u->getLogin(),
			$u->getPw(),
			$u->getType(),
			$u->getLpp()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
	}
	
	public static function supprimer(Utilisateur $u) {
		$id = $u->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Utilisateur $u) {
		//l'utilisateur existe s'il existe dans la base
		//si son nom et son prénom et son login existent
		$nom = $u->getNom();
		$prenom = $u->getPrenom();
		$login = $u->getLogin();
		$condition = "nom = '$nom' AND prenom = '$prenom' AND login = '$login'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_USER; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Utilisateur($row['nom'], $row['prenom'], $row['login'], $row['pw'], $row['type'], $row['lpp']);
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_USER; }
		$u = new Utilisateur($row['nom'], $row['prenom'], $row['login'], $row['pw'], $row['type'], $row['lpp']);
		$u->setId($u);
		return $u;
	}
	
	public static function getCols() {
		return array('nom', 'prenom', 'login', 'pw', 'type');
	}
	
	public static function getTableName() {
		return 'utilisateurs';
	}
	
	public static function getUtilisateurConnecte() {
		if(self::utilisateurConnecte()) {
			$id = $_SESSION['id'];
			$nom = $_SESSION['nom'];
			$prenom = $_SESSION['prenom'];
			$login = $_SESSION['login'];
			$pw = $_SESSION['pw'];
			$type = $_SESSION['type'];
			$lpp = $_SESSION['lpp'];
			$u = new Utilisateur($nom, $prenom, $login, $pw, $type, $lpp);
			$u->setId($id);
			return $u;
		} else {
			return self::$NO_USER;
		}
	}
	
	/**
	 * cette fonction vérifie si l'utilisateur est connecté ou pas
	 * (si l'utilisateur est déja connecté pas besoin de reconnecter)
	 */
	public static function utilisateurConnecte() {
		$id = isset($_SESSION['id']) && !empty($_SESSION['id']);
		$nom = isset($_SESSION['nom']) && !empty($_SESSION['nom']);
		$prenom = isset($_SESSION['prenom']) && !empty($_SESSION['prenom']);
		$login = isset($_SESSION['login']) && !empty($_SESSION['login']);
		$pw = isset($_SESSION['pw']) && !empty($_SESSION['pw']);
		$type = isset($_SESSION['type']) && !empty($_SESSION['type']);
		$lpp = isset($_SESSION['lpp']) && !empty($_SESSION['lpp']);
		return $id && $nom && $prenom && $login && $pw && $type && $lpp;
	}
	
	public function estAdmin() {
		return ($this->getType() == 'admin');
	}
	
	public static function peutConnecter(Utilisateur $u) {
		$login = $u->getLogin();
		$pw = $u->getPw();
		$condition = "login = '$login' AND pw = '$pw'";
		return (DB_Manager::select(self::getTableName(), $condition) != DB_Manager::$NO_RESULTS);
	}
	
	public static function connecter(Utilisateur $u) {
		$login = $u->getLogin();
		$pw = $u->getPw();
		$condition = "login = '$login' AND pw = '$pw'";
		$row = DB_Manager::select(self::getTableName(), $condition);
		$_SESSION['id'] = $row[0]['id'];
		$_SESSION['nom'] = $row[0]['nom'];
		$_SESSION['prenom'] = $row[0]['prenom'];
		$_SESSION['login'] = $row[0]['login'];
		$_SESSION['pw'] = $row[0]['pw'];
		$_SESSION['type'] = $row[0]['type'];
		$_SESSION['lpp'] = $row[0]['lpp'];
		$u->setId($row[0]['id']);
		$u->setNom($row[0]['nom']);
		$u->setPrenom($row[0]['prenom']);
		$u->setType($row[0]['type']);
		$u->setLpp($row[0]['lpp']);
		return $u;
	}
}