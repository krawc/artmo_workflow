<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers
 * @version   1.0.0
 */

class WCFM_Products_Manage_Controller {

	public function __construct() {
		global $WCFM;

		$this->processing();
	}

	public function processing() {
		global $WCFM, $wpdb, $_POST;

		$wcfm_products_manage_form_data = array();
	  parse_str($_POST['wcfm_products_manage_form'], $wcfm_products_manage_form_data);
	  $wcfm_products_manage_messages = get_wcfm_products_manager_messages();
	  $has_error = false;

		$dataFilled = ((!empty($wcfm_products_manage_form_data['featured_img'])
			 	&& !empty($wcfm_products_manage_form_data['year'])
				&& !empty($wcfm_products_manage_form_data['materialMedia'])
				&& !empty($wcfm_products_manage_form_data['art_length'])
				&& !empty($wcfm_products_manage_form_data['art_height'])
				&& !empty($wcfm_products_manage_form_data['product_custom_taxonomies'])
				&& !(empty($wcfm_products_manage_form_data['youTheArtist']) && empty($wcfm_products_manage_form_data['artistFirstName']))
				&& !(!empty($wcfm_products_manage_form_data['series']) && empty($wcfm_products_manage_form_data['series_name']))
				&& !(!empty($wcfm_products_manage_form_data['edition']) && empty($wcfm_products_manage_form_data['edition_no']) && empty($wcfm_products_manage_form_data['edition_no']))
				&& !(!empty($wcfm_products_manage_form_data['dimensions'] ) && empty($wcfm_products_manage_form_data['art_width']))
				&& !(!empty($wcfm_products_manage_form_data['inFrame'] ) && empty($wcfm_products_manage_form_data['framed_length']))
				&& !(!empty($wcfm_products_manage_form_data['inFrame'] ) && empty($wcfm_products_manage_form_data['framed_height']))
			) ? true : false);

		$is_draft = ((isset($_POST['status']) && ($_POST['status'] == 'draft')) ? true : false);

		if ($dataFilled || $is_draft) {

	  	$is_update = false;
	  	$is_publish = false;

		  $current_user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );

	  	// WCFM form custom validation filter
	  	$custom_validation_results = apply_filters( 'wcfm_form_custom_validation', $wcfm_products_manage_form_data, 'product_manage' );
	  	if(isset($custom_validation_results['has_error']) && !empty($custom_validation_results['has_error'])) {
	  		$custom_validation_error = __( 'There has some error in submitted data.', 'wc-frontend-manager' );
	  		if( isset( $custom_validation_results['message'] ) && !empty( $custom_validation_results['message'] ) ) { $custom_validation_error = $custom_validation_results['message']; }
	  		echo '{"status": false, "message": "' . $custom_validation_error . '"}';
	  		die;
	  	}

	  	if(isset($_POST['status']) && ($_POST['status'] == 'draft')) {
	  		$product_status = 'draft';
	  	} else {
	  		if( current_user_can( 'publish_products' ) && apply_filters( 'wcfm_is_allow_publish_products', true ) )
	  			$product_status = 'publish';
	  		else
	  		  $product_status = 'pending';
			}

			//get form variables and verify for empty


	  	// Creating new product
			$new_product = apply_filters( 'wcfm_product_content_before_save', array(
				'post_title'   => wc_clean( $wcfm_products_manage_form_data['title'] ),
				'post_status'  => $product_status,
				'post_type'    => 'product',
				'post_excerpt' => apply_filters( 'wcfm_editor_content_before_save', stripslashes( html_entity_decode( $_POST['excerpt'], ENT_QUOTES, 'UTF-8' ) ) ),
				'post_content' => apply_filters( 'wcfm_editor_content_before_save', stripslashes( html_entity_decode( $_POST['description'], ENT_QUOTES, 'UTF-8' ) ) ),
				'post_author'  => $current_user_id
				//'post_name' => sanitize_title($wcfm_products_manage_form_data['title'])
			), $wcfm_products_manage_form_data );

			if(isset($wcfm_products_manage_form_data['pro_id']) && $wcfm_products_manage_form_data['pro_id'] == 0) {
				if ($product_status != 'draft') {
					$is_publish = true;
				}
				$new_product_id = wp_insert_post( $new_product, true );

			} else { // For Update
				$is_update = true;
				$new_product['ID'] = $wcfm_products_manage_form_data['pro_id'];
				unset( $new_product['post_author'] );
				if( ($product_status != 'draft') && (get_post_status( $new_product['ID'] ) == 'publish') ) {
					if( apply_filters( 'wcfm_is_allow_publish_live_products', true ) ) {
						$new_product['post_status'] = 'publish';
					} else {
						$new_product['post_status'] = 'pending';
					}
				} else if( (get_post_status( $new_product['ID'] ) == 'draft') && ($product_status != 'draft') ) {
					$is_publish = true;
				}
				$new_product_id = wp_update_post( $new_product, true );
			}

			if(!is_wp_error($new_product_id)) {

				// For Update - send a private message if the product is being updated after rejection
				if($is_update) {
					$new_product_id = $wcfm_products_manage_form_data['pro_id'];
					if (!empty(get_post_meta($new_product_id, 'note_to_vendor', true))) {
						artmo_send_private_message( 1, $current_user_id, 'PRODUCT UPDATE: ' .  get_permalink( $new_product_id ) );
					}
				}

				//CONSTANTS: Set manage stock to yes by default and stock quantity to 1
				if(!$is_update) {
					update_post_meta($new_product_id, '_sold_individually', '1');
					update_post_meta($new_product_id, '_manage_stock', '1');
					update_post_meta($new_product_id, '_stock', '1');
				}

				$checkboxFields = array('youTheArtist', 'series', 'priceOnRequest', 'edition', 'dimensions', 'inFrame');
				$textFields = array('artistFirstName', 'artistLastName', 'regular_price', 'edition_no', 'edition_t', 'edition_name', 'year', 'materialMedia', 'framed_length', 'framed_height', 'art_length', 'art_width', 'art_height', 'artDescription');


				foreach ($checkboxFields as $field) {
					//Update checkbox fields in bulk
					if(isset($wcfm_products_manage_form_data[$field]) && ($wcfm_products_manage_form_data[$field])) {
						update_post_meta( $new_product_id, $field, $wcfm_products_manage_form_data[$field] );
					} else {
						update_post_meta( $new_product_id, $field, '0' );
					}
				}

				foreach ($textFields as $field) {
					//Update text/number fields in bulk
					if(isset($wcfm_products_manage_form_data[$field])) {
						update_post_meta( $new_product_id, $field, $wcfm_products_manage_form_data[$field] );
					}
				}


				// specific workaround for Series and Author category assignment

				if(isset($wcfm_products_manage_form_data['series_name']) && !empty($wcfm_products_manage_form_data['series_name'])) {
					$author_id = get_post_field( 'post_author', $new_product_id );

						if(empty($author_id)) {
							$author_id = $current_user_id;
						}

						$author_cat = 'artist_'.$author_id;
						$author_cat_obj = get_term_by('slug', $author_cat, 'artists_cat');
						$parent_id = $author_cat_obj->term_id;
						$new_slug = sanitize_title($wcfm_products_manage_form_data['series_name']);
						$term_exists = term_exists( $new_slug, 'artists_cat', intval($parent_id));

						if ( $term_exists !== 0 && $term_exists !== null && is_array($term_exists) ) {
							$term_id = $term_exists['term_id'];
							$cat_ids = array($term_id, $parent_id);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms( $new_product_id, $cat_ids, 'artists_cat' );
						} else {
							$series_term = wp_insert_term(
								$wcfm_products_manage_form_data['series_name'],   // the term
								'artists_cat', // the taxonomy
								array(
									'description' => '',
									'slug' => $new_slug,
									'parent' => intval($parent_id)
								)
							);

							$new_id = $series_term['term_id'];
							$cat_ids = array($new_id, $parent_id);
							$cat_ids = array_map( 'intval', $cat_ids );
							$cat_ids = array_unique( $cat_ids );
							wp_set_object_terms( $new_product_id, $cat_ids, 'artists_cat' );
						}
						update_post_meta( $new_product_id, 'series_name', $wcfm_products_manage_form_data['series_name'] );

				} else {

						$author_id = get_post_field( 'post_author', $new_product_id );

						if(empty($author_id)) {
							$author_id = $current_user_id;
						}

						$author_cat = 'artist_'.$author_id;
						$author_cat_obj = get_term_by('slug', $author_cat, 'artists_cat');
						$parent_id = $author_cat_obj->term_id;
						$cat_ids = array($parent_id);
						$cat_ids = array_map( 'intval', $cat_ids );
						wp_set_object_terms( $new_product_id, $cat_ids, 'artists_cat');

				}


				//Update Artwork Country of Production
				if(isset($wcfm_products_manage_form_data['countryProd']) && !empty($wcfm_products_manage_form_data['countryProd'])) {
					update_post_meta( $new_product_id, 'countryProd', $wcfm_products_manage_form_data['countryProd'] );
					wp_set_post_terms( $new_product_id, sanitize_title($wcfm_products_manage_form_data['countryProd']), 'country_cat' );
				} else {
					$auth_country = get_user_meta($current_user_id, 'countryField', true);
					update_post_meta( $new_product_id, 'countryProd', $auth_country);
					wp_set_post_terms( $new_product_id, sanitize_title($auth_country), 'country_cat' );
				}

				// Process product type first so we have the correct class to run setters.
				$product_type = empty( $wcfm_products_manage_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $wcfm_products_manage_form_data['product_type'] ) );
				$classname    = WC_Product_Factory::get_product_classname( $new_product_id, $product_type ? $product_type : 'simple' );
				$product      = new $classname( $new_product_id );
				$errors       = $product->set_props( apply_filters( 'wcfm_product_data_factory', array(
					'virtual'            => isset( $wcfm_products_manage_form_data['is_virtual'] ),
					'sku'                => isset( $wcfm_products_manage_form_data['sku'] ) ? wc_clean( $wcfm_products_manage_form_data['sku'] ) : null,
					'tax_status'         => isset( $wcfm_products_manage_form_data['tax_status'] ) ? wc_clean( $wcfm_products_manage_form_data['tax_status'] ) : null,
					'tax_class'          => isset( $wcfm_products_manage_form_data['tax_class'] ) ? wc_clean( $wcfm_products_manage_form_data['tax_class'] ) : null,
					'weight'             => wc_clean( $wcfm_products_manage_form_data['weight'] ),
					'length'             => wc_clean( $wcfm_products_manage_form_data['length'] ),
					'width'              => wc_clean( $wcfm_products_manage_form_data['width'] ),
					'height'             => wc_clean( $wcfm_products_manage_form_data['height'] ),
					'shipping_class_id'  => absint( $wcfm_products_manage_form_data['shipping_class'] ),
					'upsell_ids'         => isset( $wcfm_products_manage_form_data['upsell_ids'] ) ? array_map( 'intval', (array) $wcfm_products_manage_form_data['upsell_ids'] ) : array(),
					'cross_sell_ids'     => isset( $wcfm_products_manage_form_data['crosssell_ids'] ) ? array_map( 'intval', (array) $wcfm_products_manage_form_data['crosssell_ids'] ) : array(),
					'regular_price'      => wc_clean( $wcfm_products_manage_form_data['regular_price'] ),
					'sale_price'         => wc_clean( $wcfm_products_manage_form_data['sale_price'] ),
					'backorders'         => wc_clean( $wcfm_products_manage_form_data['backorders'] ),
					'stock_status'       => wc_clean( $wcfm_products_manage_form_data['stock_status'] ),
					'product_url'        => esc_url_raw( $wcfm_products_manage_form_data['product_url'] ),
					'button_text'        => wc_clean( $wcfm_products_manage_form_data['button_text'] ),
					'children'           => 'grouped' === $product_type ? $grouped_products : null,
					'attributes'         => $pro_attributes,
					'default_attributes' => $default_attributes,
					'reviews_allowed'    => true,
				), $new_product_id, $product, $wcfm_products_manage_form_data ) );


				// Set Product Type
				wp_set_object_terms( $new_product_id, $wcfm_products_manage_form_data['product_type'], 'product_type' );

				if ( is_wp_error( $errors ) ) {
					if( !$has_error )
						echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';
					$has_error = true;
				}

				$product->save();

				// Set Product Custom Taxonomies
				if(isset($wcfm_products_manage_form_data['product_custom_taxonomies']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies'])) {
					foreach($wcfm_products_manage_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
						if( !empty( $taxonomy_values ) ) {
							$is_first = true;
							foreach( $taxonomy_values as $taxonomy_value ) {
								if($is_first) {
									$is_first = false;
									wp_set_object_terms( $new_product_id, (int)$taxonomy_value, $taxonomy );
								} else {
									wp_set_object_terms( $new_product_id, (int)$taxonomy_value, $taxonomy, true );
								}
							}
						}
					}
				}

				// Set Product Tags
				if(isset($wcfm_products_manage_form_data['product_tags']) && !empty($wcfm_products_manage_form_data['product_tags'])) {
					wp_set_post_terms( $new_product_id, $wcfm_products_manage_form_data['product_tags'], 'product_tag' );
				}

				if(isset($wcfm_products_manage_form_data['genre_tag']) && !empty($wcfm_products_manage_form_data['genre_tag'])) {
					wp_set_post_terms( $new_product_id, $wcfm_products_manage_form_data['genre_tag'], 'genre_tag' );
				}

				if(isset($wcfm_products_manage_form_data['theme_tag']) && !empty($wcfm_products_manage_form_data['theme_tag'])) {
					wp_set_post_terms( $new_product_id, $wcfm_products_manage_form_data['theme_tag'], 'theme_tag' );
				}


				// Set Product Custom Taxonomies Flat
				if(isset($wcfm_products_manage_form_data['product_custom_taxonomies_flat']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies_flat'])) {
					foreach($wcfm_products_manage_form_data['product_custom_taxonomies_flat'] as $taxonomy => $taxonomy_values) {
						if( !empty( $taxonomy_values ) ) {
							wp_set_post_terms( $new_product_id, $taxonomy_values, $taxonomy );
						}
					}
				}


				// Set Product Featured Image
				if(isset($wcfm_products_manage_form_data['featured_img']) && !empty($wcfm_products_manage_form_data['featured_img'])) {
					$featured_img_id = $WCFM->wcfm_get_attachment_id($wcfm_products_manage_form_data['featured_img']);
					set_post_thumbnail( $new_product_id, $featured_img_id );
					$current_user_id = get_current_user_id();
					$author_cat = 'artist_'.$current_user_id;
					$author_cat_obj = get_term_by('slug', $author_cat, 'artists_cat');
					$parent_id = $author_cat_obj->term_id;

					wp_set_object_terms( $featured_img_id, (int)$parent_id, 'artists_cat' );

					if(isset($wcfm_products_manage_form_data['product_custom_taxonomies']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies'])) {
						foreach($wcfm_products_manage_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
							if( !empty( $taxonomy_values ) ) {
								$is_first = true;
								foreach( $taxonomy_values as $taxonomy_value ) {
									if($is_first) {
										$is_first = false;
										wp_set_object_terms( $featured_img_id, (int)$taxonomy_value, $taxonomy );
									} else {
										wp_set_object_terms( $featured_img_id, (int)$taxonomy_value, $taxonomy, true );
									}
								}
							}
						}
					}

					// Set Product Custom Taxonomies Flat
					if(isset($wcfm_products_manage_form_data['product_custom_taxonomies_flat']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies_flat'])) {
						foreach($wcfm_products_manage_form_data['product_custom_taxonomies_flat'] as $taxonomy => $taxonomy_values) {
							if( !empty( $taxonomy_values ) ) {
								wp_set_post_terms( $featured_img_id, $taxonomy_values, $taxonomy );
							}
						}
					}
				}
				elseif(isset($wcfm_products_manage_form_data['featured_img']) && empty($wcfm_products_manage_form_data['featured_img'])) {
					delete_post_thumbnail( $new_product_id );
				}

				do_action( 'after_wcfm_products_manage_meta_save', $new_product_id, $wcfm_products_manage_form_data );

				if(!$has_error) {
					if( get_post_status( $new_product_id ) == 'publish' ) {
						if(!$has_error) echo '{"status": true, "message": "' . apply_filters( 'product_published_message', $wcfm_products_manage_messages['product_published'], $new_product_id ) . '", "id": "' . $new_product_id . '", "title": "' . get_the_title( $new_product_id ) . '"}';
					} elseif( get_post_status( $new_product_id ) == 'pending' ) {
						if(!$has_error) echo '{"status": true, "message": "' . apply_filters( 'product_pending_message', $wcfm_products_manage_messages['product_pending'], $new_product_id ) . '", "id": "' . $new_product_id . '", "title": "' . get_the_title( $new_product_id ) . '"}';
					} else {
						if(!$has_error) echo '{"status": true, "message": "' . apply_filters( 'product_saved_message', $wcfm_products_manage_messages['product_saved'], $new_product_id ) . '", "id": "' . $new_product_id . '"}';
					}
				}
				die;
			}
		} else {
			echo '{"status": false, "message": "Missing artwork data. For now, you may save it as draft."}';
		}
	  die;
	}
}
