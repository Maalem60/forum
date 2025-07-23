<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump($_POST);
}
?>



<form method="POST">
    <input type="text" name="test" />
    <button type="submit">Envoyer</button>
</form>




