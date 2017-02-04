<?php

/**
 * Controller for default location search app
 */

define( 'DEFAULT_RADIUS', 100 );

define( 'VIEW_HTML', 1 );
define( 'VIEW_JSON', 2 );


DEFINE( 'BOUND_LAT_MAX', 49.3457868 );
DEFINE( 'BOUND_LAT_MIN', 24.7433195 );
DEFINE( 'BOUND_LON_MAX', -66.9513812 );
DEFINE( 'BOUND_LON_MIN', -124.7844079 );
DEFINE( 'BOUND_RADIUS_MIN', 10 );
DEFINE( 'BOUND_RADIUS_MAX', 100 );

DEFINE( 'RESULTS_MAX', 20 );


class Search extends My_Controller {

    private $lat;
    private $lon;
    private $radius;
    private $criteria;
    private $view_type;
    private $criteria_list;
    private $view_list;

    function __construct( $request ) {
        parent::__construct( $request );

        $this->model( "locations" );

        $this->criteria_list = $this->locations->criteria_list;
        $this->view_list = array(
            VIEW_HTML => 'HTML',
            VIEW_JSON => 'JSON'
        );


    }


    public function index( $lat = 0, $lon = 0, $criteria = CRITERIA_MOST_POPULATED, $radius = DEFAULT_RADIUS, $view_type = VIEW_HTML ) {

        $this->lat       = $lat;
        $this->lon       = $lon;
        $this->criteria  = $criteria;
        $this->radius    = $radius;
        $this->view_type = $view_type;

        if ( $view_type == VIEW_HTML ) {
            $this->show_html( );
        } else {
            $this->show_json( );
        }
        $this->set_mode( ( $this->view_type == VIEW_JSON ) ? MODE_BARE : MODE_FRONT );
    }

    /**
     * Make sure the lat and lon provided represent a point within the US
     *
     * @return bool
     */
    private function check_bounds( ) {
        return ( ( $this->lat >= BOUND_LAT_MIN ) && ( $this->lat <= BOUND_LAT_MAX ) &&
                 ( $this->lon >= BOUND_LON_MIN ) && ( $this->lon <= BOUND_LAT_MIN ) &&
                 ( $this->radius >= BOUND_RADIUS_MIN ) && ( $this->radius <= BOUND_RADIUS_MAX ) );
    }


    /**
     * Display the HTML view of the app, which could be a query form or query results...
     */
    public function show_html( ) {

        // Set our controller to front page mode
        $this->set_mode( MODE_FRONT );

        // If they provided boundaries...
        if ( $this->check_bounds() ) {

            // ...show the query results within those boundaries.
            $this->show_html_results();
        } else {

            //...otherwise, show the query form
            $this->show_html_form();
        }
    }


    /**
     * Show the basic search form for HTML presentation
     *
     * @return string
     */
    public function show_html_form( ) {

        // Pass the criteria as data to the form
        $this->data( 'lat',       $this->lat );
        $this->data( 'lon',       $this->lon );
        $this->data( 'criteria',  $this->criteria );
        $this->data( 'radius',    $this->radius );
        $this->data( 'view_type', $this->view_type );

        // Pass the selection options to the form
        $this->data( 'criteria_list', $this->criteria_list );
        $this->data( 'view_list',     $this->view_list );

        $this->data( 'template',    'form.php' );

        return( $this->view(  ) );
    }


    /**
     * Show the basic search form for HTML presentation
     *
     * @return string
     */
    public function show_html_results( ) {

        // Pass the criteria as data to the report
        $this->data( 'lat',       $this->lat );
        $this->data( 'lon',       $this->lon );
        $this->data( 'criteria',  $this->criteria );
        $this->data( 'radius',    $this->radius );
        $this->data( 'view_type', $this->view_type );

        // Pass the selection labels to the report
        $this->data( 'criteria_label', $this->criteria_list[$this->criteria] );
        $this->data( 'view_label',     $this->view_list[$this->view_type] );

        // Get the search results and pass them to the report
        $search_results = $this->locations->query( $this->lat, $this->lon, $this->radius, $this->criteria );
        $this->data( 'results',     $search_results );
        $this->data( 'results_max', RESULTS_MAX );
        $this->data( 'error_code',     $this->locations->error_code );
        $this->data( 'error_message',  $this->locations->error_messages[$this->locations->error_code] );

        $this->data( 'template',    'results.php' );

        return( $this->view(  ) );
    }


    public function show_json( ) {

        // We're not using a fancy HTML view, just returning a bare JSON result
        $this->set_mode( MODE_BARE );

        // search the locations
        $search_results = $this->locations->query( $this->lat, $this->lon, $this->radius, $this->criteria );

        // Create the results package
        $result_package = array( );

        // Bundle up the result codes
        $result_package['result_codes'] = array(
            'code'    => $this->locations->error_code,
            'message' => $this->locations->error_messages[$this->locations->error_code]
        );

        // Echo back the search criteria
        $result_package['query'] = array(
            'lat'       => $this->lat,
            'lon'       => $this->lon,
            'criteria'  => $this->criteria,
            'radius'    => $this->radius,
            'view_type' => $this->view_type,
            'url'       => $this->gPZ['base_url'] . "/search/" . $this->lat . "/" . $this->lon . "/" .
                           $this->criteria . "/" . $this->radius . "/" . $this->view_type
        );

        // Now give them the list of locations
        $result_package['locations'] = array_slice( $search_results, 0, RESULTS_MAX );

        $this->data( 'json_data',  json_encode( $result_package ) );
        $this->data( 'template',   'bare_json.php' );

        return( $this->view(  ) );
    }




}
