<?php
$category = $result["data"]['category'] ?? null;
$topics = $result["data"]['topics'] ?? [];
?>

<h1>Liste des topics<?= $category ? " - " . htmlspecialchars($category->getName()) : "" ?></h1>

<?php if (!empty($topics)) : ?>
    <?php foreach($topics as $topic) :
        $user = $topic->getUser();
        $pseudo = $user ? $user->getPseudo() : "Utilisateur inconnu";
    ?>
        <p>
            <a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $topic->getId() ?>">
                <?= htmlspecialchars($topic->getTitle()) ?>
            </a>
            par <?= htmlspecialchars($pseudo) ?>
        </p>
    <?php endforeach; ?>
<?php else : ?>
    <p>Aucun topic pour cette catégorie</p>
    <?php endif; ?>