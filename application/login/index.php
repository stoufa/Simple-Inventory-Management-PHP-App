<?php
	$title = 'login';
	require_once '../include/include.php';
	if(Utilisateur::utilisateurConnecte()) {
		Application::redir((Utilisateur::getUtilisateurConnecte()->estAdmin())? Application::$PAGE_ADMIN : Application::$PAGE_USER);
	}
	$error = Utilisateur::pasDelements();
	$afficheMsgErr = false;
	if(isset($_POST['submit'])):	//bouton cliqué
		$login = $_POST['login'];	//le login est unique
		$pw = $_POST['password'];
		$u = new Utilisateur(null, null, $login, $pw, null, null);
		if(Utilisateur::peutConnecter($u)):	//utilisateur existe
			$u = Utilisateur::connecter($u);
			Application::redir(($u->estAdmin())? Application::$PAGE_ADMIN : Application::$PAGE_USER);
		else:
			$afficheMsgErr = true;
		endif;
	endif;
?>
<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo $title; ?></title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="../style/style.css" />
  <style type="text/css">
  	#site_content {
  		-o-border-radius:10px;
  		-moz-border-radius:10px;
  		-webkit-border-radius:10px;
  		border-radius:10px;
  	}
  </style>
</head>
<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <h1><a href="#"><span class="logo_colour">gestion de stock</span></a></h1>
          <h2>juillet 2014</h2>
        </div>
      </div>
    <div id="site_content">
      <div id="content" style="margin:auto;width:220px;">
      	<?php
      		if(!$error):
      	?>
		        <h1>Connexion</h1>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login">
					<table>
						<tr>
							<td>
								<label for="login">Login:</label>
							</td>
							<td>
								<input type="text" name="login" placeholder="login" required><br />
							</td>
						</tr>
						<tr>
							<td>
								<label for="password">Password:</label>
							</td>
							<td>
								<input type="password" name="password" placeholder="password" required>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<button type="submit" name="submit">Login</button>
							</td>
						</tr>
					</table>
				</form>
		<?php
			else:
				echo "Aucun utilisateur trouvé!, l'application doit avoir au moins un administrateur!";
			endif;
		?>
      </div>
    </div>
  </div>
 </div>
</body>
</html>
<?php
	if($afficheMsgErr) {
		Application::alert('utilisateur introuvable !');
	}
?>