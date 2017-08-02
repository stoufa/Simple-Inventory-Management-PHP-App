<?php
	require_once 'include.php';
	$tables = Application::getTables();
	$verif = isset($_GET['table']) && in_array($_GET['table'], $tables);
	if(!$verif):
		print('table invalide!');
		die;
	endif;
	$table = $_GET['table'];
	$nom_fichier = $table . '.xml';
	$fichier = fopen($nom_fichier, 'w');	//ouverture du fichier
	fputs($fichier, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");	//en-tête XML
	fputs($fichier, "<!DOCTYPE xml>\n");
	fputs($fichier, "<$table>\n");	//container_start
	$sql = "SELECT * FROM $table ORDER BY id DESC";
	$result = DB_Manager::query($sql);
	$data = array();	//conteneur des données	(attribut => valeur)

	foreach($result as $record):
		switch($table):
			case 'articles':
				$data['id'] = $record['id'];
				$data['id_gamme'] = $record['id_gamme'];
				$data['nom_gamme'] = Gamme::get($record['id_gamme'])->getNom();
				$data['nom'] = $record['nom'];

				fputs($fichier, "<article>\n");
				XMLgenerator::ajouter_element($data, $fichier);
				fputs($fichier, "</article>\n");
			break;
			case 'gadgets':
				$data['id'] = $record['id'];
				$data['id_article'] = $record['id_article'];
				$data['nom_article'] = Article::get($record['id_article'])->getNom();
				$data['id_gamme'] = $record['id_gamme'];
				$data['nom_gamme'] = Gamme::get($record['id_gamme'])->getNom();
				$data['nom'] = $record['nom'];

				fputs($fichier, "<gadget>\n");
				XMLgenerator::ajouter_element($data, $fichier);
				fputs($fichier, "</gadget>\n");
			break;
			case 'gammes':
				$data['id'] = $record['id'];
				$data['nom'] = $record['nom'];
				$data['nom_court'] = $record['nom_court'];

				fputs($fichier, "<gamme>\n");
				XMLgenerator::ajouter_element($data, $fichier);
				fputs($fichier, "</gamme>\n");
			break;
			case 'clients':
				$data['id'] = $record['id'];
				$data['nom'] = $record['nom'];

				fputs($fichier, "<client>\n");
				XMLgenerator::ajouter_element($data, $fichier);
				fputs($fichier, "</client>\n");
			break;
		endswitch;
	endforeach;
	fputs($fichier, "</$table>\n");	//container_ending
	fclose($fichier);	//fermeture du fichier
	Application::redir($nom_fichier);	//redirection vers le fichier à télécharger
/*
	le fichier résultant est de la forme:

	<?xml version="1.0"?>
	<container>
		<attribut1>valeur1</attribut1>
		<attribut2>valeur2</attribut2>
		<attribut3>valeur3</attribut3>
	</container>
*/
?>