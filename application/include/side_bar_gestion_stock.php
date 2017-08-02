<?php require_once 'include.php'; ?>
<div id="sidebar_container">
	<div class="sidebar">
		<div class="sidebar_top"></div>
		<div class="sidebar_item">
			<h3>Menu</h3>
			<ul>
				<li><a href="../index.php">Accueil</a></li>
				<li><a href="../gestion_stock">gestion_stock</a></li>
				<li><a href="reception.php">reception</a></li>
				<li><a href="livraison.php">livraison</a></li>
				<li><a href="consultation.php">consultation (<?php echo Mouvement::nb(); ?>)</a></li>
			</ul>
		</div>
		<div class="sidebar_base"></div>
	</div>
</div>