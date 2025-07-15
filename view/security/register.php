<h2>Inscription</h2>
<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post" action="index.php?ctrl=security&action=register">
    <label>Nom d'utilisateur : <input type="text" name="pseudo"></label><br>
    <label>Email : <input type="email" name="email"></label><br>
    <label>Mot de passe : <input type="password" name="password"></label><br>
    <input type="submit" value="S'inscrire">
</form>
