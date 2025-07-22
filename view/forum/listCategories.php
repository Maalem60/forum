<?php
    $categories = $result["data"]['categories']; 
?>

<h1>Liste des catégories</h1>

<section class="categories-list">
    <?php foreach($categories as $category): ?>
        <article class="category-card">
            <h3>
                <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>">
                    <?= htmlspecialchars($category->getName()) ?>
                </a>
            </h3>
            <p>Découvrez les discussions autour de <?= htmlspecialchars($category->getName()) ?>.</p>
        </article>
  
    <?php endforeach; ?>
    <a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>
  </section>
  