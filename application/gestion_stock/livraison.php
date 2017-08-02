<?php
	$title = 'Livraison';
	require_once '../include/include.php';
	if(!Utilisateur::utilisateurConnecte()):
		Application::redir('../login/');
	endif;
  if(Gadget::pasDelements()): //s'il n'ya pas de gadgets, on peut rien livrer !
    Application::alert("aucun gadget trouvé!");
    Application::redir('index.php');
  endif;
  if(Client::pasDelements()): //s'il n'ya pas de clients, on peut rien livrer !
    Application::alert("aucun client trouvé!");
    Application::redir('index.php');
  endif;
  $gadgets = Gadget::getAll(); //gadgets
  $articles = array();  //articles
  $gammes = array();  //gammes
  $qte_stock = array();	//quantités
  $clients = Client::getAll(); //clients
	$dateLivraison = (isset($_SESSION['datefield']) && !empty($_SESSION['datefield']))? $_SESSION['datefield']: '';
    $idGadget = (isset($_SESSION['gadget']) && !empty($_SESSION['gadget']))? $_SESSION['gadget']: '';
    $quantite = (isset($_SESSION['qte']) && !empty($_SESSION['qte']))? $_SESSION['qte']: '';
    $idClient = (isset($_SESSION['client']) && !empty($_SESSION['client']))? $_SESSION['client']: '';
    $message = (isset($_SESSION['message']) && !empty($_SESSION['message']))? $_SESSION['message']: '';
?>
<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo $title; ?></title>
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
<!-- l'attribut onLoad est spécifié pour que les champs gamme et article ne reste pas vide lors du chargement de la page -->
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
    <form method="post" action="add_l.php">
      <fieldset>
        <legend>Livraison:</legend>
        <div>
          <label for="datefield">Date livraison: </label>
          <input type='text' id='datefield' required name="datefield" value="<?php echo $dateLivraison; ?>"/><br />
        </div>
        <div>
          <label for="gadget">Gadget: </label>
          <select onChange="updateInfo()" id="gadget" name="gadget">
            <?php
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
          <label for="qte">Quantité à livrer:</label>
          <input type="number" min="1" name="qte" required value="<?php echo $quantite; ?>"/>
        </div>
        <div>
          <label for="client">Client: </label>
          <select id="client" name="client">
            <?php
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
      <button type="submit" name="btn_creat">Création</button>
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