<?php

/**
 * WooCommerce API Manager API Key Class
 *
 * @package Update API Manager/Key Handler
 * @since 1.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WCFMgs_Key_Api {

	// API Key URL
	public function create_software_api_url( $args ) {
	  global $WCFMgs;
	  
		$api_url = add_query_arg( 'wc-api', 'am-software-api', $WCFMgs->license->upgrade_url );

		return $api_url . '&' . http_build_query( $args );
	}

	public function activate( $args ) {
	  global $WCFMgs;
	  
		$defaults = array(
			'request' 			=> 'activation',
			'product_id' 		=> $WCFMgs->license->license_product_id,
			'instance' 			=> $WCFMgs->license->license_instance_id,
			'platform' 			=> $WCFMgs->license->license_domain,
			'software_version' 	=> $WCFMgs->license->license_software_version
			);
   
		$args = wp_parse_args( $defaults, $args );
    
		$target_url = self::create_software_api_url( $args );
    
		$request = wp_remote_get( $target_url, array('sslverify'   => false) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}
   
		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	public function deactivate( $args ) {
	  global $WCFMgs;
	  
		$defaults = array(
			'request' 		=> 'deactivation',
			'product_id' 	=> $WCFMgs->license->license_product_id,
			'instance' 		=> $WCFMgs->license->license_instance_id,
			'platform' 		=> $WCFMgs->license->license_domain
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = self::create_software_api_url( $args );

		$request = wp_remote_get( $target_url, array('sslverify'   => false) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

	/**
	 * Checks if the software is activated or deactivated
	 * @param  array $args
	 * @return array
	 */
	public function status( $args ) {
	  global $WCFMgs;
	  
		$defaults = array(
			'request' 		=> 'status',
			'product_id' 	=> $WCFMgs->license->license_product_id,
			'instance' 		=> $WCFMgs->license->license_instance_id,
			'platform' 		=> $WCFMgs->license->license_domain
			);

		$args = wp_parse_args( $defaults, $args );

		$target_url = self::create_software_api_url( $args );

		$request = wp_remote_get( $target_url, array('sslverify'   => false) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
		// Request failed
			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		return $response;
	}

}

// Class is instantiated as an object by other classes on-demand
