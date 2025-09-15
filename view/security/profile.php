<?php
if (isset($data['user'])):
    $user = $data['user'];
    ?>
    ?>

    <h1>Profil de <?= htmlspecialchars($user->getPseudo()) ?></h1>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
    <!-- Bouton retour vers la page membre -->
    <p><a href="index.php?ctrl=security&action=listUsers">← Retour à la liste des utilisateurs</a></p>

    <button onclick="history.back()">← Retour</button>
    <!-- On enlève l'affichage du rôle tant que ce n'est pas géré -->
    <!--<p><strong>Rôle :</strong> <?= htmlspecialchars($user->getRole()) ?></p>-->
    <h1>Profil de <?= htmlspecialchars($user->getPseudo()) ?></h1>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
    <!-- Bouton retour vers la page membre -->
    <p><a href="index.php?ctrl=security&action=listUsers">← Retour à la liste des utilisateurs</a></p>

    <button onclick="history.back()">← Retour</button>
    <!-- On enlève l'affichage du rôle tant que ce n'est pas géré -->
    <!--<p><strong>Rôle :</strong> <?= htmlspecialchars($user->getRole()) ?></p>-->

    <!-- Section admin commentée -->
     <!-- Section administrateur -->
<?php 
if (\app\Session::isAdmin()): ?>
    <h3>Section administrateur</h3>
    <p>Vous avez des privilèges d'administrateur.</p>
    <ul>
   
    <li><a href="index.php?ctrl=admin&action=listUsers">Gérer les utilisateurs</a></li>
    <li><a href="index.php?ctrl=admin&action=listTopics">Gérer les topics</a></li>
    <li><a href="index.php?ctrl=admin&action=listPosts">Gérer les posts</a></li>
</ul>

    <?php endif; ?>
    


    <p><a href="index.php?ctrl=security&action=logout">Déconnexion</a></p>
    <p><a href="index.php?ctrl=security&action=logout">Déconnexion</a></p>


<?php else: ?>
    <p>Erreur : utilisateur non trouvé.</p>
    <p>Erreur : utilisateur non trouvé.</p>
    <?php endif; ?>