<?php
/**
 * WCFMu plugin core
 *
 * Plugin Vendor Verification Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmu/core
 * @version   3.3.1
 */

class WCFMu_Vendor_Verification {

	public function __construct() {
		global $WCFM, $WCFMu;

		// Verification Settings
		add_action( 'end_wcfm_settings', array( &$this, 'wcfmu_vendor_verification_settings' ), 15 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfmu_vendor_verification_settings_update' ), 15 );

		// Verification Profile Option
		if( wcfm_is_vendor() ) {
			$this->wcfm_hybridauth_init();
			$this->wcfm_vendor_auth_requests();
			add_action( 'end_wcfm_user_profile', array( &$this, 'wcfmu_vendor_verification_user_profile_fields' ), 15 );
			add_action( 'wcfm_profile_update', array( &$this, 'wcfmu_vendor_verification_user_profile_meta_save' ), 15, 2 );
		}

		// Generate Verification Response Html
    add_action( 'wp_ajax_wcfmu_seller_verification_html', array( &$this, 'wcfmu_seller_verification_html' ) );

    // Update Verification Response
    add_action( 'wp_ajax_wcfmu_verification_response_update', array( &$this, 'wcfmu_verification_response_update' ) );

		// Verification direct message type
		add_filter( 'wcfm_message_types', array( &$this, 'wcfm_verification_message_types' ), 100 );

		// Show verified seller badge
		add_filter( 'wcfm_dashboard_username', array( &$this, 'after_wcfm_dashboard_user' ) );

		// Show verified seller badge before custom badges
		add_action( 'before_wcfm_vendor_badges', array( &$this, 'show_verified_seller_badge' ), 10, 2 );

		if( $WCFMu->is_marketplace == 'wcpvendors' ) {
			add_filter( 'before_wcv_wcfm_vendor_badges', array( &$this, 'show_verified_seller_badge_by_name' ), 10, 3 );
		} elseif( $WCFMu->is_marketplace == 'dokan' ) {
			add_filter( 'before_dokan_wcfm_vendor_badges', array( &$this, 'show_verified_seller_badge_by_name' ), 10, 3 );
		}
	}

	function wcfm_hybridauth_init() {
		global $WCFM, $WCFMu;

		if ( !class_exists( 'Hybrid_Auth' ) ) {
			require_once $WCFMu->plugin_path . 'includes/libs/Hybrid/Auth.php';
		}
		if ( !class_exists( 'Hybrid_Endpoint' ) ) {
			require_once $WCFMu->plugin_path . 'includes/libs/Hybrid/Endpoint.php';
		}
  }

  function wcfm_vendor_auth_requests() {
		global $WCFM, $WCFMu;

		// Social Config
		$vendor_verification_options = (array) get_option( 'wcfm_vendor_verification_options' );

		$verify_google_client_id = isset( $vendor_verification_options['verify_google_client_id'] ) ? $vendor_verification_options['verify_google_client_id'] : '';
		$verify_google_client_secret = isset( $vendor_verification_options['verify_google_client_secret'] ) ? $vendor_verification_options['verify_google_client_secret'] : '';

		$verify_facebook_client_id = isset( $vendor_verification_options['verify_facebook_client_id'] ) ? $vendor_verification_options['verify_facebook_client_id'] : '';
		$verify_facebook_client_secret = isset( $vendor_verification_options['verify_facebook_client_secret'] ) ? $vendor_verification_options['verify_facebook_client_secret'] : '';

		$verify_linkedin_client_id = isset( $vendor_verification_options['verify_linkedin_client_id'] ) ? $vendor_verification_options['verify_linkedin_client_id'] : '';
		$verify_linkedin_client_secret = isset( $vendor_verification_options['verify_linkedin_client_secret'] ) ? $vendor_verification_options['verify_linkedin_client_secret'] : '';

		$verify_twitter_client_id = isset( $vendor_verification_options['verify_twitter_client_id'] ) ? $vendor_verification_options['verify_twitter_client_id'] : '';
		$verify_twitter_client_secret = isset( $vendor_verification_options['verify_twitter_client_secret'] ) ? $vendor_verification_options['verify_twitter_client_secret'] : '';

		$wcfm_social_providers = array (
			 "Google"   => array(
								"enabled" => true,
								"keys"    => array( "id" => $verify_google_client_id, "secret" => $verify_google_client_secret ),
								"scope"   => "https://www.googleapis.com/auth/userinfo.profile "
						),
			 "Facebook" => array(
								"enabled" => true,
								"keys"    => array( "id" => $verify_facebook_client_id, "secret" => $verify_facebook_client_secret ),
								"scope"   => "email, public_profile, user_friends",
								"trustForwarded" => true
						),
			 "Twitter"  => array(
								"enabled" => true,
								"keys"    => array( "key" => $verify_twitter_client_id, "secret" => $verify_twitter_client_secret ),
						),
			 "LinkedIn" => array(
								"enabled" => true,
								"keys"    => array( "key" => $verify_linkedin_client_id, "secret" => $verify_linkedin_client_secret ),
						)
		);

		$wcfm_social_config = array(   "base_url" 	=> get_wcfm_profile_url(),
																	 "debug_mode" => false ,
																	 "debug_file" => $WCFMu->plugin_path . "includes/libs/Hybrid/hybridauth.log",
																	 "providers"  => $wcfm_social_providers
																	 );

		$hybridauth = new Hybrid_Auth( $wcfm_social_config );
		$params = array( 'hauth_return_to' => get_wcfm_profile_url() );

		if(isset($_REQUEST['hauth_start'])) {
			Hybrid_Endpoint::process();
		}

		if(isset($_REQUEST['hauth_done'])) {
			if(isset($_REQUEST['code']) || isset($_REQUEST['oauth_token'])) {
				Hybrid_Endpoint::process();
			} elseif(isset($_REQUEST['oauth_problem']) || isset($_REQUEST['denied']) || isset($_REQUEST['error']) || isset($_REQUEST['error_code'])) {
				if(isset($params['hauth_return_to']))
    		  $hybridauth->redirect( $params['hauth_return_to'] );
			}
		}

		if(isset($_REQUEST['auth_out'])) {
    	$provider_logout = ucfirst( sanitize_text_field( $_GET['auth_out'] ) );
    	try {
				$adapter = $hybridauth->authenticate( $provider_logout, $params );
				$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
				$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );
				if( isset( $vendor_verification_data['social'] ) && isset( $vendor_verification_data['social'][$provider_logout] ) ) { unset( $vendor_verification_data['social'][$provider_logout] ); }
				update_user_meta( $vendor_id, 'vendor_verification_data', $vendor_verification_data );
				$adapter->logout();
			} catch( Exception $e ) {
				wc_add_notice($e->getMessage(), 'error');
			}
    }

		if(isset($_REQUEST['hybridauth'])) {

	  }

		if(isset($_REQUEST['auth_in'])) {
      $provider = ucfirst( sanitize_text_field( $_GET['auth_in'] ) );

			if(!empty($provider)) {
				try {
					$adapter = $hybridauth->authenticate( $provider );
					$user_profile = $adapter->getUserProfile();
					$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
					$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );
					$vendor_verification_data['social'][$provider] = (array) $user_profile;
					update_user_meta( $vendor_id, 'vendor_verification_data', $vendor_verification_data );
					if(isset($params['hauth_return_to']))
						$hybridauth->redirect( $params['hauth_return_to'] );
	      } catch( Exception $e ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}
			}
		}
	}

	function wcfmu_vendor_verification_settings( $wcfm_options ) {
		global $WCFM, $WCFMu;

		$vendor_verification_options = get_option( 'wcfm_vendor_verification_options', array() );

		$verification_badge = isset( $vendor_verification_options['verification_badge'] ) ? $vendor_verification_options['verification_badge'] : '';
		if( !$verification_badge ) $verification_badge = $WCFMu->plugin_url . 'assets/images/verification_badge.png';

		$verify_by_google = isset( $vendor_verification_options['verify_by_google'] ) ? $vendor_verification_options['verify_by_google'] : 'no';
		$verify_google_redirect_url = add_query_arg( 'hauth.done', 'Google', get_wcfm_profile_url() );
		$verify_google_client_id = isset( $vendor_verification_options['verify_google_client_id'] ) ? $vendor_verification_options['verify_google_client_id'] : '';
		$verify_google_client_secret = isset( $vendor_verification_options['verify_google_client_secret'] ) ? $vendor_verification_options['verify_google_client_secret'] : '';

		$verify_by_facebook = isset( $vendor_verification_options['verify_by_facebook'] ) ? $vendor_verification_options['verify_by_facebook'] : 'no';
		$verify_facebook_redirect_url = add_query_arg( 'hauth.done', 'Facebook', get_wcfm_profile_url() );
		$verify_facebook_client_id = isset( $vendor_verification_options['verify_facebook_client_id'] ) ? $vendor_verification_options['verify_facebook_client_id'] : '';
		$verify_facebook_client_secret = isset( $vendor_verification_options['verify_facebook_client_secret'] ) ? $vendor_verification_options['verify_facebook_client_secret'] : '';

		$verify_by_linkedin = isset( $vendor_verification_options['verify_by_linkedin'] ) ? $vendor_verification_options['verify_by_linkedin'] : 'no';
		$verify_linkedin_redirect_url = add_query_arg( 'hauth.done', 'LinkedIn', get_wcfm_profile_url() );
		$verify_linkedin_client_id = isset( $vendor_verification_options['verify_linkedin_client_id'] ) ? $vendor_verification_options['verify_linkedin_client_id'] : '';
		$verify_linkedin_client_secret = isset( $vendor_verification_options['verify_linkedin_client_secret'] ) ? $vendor_verification_options['verify_linkedin_client_secret'] : '';

		$verify_by_twitter = isset( $vendor_verification_options['verify_by_twitter'] ) ? $vendor_verification_options['verify_by_twitter'] : 'no';
		$verify_twitter_redirect_url = add_query_arg( 'hauth.done', 'Twitter', get_wcfm_profile_url() );
		$verify_twitter_client_id = isset( $vendor_verification_options['verify_twitter_client_id'] ) ? $vendor_verification_options['verify_twitter_client_id'] : '';
		$verify_twitter_client_secret = isset( $vendor_verification_options['verify_twitter_client_secret'] ) ? $vendor_verification_options['verify_twitter_client_secret'] : '';

		?>
		<!-- collapsible -->

				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmu_settings_fields_vendor_verification_general', array(
																																																"verification_badge" => array('label' => __('Verification Badge', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verification_badge]', 'type' => 'upload', 'class' => 'wcfm_ele', 'prwidth' => 64, 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'Upload badge image 32x32 size for best view.', '' ), 'value' => $verification_badge )
																																																) ) );
			  ?>
			  <div class="wcfm_clearfix"></div><h2>Google</h2><div class="wcfm_clearfix"></div>
			  <p><?php printf( _x( 'Generate your Client ID & Secret Key from %s.', 'wc-frontend-manager-ultimate' ), '<a target="_blank" href="https://console.developers.google.com/project">here</a>' ); ?></p>
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmu_settings_fields_vendor_verification_google', array(
																																																"verify_by_google" => array('label' => __('Enable', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_by_google]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $verify_by_google ),
																																																"verify_google_redirect_url" => array('label' => __('Redirect URL', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_google_redirect_url]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'User will be redirect to the URL after successful authentication.', 'wc-frontend-manager-ultimate' ), 'attributes' => array( 'readonly' => true ), 'value' => $verify_google_redirect_url ),
																																																"verify_google_client_id" => array('label' => __('Client ID', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_google_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client ID required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_google_client_id ),
																																																"verify_google_client_secret" => array('label' => __('Client Secret', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_google_client_secret]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client Secret key required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_google_client_secret ),
																																																) ) );
				?>

				<div class="wcfm_clearfix"></div><h2>Facebook</h2><div class="wcfm_clearfix"></div>
				<p><?php printf( _x( 'Generate your Client ID & Secret Key from %s.', 'wc-frontend-manager-ultimate' ), '<a target="_blank" href="https://developers.facebook.com/apps/">here</a>' ); ?></p>
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmu_settings_fields_vendor_verification_facebook', array(
																																																"verify_by_facebook" => array('label' => __('Enable', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_by_facebook]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $verify_by_facebook ),
																																																"verify_facebook_redirect_url" => array('label' => __('Redirect URL', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_facebook_redirect_url]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'User will be redirect to the URL after successful authentication.', 'wc-frontend-manager-ultimate' ), 'attributes' => array( 'readonly' => true ), 'value' => $verify_facebook_redirect_url ),
																																																"verify_facebook_client_id" => array('label' => __('Client ID', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_facebook_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client ID required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_facebook_client_id ),
																																																"verify_facebook_client_secret" => array('label' => __('Client Secret', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_facebook_client_secret]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client Secret key required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_facebook_client_secret ),
																																																) ) );
				?>

				<div class="wcfm_clearfix"></div><h2>LinkedIn</h2><div class="wcfm_clearfix"></div>
				<p><?php printf( _x( 'Generate your Client ID & Secret Key from %s.', 'wc-frontend-manager-ultimate' ), '<a target="_blank" href="https://www.linkedin.com/developer/apps">here</a>' ); ?></p>
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmu_settings_fields_vendor_verification_linkedin', array(
																																																"verify_by_linkedin" => array('label' => __('Enable', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_by_linkedin]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $verify_by_linkedin ),
																																																"verify_linkedin_redirect_url" => array('label' => __('Redirect URL', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_linkedin_redirect_url]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'User will be redirect to the URL after successful authentication.', 'wc-frontend-manager-ultimate' ), 'attributes' => array( 'readonly' => true ), 'value' => $verify_linkedin_redirect_url ),
																																																"verify_linkedin_client_id" => array('label' => __('Client ID', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_linkedin_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client ID required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_linkedin_client_id ),
																																																"verify_linkedin_client_secret" => array('label' => __('Client Secret', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_linkedin_client_secret]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Client Secret key required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_linkedin_client_secret ),
																																																) ) );
				?>

				<div class="wcfm_clearfix"></div><h2>Twitter</h2><div class="wcfm_clearfix"></div>
				<p><?php printf( _x( 'Generate your Consumer Key & Consumer Secret from %s.', 'wc-frontend-manager-ultimate' ), '<a target="_blank" href="https://apps.twitter.com/">here</a>' ); ?></p>
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmu_settings_fields_vendor_verification_twitter', array(
																																																"verify_by_twitter" => array('label' => __('Enable', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_by_twitter]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'dfvalue' => $verify_by_twitter ),
																																																"verify_twitter_redirect_url" => array('label' => __('Redirect URL', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_twitter_redirect_url]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'User will be redirect to the URL after successful authentication.', 'wc-frontend-manager-ultimate' ), 'attributes' => array( 'readonly' => true ), 'value' => $verify_twitter_redirect_url ),
																																																"verify_twitter_client_id" => array('label' => __('Consumer key', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_twitter_client_id]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Consumer Key required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_twitter_client_id ),
																																																"verify_twitter_client_secret" => array('label' => __('Consumer Secret', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_vendor_verification_options[verify_twitter_client_secret]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __( 'App generated Consumer Secret required.', 'wc-frontend-manager-ultimate' ), 'value' => $verify_twitter_client_secret ),
																																																) ) );
				?>

		<!-- end collapsible -->

		<?php

	}

	function wcfmu_vendor_verification_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMu, $_POST;

		if( isset( $wcfm_settings_form['wcfm_vendor_verification_options'] ) ) {
			$wcfm_vendor_verification_options = $wcfm_settings_form['wcfm_vendor_verification_options'];
			update_option( 'wcfm_vendor_verification_options',  $wcfm_vendor_verification_options );
		}
	}

	public function get_identity_types() {
		$default_ids = array(
			"national_id"     => __('National ID Card', 'wc-frontend-manager-ultimate'),
			"business_card"   => __('Business Card', 'wc-frontend-manager-ultimate'),
			"passport"        => __('Passport', 'wc-frontend-manager-ultimate'),
			"driving_license" => __('Driving License', 'wc-frontend-manager-ultimate'),
			);
		return apply_filters( 'wcfm_vendor_verification_identity_types', $default_ids );
	}

	function wcfmu_vendor_verification_user_profile_fields() {
		global $WCFM, $WCFMu;

		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );

    $first_name = get_user_meta( $vendor_id, 'first_name', true );
    $last_name  = get_user_meta( $vendor_id, 'last_name', true );
    $phone  = get_user_meta( $vendor_id, 'billing_phone', true );

		$verification_status = 'noprompt';
		if( !empty( $vendor_verification_data ) && isset( $vendor_verification_data['verification_status'] ) ) $verification_status = $vendor_verification_data['verification_status'];


		$vendor_verification_options = (array) get_option( 'wcfm_vendor_verification_options' );

		$verification_badge = isset( $vendor_verification_options['verification_badge'] ) ? $vendor_verification_options['verification_badge'] : '';
		if( !$verification_badge ) $verification_badge = $WCFMu->plugin_url . 'assets/images/verification_badge.png';

		$verify_by_google = isset( $vendor_verification_options['verify_by_google'] ) ? $vendor_verification_options['verify_by_google'] : '';
		$verify_google_client_id = isset( $vendor_verification_options['verify_google_client_id'] ) ? $vendor_verification_options['verify_google_client_id'] : '';
		$verify_google_client_secret = isset( $vendor_verification_options['verify_google_client_secret'] ) ? $vendor_verification_options['verify_google_client_secret'] : '';

		$verify_by_facebook = isset( $vendor_verification_options['verify_by_facebook'] ) ? $vendor_verification_options['verify_by_facebook'] : '';
		$verify_facebook_client_id = isset( $vendor_verification_options['verify_facebook_client_id'] ) ? $vendor_verification_options['verify_facebook_client_id'] : '';
		$verify_facebook_client_secret = isset( $vendor_verification_options['verify_facebook_client_secret'] ) ? $vendor_verification_options['verify_facebook_client_secret'] : '';

		$verify_by_linkedin = isset( $vendor_verification_options['verify_by_linkedin'] ) ? $vendor_verification_options['verify_by_linkedin'] : '';
		$verify_linkedin_client_id = isset( $vendor_verification_options['verify_linkedin_client_id'] ) ? $vendor_verification_options['verify_linkedin_client_id'] : '';
		$verify_linkedin_client_secret = isset( $vendor_verification_options['verify_linkedin_client_secret'] ) ? $vendor_verification_options['verify_linkedin_client_secret'] : '';

		$verify_by_twitter = isset( $vendor_verification_options['verify_by_twitter'] ) ? $vendor_verification_options['verify_by_twitter'] : '';
		$verify_twitter_client_id = isset( $vendor_verification_options['verify_twitter_client_id'] ) ? $vendor_verification_options['verify_twitter_client_id'] : '';
		$verify_twitter_client_secret = isset( $vendor_verification_options['verify_twitter_client_secret'] ) ? $vendor_verification_options['verify_twitter_client_secret'] : '';

		$verification_note = isset( $vendor_verification_data['verification_note'] ) ? $vendor_verification_data['verification_note'] : '';

		$is_social_verification_enabled = false;
		if( $verify_by_google || $verify_by_facebook || $verify_by_linkedin || $verify_by_twitter ) { $is_social_verification_enabled = true; }

		// Check Social Pending
		if( $is_social_verification_enabled ) {
			if( $verification_status == 'approve' ) {
				if( !isset( $vendor_verification_data['social'] ) ) {
					$verification_status = 'social_pending';
				} else {
					if( $verify_by_google && !isset( $vendor_verification_data['social']['google'] ) ) $verification_status = 'social_pending';
					elseif( $verify_by_google && !isset( $vendor_verification_data['social']['twitter'] ) ) $verification_status = 'social_pending';
					elseif( $verify_by_google && !isset( $vendor_verification_data['social']['linkedin'] ) ) $verification_status = 'social_pending';
					elseif( $verify_by_google && !isset( $vendor_verification_data['social']['facebook'] ) ) $verification_status = 'social_pending';
					else {
						$vendor_verification_data['social_verification_status'] = 'approve';
						update_user_meta( $vendor_id, 'wcfm_vendor_verification_data', $vendor_verification_data );
					}
				}
			}
		} else {
			$vendor_verification_data['social_verification_status'] = 'approve';
			update_user_meta( $vendor_id, 'wcfm_vendor_verification_data', $vendor_verification_data );
		}

		?>


				<?php
				$this->show_verification_status_message( $verification_status );

				if( $verification_status != 'pending' ) {

					if( $verification_note ) {
						echo '<div class="verification_status_note"><span class="fa fa-sticky-note-o"></span><span>' . __( 'Admin Note', 'wc-frontend-manager-ultimate' ) . ': ' . $verification_note . '</span></div><br />';
					}

					if( $verification_status != 'social_pending' ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																																"prompt_verify" => array( 'label' => __( 'Prompt Verify', 'wc-frontend-manager-ultimate'), 'name' => 'vendor_verification[prompt_verify]', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title wcfm_ele', 'value' => 'yes', 'hints' => __( 'Check this to submit your verification data to admin.', 'wc-frontend-manager-ultimate') )
																														) );
					}
					?>

					<?php if( apply_filters( 'wcfm_is_allow_vendor_identity_verification', true ) ) { ?>
						<h2><?php _e( 'Identity Verification', 'wc-frontend-manager-ultimate' ); ?></h2><br />
						<div class="wcfm_clearfix"></div>
						<?php

            $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_profile_fields_billing', array(
                                                                                              "first_name" => array('label' => __('First Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $first_name ),
                                                                                              "last_name" => array('label' => __('Last Name', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $last_name ),
                                                                                              //"email" => array('label' => __('Email', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $email ),
                                                                                              "phone" => array('label' => __('Phone', 'wc-frontend-manager') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $phone ),
                                                                                              ) ) );
						$identity_types = $this->get_identity_types();
						if( !empty( $identity_types ) ) {
							foreach( $identity_types as $identity_type => $identity_type_label ) {
								$identity_type_value = '';
								if( !empty( $vendor_verification_data ) && isset( $vendor_verification_data['identity'] ) && isset( $vendor_verification_data['identity'][$identity_type] ) ) $identity_type_value = $vendor_verification_data['identity'][$identity_type];
								$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																																	$identity_type => array( 'label' => $identity_type_label, 'name' => 'vendor_verification[identity]['.$identity_type.']', 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'prwidth' => 32, 'value' => $identity_type_value )
																																	) );
							}
						}
						?>
						<div class="wcfm_clearfix"></div><br />
					<?php } ?>

					<?php if( apply_filters( 'wcfm_is_allow_vendor_address_verification', true ) ) { ?>
						<h2><?php _e( 'Address Verification', 'wc-frontend-manager-ultimate' ); ?></h2><br />
						<div class="wcfm_clearfix"></div>
						<?php
						$street_1 = isset( $vendor_verification_data['address']['street_1'] ) ? $vendor_verification_data['address']['street_1'] : '';
						$street_2 = isset( $vendor_verification_data['address']['street_2'] ) ? $vendor_verification_data['address']['street_2'] : '';
						$city    = isset( $vendor_verification_data['address']['city'] ) ? $vendor_verification_data['address']['city'] : '';
						$zip     = isset( $vendor_verification_data['address']['zip'] ) ? $vendor_verification_data['address']['zip'] : '';
						$country = isset( $vendor_verification_data['address']['country'] ) ? $vendor_verification_data['address']['country'] : '';
						$state   = isset( $vendor_verification_data['address']['state'] ) ? $vendor_verification_data['address']['state'] : '';

						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_vendor_verification_profile_fields_address', array(
																																																		"vstreet_1" => array('label' => __('Street', 'wc-frontend-manager'), 'placeholder' => __('Street adress', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][street_1]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $street_1 ),
																																																		"vstreet_2" => array('label' => __('Street 2', 'wc-frontend-manager'), 'placeholder' => __('Apartment, suit, unit etc. (optional)', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][street_2]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $street_2 ),
																																																		"vcity" => array('label' => __('City/Town', 'wc-frontend-manager'), 'placeholder' => __('Town / City', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][city]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $city ),
																																																		"vzip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager'), 'placeholder' => __('Postcode / Zip', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][zip]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $zip ),
																																																		"vcountry" => array('label' => __('Country', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][country]', 'attributes' => array( 'style' => 'width: 60%;' ), 'type' => 'country', 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $country ),
																																																		"vstate" => array('label' => __('State/County', 'wc-frontend-manager'), 'name' => 'vendor_verification[address][state]', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $state ),
																																																		) ) );
						?>
						<div class="wcfm_clearfix"></div><br />
					<?php } ?>
				<?php } ?>

				<?php if( $is_social_verification_enabled ) { ?>
					<?php if( apply_filters( 'wcfm_is_allow_vendor_social_verification', true ) ) { ?>
						<h2><?php _e( 'Social Verification', 'wc-frontend-manager-ultimate' ); ?></h2><br />
						<div class="wcfm_clearfix"></div>

						<div class="wcfm_social_buttons">
							<?php
							// Google
							if( $verify_by_google && apply_filters( 'wcfm_is_allow_vendor_social_verification_google', true ) ) {
								$google_verification_data = isset( $vendor_verification_data['social']['google'] ) ? $vendor_verification_data['social']['google'] : '';
								if( is_array( $google_verification_data ) && count( $google_verification_data ) > 1 ) {
									?>
									<div class="box">
										<div class="box-icon">
											<a href="<?php echo $google_verification_data['profileURL']; ?>"><img src="<?php echo $google_verification_data['photoURL']; ?>" /></a>
											<span class="social_ico"><img src="<?php echo $WCFMu->plugin_url . 'assets/images/google.png'; ?>"/></span>
										</div>
										<div class="info">
											<h4 class="put_the_name"><?php echo $google_verification_data['displayName']; ?></h4>
											<a class="btn" href="<?php echo add_query_arg( 'auth_out', 'google', get_wcfm_profile_url() ); ?>"><?php echo _e( 'Logout', 'wc-frontend-manager-ultimate' ); ?></a>
											<div class="wcfm_clearfix"></div>
											<p><?php echo wp_trim_words( $google_verification_data['description'], 20, '...' ); ?></p>
										</div>
									</div>
									<?php
								} elseif( $verify_google_client_id && $verify_google_client_secret ) {
									?>
									<a href="<?php echo add_query_arg( 'auth_in', 'google', get_wcfm_profile_url() ); ?>" class="social_btn_cnnct button GoogleConnect rounded large">
										<em><img src="<?php echo $WCFMu->plugin_url . 'assets/images/google.png'; ?>" style="height: 95%;"/></em>
										<span class="buttonText"><?php echo __( 'Connect to', 'wc-frontend-manager-ultimate' ). ' Google'; ?></span>
									</a>
									<?php
								}
							}

							// Linkedin
							if( $verify_by_linkedin && apply_filters( 'wcfm_is_allow_vendor_social_verification_linkedin', true ) ) {
								$linkedin_verification_data = isset( $vendor_verification_data['social']['linkedin'] ) ? $vendor_verification_data['social']['linkedin'] : '';
								if( is_array( $linkedin_verification_data ) && count( $linkedin_verification_data ) > 1 ) {
									?>
									<div class="box">
										<div class="box-icon">
											<a href="<?php echo $linkedin_verification_data['profileURL']; ?>"><img src="<?php echo $linkedin_verification_data['photoURL']; ?>" /></a>
											<span class="social_ico"><img src="<?php echo $WCFMu->plugin_url . 'assets/images/linkedin.png'; ?>"/></span>
										</div>
										<div class="info">
											<h4 class="put_the_name"><?php echo $linkedin_verification_data['displayName']; ?></h4>
											<a class="btn" href="<?php echo add_query_arg( 'auth_out', 'linkedin', get_wcfm_profile_url() ); ?>"><?php echo _e( 'Logout', 'wc-frontend-manager-ultimate' ); ?></a>
											<div class="wcfm_clearfix"></div>
											<p><?php echo wp_trim_words( $linkedin_verification_data['description'], 20, '...' ); ?></p>
										</div>
									</div>
									<?php
								} elseif( $verify_linkedin_client_id && $verify_linkedin_client_secret ) {
									?>
									<a href="<?php echo add_query_arg( 'auth_in', 'linkedin', get_wcfm_profile_url() ); ?>" class="social_btn_cnnct button LinkedInConnect rounded large">
										<em><img src="<?php echo $WCFMu->plugin_url . 'assets/images/linkedin.png'; ?>" style="height: 95%;"/></em>
										<span class="buttonText"><?php echo __( 'Connect to', 'wc-frontend-manager-ultimate' ). ' LinkedIn'; ?></span>
									</a>
									<?php
								}
							}

							// Facebook
							if( $verify_by_facebook && apply_filters( 'wcfm_is_allow_vendor_social_verification_facebook', true ) ) {
								$facebook_verification_data = isset( $vendor_verification_data['social']['facebook'] ) ? $vendor_verification_data['social']['facebook'] : '';
								if( is_array( $facebook_verification_data ) && count( $facebook_verification_data ) > 1 ) {
									?>
									<div class="box">
										<div class="box-icon">
											<a href="<?php echo $facebook_verification_data['profileURL']; ?>"><img src="<?php echo $facebook_verification_data['photoURL']; ?>" /></a>
											<span class="social_ico"><img src="<?php echo $WCFMu->plugin_url . 'assets/images/facebook.png'; ?>"/></span>
										</div>
										<div class="info">
											<h4 class="put_the_name"><?php echo $facebook_verification_data['displayName']; ?></h4>
											<a class="btn" href="<?php echo add_query_arg( 'auth_out', 'facebook', get_wcfm_profile_url() ); ?>"><?php echo _e( 'Logout', 'wc-frontend-manager-ultimate' ); ?></a>
											<div class="wcfm_clearfix"></div>
											<p><?php echo wp_trim_words( $facebook_verification_data['description'], 20, '...' ); ?></p>
										</div>
									</div>
									<?php
								} elseif( $verify_facebook_client_id && $verify_facebook_client_secret ) {
									?>
									<a href="<?php echo add_query_arg( 'auth_in', 'facebook', get_wcfm_profile_url() ); ?>" class="social_btn_cnnct button FacebookConnect rounded large">
										<em><img src="<?php echo $WCFMu->plugin_url . 'assets/images/facebook.png'; ?>" style="height: 95%;"/></em>
										<span class="buttonText"><?php echo __( 'Connect to', 'wc-frontend-manager-ultimate' ). ' Facebook'; ?></span>
									</a>
									<?php
								}
							}

							// Twitter
							if( $verify_by_twitter && apply_filters( 'wcfm_is_allow_vendor_social_verification_twitter', true ) ) {
								$twitter_verification_data = isset( $vendor_verification_data['social']['twitter'] ) ? $vendor_verification_data['social']['twitter'] : '';
								if( is_array( $twitter_verification_data ) && count( $twitter_verification_data ) > 1 ) {
									?>
									<div class="box">
										<div class="box-icon">
											<a href="<?php echo $twitter_verification_data['profileURL']; ?>"><img src="<?php echo $twitter_verification_data['photoURL']; ?>" /></a>
											<span class="social_ico"><img src="<?php echo $WCFMu->plugin_url . 'assets/images/twitter.png'; ?>"/></span>
										</div>
										<div class="info">
											<h4 class="put_the_name"><?php echo $twitter_verification_data['displayName']; ?></h4>
											<a class="btn" href="<?php echo add_query_arg( 'auth_out', 'twitter', get_wcfm_profile_url() ); ?>"><?php echo _e( 'Logout', 'wc-frontend-manager-ultimate' ); ?></a>
											<div class="wcfm_clearfix"></div>
											<p><?php echo wp_trim_words( $twitter_verification_data['description'], 20, '...' ); ?></p>
										</div>
									</div>
									<?php
								} elseif( $verify_twitter_client_id && $verify_twitter_client_secret ) {
									?>
									<a href="<?php echo add_query_arg( 'auth_in', 'twitter', get_wcfm_profile_url() ); ?>" class="social_btn_cnnct button TwitterConnect rounded large">
										<em><img src="<?php echo $WCFMu->plugin_url . 'assets/images/twitter.png'; ?>" style="height: 95%;"/></em>
										<span class="buttonText"><?php echo __( 'Connect to', 'wc-frontend-manager-ultimate' ). ' Twitter'; ?></span>
									</a>
									<?php
								}
							}
							?>
						</div>
					<?php } ?>
				<?php } ?>

		<?php
	}

	function wcfmu_vendor_verification_user_profile_meta_save( $user_id, $wcfm_profile_form ) {
		global $WCFM;

		$vendor_verification_data = (array) get_user_meta( $user_id, 'wcfm_vendor_verification_data', true );

		if( isset( $wcfm_profile_form['vendor_verification'] ) && ! empty( $wcfm_profile_form['vendor_verification'] ) ) {
			$vendor_verification_data = array_merge( $vendor_verification_data, $wcfm_profile_form['vendor_verification'] );
			update_user_meta( $user_id, 'wcfm_vendor_verification_data', $vendor_verification_data );
		}

		if( isset( $wcfm_profile_form['vendor_verification'] ) && isset( $wcfm_profile_form['vendor_verification']['prompt_verify'] ) ) {
			// Verification Admin Notification
			$author_id = $user_id;
			$author_is_admin = 0;
			$author_is_vendor = 1;
			$message_to = 0;
			$wcfm_messages = sprintf( __( '<b>%s</b> - verification pending for review', 'wc-frontend-manager-ultimate' ), get_user_by( 'id', $user_id )->display_name );
			$WCFM->frontend->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'verification' );

			// Verification Status Update
			$vendor_verification_data['verification_status'] = 'pending';
			update_user_meta( $user_id, 'wcfm_vendor_verification_data', $vendor_verification_data );
		}
	}

	function show_verification_status_message( $verification_status ) {

		$verification_status_class = '';
		$verification_status_icon = '';
		$verification_status_message = '';
		switch( $verification_status ) {
			case 'approve':
				  $verification_status_class = 'verification_approve';
				  $verification_status_icon = 'check-circle-o';
				  $verification_status_message = __( 'Congratulation!!! You are already a verified seller.', 'wc-frontend-manager-ultimate' );
			break;

			case 'pending':
				  $verification_status_class = 'verification_pending';
				  $verification_status_icon = 'exclamation-circle';
				  $verification_status_message = __( 'Ahh!!! Your request still under review.', 'wc-frontend-manager-ultimate' );
			break;

			case 'social_pending':
				  $verification_status_class = 'verification_pending';
				  $verification_status_icon = 'exclamation-circle';
				  $verification_status_message = __( 'Hey!!! Complete social verification now and be a verified seller.', 'wc-frontend-manager-ultimate' );
			break;

			case 'reject':
				  $verification_status_class = 'verification_reject';
				  $verification_status_icon = 'times-circle-o';
				  $verification_status_message = __( 'Opps!!! Your verification rejected, please try again.', 'wc-frontend-manager-ultimate' );
			break;

			default:
				  $verification_status_class = 'verification_noprompt';
				  $verification_status_icon = 'info-circle';
				  $verification_status_message = __( 'Hey!!! Prompt for verification now and be a verified seller.', 'wc-frontend-manager-ultimate' );
			break;
		}

		echo '<div class="verification_status_block '.$verification_status_class.'"><span class="fa fa-' . $verification_status_icon . '"></span><span>' . $verification_status_message . '</span></div>';
	}

	/**
	 * Generate Seller Verification Vacation HTMl
	 */
	function wcfmu_seller_verification_html() {
		global $WCFM, $WCFMu;

		if( isset( $_POST['messageid'] ) && isset($_POST['vendorid']) ) {
			$message_id = absint( $_POST['messageid'] );
			$vendor_id = absint( $_POST['vendorid'] );

			if( $vendor_id && $message_id ) {

				$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );
				$identity_types = $this->get_identity_types();

				$address = isset( $vendor_verification_data['address']['street_1'] ) ? $vendor_verification_data['address']['street_1'] : '';
				$address .= isset( $vendor_verification_data['address']['street_2'] ) ? ' ' . $vendor_verification_data['address']['street_2'] : '';
				$address .= isset( $vendor_verification_data['address']['city'] ) ? '<br />' . $vendor_verification_data['address']['city'] : '';
				$address .= isset( $vendor_verification_data['address']['zip'] ) ? ' '  . $vendor_verification_data['address']['zip'] : '';
				$address .= isset( $vendor_verification_data['address']['country'] ) ? '<br />' . $vendor_verification_data['address']['country'] : '';
				$address .= isset( $vendor_verification_data['address']['state'] ) ? ', ' . $vendor_verification_data['address']['state'] : '';

				$verification_note = isset( $vendor_verification_data['verification_note'] ) ? $vendor_verification_data['verification_note'] : '';

				?>
				<form id="wcfm_verification_response_form">
					<table>
						<tbody>
						  <?php
						  if( !empty( $identity_types ) ) {
								foreach( $identity_types as $identity_type => $identity_type_label ) {
									$identity_type_value = '';
									if( !empty( $vendor_verification_data ) && isset( $vendor_verification_data['identity'] ) && isset( $vendor_verification_data['identity'][$identity_type] ) ) $identity_type_value = $vendor_verification_data['identity'][$identity_type];
									if( $identity_type_value ) {
										?>
											<tr>
												<td class="wcfm_verification_response_form_label"><?php echo $identity_type_label; ?></td>
												<td><a class="wcfm-wp-fields-uploader" target="_blank" style="width: 32px; height: 32px;" href="<?php echo $identity_type_value; ?>"><span style="width: 32px; height: 32px; display: inline-block;" class="placeHolderDocs"></span></a></td>
											</tr>
										<?php
									}
								}
							}
							?>
							<tr>
								<td class="wcfm_verification_response_form_label"><?php _e( 'Address', 'wc-frontend-manager-ultimate' ); ?></td>
								<td><?php echo $address; ?></td>
							</tr>
							<tr>
								<td class="wcfm_verification_response_form_label"><?php _e( 'Note to Vendor', 'wc-frontend-manager-ultimate' ); ?></td>
								<td><textarea class="wcfm-textarea" name="wcfm_verification_response_note"></textarea></td>
							</tr>
							<tr>
								<td class="wcfm_verification_response_form_label"><?php _e( 'Status Update', 'wc-frontend-manager-ultimate' ); ?></td>
								<td>
								  <label for="wcfm_verification_response_status_approve"><input type="radio" id="wcfm_verification_response_status_approve" name="wcfm_verification_response_status" value="approve" checked /><?php _e( 'Approve', 'wc-frontend-manager-ultimate' ); ?></label>
								  <label for="wcfm_verification_response_status_reject"><input type="radio" id="wcfm_verification_response_status_reject" name="wcfm_verification_response_status" value="reject" /><?php _e( 'Reject', 'wc-frontend-manager-ultimate' ); ?></label>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="wcfm_verification_vendor_id" value="<?php echo $vendor_id; ?>" />
					<input type="hidden" name="wcfm_verification_message_id" value="<?php echo $message_id; ?>" />
					<div class="wcfm-message" tabindex="-1"></div>
					<input type="button" class="wcfm_verification_response_button wcfm_submit_button" id="wcfm_verification_response_button" value="<?php _e( 'Update', 'wc-frontend-manager-ultimate' ); ?>" />
				</form>
				<?php
			}
		}
		die;
	}

	function wcfmu_verification_response_update() {
		global $WCFM, $WCFMu, $_POST, $wpdb;

		$wcfm_verification_response_form_data = array();
	  parse_str($_POST['wcfm_verification_response_form'], $wcfm_verification_response_form_data);

		if( isset( $wcfm_verification_response_form_data['wcfm_verification_message_id'] ) && isset($wcfm_verification_response_form_data['wcfm_verification_vendor_id']) ) {
			$message_id = absint( $wcfm_verification_response_form_data['wcfm_verification_message_id'] );
			$vendor_id  = absint( $wcfm_verification_response_form_data['wcfm_verification_vendor_id'] );

			if( $vendor_id && $message_id ) {
				$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );

				$verification_note   = $wcfm_verification_response_form_data['wcfm_verification_response_note'];
				$verification_status = $wcfm_verification_response_form_data['wcfm_verification_response_status'];

				$vendor_verification_data['verification_status'] = $verification_status;
				$vendor_verification_data['verification_note']   = $verification_note;
				update_user_meta( $vendor_id, 'wcfm_verification_status', $verification_status );
				update_user_meta( $vendor_id, 'wcfm_vendor_verification_data', $vendor_verification_data );

				// Verification Vendor Notification
				$author_id = -1;
				$author_is_admin = 1;
				$author_is_vendor = 0;
				$message_to = $vendor_id;
				if( $verification_status == 'reject' ) {
					$wcfm_messages = __( '<b>Opps!!!</b> Your verification rejected, please try again. <br />Added note: ', 'wc-frontend-manager-ultimate' ) . $verification_note;
				} else {
					$wcfm_messages = __( '<b>Congratulation!!!</b> Your verification approved. <br />Added note: ', 'wc-frontend-manager-ultimate' ) . $verification_note;
				}
				$WCFM->frontend->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'verification' );

				// Verification message mark read
				$author_id = apply_filters( 'wcfm_message_author', get_current_user_id() );
				$todate = date('Y-m-d H:i:s');

				$wcfm_read_message     = "INSERT into {$wpdb->prefix}wcfm_messages_modifier
																		(`message`, `is_read`, `read_by`, `read_on`)
																		VALUES
																		({$message_id}, 1, {$author_id}, '{$todate}')";
				$wpdb->query($wcfm_read_message);


				echo '{"status": true, "message": "' . __( 'Verification ststus successfully updated.', 'wc-frontend-manager-ultimate' ) . '"}';
				die;
			}
		}
		echo '{"status": false, "message": "' . __( 'Verification ststus update failed.', 'wc-frontend-manager-ultimate' ) . '"}';
		die;
	}

	function wcfm_verification_message_types( $message_types ) {
		$message_types['verification'] = __( 'Vendor Verification', 'wc-frontend-manager-ultimate' );
		return $message_types;
	}

	function get_wcfm_verification_badge() {
		global $WCFM, $WCFMu;

		$vendor_verification_options = (array) get_option( 'wcfm_vendor_verification_options' );

		$verification_badge = isset( $vendor_verification_options['verification_badge'] ) ? $vendor_verification_options['verification_badge'] : '';
		if( !$verification_badge ) $verification_badge = $WCFMu->plugin_url . 'assets/images/verification_badge.png';

		return $verification_badge;
	}

	function is_verified_vendor( $vendor_id ) {
		global $WCFM, $WCFMu;

		$vendor_verification_data = (array) get_user_meta( $vendor_id, 'wcfm_vendor_verification_data', true );

		$verification_status = 'noprompt';
		if( !empty( $vendor_verification_data ) && isset( $vendor_verification_data['verification_status'] ) ) $verification_status = $vendor_verification_data['verification_status'];

		$social_verification_status = 'pending';
		if( !empty( $vendor_verification_data ) && isset( $vendor_verification_data['social_verification_status'] ) ) $social_verification_status = $vendor_verification_data['social_verification_status'];

		if( ( $verification_status == 'approve' ) && ( $social_verification_status == 'approve' ) ) return true;

		return false;
	}

	function after_wcfm_dashboard_user( $username ) {
		global $WCFM, $WCFMu;
		$vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		if( $this->is_verified_vendor( $vendor_id ) ) {
			$badge = $this->get_wcfm_verification_badge();
			if( $badge ) {
				 $username .= '<div class="wcfm_vendor_badge text_tip"  data-tip="' . __( 'Verified Vendor', 'wc-frontend-manager-ultimate' ) . '"><img src="' . $badge . '" /></div>';
			}
		}

		return $username;
	}

	function show_verified_seller_badge( $vendor_id, $badge_classses ) {
		global $WCFM, $WCFMu;
		if( $vendor_id ) {
			if( $this->is_verified_vendor( $vendor_id ) ) {
				$badge = $this->get_wcfm_verification_badge();
				if( $badge ) {
					echo '<div class="'.$badge_classses.' text_tip" data-tip="' . __( 'Verified Vendor', 'wc-frontend-manager-ultimate' ) . '"><img src="' . $badge . '" /></div>';
				}
			}
		}
	}

	function show_verified_seller_badge_by_name( $name, $vendor_id, $badge_classses ) {
		global $WCFM, $WCFMu;
		if( $vendor_id ) {
			if( $this->is_verified_vendor( $vendor_id ) ) {
				$badge = $this->get_wcfm_verification_badge();
				if( $badge ) {
					$name .= '<div class="'.$badge_classses.' text_tip" data-tip="' . __( 'Verified Vendor', 'wc-frontend-manager-ultimate' ) . '"><img src="' . $badge . '" /></div>';
				}
			}
		}
		return $name;
	}
}
