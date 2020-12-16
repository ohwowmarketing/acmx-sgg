<?php
function add_state_query_vars_filter( $vars ) {
  $vars[] = "state_abbr";
  $vars[] = "future";
  $vars[] = "league";
  return $vars;
}
add_filter( 'query_vars', 'add_state_query_vars_filter', 0 );

function state_check() {
  if ( isset( $_GET['state_abbr'] ) ) {
    setcookie( 'state_abbr', $_GET['state_abbr'], strtotime( '+1 day' ), '/' );
  } else {
    if ( ! isset( $_COOKIE['state_abbr'] ) ) {

      $isValid = false;

      if ( isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] !== '::1') {
        $response = json_decode( 
          wp_remote_retrieve_body( 
            wp_remote_get( 'https://api.ipstack.com/' . $_SERVER['REMOTE_ADDR'] . '?access_key=df8f6bf77c6da3a5a45166435f317b92&fields=country_code,region_code' )
          )
        );
        
        if ( isset( $response ) && $response->country_code === 'US' ) {
          $states = get_field( 'states_operation', 'option' );
          $valid_states = [];
          foreach ( $states as $state ) {
            $valid_states[] = $state['value'];
          }
          if ( in_array( $response->region_code, $valid_states ) ) {
            setcookie( 'state_abbr', $response->region_code, strtotime( '+3 day' ), '/' );
            $isValid = true;
          } else {
            setcookie( 'state_abbr', $response->region_code, strtotime( '+7 day' ), '/' );
            $isValid = true;
          }
        }
      }
      
      if ( !$isValid ) {
        setcookie( 'state_abbr', 'XX', strtotime( '+7 day' ), '/' );
      }
    }
  }
}
add_action( 'init', 'state_check' );


function get_user_state() {
  $user_state = '';
  $valid_states = get_all_sportsbook_states();
  if ( isset( $_COOKIE['state_abbr'] ) && array_key_exists( $_COOKIE['state_abbr'], $valid_states) ) {
    $user_state = $_COOKIE['state_abbr'];
  }
  return $user_state;
}

function get_state_from_code( $code ) {
  $states = ['AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'];
  return $states[ $code ];
}