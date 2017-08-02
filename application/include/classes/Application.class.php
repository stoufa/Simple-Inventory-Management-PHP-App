<?php

class Application {
	
	public static $PAGE_ADMIN = '../admin.php';	//la page de l'administrateur
	public static $PAGE_USER = '../autre.php';	//la page des autres utilisateurs
	public static $ERREUR_AJOUT = 1;
	public static $SUCCES_AJOUT = 2;
	public static $ERREUR_MODIFICATION = 3;
	public static $SUCCES_MODIFICATION = 4;
	
	public static function getTables() {
		return array('clients', 'gadgets', 'gammes', 'articles');
	}

	public static function redir($url) {
		//fonction qui fait la redirection du visiteur vers la page passé en paramétre	
		?>
			<script type="text/javascript">
				document.location = '<?php echo $url; ?>';
			</script>
		<?php
	}
	
	public static function alert($msg) {
		//fonction qui affiche un message d'erreur	
		?>
			<script type="text/javascript">
				alert('<?php echo $msg; ?>');
			</script>
		<?php
	}
	
	public static function toNormalDate($date) {
    	$date_tab = explode('-', $date);	//aaaa-mm-jj
    	$a = 0;	//année
    	$m = 1;	//mois
    	$j = 2;	//jour
    	$normal_date = $date_tab[$j] . '/' . $date_tab[$m] . '/' . $date_tab[$a];	//Normal date format
    	return $normal_date;
	}
	
	public static function toSQLdate($date) {
    	$date_tab = explode('/', $date);	//jj/mm/aaaa
		$j = 0;	//jour
		$m = 1;	//mois
    	$a = 2;	//année
    	$sql_date = $date_tab[$a] . '-' . $date_tab[$m] . '-' . $date_tab[$j];	//SQL date format
    	return $sql_date;
	}
	
	public static function Lpp() {
		//récuperer l'utilisateur courant
		$user = Utilisateur::getUtilisateurConnecte();
		//retourner son lpp
		return $user->getLpp();
	}
    
	public static function datesValides($deb, $fin) {
        //les 2 tableaux suivants sont de la forme: jj/mm/aaaa
        $j = 0;
        $m = 1;
        $a = 2;
        $date_deb = explode('/', $deb);
        $date_fin = explode('/', $fin);
        $valide = true;
        if($date_deb[$a] > $date_fin[$a]) { $valide = false; }
        if($date_deb[$m] > $date_fin[$m]) { $valide = false; }
        if($date_deb[$j] > $date_fin[$j]) { $valide = false; }
        return $valide;
    }
    
    public static function buildDate($j, $m, $a) {
    	return "$j/$m/$a";
    }
}