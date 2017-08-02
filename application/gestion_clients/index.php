<?php
	$title = 'gestionClients';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):
		Application::redir('../login/');
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
      <?php require_once '../include/side_bar_clients.php'; ?>
	<div id="content">
		<a href="ajouter_client.php">ajouter client</a><br />
		<a href="afficher_client.php?n=1">afficher clients (<?php echo Client::nb(); ?>)</a><br />
		<a href="rechercher_client.php">rechercher client</a><br />
	    <a href="../include/telecharger_liste.php?table=clients" target="_blank">télécharger liste des clients</a><br />
	</div>
    </div>
    <div id="footer">
		<p><a href="#">ADWYA - juillet 2014</a></p>
	</div>
	</div>
	</div>
</body>
</html>