<?php
class Reception {
	//attributs
	private $_id = null;
	private $_idGadget = null;
	private $_quantite = null;
	private $_dateReception = null;
	private $_message = null;
	public static $NO_RECEPTION = 1;
	private static $_options = array(
		'id' => 0,
		'id_gadget' => 1,
		'quantite' => 2,
		'date_reception' => 3
	);

	//constructeur
	public function Reception($idGadget, $quantite, $dateReception) {
		$this->_idGadget = $idGadget;
		$this->_quantite = $quantite;
		$this->_dateReception = $dateReception;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getIdGadget() { return $this->_idGadget; }
	public function getQuantite() { return $this->_quantite; }
	public function getDateReception() { return $this->_dateReception; }
	public function getMessage() {
		//pour que le message s'affiche une seule fois on le réinitialise 
		$str = $this->_message;
		$this->_message = '';
		return $str;
	}

	//setters
	public function setId($value) { $this->_id = $value; }
	public function setIdGadget($value) { $this->_idGadget = $value; }
	public function setQuantite($value) { $this->_quantite = $value; }
	public function setDateReception($value) { $this->_dateReception = $value; }
	public function setMessage($value) { $this->_message = $value; }
	
	public function estValide() {
		$this->setMessage('');
		$quantite = ($this->getQuantite() > 0);
		//la date de reception doit être aujourd'hui ou avant pas aprés!
		$date = Application::datesValides($this->getDateReception(), Application::buildDate(date("d"), date("m"), date("Y")));
		if(!$quantite) {
			$this->_message .= 'quantite doit être > 0<br/>';
		}
		if(!$date) {
			$this->_message .= 'dateReception doit être inférieur ou égale à ' . Application::buildDate(date("d"), date("m"), date("Y")) . '<br/>';
		}
		if(empty($this->_message)) {
			$this->setMessage('Reception valide');
		}
		return $quantite && $date;
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
	
	public static function ajouter(Reception $r) {
		$cols = self::getCols();
		$vals = array(
			$r->getIdGadget(),
			$r->getQuantite(),
			Application::toSQLdate($r->getDateReception())
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
		//en plus on doit mettre à jour la quantité en stock du gadget
		$g = Gadget::get($r->getIdGadget());
		$g->setQuantite($g->getQuantite() + $r->getQuantite());
		Gadget::modifier($g);
		//on doit aussi ajouter un mouvement
		$m = new Mouvement('reception', $r->getDateReception());
		$m->setId(self::getLastId());
		Mouvement::ajouter($m);
	}
	
	public static function modifier(Reception $r) {
		$id = $r->getId();
		$cols = self::getCols();
		$vals = array(
			$r->getIdGadget(),
			$r->getQuantite(),
			Application::toSQLdate($r->getDateReception())
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
		//mise à jour de la quantité en stock
		$g = Gadget::get($r->getIdGadget());
    	$g->setQuantite($g->getQuantite() + $r->getQuantite());
    	Gadget::modifier($g);
	}
	
	public static function supprimer(Reception $r) {
		$id = $r->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Reception $r) {
		//la reception existe s'il existe dans la base
		$idGadget = $r->getIdGadget();
		$quantite = $r->getQuantite();
		$dateReception = $r->getDateReception();
		$condition = "id_gadget = '$idGadget' AND quantite = '$quantite' AND date_reception = '$dateReception'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_RECEPTION; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Reception($row['id_gadget'], $row['quantite'], Application::toNormalDate($row['date_livraison']));
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_RECEPTION; }
		$r = new Reception($row['id_gadget'], $row['quantite'], Application::toNormalDate($row['date_reception']));
		$r->setId($id);
		return $r;
	}
	
	public static function getCols() {
		return array('id_gadget', 'quantite', 'date_reception');
	}
	
	public static function getTableName() {
		return 'receptions';
	}
	
	public static function getLastId() {
		$condition = "id = (SELECT MAX(id) FROM " . self::getTableName() . ")";
		$row = DB_Manager::select(self::getTableName(), $condition);
		return $row[0]['id'];
	}
}