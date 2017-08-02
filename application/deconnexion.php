<?php
	require_once 'include/include.php';
	//si l'utilisateur n'est pas connecte, la deconnexion n'a aucun effet!
	if(Utilisateur::utilisateurConnecte()) {
		session_unset();
	}
	Application::redir('login/');
?>