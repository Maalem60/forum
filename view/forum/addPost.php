<h3>Répondre au topic : <?= htmlspecialchars($topic->getTitre()) ?></h3>

<form action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" method="post">
    <label for="content">Votre message :</label><br>
    <textarea name="content" id="content" rows="6" required></textarea><br><br>

    <!-- Champ caché pour CSRF -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

    <input type="submit" value="Envoyer">
</form>
