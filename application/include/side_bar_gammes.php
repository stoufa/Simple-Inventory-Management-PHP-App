<div id="sidebar_container">
  <div class="sidebar">
    <div class="sidebar_top"></div>
    <div class="sidebar_item">
      <h3>Menu</h3>
      <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="index.php">gestion_gammes</a></li>
        <li><a href="ajouter_gamme.php">ajouter gamme</a></li>
        <li><a href="afficher_gamme.php?n=1">afficher gamme (<?php echo Gamme::nb(); ?>)</a></li>
        <?php /* le paramétre passé pour demander la premiére page (le résultat est paginé) */ ?>
        <li><a href="rechercher_gamme.php">rechercher gamme</a><li/>
        <li><a href="../include/telecharger_liste.php?table=gammes" target="_blank">télécharger liste</a></li>
      </ul>
    </div>
    <div class="sidebar_base"></div>
  </div>
</div>