<?php


class Baidu_Maps_API {

	/**
	 * Start up
	 */
	public function __construct() {

	}

	/**
	 *    Create HTML for the Baidu Map
	 */
	public function createMapElement( $id, $width, $height, $full_width ) {
		$height = $height . 'px';
		$width  = $full_width ? '100%' : $width . 'px';


		$html[] = "<div class='baidu-map-container' style='width: $width' >";
		$html[] = "<div id='$id' class='baidu-map' style='width: $width; height: $height;'></div>";
		$html[] = "</div>";

		return implode( "\n", $html );
	}

	public function createMap( $id, $zoom, $lat, $lng ) {
		?>

		<script type='text/javascript'>
			(function ($) {
				$(document).ready(function () {
					// Create the map
					var map = new BMap.Map('<?php echo $id; ?>');
					map.centerAndZoom(new BMap.Point(<?php echo $lat; ?>, <?php echo $lng; ?>), <?php echo $zoom; ?>);
				})
			})(window.jQuery)
		</script>

	<?php
	}

	public function createMapWithID( $id ) {
		$height     = get_post_meta( $id, 'baidu_maps_meta_height', true );
		$width      = get_post_meta( $id, 'baidu_maps_meta_width', true );
		$full_width = get_post_meta( $id, 'baidu_maps_meta_set_full_width', true );
		$center_lat = get_post_meta( $id, 'baidu_maps_meta_center_lat', true );
		$center_lng = get_post_meta( $id, 'baidu_maps_meta_center_lng', true );
		$zoom       = get_post_meta( $id, 'baidu_maps_meta_zoom', true );
		$markers    = get_post_meta( $id, 'markers', true );
		$prefix     = 'baidu_maps_marker_meta_';

		if ( $full_width == 'on' ) {
			$full_width = true;
		}

		$map_element = $this->createMapElement( $id, $width, $height, $full_width );

		?>

		<script type='text/javascript'>
			(function ($) {
				$(document).ready(function () {
					// Create the map
					var map = new BMap.Map('<?php echo $id; ?>');
					map.setMapStyle({features: ['road', 'water', 'land', 'building']});
					map.centerAndZoom(new BMap.Point(<?php echo $center_lat; ?>, <?php echo $center_lng; ?>), <?php echo $zoom; ?>);

					<?php foreach($markers as $marker_count => $marker) : ?>

					<?php
							$meta_name = $marker[$prefix . 'name' . '-' . $marker_count];
							$meta_description = $marker[$prefix . 'description' . '-' . $marker_count];
							$meta_lat = $marker[$prefix . 'lat' . '-' . $marker_count];
							$meta_lng = $marker[$prefix . 'lng' . '-' . $marker_count];
							$meta_icon = $marker[$prefix . 'icon' . '-' . $marker_count];
							$meta_bgcolor = $marker[$prefix . 'bgcolor' . '-' . $marker_count];
							$meta_fgcolor = $marker[$prefix . 'fgcolor' . '-' . $marker_count];
							$meta_isopen = $marker[$prefix . 'isopen' . '-' . $marker_count];

							if(($meta_lat == '' || !is_numeric($meta_lat)) && ($meta_lng == '' || !is_numeric($meta_lng)))
					?>

					var point = new BMap.Point(<?php echo $meta_lat?>, <?php echo $meta_lng?>);
					var checked_isopen = "<?php echo $meta_isopen ?>";
					var data = {
						name       : "<?php echo $meta_name; ?>",
						description: "<?php echo $meta_description; ?>",
						bgcolor    : "<?php echo $meta_bgcolor; ?>",
						fgcolor    : "<?php echo $meta_fgcolor; ?>",
						isHidden   : checked_isopen ? false : true,
						marker     : ''
					}

					var myIcon = new BMap.Icon("<?php echo $meta_icon ?>", new BMap.Size(22, 33));
					var marker_icon = new BMap.Marker(point, {icon: myIcon});

					data.marker = marker_icon;
					var marker = new Marker(point, data);


					map.addOverlay(marker_icon);
					map.addOverlay(marker);

					<?php endforeach; ?>
				});
			})(window.jQuery)
		</script>

		<?php


		return $map_element;
	}
}