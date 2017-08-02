<?php
	require_once '../include/include.php';
	$nom = $_POST['nom'];
	$_SESSION['ClientNom'] = $nom;
	$c = new Client($nom);
	if(!$c->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_AJOUT;
	} else {
		$_SESSION['status'] = Application::$SUCCES_AJOUT;
		Client::ajouter($c);
		$_SESSION['ClientNom'] = '';
	}
	$_SESSION['message'] = $c->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);