<?php
	require_once '../include/include.php';	
	$table = Article::getTableName();
	$title = 'recherche - ' . $table;
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if (Article::pasDelements()):	//test pour vérifier qu'il y a des articles dans la base
		Application::alert("aucun article trouvé!");
		Application::redir('index.php');
	endif;
	$options = Article::loadOptions();
	$afficherResultat = false;
	$last_selected_index = 0; //par défaut on pointe sur la premiére option
	$texte = '';
	$critere = '';
	if(isset($_POST['btn_submit'])):
		$texte = $_POST['texte'];
		$critere = $_POST['critere'];
		$last_selected_index = $options[$critere];  //récupération de l'indice du choix séléctionné
		if($critere == 'id' || $critere == 'id_gamme'):
			if(preg_match("~^-?[0-9]+$~i", $texte)):
				//valeur valide
				$afficherResultat = true;
			else:
				//Erreur!
				Application::alert('id/id_gamme doiventt être numériques!');
			endif;
		else:
			$afficherResultat = true;
		endif;
	endif;
?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta name="description" content="website description" />
	<meta name="keywords" content="website keywords, website keywords" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../style/style.css" />
	<link rel="stylesheet" type="text/css" href="../include/style/cssmenu.css" />
	<style type="text/css"> table, td, th { border:1px solid black; } </style>
</head>
<body>
	<?php require_once '../include/login_info.php'; ?>
	<div id="main">
		<div id="header">
			<div id="logo">
				<div id="logo_text">
					<h1>
						<a href="#"><span class="logo_colour">gestion de stock</span></a>
					</h1>
					<h2>juillet 2014</h2>
				</div>
			</div>
			<?php require '../include/cssmenu.php'; ?>
			<div id="site_content">
			<?php require_once '../include/side_bar_articles.php'; ?>
				<div id="content">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						recherche <?php echo $table; ?>:
						<input type="text" name="texte" required value="<?php echo $texte ?>">
						<select name="critere" id="liste_criteres">
							<?php
							foreach ($options as $key => $value) {
								?>
								<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
								<?php
							}
							?>
						</select>
						<input type="submit" name="btn_submit" value="Rechercher">
					</form><br />
					<?php /* code javascript qui permet de séléctionner la derniére option choisie */ ?>
					<script type="text/javascript">
    					var liste_criteres = document.getElementById('liste_criteres');
    					liste_criteres.selectedIndex = <?php echo $last_selected_index ?>;
  					</script>
					<?php
						if($afficherResultat):
							//affichage des résultats s'ils existent!
							if($critere == 'id' || $critere == 'id_gamme'):
								$res = DB_Manager::select($table, "$critere = '$texte'");
							else:
								$res = DB_Manager::select($table, "$critere LIKE '%$texte%'");
							endif;
							if($res == DB_Manager::$NO_RESULTS): //s'il n'ya pas de résultats
								print('pas de résultats!');
							else:
								$style = 'style="background-color:#92acff;"';
					?>
					<table>
						<tr>
							<?php
							foreach ($options as $key => $value) {
								?>
								<th <?php if($critere == $key) { echo $style; } ?>><?php echo $key; ?></th>
								<?php
							}
							?>
							<?php if(Utilisateur::getUtilisateurConnecte()->estAdmin()): ?>
								<th colspan="2">Options</th>
							<?php endif; ?>
						</tr>
						<?php
						foreach ($res as $record) {
							?>
							<tr>
								<?php
								foreach ($options as $key => $value) {
									?>
									<td <?php if($critere == $key) { echo $style; } ?>><?php echo $record[$key]; ?>
									</td>
									<?php
								}
								?>
								<?php if(Utilisateur::getUtilisateurConnecte()->estAdmin()): ?>
									<td><a href="modifier_article.php?id=<?php echo $record['id']; ?>"><img src="../include/img/edit-icon.png" title="modifier" style="height: 24px; width: 24px;" /></a></td>
									<td><a href="supprimer_article.php?id=<?php echo $record['id']; ?>" onclick="return(confirm('Supprimer?'))"><img src="../include/img/delete-icon.png" title="supprimer" style="height: 24px; width: 24px;" /></a></td>
								<?php endif; ?>
							</tr>
							<?php
						}
						?>
					</table>
					<input type="button" value="Imprimer cette page" onClick="window.print()">
						<?php
						 endif;
						endif;
						?>
				</div>
			</div>
			<div id="footer">
				<p>
					<a href="#">ADWYA - juillet 2014</a>
				</p>
			</div>
		</div>
	</div>
</body>
</html>