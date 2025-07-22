<h1>BIENVENUE SUR LE FORUM</h1>

<p>Ce forum est destiné à promouvoir les activités des développeurs web et à échanger sur différents thèmes.</p>

<?php if (!App\Session::getUser()): ?>
    <p>Pour participer aux discussions, <a href="index.php?ctrl=security&action=register">inscris-toi</a> ou <a href="index.php?ctrl=security&action=login">connecte-toi</a>.</p>
<?php else: ?>
    <p>Heureux de te revoir sur le forum ! Tu peux <a href="index.php?ctrl=forum&action=addTopic">créer un topic</a> ou explorer les <a href="index.php?ctrl=forum&action=listAllTopics">discussions en cours</a>.</p>
<?php endif; ?>
