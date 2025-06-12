<?php
require_once __DIR__ . '/../../controllers/LayoutController.php';
require_once __DIR__ . '/../../services/UrlService.php';
$headerData = LayoutController::getHeaderData();
$panier = $headerData['panier'];
$auth = $headerData['auth'];
$nav = $headerData['navigation'];
?>

<div id="bandeau">
    <div class="header-content">
        <div class="logo-container">
            <a href="<?php echo UrlService::route('home'); ?>">
                <img src="assets/images/LOGO-TEST.png" class="logo" alt="logo">
            </a>
            <a href="<?php echo UrlService::route('home'); ?>">
                <h1 class="site-title">SneakersAddict</h1>
            </a>
        </div>
        
        <nav id="petit_boutons" role="navigation" aria-label="Menu principal">
            <a href="<?php echo UrlService::route('home'); ?>">Accueil</a>
            
            <?php if ($nav['show_products']): ?>
                <a href="<?php echo UrlService::route('products'); ?>">Produits</a>
                <a href="<?php echo UrlService::route('contact'); ?>">Contact</a>
            <?php endif; ?>
            
            <?php if ($nav['show_admin_links']): ?>
                <a href="<?php echo UrlService::route('admin_stock'); ?>">Stock</a>
                <a href="<?php echo UrlService::route('admin_users'); ?>">Utilisateurs</a>
            <?php endif; ?>
        </nav>
            
        <div class="header-right">
            <?php if ($nav['show_products']): ?>
                <div class="panier-wrapper">
                    <a href="javascript:void(0);" id="panier-icon">
                        <img src="assets/images/PANIER.png" alt="panier" id="petit_panier">
                        <?php if ($panier['nombre_articles'] > 0): ?>
                            <span class="panier-badge"><?php echo $panier['nombre_articles']; ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <div id="mini-panier">
                        <h3>Votre Panier</h3>
                        <?php if ($panier['is_empty']): ?>
                            <p class="panier-vide-message">Votre panier est vide</p>
                        <?php else: ?>
                            <ul class="mini-panier-items">
                                <?php foreach ($panier['items'] as $id_produit => $produit): ?>
                                    <li>
                                        <img src="<?php echo htmlspecialchars($produit['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                                        <div class="mini-panier-info">
                                            <p class="mini-panier-nom"><?php echo htmlspecialchars($produit['nom']); ?></p>
                                            <p class="mini-panier-details">
                                                Pointure: <?php echo htmlspecialchars($produit['pointure']); ?> | 
                                                Qté: <?php echo $produit['quantite']; ?> | 
                                                <?php echo $produit['prix'] * $produit['quantite']; ?> €
                                            </p>
                                        </div>
                                        <a href="<?php echo UrlService::route('cart_remove', ['id' => $id_produit]); ?>" 
                                           class="mini-panier-supprimer">×</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="mini-panier-total">
                                <p>Total: <?php echo number_format($panier['total_panier'], 2, ',', ' '); ?> €</p>
                            </div>
                            <div class="mini-panier-actions">
                                <a href="<?php echo UrlService::route('cart'); ?>" class="mini-panier-btn">Voir le panier</a>
                                <a href="<?php echo UrlService::route('checkout'); ?>" class="mini-panier-btn mini-panier-payer">Payer</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($auth['is_logged_in']): ?>
                    <a href="<?php echo UrlService::route('logout'); ?>" class="bouton bouton-right">Se déconnecter</a>
                <?php else: ?>
                    <a href="<?php echo UrlService::route('login'); ?>" class="bouton bouton-right">Se connecter</a>
                <?php endif; ?>
                
                <label for="burger-toggle" class="burger-menu" aria-label="Menu">
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                </label>
            </div>
        </div>
    </div>
</div>

<input type="checkbox" id="burger-toggle" aria-label="Toggle menu">

<div class="mobile-menu-overlay">
    <label for="burger-toggle" class="close-menu-overlay" aria-label="Fermer le menu"></label>
</div>
<div class="mobile-menu">
    <nav class="mobile-nav">
        <a href="<?php echo UrlService::route('cart'); ?>" class="mobile-panier">
            <div class="mobile-panier-info">
                <img src="assets/images/PANIER.png" alt="panier">
                <span>Panier</span>
            </div>
            <?php if ($panier['nombre_articles'] > 0): ?>
                <span class="mobile-panier-badge"><?php echo $panier['nombre_articles']; ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?php echo UrlService::route('home'); ?>">Accueil</a>
        
        <?php if ($nav['show_products']): ?>
            <a href="<?php echo UrlService::route('products'); ?>">Produits</a>
            <a href="<?php echo UrlService::route('contact'); ?>">Contact</a>
        <?php endif; ?>
        
        <?php if ($nav['show_admin_links']): ?>
            <a href="<?php echo UrlService::route('admin_stock'); ?>">Stock</a>
            <a href="<?php echo UrlService::route('admin_users'); ?>">Utilisateurs</a>
        <?php endif; ?>
        
        <div class="mobile-header-actions">
            <?php if ($auth['is_logged_in']): ?>
                <a href="<?php echo UrlService::route('logout'); ?>" class="bouton">Se déconnecter</a>
            <?php else: ?>
                <a href="<?php echo UrlService::route('login'); ?>" class="bouton">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>
</div> 