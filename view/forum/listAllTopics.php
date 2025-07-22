<h1>Liste de tous les topics</h1>


<?php if (!empty($topics)) : ?>
    <?php foreach ($topics as $topic): ?>
        <p>
            <a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $topic->getId() ?>">
                <?= htmlspecialchars($topic->getTitle()) ?>
            </a>
            par <?= htmlspecialchars($topic->getUser()->getPseudo()) ?>
        </p>
    <?php endforeach; ?>
<?php else : ?>
    
    <p>Aucun topic pour le moment.</p>
<?php endif; ?>
<a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>
