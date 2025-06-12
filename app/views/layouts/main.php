<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" href="assets/images/SNEAKERSADDICT.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/preprocessor/preprocesseur.css">
    <link href="assets/css/style_optimized.css" rel="stylesheet">
    
    <?php if (isset($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link href="<?php echo $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($GLOBALS['view_css_files'])): ?>
        <?php foreach ($GLOBALS['view_css_files'] as $css): ?>
            <link href="<?php echo $css; ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($GLOBALS['view_inline_styles'])): ?>
        <style>
            <?php echo $GLOBALS['view_inline_styles']; ?>
        </style>
    <?php endif; ?>

    <title><?php echo $pageTitle ?? 'SneakersAddict'; ?></title>
</head>

<body>
    <header role="banner">
        <?php include __DIR__ . '/header.php'; ?>
    </header>
    
    <main id="main-content" role="main">
      
        
        <section class="page-content">
            <?php 
            echo $content ?? '';
            ?>
        </section>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <script src="assets/js/core.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/script.js?v=<?php echo time(); ?>"></script>
    
    <?php if (isset($js_files)): ?>
        <?php foreach ($js_files as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($GLOBALS['view_js_files'])): ?>
        <?php foreach ($GLOBALS['view_js_files'] as $js): ?>
            <script src="<?php echo $js; ?>?v=<?php echo time(); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html> 