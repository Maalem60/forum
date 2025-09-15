<h2>Liste des utilisateurs</h2>

<?php if (!empty($allUsers)): ?>
    <ul>
        <?php foreach ($allUsers as $user): ?>
            <li>
                <a href="index.php?ctrl=security&action=showUser&id=<?= $user->getId(); ?>">
                    <?= htmlspecialchars($user->getPseudo()); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun utilisateur inscrit.</p>
<?php endif; ?>

