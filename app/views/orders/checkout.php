<?php
$GLOBALS['view_css_files'] = ['assets/css/checkout.css'];

require_once __DIR__ . '/../../services/UrlService.php';
?>

<div class="reste">
    <div class="content">
        <div class="checkout-container">
            <h1>Finaliser votre commande</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="checkout-steps">
                <div class="step active">1. Livraison</div>
                <div class="step">2. Paiement</div>
                <div class="step">3. Confirmation</div>
            </div>
            
            <div class="checkout-summary">
                <h3>Récapitulatif de votre commande</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Pointure</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($panier as $produit): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                                <td><?php echo htmlspecialchars($produit['pointure']); ?></td>
                                <td><?php echo htmlspecialchars($produit['quantite']); ?></td>
                                <td><?php echo htmlspecialchars($produit['prix'] * $produit['quantite']); ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong><?php echo number_format($total, 2, ',', ' '); ?> €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="checkout-form">
                <h3>Informations de livraison</h3>
                <form method="post" action="<?php echo UrlService::route('checkout'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville</label>
                            <input type="text" id="ville" name="ville" required>
                        </div>
                        <div class="form-group">
                            <label for="code_postal">Code postal</label>
                            <input type="text" id="code_postal" name="code_postal" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="<?php echo UrlService::route('cart'); ?>" class="btn-secondary">Retour au panier</a>
                        <button type="submit" class="btn-primary">Continuer vers le paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 