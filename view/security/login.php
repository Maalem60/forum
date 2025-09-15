<?php
// Récupération du token transmis par le contrôleur
$token = $result["data"]["token"] ?? "";
?>

<h2>Connexion</h2>

<form method="post" action="index.php?ctrl=security&action=login">
    <label>
        Nom d'utilisateur :
        <input type="text" name="pseudo" required>
    </label>
    <br><br>

    <label>
        Mot de passe :
        <input type="password" name="password" required>
    </label>
    <br><br>

    <!-- Token CSRF pour sécuriser le formulaire -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">

    <input type="submit" value="Se connecter">
</form>
