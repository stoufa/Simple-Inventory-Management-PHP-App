<?php
	$title = 'modifierArticle';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
	if(Gamme::pasDelements()):	//test pour vérifier qu'il y a des gammes car un article doit être associé à une gamme
		Application::alert("aucune gamme trouvé!");
		Application::redir('index.php');
	endif;
	if(isset($_GET['id'])):	//test s'il ya un paramétre passé à cette page et si ce dernier est valide ou pas
		$cleId = intval($_GET['id']);
		if(is_int($cleId)):
			$a = Article::get($cleId);
			if(!Article::existe($a)):
				Application::alert("article introuvable!");
				Application::redir('index.php');
			endif;
		else:
			Application::alert("la clé doit être numérique!");
			Application::redir('index.php');
		endif;
	else:
		Application::alert("paramétre introuvable!");
		Application::redir('index.php');
	endif;
	$article = Article::get($cleId);
	$nom = $article->getNom();
	$idGamme = $article->getIdGamme();
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
					<form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>" class="login">
						<fieldset>
							<legend>Article:</legend>
							<div>
								<label for="nom">Nom:</label>
								<input type="text" name="nom" placeholder="Nom de l'article" required value="<?php echo $nom; ?>" /><br />
							</div>
							<div>
								<label>Ancienne gamme:</label>
								<?php
									echo Gamme::get($article->getIdGamme())->getNom() . ' (' . Gamme::get($article->getIdGamme())->getNomCourt() . ')';
								?>
							</div>
							<div>
								<label for="id_gamme">Gamme:</label>
								<select name="id_gamme" id="select">
									<?php
									$gammes = Gamme::getAll();
									$indice = -1;
									for($i=0;$i<count($gammes);$i++):
										$gamme = $gammes[$i];
										//récupérer l'indice de la liste
										if($gamme->getId() == $idGamme):
											$indice = $i;
										endif;
									?>
										<option value="<?php echo $gamme->getId(); ?>"><?php echo $gamme->getNom() . ' (' . $gamme->getNomCourt() . ')'; ?></option>
									<?php
									endfor;
									?>
								</select>
								<?php
								if($indice != -1):	//affectation de l'indice
								?>
									<script type="text/javascript">
										document.getElementById("select").selectedIndex = <?php echo $indice; ?>;
									</script>
						<?php
						endif;
						?>
							</div>
							<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
						</fieldset>
						<input type="submit" name="modifier_article" value="Modifier l'article" />
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