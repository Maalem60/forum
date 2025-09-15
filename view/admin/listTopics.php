<?php if (isset($posts) && !empty($posts)): ?>
    <h1>Gestion des Posts</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post->getId()) ?></td>
                    <td><?= htmlspecialchars($post->getTitle()) ?></td>
                    <td><?= htmlspecialchars($post->getUser()->getPseudo()) ?></td>
                    <td><?= htmlspecialchars($post->getCreationDate()) ?></td>
                    <td>
                        <!-- Lien pour voir le post -->
                        <a href="index.php?ctrl=forum&action=detailPost&id=<?= $post->getId() ?>">👁 Voir</a> |

                        <!-- Supprimer -->
                        <a href="index.php?ctrl=admin&action=deletePost&id=<?= $post->getId() ?>"
                           onclick="return confirm('Confirmer la suppression de ce post ?')">🗑 Supprimer</a> |

                        <!-- Verrouiller / Déverrouiller (si la relation topic existe) -->
                        <?php if ($post->getTopic()->isClosed()): ?>
                            <a href="index.php?ctrl=admin&action=openPost&id=<?= $post->getId() ?>">🔓 Déverrouiller</a>
                        <?php else: ?>
                            <a href="index.php?ctrl=admin&action=closePost&id=<?= $post->getId() ?>">🔒 Verrouiller</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="index.php?ctrl=admin&action=dashboard">← Retour au tableau de bord</a></p>

<?php else: ?>
    <p>Aucun post trouvé.</p>
<?php endif; ?>
