<?php
	$title = 'ajouterArticle';
	require_once '../include/include.php';
	if (!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if (Gamme::pasDelements()):	//test pour vérifier qu'il y a des gammes car un article doit être associé à une gamme
		Application::alert("aucune gamme trouvé!");
		Application::redir('index.php');
	endif;
	$nom = (isset($_SESSION['ArticleNom']) && !empty($_SESSION['ArticleNom']))? $_SESSION['ArticleNom'] : '';
	$idGamme = (isset($_SESSION['ArticleIdGamme']) && !empty($_SESSION['ArticleIdGamme']))? $_SESSION['ArticleIdGamme'] : '';
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
			<?php require_once '../include/side_bar_articles.php'; ?>
				<div id="content">
					<form method="post" action="add.php" class="login">
						<fieldset>
							<legend>Article:</legend>
							<div>
								<label for="nom">Nom:</label>
								<input type="text" name="nom" placeholder="Nom de l'article" required value="<?php echo $nom; ?>" /><br />
							</div>
							<div>
								<label for="id_gamme">Gamme:</label>
								<select name="id_gamme" id="select">
									<?php
									$gammes = Gamme::getAll();
									if($gammes != Gamme::$NO_GAMME) {
										foreach($gammes as $gamme):
											?>
												<option value="<?php echo $gamme->getId(); ?>"><?php echo $gamme->getNom() . ' (' . $gamme->getNomCourt() . ')'; ?></option>
											<?php
										endforeach;
									}
									?>
								</select>
							</div>
							<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
						</fieldset>
						<input type="submit" name="add_article" value="Ajouter l'article" />
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