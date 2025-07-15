<!-- view/forum/addTopic.php -->
<h2>Créer un nouveau topic</h2>

<form method="post" action="index.php?ctrl=forum&action=saveTopic">
    <label for="title">Titre du topic :</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="category_id">Catégorie :</label><br>
    <select id="category_id" name="category_id" required>
        <!-- ici tu peux boucler sur les catégories en PHP -->
        <option value="1">Général</option>
        <option value="2">Technique</option>
        <!-- etc -->
    </select><br><br>

    <label for="content">Message :</label><br>
    <textarea id="content" name="content" required></textarea><br><br>

    <button type="submit">Créer</button>
</form>
