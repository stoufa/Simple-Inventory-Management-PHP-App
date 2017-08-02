<?php
class Mouvement {
	//attributs
	private $_id = null;
	private $_type = null;
	private $_date = null;
	public static $NO_MOUVEMENT = 1;
	private static $_options = array(
		'id' => 0,
		'type' => 1,
		'date' => 2
	);

	//constructeur
	public function Mouvement($type, $date) {
		$this->_type = $type;
		$this->_date = $date;
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getType() { return $this->_type; }
	public function getDate() { return $this->_date; }

	//setters
	public function setId($value) { $this->_id = $value; }
	public function setType($value) { $this->_type = $value; }
	public function setDate($value) { $this->_date = $value; }
	
	public static function nb() {
		return DB_Manager::getNbRows(self::getTableName());
	}
	
	public static function pasDelements() {
		return !self::nb();
	}
	
	public static function pasDeMouvements($deb, $fin) {
		$sql_deb = Application::toSQLdate($deb);
		$sql_fin = Application::toSQLdate($fin);
		$condition = "date >= '$sql_deb' AND date <= '$sql_fin'";
		$rows = DB_Manager::select(self::getTableName(), $condition);
		return ($rows == DB_Manager::$NO_RESULTS);
	}
	
	public static function loadOptions() {
		return self::$_options;
	}
	
	public static function ajouter(Mouvement $m) {
		$cols = self::getCols();
		$vals = array(
			$m->getId(),
			$m->getType(),
			Application::toSQLdate($m->getDate())
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
	}
	
	public static function modifier(Mouvement $m) {
		$id = $m->getId();
		$cols = self::getCols();
		$vals = array(
			$m->getType(),
			$m->getDate()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
	}
	
	public static function supprimer(Mouvement $m) {
		//on vérifie le type du mouvement
		$typeMouvement = $m->getType();
		//si c'est une livraison on augmente la quantité en stock du gadget conserné et l'inverse pour une réception
		if($typeMouvement == 'livraison') {
			$l = Livraison::get($m->getId());
			$g = Gadget::get($l->getIdGadget());
			$g->setQuantite($g->getQuantite() + $l->getQuantite());
			Gadget::modifier($g);
		} else {
			$r = Reception::get($this->getId());
			$g = Gadget::get($r->getIdGadget());
			$g->setQuantite($g->getQuantite() - $r->getQuantite());
			Gadget::modifier($g);
		}
		$id = $m->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Mouvement $m) {
		//le mouvement existe s'il existe dans la base
		$type = $m->getType();
		$date = $m->getDate();
		$condition = "type = '$type' AND date = '$date'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_MOUVEMENT; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Mouvement($row['type'], Application::toNormalDate($row['date']));
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_MOUVEMENT; }
		$m = new Mouvement($row['type'], Application::toNormalDate($row['date']));
		$m->setId($id);
		return $m;
	}
	
	public static function getMouvements($deb, $fin) {
		$sql_deb = Application::toSQLdate($deb);
		$sql_fin = Application::toSQLdate($fin);
		$condition = "date >= '$sql_deb' AND date <= '$sql_fin'";
		$rows = DB_Manager::select(self::getTableName(), $condition);
		return $rows;
	}
	
	public static function getCols() {
		return array('id', 'type', 'date');
	}
	
	public static function getTableName() {
		return 'mouvements';
	}
}