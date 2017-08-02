<?php
	$title = 'modifierGadget';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
	if(Gadget::pasDelements()):	//test pour vérifier qu'il y a des gadgets à modifier
		Application::alert("aucun gadget trouvé!");
		Application::redir('index.php');
	endif;
	$cleId;
	if(isset($_GET['id'])):	//test s'il ya un paramétre passé à cette page et si ce dernier est valide ou pas
		$cleId = intval($_GET['id']);
		if(is_int($cleId)):
			$g = Gadget::get($cleId);
			if(!Gadget::existe($g)):
				Application::alert("gadget introuvable!");
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
	$gadget = Gadget::get($cleId);
	$nom = (isset($_SESSION['GadgetNom']) && !empty($_SESSION['GadgetNom']))? $_SESSION['GadgetNom']: $gadget->getNom();
	$idArticle = (isset($_SESSION['GadgetIdArticle']) && !empty($_SESSION['GadgetIdArticle']))? $_SESSION['GadgetIdArticle']: $gadget->getIdArticle();
	$idGamme = (isset($_SESSION['GadgetIdGamme']) && !empty($_SESSION['GadgetIdGamme']))? $_SESSION['GadgetIdGamme']: $gadget->getIdGamme();
	//$quantite = (isset($_SESSION['GadgetQuantite']) && !empty($_SESSION['GadgetQuantite']))? $_SESSION['GadgetQuantite']: $gadget->getQuantite();
	$designation = (isset($_SESSION['GadgetDesignation']) && !empty($_SESSION['GadgetDesignation']))? $_SESSION['GadgetDesignation']: $gadget->getDesignation();
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
          <h1><a href="#"><span class="logo_colour">gestion de stock</span></a></h1>
          <h2>juillet 2014</h2>
        </div>
      </div>
    <?php require '../include/cssmenu.php'; ?>
    <div id="site_content">
      <?php require_once '../include/side_bar_gadgets.php'; ?>
	<div id="content">
  <form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>" class="login">
  		<fieldset>
			<legend>Gadget:</legend>
			<div>
				<label for="nom">Nom:</label>
				<input type="text" name="nom" placeholder="Nom du gadget" required value="<?php echo $nom; ?>"/>
			</div>
			<div>
				<label for="id_article">Article:</label>
				ancien article: <?php echo Article::get($gadget->getIdArticle())->getNom(); ?>
			</div>
			<div>
				<label for="select">Article:</label>
				<select name="id_article" id="select">
					<?php
						$articles = Article::getAll();
						$indice = -1;
						for($i=0;$i<count($articles);$i++):
							$article = $articles[$i];
							//récupérer l'indice de la liste
							if($article->getId() == $idArticle):
								$indice = $i;
							endif;
							?>
								<option value="<?php echo $article->getId(); ?>"><?php echo $article->getNom(); ?></option>
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
			<div>
				<label for="id_gamme">Gamme:</label>
				<select name="id_gamme" id="select2">
					<?php
						$gammes = Gamme::getAll();
						$indice2 = -1;
						for($i=0;$i<count($gammes);$i++):
							$gamme = $gammes[$i];
							//récupérer l'indice de la liste
							if($gamme->getId() == $idGamme):
								$indice2 = $i;
							endif;
							?>
								<option value="<?php echo $gamme->getId(); ?>"><?php echo $gamme->getNom(); ?></option>
							<?php		
						endfor;
					?>
				</select>
				<?php
					if($indice2 != -1):	//affectation de l'indice
						?>
							<script type="text/javascript">
								document.getElementById("select2").selectedIndex = <?php echo $indice2; ?>;
							</script>
						<?php
					endif;
				?>
			</div>
			<!--
			<div>
				<label for="quantite">Quantité:</label>
				<input type="number" min="0" name="quantite" placeholder="Quantité du gadget" required value="<?php //echo $quantite; ?>"/>
			</div>
			-->
			<div>
				<label for="designation">Designation:</label>
				<textarea name="designation"><?php echo $designation; ?></textarea>
			</div>
			<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
  		</fieldset>
		<input type="submit" name="modifier_gadget" value="Modifier gadget" />
  </form>
	</div>
    </div>
    <div id="footer">
      <p><a href="#">ADWYA - juillet 2014</a></p>
    </div>
  </div>
  </div>
</body>
</html>