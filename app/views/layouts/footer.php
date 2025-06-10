<?php
require_once __DIR__ . '/../../controllers/LayoutController.php';
$footerData = LayoutController::getFooterData();
?>

<footer>
    <p>&copy; <?php echo $footerData['current_year']; ?> <?php echo $footerData['site_name']; ?> - Tous droits réservés.</p>
</footer>