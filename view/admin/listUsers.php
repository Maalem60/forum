<h2>Gestion des utilisateurs</h2>

<?php if (!empty($users)): ?>
    <table>
        <thead>
            <tr>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
           <tr>
    <td><?= htmlspecialchars($user->getPseudo()) ?></td>
    <td><?= htmlspecialchars($user->getEmail()) ?></td>
    <td><?= htmlspecialchars($user->getRole()) ?></td>
    <td>
        <?php if ($user->getRole() !== "ROLE_ADMIN"): ?>
            <a href="index.php?ctrl=admin&action=banUser&id=<?= $user->getId() ?>"
               onclick="return confirm('Voulez-vous vraiment bannir cet utilisateur ?');">
               Bannir
            </a>
            |
            <form style="display:inline;" method="get" action="index.php">
                <input type="hidden" name="ctrl" value="admin">
                <input type="hidden" name="action" value="changeRole">
                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                <select name="role" onchange="this.form.submit()">
                    <option value="ROLE_USER" <?= $user->getRole() === "ROLE_USER" ? 'selected' : '' ?>>Membre</option>
                    <option value="ROLE_MODERATOR" <?= $user->getRole() === "ROLE_MODERATOR" ? 'selected' : '' ?>>Modérateur</option>
                </select>
            </form>
        <?php else: ?>
            <em>Admin</em>
        <?php endif; ?>
    </td>
</tr>
 
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>
