<div class="reste">
    <div class="content">
        <h1 id="nos_produits">Nos Sneakers</h1>
        
        <div id="conteneur">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $index => $product): ?>
                    <div id="chaussure<?php echo $index + 1; ?>" 
                         data-color="<?php echo htmlspecialchars($product['couleur'] ?? ''); ?>"
                         data-price="<?php echo $product['prix']; ?>"
                         data-name="<?php echo htmlspecialchars($product['nom']); ?>">
                        
                        <a href="?route=product&id=<?php echo $product['id']; ?>">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['nom']); ?>"
                                 class="images">
                        </a>
                        
                        <div class="description">
                            <strong><?php echo htmlspecialchars($product['nom']); ?></strong><br>
                            <?php if (!empty($product['couleur'])): ?>
                                <em><?php echo htmlspecialchars($product['couleur']); ?></em><br>
                            <?php endif; ?>
                            <span style="color: #007bff; font-weight: bold;"><?php echo number_format($product['prix'], 2); ?>€</span>
                            
                            <?php 
                            $stock = $product['stock'] ?? 0;
                            if ($stock == 0): ?>
                                <br><small style="color: red;">Rupture de stock</small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 50px; color: #666;">
                    <h3>Aucun produit trouvé</h3>
                    <p>Aucune sneaker n'est disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>