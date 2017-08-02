<?php
	$title = 'gestionGadgets';
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
				<?php require_once '../include/side_bar_gadgets.php'; ?>
				<div id="content">
					<a href="ajouter_gadget.php">ajouter gadget</a><br />
					<a href="afficher_gadget.php?n=1">afficher gadgets (<?php echo Gadget::nb(); ?>)</a><br />
					<a href="rechercher_gadget.php">rechercher gadget</a><br />
					<a href="../include/telecharger_liste.php?table=gadgets" target="_blank">télécharger liste des gadgets</a><br />
				</div>
			</div>
			<div id="footer">
			<p><a href="#">ADWYA - juillet 2014</a></p>
			</div>
		</div>
	</div>
</body>
</html>