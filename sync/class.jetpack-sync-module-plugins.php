<?php

class Jetpack_Sync_Module_Plugins extends Jetpack_Sync_Module {

	public function name() {
		return 'plugins';
	}

	public function init_listeners( $callable ) {
		add_action( 'deleted_plugin', $callable, 10, 2 );
		add_action( 'activated_plugin', $callable, 10, 2 );
		add_action( 'deactivated_plugin', $callable, 10, 2 );
	}

	public function init_before_send() {
		add_filter( 'jetpack_sync_before_send_deleted_plugin', array( $this, 'expand_plugin_data' ) );
		add_filter( 'jetpack_sync_before_send_activated_plugin', array( $this, 'expand_plugin_data' ) );
		add_filter( 'jetpack_sync_before_send_deactivated_plugin', array( $this, 'expand_plugin_data' ) );
	}

	public function expand_plugin_data( $args ) {
		$plugin_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $args[0];
		if ( file_exists( $plugin_path ) ) {
			$all_plugin_data = get_plugin_data( $plugin_path );
			$plugin_data = array(
				'Name' => $all_plugin_data['Name'],
				'Version' => $all_plugin_data['Version'],
			);
		} else {
			$plugin_data = array();
		}

		return array(
			$args[0],
			$args[1],
			$plugin_data,
		);
	}
}
