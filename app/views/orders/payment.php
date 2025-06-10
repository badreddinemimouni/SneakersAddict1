<?php
$GLOBALS['view_css_files'] = [
    'assets/css/checkout.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

require_once __DIR__ . '/../../services/UrlService.php';
?>

<div class="container">
    <div class="reste">
        <div class="content">
            <div class="checkout-container">
                <h1><i class="fas fa-credit-card"></i> Paiement sécurisé</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="checkout-steps">
                    <div class="step completed">
                        <i class="fas fa-check"></i>
                        <span>1. Livraison</span>
                    </div>
                    <div class="step active">
                        <i class="fas fa-credit-card"></i>
                        <span>2. Paiement</span>
                    </div>
                    <div class="step">
                        <i class="fas fa-check-circle"></i>
                        <span>3. Confirmation</span>
                    </div>
                </div>
                
                <div class="checkout-content">
                    <div class="checkout-main">
                        <div class="checkout-form">
                            <h3><i class="fas fa-lock"></i> Informations de paiement</h3>
                            
                            <div class="security-notice">
                                <i class="fas fa-shield-alt"></i>
                                <span>Vos informations de paiement sont sécurisées et cryptées</span>
                            </div>
                            
                            <div class="card-icons">
                                <img src="assets/images/visa.png" alt="Visa" width="40">
                                <img src="assets/images/mastercard.png" alt="Mastercard" width="40">
                                <img src="assets/images/amex.png" alt="American Express" width="40">
                            </div>
                            
                            <form method="post" action="<?php echo UrlService::route('payment'); ?>" id="payment-form">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="action" value="payer">
                                
                                <div class="form-group">
                                    <label for="card_number"><i class="fas fa-credit-card"></i> Numéro de carte</label>
                                    <input type="text" id="card_number" name="card_number" class="form-control" 
                                           placeholder="1234 5678 9012 3456" 
                                           pattern="[0-9\s]{13,19}" 
                                           maxlength="19" 
                                           inputmode="numeric"
                                           title="Entrez un numéro de carte valide (13-16 chiffres)"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="card_name"><i class="fas fa-user"></i> Nom sur la carte</label>
                                    <input type="text" id="card_name" name="card_name" class="form-control" 
                                           placeholder="JEAN DUPONT" 
                                           pattern="[A-Za-z\s]{2,50}"
                                           minlength="2"
                                           maxlength="50"
                                           title="Nom complet tel qu'il apparaît sur la carte (lettres et espaces uniquement)"
                                           required>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="card_expiry"><i class="fas fa-calendar"></i> Date d'expiration</label>
                                        <input type="text" id="card_expiry" name="card_expiry" class="form-control" 
                                               placeholder="MM/AA" 
                                               pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
                                               maxlength="5" 
                                               inputmode="numeric"
                                               title="Format MM/AA (ex: 12/28)"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="card_cvv"><i class="fas fa-lock"></i> Code CVV</label>
                                        <input type="text" id="card_cvv" name="card_cvv" class="form-control" 
                                               placeholder="123" 
                                               pattern="[0-9]{3,4}"
                                               minlength="3"
                                               maxlength="4" 
                                               inputmode="numeric"
                                               title="Code de sécurité à 3 ou 4 chiffres au dos de votre carte"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="payment-terms">
                                    <label class="checkbox-container">
                                        <input type="checkbox" required>
                                        <span class="checkmark"></span>
                                        J'accepte les <a href="#" target="_blank">conditions générales de vente</a>
                                    </label>
                                </div>
                                
                                <div class="form-actions">
                                    <a href="<?php echo UrlService::route('checkout'); ?>" class="btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Retour
                                    </a>
                                    <button type="submit" class="btn-primary btn-pay">
                                        <i class="fas fa-lock"></i> Payer <?php echo number_format($total, 2); ?>€
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="checkout-sidebar">
                        <div class="checkout-summary">
                            <h3><i class="fas fa-receipt"></i> Récapitulatif de votre commande</h3>
                            <div class="summary-items">
                                <?php foreach ($panier as $produit): ?>
                                    <div class="summary-item">
                                        <div class="item-info">
                                            <h4><?php echo htmlspecialchars($produit['nom']); ?></h4>
                                            <p>Taille: <?php echo htmlspecialchars($produit['pointure']); ?></p>
                                            <p>Quantité: <?php echo htmlspecialchars($produit['quantite']); ?></p>
                                        </div>
                                        <div class="item-price">
                                            <?php echo number_format($produit['prix'] * $produit['quantite'], 2); ?>€
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="summary-total">
                                <div class="total-line">
                                    <span>Sous-total:</span>
                                    <span><?php echo number_format($total, 2); ?>€</span>
                                </div>
                                <div class="total-line">
                                    <span>Livraison:</span>
                                    <span><?php echo $total >= 50 ? 'Gratuite' : '5,99€'; ?></span>
                                </div>
                                <div class="total-line total-final">
                                    <strong>
                                        <span>Total:</span>
                                        <span><?php echo number_format($total >= 50 ? $total : $total + 5.99, 2); ?>€</span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="security-badges">
                            <h4><i class="fas fa-shield-alt"></i> Paiement sécurisé</h4>
                            <div class="badges">
                                <div class="badge">
                                    <i class="fas fa-lock"></i>
                                    <span>SSL 256-bit</span>
                                </div>
                                <div class="badge">
                                    <i class="fas fa-shield-check"></i>
                                    <span>Données protégées</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript déplacé vers assets/js/card-payment.js --> 