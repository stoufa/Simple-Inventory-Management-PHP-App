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
	$g = new Gadget($idArticle, $idGamme, $nom, $designation, 0);	//la quantité du gadget est par défaut 0, et est modifiée a travers les mouvements
	if(!$g->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_AJOUT;
	} else {
		$_SESSION['status'] = Application::$SUCCES_AJOUT;
		Gadget::ajouter($g);
		$_SESSION['GadgetNom'] = '';
		$_SESSION['GadgetIdArticle'] = '';
		$_SESSION['GadgetIdGamme'] = '';
		$_SESSION['GadgetQuantite'] = '';
		$_SESSION['GadgetDesignation'] = '';
	}
	$_SESSION['message'] = $g->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);