<?php
$GLOBALS['view_css_files'] = [
    'assets/css/admin_users.css',
    'assets/css/admin_stock.css'
];

$GLOBALS['view_js_files'] = [
    'assets/js/admin-stock.js'
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
                <h1><i class="fas fa-boxes"></i> Gestion des Stocks</h1>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="stock-controls">
                <button class="btn-add" id="btn-add-product">
                    <i class="fas fa-plus"></i> Ajouter un produit
                </button>
                
                <div class="tabs">
                    <button class="tab active" data-view="grid">
                        <i class="fas fa-th-large"></i> Grille
                    </button>
                    <button class="tab" data-view="table">
                        <i class="fas fa-table"></i> Tableau
                    </button>
                </div>
            </div>
            
            <div class="tab-content active" id="grid-view">
                <div class="stock-grid">
                    <?php foreach ($produits as $produit): ?>
                        <div class="stock-card">
                            <div class="stock-card-image">
                                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($produit['image'] ?: 'assets/images/default.webp'); ?>" 
                                     alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                            </div>
                            
                            <div class="stock-card-content">
                                <h3 class="stock-card-title"><?php echo htmlspecialchars($produit['nom']); ?></h3>
                                <div class="stock-card-info">
                                    <span class="price"><?php echo number_format($produit['prix'], 2); ?>€</span>
                                    <?php if (!empty($produit['couleur'])): ?>
                                        <span class="color"><?php echo htmlspecialchars($produit['couleur']); ?></span>
                                    <?php endif; ?>
                                </div>
                            
                            <div class="stock-card-actions">
                                <form class="stock-form" method="post">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="action" value="update_stock">
                                    <input type="hidden" name="produit_id" value="<?php echo $produit['id']; ?>">
                                    
                                    <div class="form-group">
                                        <label>Pointure:</label>
                                        <select name="pointure_id" required>
                                            <option value="">Choisir une pointure</option>
                                            <?php foreach ($pointures as $pointure): ?>
                                                <option value="<?php echo $pointure['id']; ?>">
                                                    <?php echo $pointure['size']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Quantité:</label>
                                            <input type="number" name="quantite" min="0" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Prix (€):</label>
                                            <input type="number" name="prix" min="0" step="0.01" 
                                                   value="<?php echo $produit['prix']; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn-update">
                                        <i class="fas fa-save"></i> Mettre à jour
                                    </button>
                                </form>
                                
                                <form class="delete-form" method="post" data-confirm="Êtes-vous sûr de vouloir supprimer ce produit ?">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="action" value="delete_product">
                                    <input type="hidden" name="produit_id" value="<?php echo $produit['id']; ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Vue en tableau -->
            <div class="tab-content" id="table-view">
                <div class="table-responsive">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Couleur</th>
                                <th>Stocks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($produit['image'] ?: 'assets/images/default.webp'); ?>" 
                                             alt="<?php echo htmlspecialchars($produit['nom']); ?>" class="table-image">
                                    </td>
                                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                                    <td><?php echo number_format($produit['prix'], 2); ?>€</td>
                                    <td><?php echo htmlspecialchars($produit['couleur'] ?: '-'); ?></td>
                                    <td>
                                        <div class="stock-list">
                                            <?php if (!empty($produit['stocks'])): ?>
                                                <?php foreach ($produit['stocks'] as $stock): ?>
                                                    <div class="stock-item">
                                                        <span class="size-badge"><?php echo $stock['size']; ?></span>
                                                        <span class="amount-badge <?php echo $stock['amount'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                                            <?php echo $stock['amount']; ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="no-stock">Aucun stock</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <form method="post" style="display: inline;" data-confirm="Êtes-vous sûr de vouloir supprimer ce produit ?">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="produit_id" value="<?php echo $produit['id']; ?>">
                                            <button type="submit" class="btn-action">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add-product-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-plus"></i> Ajouter un nouveau produit</h2>
            <button class="close">&times;</button>
        </div>
        
        <form method="post" enctype="multipart/form-data" class="modal-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="action" value="add_product">
            
            <div class="form-group">
                <label for="nom"><i class="fas fa-tag"></i> Nom du produit</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="prix"><i class="fas fa-euro-sign"></i> Prix (€)</label>
                <input type="number" id="prix" name="prix" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="couleur"><i class="fas fa-palette"></i> Couleur</label>
                <input type="text" id="couleur" name="couleur">
            </div>
            
            <div class="form-group">
                <label for="image_file"><i class="fas fa-image"></i> Image du produit</label>
                <input type="file" id="image_file" name="image_file" accept="image/*">
                <small>Formats acceptés: JPG, PNG, WEBP. Taille max: 2 Mo</small>
            </div>
            
            <div class="modal-footer">
                <div class="actions">
                    <button type="button" class="btn-delete modal-cancel">Annuler</button>
                    <button type="submit" class="btn-update">
                        <i class="fas fa-save"></i> Ajouter le produit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 