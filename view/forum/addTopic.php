<!-- view/forum/addTopic.php -->
<h2>Créer un nouveau topic</h2>
<?php $categories = $result["data"]["categories"]; ?>

<form action="index.php?ctrl=forum&action=saveTopic" method="post">
  <label for="title">Titre du topic :</label><br>
  <input type="text" id="title" name="title" required><br><br>
    <label for="category_id">Catégorie :</label><br>
    <select id="category_id" name="category_id" required>
        <?php foreach($categories as $category): ?>
            <option value="<?= $category->getId() ?>">
                <?= $category->getName() ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>
    <label for="content">Message :</label><br>
    <!--<textarea id="content" name="content" class="tinymce" required></textarea>-->
 <textarea id="content" name="content" ></textarea>
    <button type="submit" name="submit">Créer le Topic</button>    
</form>
