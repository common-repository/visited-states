<?php
defined('ABSPATH') or die("No script kiddies please!");

// Generate map for display off of a shortcode
function vus_show_map($atts, $content = '') {
  $state_count = 0;
  $vus_map_width = 600;
  $vus_map_height = 400;
  $options = get_option('vus_settings');
  $vus_theme = $options['theme'];
  vus_validate_atts($atts, $vus_map_width, $vus_map_height);
  
	$vus_show_map_code = '
				<div id="vus_states_map">
				<script src="' . vus_ammap_url .'ammap.js" type="text/javascript"></script>
				<script src="' . vus_ammap_url .'maps/usaLow.js" type="text/javascript"></script>
				<script src="' . vus_ammap_url .'themes/'.$vus_theme.'.js" type="text/javascript"></script>      			
			  <div id="vus_mapdiv" style="width: '.$vus_map_width.'px; height: '.$vus_map_height.'px;"></div>
        			
        			<script type="text/javascript">
            			var map = AmCharts.makeChart("vus_mapdiv",{
                			type: "map",
                theme: "'.$vus_theme.'",
                pathToImages     : "' . vus_ammap_url . 'images/",
                panEventsEnabled : true,
                backgroundColor  : "'.$options['waterColor'].'",
                backgroundAlpha  : 1,

                zoomControl: {
                    panControlEnabled  : true,
                    zoomControlEnabled : true
                },

                dataProvider     : {
                    mapVar          : AmCharts.maps.usaLow,
										getAreasFromMap:true,
                    areas           : [
										'.vus_fill_states().'	
											
                    ]
                },

                areasSettings    : {
                    autoZoom             : false,
                    color                : "'.$options['color'].'",
                    colorSolid           : "'.$options['colorSolid'].'",
                    selectedColor        : "'.$options['selectedColor'].'",
                    outlineColor         : "'.$options['outlineColor'].'",
                    rollOverColor        : "'.$options['rollOverColor'].'",
                    rollOverOutlineColor : "'.$options['rollOverOutlineColor'].'"
                }
            });
        </script>
		</div>';
		$state_count = vus_get_state_count();	
    $total_count = 50;
    $percent_visited = number_format((($state_count/$total_count)*100), 0).'%';
   $content = str_replace('{total}', $total_count, $content);
   $content = str_replace('{num}', $state_count, $content);
   $content = str_replace('{percent}', $percent_visited, $content);
   $content='<div>'.$content.'</div>';
  return $vus_show_map_code.$content;
}

// Verify atts for height and width
function vus_validate_atts($atts, &$vus_map_width, &$vus_map_height) {
	$vus_atts_width = 0;
	$vus_atts_height = 0;
  if(!empty($atts['width'])) {$vus_atts_width = number_format($atts['width'],0,".","");}
  if(!empty($atts['height'])) {$vus_atts_height = number_format($atts['height'],0,".","");}
  if ($vus_atts_width > 0 ) {$vus_map_width = $vus_atts_width;}
  if ($vus_atts_height > 0) {$vus_map_height = $vus_atts_height;}
  }

// Get states from DB and process for Map Display
function vus_fill_states() {
   $vstates[] = serialize(get_option('vus_states'));
	foreach($vstates as $key => $state ) {
    $states_name = vus_get_state_id($state);
      }
  return $states_name;
}

// Create string of States for Map Display
	function vus_get_state_id( $name ) {
    	$outString = '';
		$temp = explode( '"', $name );
    	foreach ($temp as $test) {
        $firsttwo = substr($test,0,2);
        if ($firsttwo == 'US') {
          $outString = $outString.'	{ id: "'.$test.'", showAsSelected: true }, 
											';
          }
        }
    return $outString;
	}

// Count how many states are selected
 function vus_get_state_count() {
   $count = 0;
   $states = get_option('vus_states');
    if($states) {
      $count = count($states);
    }
   return $count;
   }

?>