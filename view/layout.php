<?php
use App\Session;
$user = Session::getUser();  // <- ici la variable $user est définie. Ainsi, $user est partout sans rappeler Session::getUser()  fois.Mais est-ce une bonne pratique MVC ?
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= $meta_description ?>" />
    <title>FORUM</title>
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js"
        referrerpolicy="origin" defer></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" defer></script>
    <script src="<?= PUBLIC_DIR ?>/js/script.js"></script>

</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">

                <img src="public/img/Logo2Forum.jpg" alt="Logo Forum" class="logo-img">
                <a href="index.php?ctrl=home&action=index">Web Forum</a>
            </div>



            <div class="nav-center">
                <a href="index.php?ctrl=forum&action=index">Catégories</a>
                <a href="index.php?ctrl=forum&action=listAllTopics">Tous les topics</a>

                <?php if ($user): ?>
                    <a href="index.php?ctrl=forum&action=addTopic">Créer un topic</a>

                    <a href="index.php?ctrl=security&action=profile">Membre</a>
                <?php endif; ?>

                <?php if ($user && $user->getRole() === "admin"): ?>
                    <!-- Afficher les options d'administration -->
                    <a href="index.php?ctrl=forum&action=adminPanel">Administration</a>
                <?php endif; ?>
            </div>


            <div class="nav-right">

                <?php if (App\Session::getUser()): ?>                    
                    <a href="index.php?ctrl=security&action=profile">
                        <span class="fas fa-user"></span> <?= htmlspecialchars((string) (App\Session::getUser() ?? '')) ?>
                    </a>
                    <a href="index.php?ctrl=security&action=logout">Déconnexion</a>
                <?php else: ?>
                    <a href="index.php?ctrl=security&action=login">Connexion</a>
                    <a href="index.php?ctrl=security&action=register">Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main id="forum">
        <?php if ($error = App\Session::getFlash("error")): ?>
            <h3 class="message error"><?= htmlspecialchars($error) ?></h3>
        <?php endif; ?>
        <?php if ($success = App\Session::getFlash("success")): ?>
            <h3 class="message success"><?= htmlspecialchars($success) ?></h3>
        <?php endif; ?>

        <?= $page ?>
    </main>

    <footer>
    <div class="footer-content">
        <div class="footer-left">
            <img src="public/img/Logo2Forum.jpg" alt="Logo Forum Footer" class="footer-logo-img">
            <a href="index.php?ctrl=home&action=index" class="footer-title">Web Forum</a>
        </div>
        <div class="footer-right">
            <p>&copy; <?= date('Y') ?> - <a href="politique_confidentialité.html">politique de confidentialité</a> - <a href="mentionsLegales.php">Mentions légales</a></p>
        </div>
    </div>
</footer>




</body>

</html>