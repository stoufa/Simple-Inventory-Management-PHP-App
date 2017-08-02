<?php
	$title = 'modifierGamme';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
	$cleId;
	if(isset($_GET['id'])):	//test s'il ya un paramétre passé à cette page et si ce dernier est valide ou pas
		$cleId = intval($_GET['id']);
		if(is_int($cleId)):
			$gamme = Gamme::get($cleId);
			if(!Gamme::existe($gamme)):
				Application::alert('gamme introuvable!');
				Application::redir('afficher_gamme.php?n=1');
			endif;
		else:
			Application::alert('la clé doit être numérique!');
			Application::redir('afficher_gamme.php?n=1');
		endif;
	else:
		Application::alert('paramétre introuvable!');
		Application::redir('afficher_gamme.php?n=1');
	endif;
	$nom = (isset($_SESSION['GammeNom']) && !empty($_SESSION['GammeNom']))? $_SESSION['GammeNom'] : $gamme->getNom();
	$nomCourt = (isset($_SESSION['GammeNomCourt']) && !empty($_SESSION['GammeNomCourt']))? $_SESSION['GammeNomCourt'] : $gamme->getNomCourt();
	$message = (isset($_SESSION['message']) && !empty($_SESSION['message']))? $_SESSION['message']: '';
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
	<link rel="stylesheet" type="text/css" href="../include/style/form.css" />
</head>
<body>
	<?php require_once '../include/login_info.php'; ?>
	<div id="main">
		<div id="header">
			<div id="logo">
				<div id="logo_text">
					<h1>
						<a href="#"><span class="logo_colour">gestion de stock</span> </a>
					</h1>
					<h2>juillet 2014</h2>
				</div>
			</div>
			<?php require '../include/cssmenu.php'; ?>
			<div id="site_content">
			<?php require_once '../include/side_bar_gammes.php'; ?>
				<div id="content">
					<form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>" class="login">
						<fieldset>
							<legend>Gamme</legend>
							<div>
								<label for="nom">Nom:</label><input type="text" name="nom" placeholder="Nom de la gamme" required value="<?php echo $nom; ?>" /><br />
							</div>
							<div>
								<label for="nom_court">Nom court:</label> <input type="text" name="nom_court" placeholder="Nom court de la gamme" required value="<?php echo $nomCourt; ?>" /><br />
							</div>
							<?php //Remarque: si c'est la premiére visite la valeur est tirée de la base sinon, la valeur de l'utilisateur est affiché ?>
							<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
						</fieldset>
						<input type="submit" name="modifier_gamme" value="Modifier la gamme" />
					</form>
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