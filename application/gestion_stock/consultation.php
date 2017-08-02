<?php
	$title = 'Consultation';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):
		Application::redir('../login/');
	endif;
	if(Mouvement::pasDelements()): //s'il n'ya pas de mouvements, on peut rien consulter !
		Application::alert("aucun mouvement trouvé!");
		Application::redir('index.php');
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
  <script type="text/javascript" src="../include/js/jquery-1.10.1.min.js"></script>
  <link rel="stylesheet" href="../include/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.datepicker.js"></script>
  <!--<link rel="stylesheet" href="../include/js/jquery-ui-1.10.3/demos/demos.css">-->
  <script type="text/javascript">
    $('document').ready(function(){
      $('.datefield').datepicker();
    });
  </script>
<style type="text/css"> table[id="t"], table[id="t"] td, table[id="t"] th { border:1px solid black; } </style>
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
    <?php require_once '../include/side_bar_gestion_stock.php'; ?>
	<div id="content">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      Du: <input type='text' class='datefield' name="deb" required />
      Jusqu'au: <input type='text' class='datefield' name="fin" required />
      <button type="submit" name="submit">Consulter</button>
    </form><br />
    <?php
      if(isSet($_POST['submit'])):
        //affichage des resultats
        $deb = $_POST['deb'];
        $fin = $_POST['fin'];
        if(Application::datesValides($deb, $fin)):
			if(!Mouvement::pasDeMouvements($deb, $fin)):
				$mouvements = Mouvement::getMouvements($deb, $fin);
				?>
					<table id="t">
						<tr>
							<td>id</td>
							<td>type</td>
							<td>date</td>
							<?php if(Utilisateur::getUtilisateurConnecte()->estAdmin()): ?>
								<td colspan="2">options</td>
							<?php endif; ?>
						</tr>
						<?php
						foreach ($mouvements as $mouvement) {
							?>
								<tr>
									<td><?php echo $mouvement['id']; ?></td>
									<td><?php echo $mouvement['type']; ?></td>
									<td><?php echo Application::toNormalDate($mouvement['date']); ?></td>
									<?php if(Utilisateur::getUtilisateurConnecte()->estAdmin()): ?>
										<td><a href="modifierMouvement.php?id=<?php echo $mouvement['id']; ?>"><img src="../include/img/edit-icon.png" title="modifier" style="height: 24px; width: 24px;" /></a></td>
										<td><a href="supprimerMouvement.php?id=<?php echo $mouvement['id']; ?>" onclick="return(confirm('Supprimer?'))"><img src="../include/img/delete-icon.png" title="supprimer" style="height: 24px; width: 24px;" /></a></td>
									<?php endif; ?>					
								</tr>
							<?php
						}
						?>
					</table>
				<?php
			else:
				echo 'aucun mouvement a afficher';
			endif;
        else:
          echo 'La date de début doit être <strong style="color:red;font-weight:bold;font-size:1.3em;">avant</strong> la date de fin!';
        endif;
      endif;
    ?>
	</div>
    </div>   
    <div id="footer">
		<p><a href="#">ADWYA - juillet 2014</a></p>
	</div>
	</div>
	</div>
</body>
</html>