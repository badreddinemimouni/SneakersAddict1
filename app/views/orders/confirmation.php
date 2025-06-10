<?php
$GLOBALS['view_css_files'] = [
    'assets/css/checkout.css',
    'assets/css/confirmation.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

require_once __DIR__ . '/../../services/UrlService.php';
?>

<div class="container">
    <div class="reste">
        <div class="content">
            <div class="confirmation-container">
                <div class="confirmation-header">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1>Commande confirmée !</h1>
                    <p class="confirmation-message">
                        Merci pour votre achat ! Votre commande a été traitée avec succès.
                    </p>
                </div>
                
                <div class="checkout-steps">
                    <div class="step completed">
                        <i class="fas fa-check"></i>
                        <span>1. Livraison</span>
                    </div>
                    <div class="step completed">
                        <i class="fas fa-check"></i>
                        <span>2. Paiement</span>
                    </div>
                    <div class="step completed">
                        <i class="fas fa-check-circle"></i>
                        <span>3. Confirmation</span>
                    </div>
                </div>
                
                <div class="confirmation-content">
                    <div class="order-details">
                        <div class="order-info-card">
                            <h3><i class="fas fa-receipt"></i> Détails de votre commande</h3>
                            <div class="order-info">
                                <div class="info-row">
                                    <span class="label">Numéro de commande:</span>
                                    <span class="value"><?php echo htmlspecialchars($order['number']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Date de commande:</span>
                                    <span class="value"><?php echo date('d/m/Y à H:i', strtotime($order['date'])); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Montant total:</span>
                                    <span class="value total-amount"><?php echo number_format($order['total'], 2); ?>€</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Statut:</span>
                                    <span class="value status-confirmed">
                                        <i class="fas fa-check"></i> Confirmée
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="shipping-info-card">
                            <h3><i class="fas fa-truck"></i> Adresse de livraison</h3>
                            <div class="shipping-address">
                                <p><strong><?php echo htmlspecialchars($order['shipping']['prenom'] . ' ' . $order['shipping']['nom']); ?></strong></p>
                                <p><?php echo htmlspecialchars($order['shipping']['adresse']); ?></p>
                                <p><?php echo htmlspecialchars($order['shipping']['code_postal']); ?> <?php echo htmlspecialchars($order['shipping']['ville']); ?></p>
                                <p>France</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <h3><i class="fas fa-shopping-bag"></i> Articles commandés</h3>
                        <div class="items-list">
                            <?php foreach ($order['products'] as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="assets/images/<?php echo htmlspecialchars(basename($item['image'])); ?>" 
                                             alt="<?php echo htmlspecialchars($item['nom']); ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo htmlspecialchars($item['nom']); ?></h4>
                                        <?php if (isset($item['couleur']) && !empty($item['couleur'])): ?>
                                            <p class="item-brand">Couleur: <?php echo htmlspecialchars($item['couleur']); ?></p>
                                        <?php endif; ?>
                                        <div class="item-specs">
                                            <span class="size">Taille: <?php echo htmlspecialchars($item['pointure']); ?></span>
                                            <span class="quantity">Quantité: <?php echo htmlspecialchars($item['quantite']); ?></span>
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        <span class="unit-price"><?php echo number_format($item['prix'], 2); ?>€</span>
                                        <span class="total-price"><?php echo number_format($item['prix'] * $item['quantite'], 2); ?>€</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-total">
                            <div class="total-breakdown">
                                <div class="total-line">
                                    <span>Sous-total:</span>
                                    <span><?php echo number_format($order['total'] - ($order['total'] >= 50 ? 0 : 5.99), 2); ?>€</span>
                                </div>
                                <div class="total-line">
                                    <span>Livraison:</span>
                                    <span><?php echo $order['total'] >= 50 ? 'Gratuite' : '5,99€'; ?></span>
                                </div>
                                <div class="total-line total-final">
                                    <strong>
                                        <span>Total payé:</span>
                                        <span><?php echo number_format($order['total'], 2); ?>€</span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="confirmation-actions">
                    <div class="action-buttons">
                        <a href="<?php echo UrlService::route('products'); ?>" class="btn-primary">
                            <i class="fas fa-shopping-cart"></i> Continuer mes achats
                        </a>
                        <button onclick="window.print()" class="btn-secondary">
                            <i class="fas fa-print"></i> Imprimer la facture
                        </button>
                    </div>
                </div>
                
                <div class="customer-service">
                    <h4><i class="fas fa-headset"></i> Besoin d'aide ?</h4>
                    <p>Notre service client est à votre disposition pour toute question concernant votre commande.</p>
                    <div class="contact-options">
                        <a href="<?php echo UrlService::route('contact'); ?>" class="contact-link">
                            <i class="fas fa-envelope"></i> Nous contacter
                        </a>
                        <a href="tel:+33123456789" class="contact-link">
                            <i class="fas fa-phone"></i> 01 23 45 67 89
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 