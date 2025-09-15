<?php if (isset($posts) && !empty($posts)): ?>
    <div class="posts-table-container">
        <h1>Gestion des Posts</h1>

        <table>
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
                        <td data-label="ID"><?= htmlspecialchars($post->getId() ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Titre">
                            <?= htmlspecialchars(substr((string) $post->getContent() ?? '', 0, 50), ENT_QUOTES, 'UTF-8') ?>…
                        </td>
                        <td data-label="Auteur">
                            <?= htmlspecialchars($post->getUser() ? $post->getUser()->getPseudo() : 'Anonyme', ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td data-label="Date">
                            <?= $post->getCreationDate() instanceof \DateTime ? $post->getCreationDate()->format('d/m/Y H:i') : '' ?>
                        </td>
                        <td data-label="Actions">
                            <a href="index.php?ctrl=forum&action=detailPost&id=<?= $post->getId() ?>">👁 Voir</a> |
                            <a href="index.php?ctrl=admin&action=deletePost&id=<?= $post->getId() ?>"
                                onclick="return confirm('Confirmer la suppression de ce post ?')">🗑 Supprimer</a> |


                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php?ctrl=admin&action=dashboard" class="retour">← Retour au tableau de bord</a>
    </div>
<?php else: ?>
    <p>Aucun post trouvé.</p>
<?php endif; ?>