<?php
global $gEnvironment, $gPZ;


$gPZ['environment']         = $gEnvironment;
$gPZ['app_name']            = 'location search';
$gPZ['app_slogan']          = 'test app';
$gPZ['app_dir']             = '';
$gPZ['inquiries_email']     = 'robbo@rmw.technology';
$gPZ['default_controller']  = 'search';


switch ( $gPZ['environment'] ) {
    case ENV_LOC:
        $gPZ['doc_root']          = 'C:/Users/Rob/Documents/GitHub/locsearch/';

        $gPZ['base_url']          = 'http://locsearch.loc';
        $gPZ['base_url_ssl']      = 'http://locsearch.loc';
        $gPZ['suppress_ads']      = false;
        $gPZ['ssl_environment']   = false;
        error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE ^ E_STRICT );
        break;

    case ENV_PROD:
        $gPZ['doc_root']          = 'C:/Users/Rob/Documents/GitHub/locsearch/';

        $gPZ['base_url']          = 'http://locsearch.loc';
        $gPZ['base_url_ssl']      = 'http://locsearch.loc';
        $gPZ['suppress_ads']      = false;
        $gPZ['ssl_environment']   = false;
        error_reporting( 0 );
        break;

}


define( 'BLANK_DATE', '0000-00-00 00:00:00' );

?>