<?php
$pageTitle = 'Votre Panier - SneakersAddict';
require_once __DIR__ . '/../../services/UrlService.php';
?>

<div class="reste">
    <div class="content">
        <div id="Panier_liste">
            <h2>Votre Panier</h2>
            
            <?php if(isset($_GET['confirmation']) && isset($_SESSION['message_commande'])): ?>
                <div class="message-confirmation">
                    <?php echo $_SESSION['message_commande']; ?>
                </div>
                <?php unset($_SESSION['message_commande']); ?>
            <?php endif; ?>
            
            <?php if(empty($panier)): ?>
                <div class="panier-vide">
                    <p>Votre panier est vide.</p>
                    <p><a href="<?php echo UrlService::route('products'); ?>">Continuer vos achats</a></p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Produit</th>
                            <th>Pointure</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($panier as $id_produit => $produit): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>"></td>
                                <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                                <td><?php echo htmlspecialchars($produit['pointure']); ?></td>
                                <td><?php echo htmlspecialchars($produit['prix']); ?> €</td>
                                <td>
                                    <form method="post" action="?route=cart" style="display:inline;">
                                        <input type="hidden" name="produit_id" value="<?php echo $id_produit; ?>">
                                        <input type="number" name="nouvelle_quantite" value="<?php echo $produit['quantite']; ?>" min="1" class="quantite-input">
                                        <button type="submit" name="edit" class="btn-action btn-update">Mettre à jour</button>
                                    </form>
                                </td>
                                <td><?php echo $produit['prix'] * $produit['quantite']; ?> €</td>
                                <td>
                                    <form method="post" action="?route=cart" style="display:inline;">
                                        <input type="hidden" name="produit_id" value="<?php echo $id_produit; ?>">
                                        <button type="submit" name="delete_product_cart" class="btn-action">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="panier-total">
                    Total : <?php echo number_format($total, 2, ',', ' '); ?> €
                </div>
                
                <div class="panier-actions">
                    <form method="post" action="?route=cart" style="display:inline;">
                        <button type="submit" name="vider" class="btn-action">Vider le panier</button>
                    </form>
                    <a href="?route=checkout" class="btn-action btn-payer">Procéder au paiement</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 