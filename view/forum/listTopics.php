<?php
$category = $result["data"]['category'] ?? null;
$topics = $result["data"]['topics'] ?? [];
?>

<h1>Liste des topics<?= $category ? " - " . htmlspecialchars($category->getName()) : "" ?></h1>

<section class="topics-container">
    <?php if (!empty($topics)) : ?>
        <?php foreach($topics as $topic) :
            $user = $topic->getUser();
            $pseudo = $user ? $user->getPseudo() : "Utilisateur inconnu";
        ?>
            <article class="category-card">
                <h3>
                    <a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $topic->getId() ?>">
                        <?= htmlspecialchars($topic->getTitle()) ?>
                    </a>
                </h3>
                <p>Créé par <strong><?= htmlspecialchars($pseudo) ?></strong></p>
            </article>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun topic pour cette catégorie</p>
    <?php endif; ?>
</section>

<?php if(App\Session::getUser()) : ?>
    <a href="index.php?ctrl=forum&action=addTopic" class="btn-create-topic">➕ Créer un nouveau topic</a>
<?php endif; ?>

<a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>


 