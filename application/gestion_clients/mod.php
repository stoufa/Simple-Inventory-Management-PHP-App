<?php
	require_once '../include/include.php';
	$nom = $_POST['nom'];
	$_SESSION['ClientNom'] = $nom;
	$c = new Client($nom);
	$c->setId($_GET['id']);
	if(!$c->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_MODIFICATION;
	} else {
		$_SESSION['status'] = Application::$SUCCES_MODIFICATION;
		Client::modifier($c);
		$_SESSION['ClientNom'] = '';
	}
	$_SESSION['message'] = $c->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);
	//Application::redir('afficher_gamme.php?n=1');