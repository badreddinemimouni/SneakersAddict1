<?php
$GLOBALS['view_css_files'] = [
    'assets/css/contact.css'
];

$pageTitle = 'Contact - SneakersAddict';
?>

<div class="container">
    <div class="reste">
        <div class="content">
            <h1 class="page-title">Contactez-nous</h1>
            
            <?php
            $message_statut = "";
            if (isset($_GET['success'])) {
                $message_statut = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> Votre message a été envoyé avec succès !</div>';
            } elseif (isset($_GET['error'])) {
                switch($_GET['error']) {
                    case 'csrf':
                        $message_statut = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Erreur de sécurité. Veuillez réessayer.</div>';
                        break;
                    case 'empty_fields':
                        $message_statut = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Tous les champs sont obligatoires.</div>';
                        break;
                    case 'invalid_email':
                        $message_statut = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Adresse email invalide.</div>';
                        break;
                    case 'save_failed':
                        $message_statut = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Erreur lors de l\'enregistrement du message.</div>';
                        break;
                    default:
                        $message_statut = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Une erreur est survenue.</div>';
                }
            }
            ?>
            
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Informations de contact</h3>
                    <p>Nous sommes là pour vous aider. N'hésitez pas à nous contacter pour toute question concernant nos produits ou services.</p>
                    
                    <div class="contact-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Rue des Sneakers, 75000 Paris</span>
                    </div>
                    
                    <div class="contact-info-item">
                        <i class="fas fa-phone"></i>
                        <span>+33 1 23 45 67 89</span>
                    </div>
                    
                    <div class="contact-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>badr_mim@outlook.fr</span>
                    </div>
                    
                    <div class="contact-info-item">
                        <i class="fas fa-clock"></i>
                        <span>Lun-Ven: 9h-18h | Sam: 10h-16h</span>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Envoyez-nous un message</h3>
                    
                    <div id="form-messages">
                        <?php echo $message_statut; ?>
                    </div>
                    
                    <form id="contact-form" method="post" action="traitement_contact.php">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="form-group">
                            <label for="nom">Nom complet</label>
                            <input type="text" id="nom" name="nom" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Adresse email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" name="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

