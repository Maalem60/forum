<?php
$topic = $result["data"]["topic"] ?? [];
$posts = $result["data"]["posts"] ?? [];
?>

<section class="forum-section">
    <div class="container">
        <h2 class="topic-title"><?= htmlspecialchars($topic->getTitle()) ?></h2>

        <?php if (empty($posts)) : ?>
            <p class="no-posts">Aucun message pour ce topic.</p>
        <?php else : ?>
            <div class="post-list">
                <?php foreach ($posts as $post) :
                    $user = $post->getUser();
                    $pseudo = $user ? $user->getPseudo() : "Anonyme";
                    $date = (new DateTime($post->getCreationDate()))->format('d/m/Y H:i');
                ?>
                    <article class="post-card">
                        <header class="post-header">
                            <span class="post-user"><?= htmlspecialchars($pseudo) ?></span>
                            <time class="post-date"><?= $date ?></time>
                        </header>
                        <div class="post-body">
                            <?= nl2br(htmlspecialchars($post->getContent())) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($showForm ?? false): ?>
            <form method="post" action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" class="post-form">
                <label for="content" class="form-label">Votre message :</label>
                <textarea id="content" name="content" required class="form-textarea"></textarea>
                <button type="submit" class="btn">Envoyer</button>
            </form>
        <?php endif; ?>

       
    </div>
     <a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>
</section>
