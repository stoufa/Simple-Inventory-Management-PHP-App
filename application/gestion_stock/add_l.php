<?php
	require_once '../include/include.php';
    //Création d'un object Livraison
    $dateLivraison = $_POST['datefield'];
    $idGadget = $_POST['gadget'];
    $quantite = $_POST['qte'];
    $idClient = $_POST['client'];
    $_SESSION['LivraisonDateLivraison'] = $dateLivraison;
    $_SESSION['LivraisonIdGadget'] = $idGadget;
    $_SESSION['LivraisonQuantite'] = $quantite;
    $_SESSION['LivraisonIdClient'] = $idClient;
    $l = new Livraison($idGadget, $quantite, $dateLivraison, $idClient);
	if(!$l->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_AJOUT;
    } else {
		$_SESSION['status'] = Application::$SUCCES_AJOUT;
		Livraison::ajouter($l);
    	$_SESSION['LivraisonDateLivraison'] = '';
    	$_SESSION['LivraisonIdGadget'] = '';
    	$_SESSION['LivraisonQuantite'] = '';
    	$_SESSION['LivraisonIdClient'] = '';
    }
	$_SESSION['message'] = $l->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);