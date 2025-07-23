<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?= $meta_description ?>" />
    <title>FORUM</title>
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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
            <?php if (\App\Session::getUser()): ?>
                <a href="index.php?ctrl=forum&action=addTopic">Créer un topic</a>
            <?php endif; ?>
            <?php if (App\Session::isAdmin()): ?>
                <a href="index.php?ctrl=home&action=users">Membres</a>
            <?php endif; ?>
        </div>

        <div class="nav-right">
       

            <?php if (App\Session::getUser()): ?>
                <a href="index.php?ctrl=security&action=profile">
                    <span class="fas fa-user"></span> <?= htmlspecialchars((string)(App\Session::getUser() ?? '')) ?>
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
    <p>&copy; <?= date('Y') ?> - <a href="#">Règlement du forum</a> - <a href="mentionsLegales.php">Mentions légales</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(function() {
        $(".message").each(function() {
            if ($(this).text().length > 0) {
                $(this).slideDown(500).delay(3000).slideUp(500);
            }
        });

        $(".delete-btn").click(function() {
            return confirm("Etes-vous sûr de vouloir supprimer?");
        });

        tinymce.init({
            selector: '.textarea[name="content"]',
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                     'bold italic backcolor | alignleft aligncenter ' +
                     'alignright alignjustify | bullist numlist outdent indent | ' +
                     'removeformat | help',
            content_css: '//www.tiny.cloud/css/codepen.min.css'
        });
    });
</script>

<script src="<?= PUBLIC_DIR ?>/js/script.js"></script>
</body>
</html>
