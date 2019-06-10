<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Followers Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers/followers
 * @version   4.0.6
 */

class WCFMu_Followers_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $_POST;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$vendor_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		
		$the_orderby = ! empty( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'ID';
		$the_order   = ( ! empty( $_POST['order'] ) && 'asc' === $_POST['order'] ) ? 'ASC' : 'DESC';
		
		$items_per_page = $length;
		
		$sql = "SELECT count(ID) FROM {$wpdb->prefix}wcfm_following_followers";
		$sql .= " WHERE 1 = 1";
		
		$sql .= " AND `user_id` = {$vendor_id}";
		$sql = apply_filters( 'wcfm_followers_count_query', $sql);
		$total_followers = $wpdb->get_var( $sql );
		
		$followers_query = "SELECT * FROM {$wpdb->prefix}wcfm_following_followers AS followers";
		$followers_query .= " WHERE 1 = 1";
		$followers_query .= " AND `user_id` = {$vendor_id}";
		$followers_query = apply_filters( 'wcfm_followers_list_query', $followers_query );
		
		$followers_query .= " ORDER BY followers.`{$the_orderby}` {$the_order}";
		$followers_query .= " LIMIT {$items_per_page}";
		$followers_query .= " OFFSET {$offset}";
		
		
		$wcfm_followerss_array = $wpdb->get_results( $followers_query );
		
		// Generate Followerss JSON
		$wcfm_followerss_json = '';
		$wcfm_followerss_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $total_followers . ',
															"recordsFiltered": ' . $total_followers . ',
															"data": ';
		if(!empty($wcfm_followerss_array)) {
			$index = 0;
			$wcfm_followerss_json_arr = array();
			foreach($wcfm_followerss_array as $wcfm_followerss_single) {
				// Follower Name
				$wcfm_followerss_json_arr[$index][] =  $wcfm_followerss_single->follower_name;
				
				// Follower Email
				$wcfm_followerss_json_arr[$index][] =  $wcfm_followerss_single->follower_email;
				
				// Action
				//$actions = '<a class="wcfm-action-icon" href="' . get_wcfm_followers_manage_url($wcfm_followerss_single->ID) . '"><span class="fa fa-reply text_tip" data-tip="' . esc_attr__( 'Reply', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				$actions = '<a class="wcfm_followers_delete wcfm-action-icon" href="#" data-lineid="' . $wcfm_followerss_single->ID . '" data-followersid="' . $wcfm_followerss_single->follower_id . '" data-userid="' . $wcfm_followerss_single->user_id . '"><span class="fa fa-trash-o text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				$wcfm_followerss_json_arr[$index][] = apply_filters ( 'wcfm_followers_actions', $actions, $wcfm_followerss_single );
				
				$index++;
			}												
		}
		if( !empty($wcfm_followerss_json_arr) ) $wcfm_followerss_json .= json_encode($wcfm_followerss_json_arr);
		else $wcfm_followerss_json .= '[]';
		$wcfm_followerss_json .= '
													}';
													
		echo $wcfm_followerss_json;
	}
}