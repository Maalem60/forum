<?php
// Récupération du topic et des posts depuis le tableau $result.
// Si aucune donnée n'est présente, on initialise par un tableau vide pour éviter les erreurs.
$topic = $result["data"]["topic"] ?? [];
$posts = $result["data"]["posts"] ?? [];
?>

<section class="forum-section">
    <div class="container">

        <!-- Affichage du titre du topic. Si le topic n'existe pas, affichage d'un texte par défaut. -->
        <h2 class="topic-title"><?= $topic ? htmlspecialchars($topic->getTitle()) : "Titre inconnu" ?></h2>

        <!-- Vérification s'il y a des messages à afficher -->
        <?php if (empty($posts)): ?>
            <p class="no-posts">Aucun message pour ce topic.</p>
        <?php else: ?>
            <div class="post-list">
                <!-- Boucle sur les posts du topic -->
                <?php foreach ($posts as $post):
                    $user = $post->getUser();
                    // Détermination du pseudo de l'utilisateur. Si non défini, affichage "Anonyme".
                    $pseudo = $user ? $user->getPseudo() : "Anonyme";
                    // Formatage de la date de création du post. Si non défini, chaîne vide.
                    $date = $post->getCreationDate()
                        ? $post->getCreationDate()->format('d/m/Y H:i')
                        : '';
                    ?>
                    <article class="post-card">
                        <header class="post-header">
                            <span class="post-user"><?= htmlspecialchars($pseudo) ?></span>
                            <time class="post-date"><?= $date ?></time>
                        </header>
                        <div class="post-body">
                            <!-- Affichage sécurisé du contenu du post avec gestion des sauts de ligne -->
                            <?= nl2br(htmlspecialchars($post->getContent() ?? '', ENT_QUOTES, 'UTF-8')) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    <form method="post" 
          action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" 
          class="post-form">

        <!-- Champ texte pour saisir un message -->
        <label for="content" class="form-label">Votre message :</label>
        <textarea id="content" name="content" required class="form-textarea"></textarea>

        <!-- OU si tu veux seulement une petite zone input -->
        <!--
        <input type="text" id="content" name="content" required class="form-input" />
        -->

        <!-- Bouton pour envoyer -->
        <button type="submit" class="btn">Envoyer</button>
    </form>


        <a href="javascript:history.back()" class="retour">← Retour à la page précédente</a>
        <!-- Formulaire d'ajout de message si $sho
