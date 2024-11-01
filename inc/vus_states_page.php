<?php
defined('ABSPATH') or die("No script kiddies please!");

// Create settings page for users to select states they have visited
function vus_states_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
		echo '<div class="wrap">';
  	$s_content = '{num} Visited';
  	$s_atts = array('height'=>'300','width'=>'400');
  	echo vus_show_map($s_atts, $s_content);
		echo '<h2>Visited States</h2>';
    echo '';
  	echo 'Select each state that you have visited and hit Save Changes';
    echo '<form method="post" action="options.php">'; 
    settings_fields( 'vus_visited_states' );
    do_settings_sections( 'vus_visited_states' );
		$options = get_option('vus_states');
  	echo '<table><th>US States</th><tr>';
    echo vus_checkboxes($options); 
    echo '</table>';
    submit_button();
		echo '</form>';
		echo '</div>';
}

// Provide name from State ID
function vus_return_state_name($id) {
			$states = array('US-AL'=>'Alabama',
										'US-AK'=>'Alaska',
										'US-AZ'=>'Arizona',
										'US-AR'=>'Arkansas',
										'US-CA'=>'California',
										'US-CO'=>'Colorado',
										'US-CT'=>'Connecticut',
										'US-DE'=>'Delaware',
										'US-FL'=>'Florida',
										'US-GA'=>'Georgia',
										'US-HI'=>'Hawaii',
										'US-ID'=>'Idaho',
										'US-IL'=>'Illinois',
										'US-IN'=>'Indiana',
										'US-IA'=>'Iowa',
										'US-KS'=>'Kansas',
										'US-KY'=>'Kentucky',
										'US-LA'=>'Louisiana',
										'US-ME'=>'Maine',
										'US-MD'=>'Maryland',
										'US-MA'=>'Massachusetts',
										'US-MI'=>'Michigan',
										'US-MN'=>'Minnesota',
										'US-MS'=>'Mississippi',
										'US-MO'=>'Missouri',
										'US-MT'=>'Montana',
										'US-NC'=>'North Carolina',
										'US-NE'=>'Nebraska',
										'US-NV'=>'Nevada',
										'US-NH'=>'New Hampshire',
										'US-NJ'=>'New Jersey',
										'US-NM'=>'New Mexico',
										'US-NY'=>'New York',
										'US-ND'=>'North Dokota',
										'US-OH'=>'Ohio',
										'US-OK'=>'Oklahoma',
										'US-OR'=>'Oregon',
										'US-PA'=>'Pennsylvania',
										'US-RI'=>'Rhode Island',
										'US-SC'=>'South Carolina',
										'US-SD'=>'South Dakota',
										'US-TN'=>'Tennessee',
										'US-TX'=>'Texas',
										'US-UT'=>'Utah',
										'US-VT'=>'Vermont',
										'US-VA'=>'Virginia',
										'US-WA'=>'Washington',	
										'US-WV'=>'West Virginia',
										'US-WI'=>'Wisconsin',
										'US-WY'=>'Wyoming');
    $name = $states[$id];
			return $name;
}

// Build checkboxes for each state
function vus_checkboxes(&$options) {
  $vus_states =  array('US-AL','US-AK','US-AZ','US-AR','US-CA','US-CO','US-CT','US-DE','US-FL','US-GA','US-HI','US-ID','US-IL','US-IN','US-IA','US-KS','US-KY','US-LA','US-ME','US-MD','US-MA','US-MI','US-MN','US-MS','US-MO','US-MT','US-NE','US-NV','US-NH','US-NJ','US-NM','US-NY','US-NC','US-ND','US-OH','US-OK','US-OR','US-PA','US-RI','US-SC','US-SD','US-TN','US-TX','US-UT','US-VT','US-VA','US-WA','US-WV','US-WI','US-WY');
  $i = 0;
  $output = '';
   foreach ($vus_states as $state) {    
    	if($i >= 5) {
      	$output=$output.'<tr>';
      	$i = 0;
    	} 
     $output=$output.vus_build_checkbox($state, $options);
     $i++;
   }
   return $output;
}

// Return code for individual checkbox
function vus_build_checkbox($id, &$options) {
   $crlf = chr(13).chr(10);
   $qt = chr(34);
   if($options <> '') {
      $c = checked( 1, $options[$id], false );
   }
   $name = vus_return_state_name($id);
	 $s = "<td><input type=".$qt."checkbox".$qt." id=".$qt."states".$qt." name=".$qt."vus_states[$id]".$qt." value=".$qt."1".$qt." $c/>$name</td>";
   return $s.$crlf;
}
                   

?>