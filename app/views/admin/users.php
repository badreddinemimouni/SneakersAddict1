<?php
$GLOBALS['view_css_files'] = [
    'assets/css/admin_stock.css',
    'assets/css/admin_users.css'
];

$GLOBALS['view_js_files'] = [
    'assets/js/admin-users.js'
];

$GLOBALS['view_inline_styles'] = '
.reste {
    display: flex !important;
    flex-direction: row !important;
}
';
?>

<div class="container">
    <div class="reste">
        <div class="content">
            <div class="admin-header">
                <h1 class="admin-title">Gestion des Utilisateurs</h1>
                <div class="admin-actions">
                    <button class="btn-add" id="btn-add-user">
                        <i class="fas fa-plus"></i> Ajouter un utilisateur
                    </button>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="users-table-container">
                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td data-label="ID"><?php echo $user['id']; ?></td>
                                    <td data-label="Nom complet">
                                        <div class="user-info">
                                            <strong><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></strong>
                                        </div>
                                    </td>
                                    <td data-label="Email"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td data-label="Rôle">
                                        <span class="badge badge-<?php echo $user['role']; ?>">
                                            <i class="fas fa-<?php echo $user['role'] === 'admin' ? 'user-shield' : 'user'; ?>"></i>
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <form method="post" style="display: inline;" 
                                                  data-confirm="<?php echo $user['role'] === 'admin' ? 'Êtes-vous sûr de vouloir retirer les droits administrateur de cet utilisateur ?' : 'Êtes-vous sûr de vouloir promouvoir cet utilisateur comme administrateur ?'; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                <input type="hidden" name="action" value="toggle_role">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn-update" 
                                                        title="<?php echo $user['role'] === 'admin' ? 'Retirer les droits admin' : 'Promouvoir admin'; ?>">
                                                    <i class="fas fa-<?php echo $user['role'] === 'admin' ? 'user-minus' : 'user-plus'; ?>"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="post" style="display: inline;" 
                                                  data-confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" class="btn-delete" title="Supprimer l'utilisateur">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>Aucun utilisateur trouvé</h3>
                    <p>Il n'y a actuellement aucun utilisateur dans la base de données.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="add-user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-plus"></i> Ajouter un nouvel utilisateur</h2>
            <button class="close">&times;</button>
        </div>
        
        <form method="post" class="modal-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="action" value="add_user">
            
            <div class="form-group">
                <label for="prenom"><i class="fas fa-user"></i> Prénom</label>
                <input type="text" id="prenom" name="prenom" 
                       minlength="2" maxlength="50"
                       pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                       title="Prénom (2-50 caractères, lettres uniquement)"
                       placeholder="Entrez le prénom" 
                       autocomplete="given-name" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom</label>
                <input type="text" id="nom" name="nom" 
                       minlength="2" maxlength="50"
                       pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                       title="Nom de famille (2-50 caractères, lettres uniquement)"
                       placeholder="Entrez le nom" 
                       autocomplete="family-name" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" 
                       maxlength="100"
                       title="Adresse email valide (ex: nom@exemple.com)"
                       placeholder="exemple@email.com" 
                       autocomplete="email" 
                       required>
            </div>
            
            <div class="form-group password-toggle">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="password" name="password" 
                       minlength="6" maxlength="100"
                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{6,}"
                       title="Minimum 6 caractères avec au moins une majuscule, une minuscule et un chiffre"
                       placeholder="Mot de passe sécurisé"
                       autocomplete="new-password" 
                       required>
                <button type="button" class="toggle-password" aria-label="Afficher/Masquer le mot de passe">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            
            <div class="form-group password-toggle">
                <label for="confirm_password"><i class="fas fa-lock"></i> Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       minlength="6" maxlength="100"
                       title="Doit être identique au mot de passe"
                       placeholder="Confirmez le mot de passe"
                       autocomplete="new-password" 
                       required>
                <button type="button" class="toggle-password" aria-label="Afficher/Masquer le mot de passe">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            
            <div class="form-group">
                <label for="grade"><i class="fas fa-user-shield"></i> Rôle</label>
                <select id="grade" name="grade" required>
                    <option value="client">Client</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn-delete modal-cancel">Annuler</button>
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Ajouter l'utilisateur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 