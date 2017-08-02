<?php
	require_once '../include/include.php';
    $dateReception = $_POST['datefield'];
    $idGadget = $_POST['gadget'];
    $quantite = $_POST['qte'];
	$_SESSION['ReceptionDateReception'] = $dateReception;
	$_SESSION['ReceptionIdGadget'] = $idGadget;
	$_SESSION['ReceptionQuantite'] = $quantite;
	$r = new Reception($idGadget, $quantite, $dateReception);
	if(!$r->estValide()) {
		$_SESSION['status'] = Application::$ERREUR_AJOUT;
	} else {
		$_SESSION['status'] = Application::$SUCCES_AJOUT;
		Reception::ajouter($r);
		$_SESSION['ReceptionDateReception'] = '';
		$_SESSION['ReceptionIdGadget'] = '';
		$_SESSION['ReceptionQuantite'] = '';
	}
	$_SESSION['message'] = $r->getMessage();
	Application::redir($_SERVER['HTTP_REFERER']);