<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= ( !empty( $title ) ? $title  : $site_title ) ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/search.css">

    <?= $this->asset_retrieve( REQ_ASSET_CSS ); ?>
    <?= $this->asset_retrieve( REQ_ASSET_JS_GLOBAL ); ?>
    <?= $this->asset_retrieve( REQ_ASSET_JS ); ?>
</head>
<body class="location-search">

    <?php include( 'default_head.php' ); ?>

    <?php include( $template ); ?>

    <?php include( 'default_foot.php' ); ?>

</body>
</html>