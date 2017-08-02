<?php
	require_once '../include/include.php';
	$nom = $_POST['nom'];
	$nomCourt = $_POST['nom_court'];
	$_SESSION['GammeNom'] = $nom;
	$_SESSION['GammeNomCourt'] = $nomCourt;
	$g = new Gamme($nom, $nomCourt);
	if(!$g->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_AJOUT;
	} else {
		$_SESSION['status'] = Application::$SUCCES_AJOUT;
		Gamme::ajouter($g);
		$_SESSION['GammeNom'] = '';
		$_SESSION['GammeNomCourt'] = '';
	}
	$_SESSION['message'] = $g->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);