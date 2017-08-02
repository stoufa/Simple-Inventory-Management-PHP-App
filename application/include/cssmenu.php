<?php
	//menu horizental
	require_once 'include.php';
	$user = Utilisateur::getUtilisateurConnecte();
	$page_accueil = ($user->estAdmin())? 'admin.php': 'autre.php';
?>
<div id='cssmenu'>
	<ul>
		<li><a href='../<?php echo $page_accueil; ?>'><span>Accueil</span> </a>
		</li>
		<li class='has-sub'><a href='../gestion_gammes/'><span>Gammes</span> </a>
			<ul>
				<li><a href='../gestion_gammes/ajouter_gamme.php'><span>Ajouter</span>
				</a></li>
				<li><a href='../gestion_gammes/rechercher_gamme.php'><span>Rechercher</span>
				</a></li>
				<li class='last'><a href='../gestion_gammes/afficher_gamme.php?n=1'><span>Afficher
							(<?php echo Gamme::nb(); ?>)</span> </a></li>
			</ul>
		</li>
		<li class='has-sub'><a href='../gestion_articles/'><span>Articles</span>
		</a>
			<ul>
				<li><a href='../gestion_articles/ajouter_article.php'><span>Ajouter</span>
				</a></li>
				<li><a href='../gestion_articles/rechercher_article.php'><span>Rechercher</span>
				</a></li>
				<li class='last'><a
					href='../gestion_articles/afficher_article.php?n=1'><span>Afficher
							(<?php echo Article::nb(); ?>)</span> </a></li>
			</ul>
		</li>
		<li class='has-sub'><a href='../gestion_gadgets/'><span>Gadgets</span>
		</a>
			<ul>
				<li><a href='../gestion_gadgets/ajouter_gadget.php'><span>Ajouter</span>
				</a></li>
				<li><a href='../gestion_gadgets/rechercher_gadget.php'><span>Rechercher</span>
				</a></li>
				<li class='last'><a
					href='../gestion_gadgets/afficher_gadget.php?n=1'><span>Afficher (<?php echo Gadget::nb(); ?>)</span>
				</a></li>
			</ul>
		</li>

<!--
  <li class='has-sub'><a href='#'><span>Entrées</span></a>
    <ul>
      <li><a href='#'><span>Ajouter</span></a></li>
      <li><a href='#'><span>Rechercher</span></a></li>
      <li class='last'><a href='#'><span>Afficher (#)</span></a></li>
    </ul>
  </li>
  <li class='has-sub'><a href='#'><span>Sorties</span></a>
    <ul>
      <li><a href='#'><span>Ajouter</span></a></li>
      <li><a href='#'><span>Rechercher</span></a></li>
      <li class='last'><a href='#'><span>Afficher (#)</span></a></li>
    </ul>
  </li>
-->
		<li class='has-sub'><a href='../gestion_clients'><span>Clients</span>
		</a>
			<ul>
				<li><a href='../gestion_clients/ajouter_client.php'><span>Ajouter</span>
				</a></li>
				<li><a href='../gestion_clients/rechercher_client.php'><span>Rechercher</span>
				</a></li>
				<li class='last'><a
					href='../gestion_clients/afficher_client.php?n=1'><span>Afficher (<?php echo Client::nb(); ?>)</span>
				</a></li>
			</ul>
		</li>

		<li class='has-sub last'><a href='../gestion_stock'><span>Gestion stock</span>
		</a>
			<ul>
				<li><a href='../gestion_stock/reception.php'><span>Réception</span>
				</a></li>
				<li><a href='../gestion_stock/livraison.php'><span>Livraison</span>
				</a></li>
				<li class='last'><a href='../gestion_stock/consultation.php'><span>Consultation (<?php echo Mouvement::nb(); ?>)</span>
				</a></li>
			</ul>
		</li>
	</ul>
</div>
