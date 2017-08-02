<?php
/**
 * 
 * Cette classe a un seul but retourner les enregistrements suite à une requette
 * la construction de l'objet doit être faite dans les autres classes
 * @author Stoufa
 *
 */
class DB_Manager {
	
	//attributs
	private $_db = null;
	private $_sh = null;
	public static $NO_ROWS = 1;
	public static $NOT_SELECT = 2;	//retourné si la requettre est du type autre que select
	public static $NOT_A_ROW = 3;	//retourné si la ligne de volue de la base est introuvable
	public static $NO_RESULTS = 4;	//retourné si la requêtte ne retourne aucune ligne
	
	//constructeur
	public function DB_Manager() {	
		try {
			$this->_db = new PDO('mysql:host=localhost;dbname=adwya', 'root', '');
		} catch(PDOException $e) {
			echo $e->getMessage();
			die;
		}
	}
	
	/* ****************************** CRUD ******************************************* */
	public static function insert($tableName, array $cols, array $vals) {	
		$colsStr = implode(', ', $cols);
		$valsStr = '';
		for ($i = 0; $i < count($cols); $i++) {
			if($i != 0) {
				$valsStr .= ', ';
			}
			$valsStr .= "'{$vals[$i]}'";
		}
		$sql = "INSERT INTO `$tableName` ($colsStr) VALUES ($valsStr)";
		self::query($sql, false);
	}
	/**
	 * @internal
	 * returns DB_Manager::$NO_RESULTS if no results found
	 * or one record if there is only one record to return
	 * else it returns an array of rows
	 * @param string $tableName
	 * @param string $condition
	 */
	public static function select($tableName, $condition) {	
		$sql = "SELECT * FROM `$tableName` WHERE ($condition)";
		$res = self::query($sql);
		if(!count($res)) { return self::$NO_RESULTS; }
		return $res;
	}
	
	public static function update($tableName, array $cols, array $vals, $condition) {	
		//Remarque:	les noms de collones ne doivent pas être entourrés d'apostrophes
		$affStr = "";
		for ($i = 0; $i < count($cols); $i++) {
			if($i != 0) {
				$affStr .= ', ';
			}
			$affStr .= "{$cols[$i]}='{$vals[$i]}'";
		}
		$sql = "UPDATE `$tableName` SET $affStr WHERE ($condition)";
		self::query($sql, false);
	}
	
	public static function delete($tableName, $condition) {	
		$sql = "DELETE FROM `$tableName` WHERE ($condition)";
		self::query($sql, false);
	}
	/* ******************************************************************************* */

	public static function query($sql, $estSelect = true) {	
		$db = new DB_Manager();
		return $db->queryDB($sql, $estSelect);
	}
	
	private function queryDB($sql, $estSelect = true) {	
		//par defaut la requette retourne une valeur
		$this->_sh = $this->_db->query($sql);
		return ($estSelect)? $this->_sh->fetchAll(): self::$NOT_SELECT;
	}
	
	public static function getRow($table, $id) {	
		$res = self::select($table, "id = '$id'");
		return ($res != self::$NO_RESULTS)? $res[0]: self::$NOT_A_ROW;
	}
	
	public static function getAllRows($table) {
		$res = self::select($table, 'TRUE');
		return $res;
	}
	
	public static function getNbRows($table) {
		$rows = self::select($table, 'TRUE');
		if($rows == DB_Manager::$NO_RESULTS) { return 0; }
		return count($rows);
	}
}