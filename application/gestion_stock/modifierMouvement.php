<?php
if(!isset($_GET['id']) || empty($_GET['id'])) die('error!');
	$title = 'modifierMouvement id = ' . $_GET['id'];
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):
		Application::redir('../login/');
	endif;
	if(!Utilisateur::getUtilisateurConnecte()->estAdmin()):
		Application::alert('vous devez être administrateur pour consulter cette page');
		Application::redir('autre.php');
	endif;
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
  <script type="text/javascript" src="../include/js/jquery-1.10.1.min.js"></script>
  <link rel="stylesheet" href="../include/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.widget.js"></script>
  <script src="../include/js/jquery-ui-1.10.3/ui/jquery.ui.datepicker.js"></script>
  <!--<link rel="stylesheet" href="../include/js/jquery-ui-1.10.3/demos/demos.css">-->
  <script type="text/javascript">
    $('document').ready(function(){
      $('#datefield').datepicker();
    });
  </script>
  <script type="text/javascript">
      //fonction qui change affiche l'article et la gamme qui correspond au gadget sélectionné
      function updateInfo() {
        var list = document.getElementById('gadget');
        var article = document.getElementById('article');
        var gamme = document.getElementById('gamme');
        var qte_stock = document.getElementById('qte_stock');
        var articles_list = document.getElementById('articles_list');
        var gammes_list = document.getElementById('gammes_list');
        var qte_stock_list = document.getElementById('qte_stock_list');
        var indice = list.selectedIndex;
        article.innerHTML = articles_list.options[indice].text;
        gamme.innerHTML = gammes_list.options[indice].text;
        qte_stock.innerHTML = qte_stock_list.options[indice].text;
      }
  </script>
