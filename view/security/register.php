<?php if ($error = App\Session::getFlash("error")): ?>
    <div class="flash-error"><?= $error ?></div>
<?php endif; ?>

<?php if ($success = App\Session::getFlash("success")): ?>
    <div class="flash-success"><?= $success ?></div>
<?php endif; ?>

<h2>Inscription</h2>

<form method="post" action="index.php?ctrl=security&action=register">
    <label>Nom d'utilisateur :
        <input type="text" name="pseudo" value="<?= $_POST['pseudo'] ?? '' ?>">
    </label><br>

    <label>Email :
        <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
    </label><br>

    <label>Mot de passe :
        <input type="password" name="password">
    </label><br>

    <label>Confirmation du mot de passe :
        <input type="password" name="password2">
    </label><br>

    <input type="submit" value="S'inscrire">
</form>
