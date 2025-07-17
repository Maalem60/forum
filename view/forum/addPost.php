<!-- view/forum/addPost.php -->
<h3>Répondre au topic : <?= $topic->getTitre() ?></h3>

<form action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" method="post">
    <label for="contenu">Votre message :</label><br>
    <textarea name="contenu" id="contenu" rows="6" required></textarea><br><br>
    <input type="submit" value="Envoyer">
</form>
