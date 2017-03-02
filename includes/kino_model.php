<?php

	class KC_Model {

		function __construct() {

			$ajax_events = array(

				'get_models' => true,

			)

			foreach ($ajax_events as $ajax_event => $nopriv) {

				add_action('wp_ajax_' . $ajax_event, array(__CLASS__, $ajax_event));
				add_action('wp_ajax_nopriv_' . $ajax_event, array(__CLASS__, $ajax_event));

			}

		}


		public static function get_models() {

			$the_query = new WP_Query(array(

				'post_type' => 'model'
				'posts_per_page' => -1,
				'fields' => 'ids',

			));

			echo json_encode($the_query->posts);
			wp_die();

		}

	}

?>
