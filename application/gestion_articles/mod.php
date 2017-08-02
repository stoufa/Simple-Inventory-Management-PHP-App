<?php
	require_once '../include/include.php';
	$nom = $_POST['nom'];
	$idGamme = $_POST['id_gamme'];
	$_SESSION['ArticleNom'] = $nom;
	$_SESSION['ArticleIdGamme'] = $idGamme;
	$a = new Article($idGamme, $nom);
	$a->setId($_GET['id']);
	if(!$a->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_MODIFICATION;
	} else {
		$_SESSION['status'] = Application::$SUCCES_MODIFICATION;
		Article::modifier($a);
		$_SESSION['ArticleNom'] = '';
		$_SESSION['ArticleIdGamme'] = '';
	}
	$_SESSION['message'] = $a->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);
	//Application::redir('afficher_gamme.php?n=1');