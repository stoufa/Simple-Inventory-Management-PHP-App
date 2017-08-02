<?php
	require_once '../include/include.php';
	$table = Client::getTableName();
	if (!Utilisateur::utilisateurConnecte()) ://si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
	if(Client::pasDelements()):
		//test pour vérifier qu'il y a des gammes car un article doit être associé à une gamme
		Application::alert("aucun client trouvé!");
		Application::redir('index.php');
	endif;
	if(isset($_GET['id'])):
		//test s'il ya un paramétre passé à cette page et si ce dernier est valide ou pas
		$cleId = intval($_GET['id']);
		if(is_int($cleId)):
			$c = Client::get($cleId);
			if(!Client::existe($c)):
				Application::alert("ERREUR: CLIENT INTROUVABLE!");
				Application::redir('afficher_client.php?n=1');
			else:
				//id valide
				Client::supprimer($c);
				Application::redir('afficher_client.php?n=1');
			endif;
		else:
			Application::alert("ERREUR: LA CLE DOIT ÊTRE NUMERIQUE!");
			Application::redir('afficher_client.php?n=1');
		endif;
	else:
		Application::alert("ERREUR: PARAMETRE INTROUVABLE!");
		Application::redir('afficher_client.php?n=1');
	endif;
?>