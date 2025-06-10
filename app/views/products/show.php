<?php
$GLOBALS['view_js_files'] = [
    'assets/js/product-stock.js'
];
?>

<div class="reste">
    <div class="content">
        <?php if ($product): ?>
            <div id="Chaussure1" data-product-id="<?php echo $product['id']; ?>">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['nom']); ?>">
                
                <div id="nom_shoes"><?php echo htmlspecialchars($product['nom']); ?></div>
                
                <?php if (!empty($product['couleur'])): ?>
                    <div id="couleurs">
                        Couleur: <span id="cadre_couleur1"><?php echo htmlspecialchars($product['couleur']); ?></span>
                    </div>
                <?php endif; ?>
                
                <div id="dispo">
                    <?php if ($product['stock'] == 0): ?>
                        <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i>
                        Rupture de stock
                    <?php else: ?>
                        <i class="fas fa-check-circle" style="color: green; margin-right: 5px;"></i>
                        En stock
                    <?php endif; ?>
                </div>
                
                <div id="prix"><?php echo number_format($product['prix'], 2); ?>€</div>
                
                <div id="pointure_cadre">
                    Pointure: 
                    <select id="pointure" name="pointure">
                        <option value="">Choisir une taille</option>
                        <?php 
                        $sizes = ['38', '39', '40', '41', '42', '43', '44'];
                        foreach ($sizes as $size): 
                        ?>
                            <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button id="verif_pointure" type="button">Vérifier</button>
                    <span id="resultat_pointure"></span>
                </div>

                <div id="Panier">
                    <?php if ($product['stock'] > 0): ?>
                        <form method="post" action="?route=cart" id="add-to-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="size" id="selected_size" value="">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" id="add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i> Ajouter au panier
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled>
                            <i class="fas fa-times"></i> Produit indisponible
                        </button>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <p>Produit non trouvé.</p>
        <?php endif; ?>
    </div>
</div>

