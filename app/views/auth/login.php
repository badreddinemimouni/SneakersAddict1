<div class="reste">
    <div class="content">
        <div class="login-container">
            <div class="login-header">
                <h2>Bienvenue sur SneakersAddict</h2>
                <p>Connectez-vous pour accéder à votre compte</p>
            </div>
            
            <div class="login-tabs">
                <button type="button" class="login-tab active" id="login-tab-btn">Connexion</button>
                <button type="button" class="login-tab" id="register-tab-btn">Inscription</button>
            </div>
            
            <?php if(!empty($error_message)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success_message)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <div id="login-form-container" class="active">
                <form action="?route=login" method="POST" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               maxlength="100"
                               title="Adresse email valide"
                               placeholder="votre@email.com"
                               autocomplete="username"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="password-field">
                            <input type="password" id="password" name="mdp" class="form-control" 
                                   title="Votre mot de passe"
                                   placeholder="Mot de passe"
                                   autocomplete="current-password"
                                   required>
                            <i class="toggle-password fas fa-eye" id="toggle-password"></i>
                        </div>
                    </div>
                    
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    
                    <button type="submit" name="su" class="btn-submit">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                    
                    <div class="login-footer">
                        <a href="#" id="forgot-password">Mot de passe oublié ?</a>
                    </div>
                </form>
            </div>
            
            <div id="register-form-container">
                <form action="?route=login" method="POST" class="login-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-group">
                        <label for="register-name">Nom</label>
                        <input type="text" id="register-name" name="nom" class="form-control" 
                               minlength="2" maxlength="50"
                               pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                               title="Nom de famille (2-50 caractères, lettres uniquement)"
                               placeholder="Votre nom"
                               autocomplete="family-name"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-prenom">Prénom</label>
                        <input type="text" id="register-prenom" name="prenom" class="form-control" 
                               minlength="2" maxlength="50"
                               pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                               title="Prénom (2-50 caractères, lettres uniquement)"
                               placeholder="Votre prénom"
                               autocomplete="given-name"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-email">Adresse email</label>
                        <input type="email" id="register-email" name="email" class="form-control" 
                               maxlength="100"
                               title="Adresse email valide"
                               placeholder="votre@email.com"
                               autocomplete="username"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-password">Mot de passe</label>
                        <div class="password-field">
                            <input type="password" id="register-password" name="mdp" class="form-control" 
                                   minlength="6" maxlength="100"
                                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]{6,}"
                                   title="Minimum 6 caractères avec au moins une majuscule, une minuscule et un chiffre"
                                   placeholder="Mot de passe sécurisé"
                                   autocomplete="new-password"
                                   required>
                            <i class="toggle-password fas fa-eye" id="toggle-register-password"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="register-confirm">Confirmer le mot de passe</label>
                        <div class="password-field">
                            <input type="password" id="register-confirm" name="confirm_mdp" class="form-control" 
                                   minlength="6" maxlength="100"
                                   title="Doit être identique au mot de passe"
                                   placeholder="Confirmez le mot de passe"
                                   autocomplete="new-password"
                                   required>
                            <i class="toggle-password fas fa-eye" id="toggle-confirm-password"></i>
                        </div>
                    </div>
                    
                    <button type="submit" name="register" class="btn-submit">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 