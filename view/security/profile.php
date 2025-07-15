<?php
// Vérifie si 'data' et 'user' sont bien passés à la vue
if (isset($data['user'])):
    $user = $data['user']; // On récupère l'utilisateur depuis $data
?>

<h1>Profil de <?= htmlspecialchars($data['user']->getPseudo()) ?></h1>

<p><strong>Email :</strong> <?= htmlspecialchars($data['user']->getEmail()) ?></p>
<p><strong>Rôle :</strong> <?= htmlspecialchars($data['user']->getRole()) ?></p>

<?php if ($data['user']->getRole() === 'admin'): ?>
    <h3>Section administrateur</h3>
    <p>Vous avez des privilèges d'administrateur.</p>
    <!-- Ajouter des liens spécifiques pour l'administrateur -->
    <ul>
        <li><a href="index.php?ctrl=admin&action=manageUsers">Gérer les utilisateurs</a></li>
        <li><a href="index.php?ctrl=admin&action=manageTopics">Gérer les topics</a></li>
    </ul>
<?php endif; ?>

<p><a href="index.php?ctrl=security&action=logout">Déconnexion</a></p>

<?php else: ?>
    <p>Erreur : utilisateur non trouvé.</p>
<?php endif; ?>