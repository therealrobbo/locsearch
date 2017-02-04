<?php
/**
 * Model for location table data
 */

define( 'CRITERIA_MOST_POPULATED',   1 );
define( 'CRITERIA_LEAST_POPULATED',  2 );
define( 'CRITERIA_CLOSEST',          3 );
define( 'CRITERIA_FARTHEST',         4 );

define( 'LOCATION_ERROR_NONE',         0 );
define( 'LOCATION_ERROR_FILE',       100 );
define( 'LOCATION_ERROR_NO_DATA',    101 );
define( 'LOCATION_ERROR_NO_RESULTS', 102 );

define( 'IDX_CITY',     0 );
define( 'IDX_STATE',    1 );
define( 'IDX_POP',      2 );
define( 'IDX_LAT',      3 );
define( 'IDX_LON',      4 );
define( 'IDX_DISTANCE', 5 );

DEFINE( 'EARTH_RADIUS', 3956 );



class Locations extends PZ_Model {

    private $location_file  = 'assets/data/US.txt';
    private $location_table = null;
    private $selected_table = null;
    private $criteria;

    public $criteria_list;
    public $error_code;
    public $error_messages;


    function __construct(  ) {
        parent::__construct(  );

        $this->criteria_list = array(
            CRITERIA_MOST_POPULATED  => 'Most Populated',
            CRITERIA_LEAST_POPULATED => 'Least Populated',
            CRITERIA_CLOSEST         => 'Closest',
            CRITERIA_FARTHEST        => 'Farthest'
        );

        $this->error_messages = array(
            LOCATION_ERROR_NONE       => 'Success!',
            LOCATION_ERROR_FILE       => 'Unable to read the location data file',
            LOCATION_ERROR_NO_DATA    => 'The data file contains no recognizable data',
            LOCATION_ERROR_NO_RESULTS => 'No results matched the search criteria'
        );
    }

    public function query( $lat, $lon, $radius, $criteria ) {

        // Read the location data file
        if ( $this->read() ) {

            // Do we have a valid location table?
            if ( !is_null( $this->location_table ) && is_array( $this->location_table ) && ( count( $this->location_table ) > 0 ) ) {

                // Calculate the distances between the specified lat/lon pair and the records on the table
                $this->calc_distance( $lat, $lon );

                // Select records that are within the specified radius
                $this->select_by_radius( $radius );
                if ( !empty( $this->selected_table ) ) {

                    // Now sort the location records by the specified criteria
                    $this->sort_selected( $criteria );

                    $this->error_code = LOCATION_ERROR_NONE;

                } else {
                    $this->error_code  = LOCATION_ERROR_NO_RESULTS;
                }
            } else {
                $this->error_code = LOCATION_ERROR_NO_DATA;
            }

        } else {
            $this->error_code = LOCATION_ERROR_FILE;
        }

        return( $this->selected_table );
    }


    /**
     * Read the location table into memory
     *
     * @return bool
     */
    private function read( ) {

        // Assume error
        $return_val = false;

        // Open the data file for reading
        $handle = @fopen( $this->gPZ['doc_root'] . $this->location_file, "r" );
        if ( $handle ) {

            // Open was successful, read the file line by line
            $line_count = 0;
            $this->location_table = array();
            while ( ( $line_data = fgets( $handle, 4096 ) ) !== false ) {

                // Skip the first line which is headers
                if ( $line_count > 0) {

                    // Burst the line into an array and store it in the master table
                    $location_data = explode( "\t", $line_data );
                    $this->location_table[] = array(
                        'city'          => $location_data[IDX_CITY],
                        'state'         => $location_data[IDX_STATE],
                        'population'    => $location_data[IDX_POP],
                        'lat'           => $location_data[IDX_LAT],
                        'lon'           => $location_data[IDX_LON],
                    );
                }
                $line_count++;
            }

            // Close the file
            fclose( $handle );

            $return_val = true;
        }

        return( $return_val );
    }

    /**
     * Convert degrees to radians
     *
     * @param $deg
     * @return float
     */
    private function deg_to_rad( $deg ) {

        return( $deg * M_PI/180.0 );
    }


    /**
     * Calculate the distance between our our location data set and the specified lat/long pair
     *
     * @param $lat
     * @param $lon
     */
    private function calc_distance( $lat, $lon ) {

        // Covert the specified lat and lon to radians
        $lat1 = $this->deg_to_rad( $lat );
        $lon1 = $this->deg_to_rad( $lon );

        // Loop through the location table
        foreach( $this->location_table as $index => $location_rec ) {

            // Convert the lat and lon of the current location record to radians
            $lat2 = $this->deg_to_rad( $location_rec['lat'] );
            $lon2 = $this->deg_to_rad( $location_rec['lon'] );

            // Find the deltas
            $delta_lat = $lat2 - $lat1;
            $delta_lon = ( $lon2 * -1 ) + ( $lon1 );

            // Find the Great Circle distance
            $temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);

            // Save the distance between the two lat/lon pairs in the location record
            $location_rec['distance'] = EARTH_RADIUS * 2 * atan2(sqrt($temp),sqrt(1-$temp));

            // Put the updated location record back on the list
            $this->location_table[$index] = $location_rec;
        }
    }


    /**
     * Select records from the location table that are within a specified radius.
     *
     * @param $radius
     */
    private function select_by_radius( $radius ) {

        // Loop through the location table
        foreach( $this->location_table as $index => $location_rec ) {

            // Is the distance on the current record within the specified radius?
            if ( $location_rec['distance'] <= $radius ) {

                // If we don't have a selected table, initialize it now
                if ( !$this->selected_table ) {
                    $this->selected_table = array();
                }

                // Add the location record to the selected table.
                $this->selected_table[] = $location_rec;
            }
        }
    }


    /**
     * Compare two location records against the specified criteria (for use with usort)
     *
     * @param $record_a
     * @param $record_b
     *
     * @return int
     */
    private  function compare_recs( $record_a, $record_b ) {

        $return_val = 0;
        if( $this->criteria == CRITERIA_MOST_POPULATED ) {
            if ( $record_a['population'] < $record_b['population'] ) {
                $return_val = 1;
            } elseif ( $record_a['population'] > $record_b['population'] ) {
                $return_val = -1;
            }
        } elseif ( $this->criteria == CRITERIA_LEAST_POPULATED ) {
            if ( $record_a['population'] < $record_b['population'] ) {
                $return_val = -1;
            } elseif ( $record_a['population'] > $record_b['population'] ) {
                $return_val = 1;
            }
        } elseif( $this->criteria == CRITERIA_CLOSEST ) {
            if ( $record_a['distance'] < $record_b['distance'] ) {
                $return_val = -1;
            } elseif ( $record_a['distance'] > $record_b['distance'] ) {
                $return_val = 1;
            }
        } elseif( $this->criteria == CRITERIA_FARTHEST ) {
            if ( $record_a['distance'] < $record_b['distance'] ) {
                $return_val = 1;
            } elseif ( $record_a['distance'] > $record_b['distance'] ) {
                $return_val = -1;
            }
        }

        return( $return_val );
    }


    /*
     * Sort the selected records array by the specified criteria
     */
    private function sort_selected( $criteria ) {

        $this->criteria = $criteria;

        usort( $this->selected_table, array( $this, "compare_recs" ) );
    }

}
