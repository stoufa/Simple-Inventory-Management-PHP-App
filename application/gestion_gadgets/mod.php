<?php
	require_once '../include/include.php';
	$nom = $_POST['nom'];
	$idArticle = $_POST['id_article'];
	$idGamme = $_POST['id_gamme'];
	//$quantite = $_POST['quantite'];
	$designation = $_POST['designation'];
	$_SESSION['GadgetNom'] = $nom;
	$_SESSION['GadgetIdArticle'] = $idArticle;
	$_SESSION['GadgetIdGamme'] = $idGamme;
	//$_SESSION['GadgetQuantite'] = $quantite;
	$_SESSION['GadgetDesignation'] = $designation;
	$g = new Gadget($idArticle, $idGamme, $nom, $designation, Gadget::get($_GET['id'])->getQuantite());	//ancienne quantité
	$g->setId($_GET['id']);
	if(!$g->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_MODIFICATION;
	} else {
		$_SESSION['status'] = Application::$SUCCES_MODIFICATION;
		Gadget::modifier($g);
		$_SESSION['GadgetNom'] = '';
		$_SESSION['GadgetIdArticle'] = '';
		$_SESSION['GadgetIdGamme'] = '';
		//$_SESSION['GadgetQuantite'] = '';
		$_SESSION['GadgetDesignation'] = '';
	}
	$_SESSION['message'] = $g->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);
	//Application::redir('afficher_gamme.php?n=1');