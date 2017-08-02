<?php
	require_once '../include/include.php';
	$mouvement = Mouvement::get($_GET['id']);
	if($mouvement->getType() == 'livraison') {
	    $dateLivraison = $_POST['datefield'];
	    $idGadget = $_POST['gadget'];
	    $quantite = $_POST['qte'];
	    $idClient = $_POST['client'];
	    $l = new Livraison($idGadget, $quantite, $dateLivraison, $idClient);
	    $l->setId($mouvement->getId());
	    
	    //ancienne valeur de la quantité en stock du gadget
	    $ancienneLivraison = Livraison::get($mouvement->getId());
	    $g = Gadget::get($ancienneLivraison->getIdGadget());
	    $qteStockGadget = $g->getQuantite() + $ancienneLivraison->getQuantite();
	    $valide = $l->getQuantite() <= $qteStockGadget;	//valide ssi la nouvelle qte à livrer est <= qteStockRéel du gadget
	    
		if(!$valide) {
			$_SESSION['status'] = Application::$ERREUR_AJOUT;
	    } else {
			$_SESSION['status'] = Application::$SUCCES_AJOUT;
			//mise a jour de la quantité en stock
			$g->setQuantite($qteStockGadget);	//réinitialisation de la qte du gadget
			Gadget::modifier($g);	//la quantité du gadget est comme si on n'a pas fait de livraison
			Livraison::modifier($l);
	    }
		$_SESSION['message'] = $l->getMessage();
		Application::redir($_SERVER['HTTP_REFERER']);
	} else {
	    $dateReception = $_POST['datefield'];
	    $idGadget = $_POST['gadget'];
	    $quantite = $_POST['qte'];
		$r = new Reception($idGadget, $quantite, $dateReception);
		$r->setId($mouvement->getId());
		
		//ancienne valeur de la quantité en stock du gadget
	    $ancienneReception = Reception::get($mouvement->getId());
	    $g = Gadget::get($ancienneReception->getIdGadget());
	    $qteStockGadget = $g->getQuantite() - $ancienneReception->getQuantite();
	    
	    $sommeReception = Gadget::getSommeReception($g->getId());
	    $sommeLivraison = Gadget::getSommeLivraison($g->getId());
	    
	    $sommeReception = $sommeReception - $ancienneReception->getQuantite() + $quantite;
	    
	    $valide = $sommeReception >= $sommeLivraison;	//la reception est valide ssi la somme des receptions est >= la somme des livraisons
	    
		if(!$valide) {
			$_SESSION['status'] = Application::$ERREUR_AJOUT;
			$_SESSION['message'] = $r->getMessage() . 'Remarque: il faut que la somme des receptions du gadget soit >= a la somme des livraisons!';
		} else {
			$_SESSION['status'] = Application::$SUCCES_AJOUT;
			//mise a jour de la quantité en stock
			$g->setQuantite($qteStockGadget);
			Gadget::modifier($g);	//la quantité du gadget est comme si on n'a pas fait de livraison
			Reception::modifier($r);
			$_SESSION['message'] = $r->getMessage();
		}
		Application::redir($_SERVER['HTTP_REFERER']);
	}