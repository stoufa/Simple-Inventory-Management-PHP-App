<?php
	require_once '../include/include.php';	
	$table = Client::getTableName();
	$title = "modifier - $table";
	if(!Utilisateur::utilisateurConnecte()):	//si l'utilisateur n'est pas connecté, on le renvoie vers l'interface de connexion
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
	$cleId;
	if(isset($_GET['id'])):
		$cleId = intval($_GET['id']);
		if(is_int($cleId)):
			$c = Client::get($cleId);
			if(!Client::existe($c)):
				Application::alert("client introuvable!");
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
	$client = Client::get($cleId);
	$nom = $client->getNom();
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
     <?php require_once '../include/side_bar_gammes.php'; ?>
      <div id="content">
		<form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>" class="login">
			<fieldset>
				<legend>Client</legend>
				<div>
					<label for="nom">Nom:</label>
					<input type="text" name="nom" placeholder="Nom du client" required value="<?php echo $nom; ?>"/>
				</div>
				<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
			</fieldset>
			<input type="submit" name="modifier_client" value="Modifier" />
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