<?php
	$title = 'ajouterGadget';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(Article::pasDelements()):	//test pour vérifier qu'il y a des articles car un gadget doit être associé à un article
		Application::alert("aucun article trouvé!");
		Application::redir('index.php');
	endif;
	$nom = (isset($_SESSION['GadgetNom']) && !empty($_SESSION['GadgetNom']))? $_SESSION['GadgetNom'] : '';
	$idArticle = (isset($_SESSION['GadgetIdArticle']) && !empty($_SESSION['GadgetIdArticle']))? $_SESSION['GadgetIdArticle'] : '';
	$idGamme = (isset($_SESSION['GadgetIdGamme']) && !empty($_SESSION['GadgetIdGamme']))? $_SESSION['GadgetIdGamme'] : '';
	//$quantite = (isset($_SESSION['GadgetQuantite']) && !empty($_SESSION['GadgetQuantite']))? $_SESSION['GadgetQuantite'] : '';
	$designation = (isset($_SESSION['GadgetDesignation']) && !empty($_SESSION['GadgetDesignation']))? $_SESSION['GadgetDesignation'] : '';
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
	  <form method="post" action="add.php" class="login">
	  		<fieldset>
	  			<legend>Gadget</legend>
	  			<div>
	  				<label for="nom">Nom:</label>
	  				<input type="text" name="nom" placeholder="Nom du gadget" required value="<?php echo $nom; ?>"/><br />
	  			</div>
	  			<div>
	  				<label for="id_article">Article:</label>
					<select name="id_article" id="select">
					<?php
						$indexList = array();	//id->indice
						$articles = Article::getAll();
						for($i=0;$i<count($articles);$i++):
							$article = $articles[$i];
							$indexList[$article->getId()] = $i;	//sauvegarde de l'indice
							?>
								<option value="<?php echo $article->getId(); ?>"><?php echo $article->getNom(); ?></option>
							<?php
						endfor;
					?>
					</select>
	  			</div>
	  			<div>
	  				<label for="id_gamme">Gamme:</label>
					<select name="id_gamme" id="select2">
					<?php
						$indexList2 = array();	//id->indice
						$gammes = Gamme::getAll();
						for($i=0;$i<count($gammes);$i++):
							$gamme = $gammes[$i];
							$indexList2[$gamme->getId()] = $i;	//sauvegarde de l'indice
							?>
								<option value="<?php echo $gamme->getId(); ?>"><?php echo $gamme->getNom() . ' (' . $gamme->getNomCourt() . ')'; ?></option>
							<?php
						endfor;
					?>
					</select>
	  			</div>
	  			<!--
	  			<div>
	  				<label for="quantite">Quantite:</label>
	  				<input type="number" min="0" name="quantite" placeholder="Quantite du gadget" required value="<?php //echo (isset($quantite))?$quantite:''; ?>"/><br />
	  			</div>
	  			-->
	  			<div>
	  				<label for="designation">Designation:</label>
	  				<textarea name="designation"><?php echo (isset($designation))?$designation:''; ?></textarea><br />
	  			</div>
	  			<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
	  		</fieldset>
			<?php
				if(!empty($idArticle) && !empty($idGamme)):	//affectation de l'indice
					?>
						<script type="text/javascript">
							document.getElementById("select").selectedIndex = <?php echo $indexList[$idArticle]; ?>;
							document.getElementById("select2").selectedIndex = <?php echo $indexList2[$idGamme]; ?>;
						</script>
					<?php
				endif;
			?>
			<input type="submit" name="add_gadget" value="Ajouter le gadget" />
	  </form>
	</div>
    </div>
    <div id="footer">
      <p><a href="#">ADWYA - juillet 2014</a></p>
    </div>
    <p>&nbsp;</p>
  </div>
  </div>
</body>
</html>