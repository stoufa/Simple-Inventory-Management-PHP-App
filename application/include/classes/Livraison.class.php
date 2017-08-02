<?php
class Livraison {
	//attributs
	private $_id = null;
	private $_idGadget = null;
	private $_quantite = null;
	private $_dateLivraison = null;
	private $_idClient = null;
	private $_message = null;
	public static $NO_LIVRAISON = 1;
	private static $_options = array(
		'id' => 0,
		'id_gadget' => 1,
		'quantite' => 2,
		'date_livraison' => 3,
		'id_client' => 4
	);

	
	//constructeur
	public function Livraison($idGadget, $quantite, $dateLivraison, $idClient) {
		$this->_idGadget = $idGadget;
		$this->_quantite = $quantite;
		$this->_dateLivraison = $dateLivraison;
		$this->_idClient = $idClient; 
	}
	
	//getters
	public function getId() { return $this->_id; }
	public function getIdGadget() { return $this->_idGadget; }
	public function getQuantite() { return $this->_quantite; }
	public function getDateLivraison() { return $this->_dateLivraison; }
	public function getIdClient() { return $this->_idClient; }
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
	public function setDateLivraison($value) { $this->_dateLivraison = $value; }
	public function setIdClient($value) { $this->_idClient = $value; }
	public function setMessage($value) { $this->_message = $value; }
	
	public function estValide() {
		$this->setMessage('');
		$quantite = ($this->getQuantite() > 0);
		$g = Gadget::get($this->_idGadget);
		$quantiteStock = $this->_quantite <= $g->getQuantite();	//la quantité livré doit être inférieur ou égale a la quantité en stock
		//la date de reception doit être aujourd'hui ou avant pas aprés!
		$date = Application::datesValides(Application::buildDate(date("d"), date("m"), date("Y")), $this->getDateLivraison());
		if(!$quantite) {
			$this->_message .= 'quantite doit être > 0<br/>';
		}
		if(!$quantiteStock) {
			$this->_message .= 'quantite livré doit être <= quantité en stock (' . $g->getQuantite() . ')<br/>';
		}
		if(!$date) {
			$this->_message .= 'dateLivraison doit être supérieur ou égale à ' . Application::buildDate(date("d"), date("m"), date("Y")) . '<br/>';
		}
		if(empty($this->_message)) {
			$this->setMessage('Livraison valide');
		}
		return $quantite && $quantiteStock && $date;
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
	
	public static function ajouter(Livraison $l) {
		$cols = self::getCols();
		$vals = array(
			$l->getIdGadget(),
			$l->getQuantite(),
			Application::toSQLdate($l->getDateLivraison()),
			$l->getIdClient()
		);
		DB_Manager::insert(self::getTableName(), $cols, $vals);
    	//mise à jour de la quantité en stock
    	$g = Gadget::get($l->getIdGadget());
    	$g->setQuantite($g->getQuantite() - $l->getQuantite());
    	Gadget::modifier($g);
		//on doit aussi ajouter un mouvement
		$m = new Mouvement('livraison', $l->getDateLivraison());
		$m->setId(self::getLastId());
		Mouvement::ajouter($m);
	}
	
	public static function modifier(Livraison $l) {
		$id = $l->getId();
		$cols = self::getCols();
		$vals = array(
			$l->getIdGadget(),
			$l->getQuantite(),
			Application::toSQLdate($l->getDateLivraison()),
			$l->getIdClient()
		);
		DB_Manager::update(self::getTableName(), $cols, $vals, "id = '$id'");
		//mise à jour de la quantité en stock
		$g = Gadget::get($l->getIdGadget());
    	$g->setQuantite($g->getQuantite() - $l->getQuantite());
    	Gadget::modifier($g);
	}
	
	public static function supprimer(Livraison $l) {
		$id = $g->getId();
		DB_Manager::delete(self::getTableName(), "id = '$id'");
	}
	
	public static function existe(Livraison $l) {
		//la livraison existe s'il existe dans la base
		$idGadget = $l->getIdGadget();
		$quantite = $l->getQuantite();
		$dateLivraison = $l->getDateLivraison();
		$idClient = $l->getIdClient();
		$condition = "id_gadget = '$idGadget' AND quantite = '$quantite' AND date_livraison = '$dateLivraison' AND id_client = '$idClient'";
		$res = DB_Manager::select(self::getTableName(), $condition);
		return ($res != DB_Manager::$NO_RESULTS);
	}
	
	public static function getAll() {
		$rows = DB_Manager::select(self::getTableName(), 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return self::$NO_LIVRAISON; }
		$objs = array();
		foreach ($rows as $row) {
			$obj = new Livraison($row['id_gadget'], $row['quantite'], $row['date_livraison'], $row['id_client']);
			$obj->setId($row['id']);
			$objs[] = $obj;
		}
		return $objs;
	}
	
	public static function get($id) {
		//$row = DB_Manager::select(self::getTableName(), "id = '$id'");
		$row = DB_Manager::getRow(self::getTableName(), $id);
		if($row == DB_Manager::$NOT_A_ROW) { return self::$NO_LIVRAISON; }
		$l = new Livraison($row['id_gadget'], $row['quantite'], Application::toNormalDate($row['date_livraison']), $row['id_client']);
		$l->setId($id);
		return $l;
	}
	
	public static function getCols() {
		return array('id_gadget', 'quantite', 'date_livraison', 'id_client');
	}
	
	public static function getTableName() {
		return 'livraisons';
	}
	
	public static function getLastId() {
		$condition = "id = (SELECT MAX(id) FROM " . self::getTableName() . ")";
		$row = DB_Manager::select(self::getTableName(), $condition);
		return $row[0]['id'];
	}
}