<h1>BIENVENUE SUR LE FORUM</h1>

<p>Ce forum est destiné à promouvoir les activités des developpeurs web et d'echanger sur différents thèmes.  </p>

<p>
<?php if (isset($_SESSION['user'])): ?>
    <a href="index.php?ctrl=security&action=profile"><span class="fas fa-user"></span> <?= htmlspecialchars($_SESSION['user']->getPseudo()) ?></a>
    <a href="index.php?ctrl=security&action=logout">Déconnexion</a>
<?php else: ?>
    <a href="index.php?ctrl=security&action=login">Se connecter</a>
    <a href="index.php?ctrl=security&action=register">S'inscrire</a>
<?php endif; ?>
</p>
