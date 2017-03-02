<?php

	class KC_Customizer {

		function __construct() {

			$ajax_events = array(

				'get_lighting' => true,
				'get_camera' => true,
				'customiser_texture_requests' => true,
				'customiser_texture_request' => true,
				'customiser_all_textures_request' => true,

			);

			foreach($ajax_events as $ajax_event => $nopriv) {

				add_action('wp_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
				add_action('wp_ajax_nopriv_' . $ajax_event, array(__CLASS__, $ajax_event));

			}


		}

		public function get_lighting() {

			$lights = array('PointLights' => array(), 'AmbientColour' => '');

			$lights['PointLights'] = get_field('lighting', 'options');
			$lights['AmbientColour'] = get_field('ambient_colour', 'options');

			echo json_encode($lights);
			wp_die();

		}

		public function get_camera() {

			$camera = get_field('camera', 'options');

			echo json_encode($camera);
			wp_die();

		}

		public function customiser_texture_request() {

			if (isset($_REQUEST)) {

				$texture = $_REQUEST['texture'];

			}

			$result = json_encode($this->_get_texture($texture));
			echo $result;
			wp_die();

		}

		public function customiser_texture_requests() {

			if (isset($_REQUEST)) {
				
				$textures = $_REQUEST['textures'];
				$sides = $_REQUEST['sides'];

			}

			$results = array();

			foreach ($textures as $i => $texture) {

				$results[] = $this->_get_texture($texture);
				$results[$i]['Ref'] = $sides[$i];

			}

			echo json_encode($results);
			wp_die();

		}

		public function customiser_all_textures_request() {

			if (isset($_REQUEST)) {

				$product = $_REQUEST['product'];

			}

			$result = array();

			if (isset($product)) {

				foreach(get_field('outside_colour', $product) as $texture) {

					$texture = $texture['texture'];

					$the_texture = $this->_get_texture($texture);
					$the_texture['Ref'] = 'texture_outside';

					$result[] = $the_texture;

				}

			}

			$result = json_encode($result);
			echo $result;
			wp_die();

		}

		private function _get_texture_thumbnail($texture) {

			$thumbnail = get_field('thumbnail', $texture);

			if (is_numeric($thumbnail)) {

				$thumbnail = wp_get_attachment_url($thumbnail);

			}

			return $thumbnail;

		}

		private function _get_texture_image($texture) {

			$the_texture = get_field('texture_image', $texture);

			if (is_numeric($the_texture)) {

				$the_texture = wp_get_attachment_url($the_texture);

			}

			return $the_texture;

		}

		private function _get_texture($texture) {

			$result = array();

			if (isset($texture)) {

				$result['name'] = get_the_title($texture);
				$result['thumbnail'] = $this->_get_texture_thumbnail($texture);
				$result['texture'] = $this->_get_texture_image($texture);
				$result['matt'] = get_field('matt', $texture);
				$result['reflection'] = get_field('reflection', $texture);
				$result['repeatable'] = get_field('repeatable', $texture);
				$result['intensity'] = get_field('intensity', $texture);

			}

			return $result;

		}

	}

	$customiser = new KC_Customizer();

?>
