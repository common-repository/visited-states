<?php
defined('ABSPATH') or die("No script kiddies please!");

// Create settings page for users to select the settings they want
function vus_settings_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div>';
  echo '<form method="post" action="options.php">'; 
  settings_fields( 'vus_settings' );
  do_settings_sections( 'vus_settings_section' );
	submit_button();
	echo '</form>';
	echo '</div>';
}

// Build Section info
function vus_settings_callback(){
  $s_content = '{num} Visited';
  $s_atts = array('height'=>'300','width'=>'400');
  echo vus_show_map($s_atts, $s_content);
	echo '<p>Visited States Settings</p>';
  echo 'Enter either HEX codes or common color names.<br>';
	echo 'If the color name is found it will convert it to the HEX code.<br>';
  echo 'If an invalid HEX code is entered or the color name is not found it will change back to the default color.';
  }

// Build the theme drop down list
function vus_setting_theme() {
	$options = get_option('vus_settings');
  echo '<select name="vus_settings[theme]">';
  $themes = array('dark','light','black','chalk'); 
  foreach ($themes as $t) {
    vus_drop_down_entry($t, $options);
  }
	echo '</select>';
} 

// Create Drop Down Entry for provided name
function vus_drop_down_entry($name, &$options){
  if($options['theme'] == $name) {$selected = 'selected="selected"';}
  echo '<option value="'.$name.'" '.$selected.'>'.ucfirst($name).'</option>';
}

//Return input box
function vus_setting_waterColor() {
	vus_build_input_box('waterColor');
} 

//Return input box
function vus_setting_color() {
	vus_build_input_box('color');
} 

//Return input box
function vus_setting_selectedColor() {
	vus_build_input_box('selectedColor');
}  

//Return input box
function vus_setting_outlineColor() {
	vus_build_input_box('outlineColor');
}  

//Return input box
function vus_setting_rollOverColor() {
	vus_build_input_box('rollOverColor');
}  

//Return input box
function vus_setting_rollOverOutlineColor() {
	vus_build_input_box('rollOverOutlineColor');
}

//Build an input box from setting name
function vus_build_input_box($name){
  $options = get_option('vus_settings');
  echo "<input id='$name' name='vus_settings[$name]' size='10' type='text' value='{$options[$name]}' />";
}

// Validate all settings from the page
function vus_settings_validate($input) {
  $colors = array('waterColor','color','selectedColor','outlineColor','rollOverColor','rollOverOutlineColor');
	$options = get_option('vus_settings');
  foreach ($colors as $item) {
  	$options[$item] = vus_validate_color($item, $input);  
  }
	$options['theme'] = vus_validate_theme(trim($input['theme']));
	return $options;
}

// Validate theme
function vus_validate_theme($theme){
  $themes = array('dark','light','black','chalk');
  if (in_array($theme, $themes)) {
    	return $theme;
    } else {
    	return vus_get_default('theme');
    }
}

// Validate that the color is either a valid HEX color code or one of the color names included
function vus_validate_color($txt, $input){
  $input[$txt] = trim($input[$txt]);
  if(vus_validate_hex( $input[$txt] ) ) {
		return strtoupper( $input[$txt] );
	} else {
		return vc_replace_color_name($input[$txt], $txt);
  }
}

// Validate that the HEX code is a valid code
function vus_validate_hex($color){
  if( empty( $color ) ) return true;
			return preg_match( '/^\#?[A-Fa-f0-9]{3}([A-Fa-f0-9]{3})$/', $color );
}

// Provide the default value for setting
function vus_get_default($txt) {
  $vus_defaults = array('theme'=>'dark',
								'waterColor'=>'#535364',
								'color'=>'#CDCDCD',
								'colorSolid'=>'#5EB7DE',
								'selectedColor'=>'#5EB7DE',
								'outlineColor'=>'#666666',
								'rollOverColor'=>'#88CAE7',
								'rollOverOutlineColor'=>'#000000');
  return $vus_defaults[$txt];
}

// Populate the settings if empty
function vus_check_defaults() {
  $options = get_option('vus_settings');
  if(empty($options)) {
    $array = array('theme'=>vus_get_default('theme'),
                'waterColor'=>vus_get_default('waterColor'),
								'color'=>vus_get_default('color'),
								'colorSolid'=>vus_get_default('colorSolid'),
								'selectedColor'=>vus_get_default('selectedColor'),
								'outlineColor'=>vus_get_default('outlineColor'),
								'rollOverColor'=>vus_get_default('rollOverColor'),
								'rollOverOutlineColor'=>vus_get_default('rollOverOutlineColor'));                 
    	update_option('vus_settings', $array);
  }
}

// Replace the color name with the HEX code
function vus_replace_color_name($c, $txt) {
  	$colors = array('aqua'=>'#00FFFF','black'=>'#000000','blue'=>'#0000FF','brown'=>'#A52A2A','fuchsia'=>'#FF00FF','gold'=>'#FFD700',
										'gray'=>'#808080','green'=>'#008000','lime'=>'#00FF00','maroon'=>'#800000','navy'=>'#000080','olive'=>'#808000',
										'orange'=>'#FFA500','pink'=>'#FFC0CB','purple'=>'#800080','red'=>'#FF0000','silver'=>'#C0C0C0','tan'=>'#D2B48C',
                    'teal'=>'#008080','violet'=>'#EE82EE','white'=>'#FFFFFF','yellow'=>'#FFFF00');
   if ($colors[$c] == null) {
     	return vus_get_default($txt);
   } else {
     	return $colors[$c];
   }
}

?>