<?php
$topic = $result["data"]["topic"] ?? [];
$posts = $result["data"]["posts"] ?? [];


?>

<h2>Messages du topic : <?= htmlspecialchars($topic->getTitle()) ?></h2>

<?php if (empty($posts)) : ?>
    <p>Aucun message pour ce topic.</p>
<?php else : ?>
    <?php foreach ($posts as $post) : 
        $user = $post->getUser();
        $pseudo = $user ? $user->getPseudo() : "Anonyme";
        $date = (new DateTime($post->getCreationDate()))->format('d/m/Y H:i');
    ?>
    


        <div class="post">
            <div class="post-meta">
                <?= htmlspecialchars($pseudo) ?> 
                <strong><em>(<?= $date ?>)</em></strong>
            </div>
            <div class="post-content">
           <?= nl2br(htmlspecialchars($post->getContent())) ?>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>
    <?php endif; ?>
<?php if ($showForm ?? false): ?>
    <form method="post" action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>">
        <textarea name="content" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
<?php endif; ?>

<a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>
