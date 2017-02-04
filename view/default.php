<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= ( !empty( $title ) ? $title  : $site_title ) ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/css/bootstrap-theme.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url ?>/assets/css/search.css">

</head>
<body class="location-search">

    <?php include( 'default_head.php' ); ?>

    <?php include( $template ); ?>

    <?php include( 'default_foot.php' ); ?>

</body>
</html>