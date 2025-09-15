<<<<<<< HEAD
<h2>Tableau de bord - Administration</h2>

<ul>
    <li><a href="index.php?ctrl=admin&action=listUsers">Gérer les utilisateurs</a></li>
    <li><a href="index.php?ctrl=admin&action=listTopics">Modérer les topics</a></li>
    <li><a href="index.php?ctrl=admin&action=settings">Paramètres du forum</a></li>
</ul>
=======
<h2>📊 Tableau de bord - Administration</h2>

<p>Bienvenue, <strong><?= \App\Session::getUser()->getPseudo() ?></strong> !</p>

<!-- Statistiques principales -->
<div class="topics-container">
    <div class="category-card">
        <h3>👤 Utilisateurs</h3>
        <p style="font-size: 1.5em;"><?= $nbUsers ?? 0 ?></p>
        <a href="index.php?ctrl=admin&action=listUsers">Gérer</a>
    </div>
    <div class="category-card">
        <h3>📌 Topics</h3>
        <p style="font-size: 1.5em;"><?= $nbTopics ?? 0 ?></p>
        <a href="index.php?ctrl=admin&action=listTopics">Modérer</a>
    </div>
    <div class="category-card">
        <h3>💬 Posts</h3>
        <p style="font-size: 1.5em;"><?= $nbPosts ?? 0 ?></p>
        <a href="index.php?ctrl=admin&action=listPosts">Modérer</a>
    </div>
</div>

<!-- Liens utiles -->
<div class="topics-container">
    <div class="category-card">
        <h3>⚙️ Paramètres rapides</h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li><a href="index.php?ctrl=admin&action=settings">Paramètres du forum</a></li>
            <li><a href="index.php?ctrl=security&action=logout">Se déconnecter</a></li>
        </ul>
    </div>
</div>
>>>>>>> 7d21baa (Ajout des dernières modification + mises à jour des entités et vues + suppression d'anciennes images)