</head>
<body onLoad="updateInfo()">
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
		<?php
			echo 'modifierMouvement id = ' . $_GET['id'] . '<br/>';
			//récupération du mouvement de la base
			$mouvement = Mouvement::get($_GET['id']);
			if($mouvement === Mouvement::$NO_MOUVEMENT) {
				echo 'mouvement introuvable!';
			} else {
				if($mouvement->getType() == 'livraison') {
					$l = Livraison::get($mouvement->getId());
					echo '<div style="border:1px solid #AAA;padding:5px;">';
					echo 'dateLivraison: ' . $l->getDateLivraison() . '<br/>';
					echo 'idGadget: ' . $l->getIdGadget() . '<br/>';			
					$g = Gadget::get($l->getIdGadget());
					echo 'nomGadget: ' . $g->getNom() . '<br/>';
					echo 'quantitéLivré: ' . $l->getQuantite() . '<br/>';
					echo 'quantitéStock: ' . $g->getQuantite() . '<br/>';
					echo 'idClient: ' . $l->getIdClient() . '<br/>';
					echo '</div>';
					?>
					<br/>
					<form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>">
					<fieldset>
						<legend>Livraison:</legend>
						<div>
						  <label for="datefield">Date livraison: </label>
						  <input type='text' id='datefield' required name="datefield" value="<?php echo $l->getDateLivraison(); ?>"/><br />
						</div>
						<div>
						  <label for="gadget">Gadget: </label>
						  <select onChange="updateInfo()" id="gadget" name="gadget">
							<?php
							$gadgets = Gadget::getAll();
							  foreach ($gadgets as $gadget) {
								$articles[] = Article::get($gadget->getIdArticle())->getNom();
								$g = Gamme::get($gadget->getIdGamme());
								$gammes[] = $g->getNom();
								$qte_stock[] = $gadget->getQuantite();
								?>
								  <option value="<?php echo $gadget->getId(); ?>"><?php echo $gadget->getNom(); ?></option>
								<?php
							  }
							?>
						  </select>
						</div>
						<div>
						  <label for="article">Article: </label>
						  <span id="article"></span>
						</div>
						<div>
						  <label for="gamme">Gamme: </label>
						  <span id="gamme"></span>
						</div>
						<div>
						  <label for="qte_stock">qte Stock: </label>
						  <span id="qte_stock"></span>
						</div>
						<div>
						  <label for="qte">Qantité livré:</label>
						  <input type="number" min="1" name="qte" required value="<?php echo $l->getQuantite(); ?>"/>
						</div>
						<div>
						  <label for="client">Client: </label>
						  <select id="client" name="client">
							<?php
							$clients = Client::getAll();
							  foreach ($clients as $client) {
								?>
								  <option value="<?php echo $client->getId(); ?>"><?php echo $client->getNom(); ?></option>
								<?php
							  }
							?>
						  </select>
						  <!-- script pour sélectionner la premiére option des listes de gadgets et de clients -->
						  <script>
							var gadget = document.getElementById('gadget');
							var client = document.getElementById('client');
							gadget.selectedIndex = 0;
							client.selectedIndex = 0;
						  </script>
						</div>
						<span><?php echo $message; $_SESSION['message'] = ''; ?></span>
					  </fieldset>		
					  <select id="articles_list" style="display:none">
						<?php
						  foreach ($articles as $key => $value) {
							?>
							  <option><?php echo $value; ?></option>
							<?php
						  }
						?>
					  </select>
					  <select id="gammes_list" style="display:none">
						<?php
						  foreach ($gammes as $key => $value) { 
							?>
							  <option><?php echo $value; ?></option>
							<?php
						  }
						?>
					  </select>
					  <select id="qte_stock_list" style="display:none">
						<?php
						  foreach ($qte_stock as $key => $value) { 
							?>
							  <option><?php echo $value; ?></option>
							<?php
						  }
						?>
					  </select>
					  <button type="submit" name="btn_modif">Modification</button>
					</form>
					<?php
				} else {
					$r = Reception::get($mouvement->getId());
					echo '<div style="border:1px solid #AAA;padding:5px;">';
					echo 'dateReception: ' . $r->getDateReception() . '<br/>';
					echo 'idGadget: ' . $r->getIdGadget() . '<br/>';			
					$g = Gadget::get($r->getIdGadget());
					echo 'nomGadget: ' . $g->getNom() . '<br/>';
					echo 'quantitéReçu: ' . $r->getQuantite() . '<br/>';
					echo 'quantitéStock: ' . $g->getQuantite() . '<br/>';
					echo '</div>';
					?>
					<br/>
					<form method="post" action="mod.php?id=<?php echo $_GET['id']; ?>">
						<fieldset>
					      <legend>Reception:</legend>
					      <div>
					        <label for="datefield">Date réception: </label>
					        <input type='text' id='datefield' name="datefield" required value="<?php echo $r->getDateReception(); ?>"/>
					      </div>
					      <div>
					        <label for="gadget">Gadget: </label>
					        <select onChange="updateInfo()" id="gadget" name="gadget">
					          <?php
					          	$gadgets = Gadget::getAll();
					            foreach ($gadgets as $gadget) {
					              $articles[] = Article::get($gadget->getIdArticle())->getNom();
					              $gammes[] = Gamme::get($gadget->getIdGamme())->getNom();
					              ?>
					                <option value="<?php echo $gadget->getId(); ?>"><?php echo $gadget->getNom(); ?></option>
					              <?php
					            }
					          ?>
					        </select>
					        <!-- script qui permet de sélectionner l'option de la liste précedante -->
					        <script>
					          var gadget = document.getElementById('gadget');
					          gadget.selectedIndex = 0;
					        </script>
					      </div>
					      <div>
					        <label for="article">Article: </label>
					        <span id="article"></span>
					      </div>
					      <div>
					        <label for="gamme">Gamme: </label>
					        <span id="gamme"></span><br />
					      </div>
					      <div>
					        <label for="qte">Qantité reçu:</label>
					        <input type="number" min="1" name="qte" required value="<?php echo $r->getQuantite(); ?>"/>
					      </div>
					      <span><?php echo $message; $_SESSION['message'] = ''; ?></span>
					    </fieldset>
					    <select id="articles_list" style="display:none">
					      <?php
					        foreach ($articles as $key => $value) {
					          ?>
					            <option><?php echo $value; ?></option>
					          <?php
					        }
					      ?>
					    </select>
					    <select id="gammes_list" style="display:none">
					      <?php
					        foreach ($gammes as $key => $value) {
					          ?>
					            <option><?php echo $value; ?></option>
					          <?php
					        }
					      ?>
					    </select>
					    <button type="submit" name="btn_modif">Modification</button>
					</form>
					<?php
				}
			}
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