<?php
/**
 * WCFMu plugin controllers
 *
 * Plugin Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmu/controllers
 * @version   1.0.0
 */
class WCFMu_Products_Manage_Controller {

	public function __construct() {
		global $WCFM, $WCFMu;

		// WCFMu Product Manage Additional Data Save
		add_filter( 'wcfm_product_data_factory', array( &$this, 'wcfmu_product_data_factory' ), 10, 4 );
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'after_wcfmu_products_manage_meta_save' ), 10, 2 );
    add_filter( 'wcfm_product_variation_data_factory', array( &$this, 'wcfmu_product_variation_data_factory' ), 10, 5 );
	}

	/**
	 * WCFMu Product data factory
	 */
	function wcfmu_product_data_factory( $wcfm_data, $new_product_id, $product, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMu;

		// file paths will be stored in an array keyed off md5(file path)
		$downloadables = array();
		if ( isset( $wcfm_products_manage_form_data['is_downloadable'] ) && isset( $wcfm_products_manage_form_data['downloadable_files'] ) ) {
			foreach ( $wcfm_products_manage_form_data['downloadable_files'] as $downloadable_files ) {
				if ( !empty($downloadable_files['name']) && !empty( $downloadable_files['file'] ) ) {
					$downloadables[] = array(
						'name' => wc_clean( $downloadable_files['name'] ),
						'file' => wp_unslash( trim( $downloadable_files['file'] ) ),
						'download_id' => $downloadable_files['id'],
					);
				}
			}
		}

		$wcfmu_data = array(
											  //'featured'           => isset( $wcfm_products_manage_form_data['featured'] ),
											  //'catalog_visibility' => wc_clean( $wcfm_products_manage_form_data['visibility'] ),
											  'date_on_sale_from'  => wc_clean( $wcfm_products_manage_form_data['sale_date_from'] ),
											  'date_on_sale_to'    => wc_clean( $wcfm_products_manage_form_data['sale_date_upto'] ),
												'downloadable'       => isset( $wcfm_products_manage_form_data['is_downloadable'] ),
												'download_limit'     => '' === $wcfm_products_manage_form_data['download_limit'] ? '' : absint( $wcfm_products_manage_form_data['download_limit'] ),
												'download_expiry'    => '' === $wcfm_products_manage_form_data['download_expiry'] ? '' : absint( $wcfm_products_manage_form_data['download_expiry'] ),
												'downloads'          => $downloadables,
												'reviews_allowed'    => ! empty( $wcfm_products_manage_form_data['enable_reviews'] ),
												'menu_order'         => absint( $wcfm_products_manage_form_data['menu_order'] ),
												'purchase_note'      => wp_kses_post( stripslashes( $wcfm_products_manage_form_data['purchase_note'] ) ),
												);

		if( !apply_filters( 'wcfmu_is_allow_downloadable', true ) ) {
			unset( $wcfmu_data['downloadable'] );
			unset( $wcfmu_data['download_limit'] );
			unset( $wcfmu_data['download_expiry'] );
			unset( $wcfmu_data['downloads'] );
		}

		if( !apply_filters( 'wcfm_is_allow_advanced', true ) ) {
			unset( $wcfmu_data['reviews_allowed'] );
			unset( $wcfmu_data['menu_order'] );
			unset( $wcfmu_data['purchase_note'] );
		}

		$wcfm_data = array_merge( $wcfm_data, $wcfmu_data );
		return $wcfm_data;
	}

	/**
	 * Product Additional Data Save
	 */
	function after_wcfmu_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMu;

		// Set Product Image Gallery
		if( apply_filters( 'wcfm_is_allow_gallery', true ) ) {
			if( isset($wcfm_products_manage_form_data['gallery_img']) && !empty($wcfm_products_manage_form_data['gallery_img']) ) {
				$gallery = array();
				$gallerylimit = apply_filters( 'wcfm_gallerylimit', -1 );
				if( $gallerylimit == '-1' ) $gallerylimit = 500;
				foreach($wcfm_products_manage_form_data['gallery_img'] as $gallery_imgs) {
					if(isset($gallery_imgs['image']) && !empty($gallery_imgs['image'])) {
						$gallery_img_id = $WCFM->wcfm_get_attachment_id($gallery_imgs['image']);

						$current_user_id = get_current_user_id();
						$author_cat = 'artist_'.$current_user_id;
						$author_cat_obj = get_term_by('slug', $author_cat, 'artists_cat');
						$parent_id = $author_cat_obj->term_id;

						wp_set_object_terms( $gallery_img_id, (int)$parent_id, 'artists_cat' );

						if(isset($wcfm_products_manage_form_data['product_custom_taxonomies']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies'])) {
							foreach($wcfm_products_manage_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
								if( !empty( $taxonomy_values ) ) {
									$is_first = true;
									foreach( $taxonomy_values as $taxonomy_value ) {
										if($is_first) {
											$is_first = false;
											wp_set_object_terms( $gallery_img_id, (int)$taxonomy_value, $taxonomy );
										} else {
											wp_set_object_terms( $gallery_img_id, (int)$taxonomy_value, $taxonomy, true );
										}
									}
								}
							}
						}
						if(isset($wcfm_products_manage_form_data['genre_tag']) && !empty($wcfm_products_manage_form_data['genre_tag'])) {
							wp_set_post_terms( $gallery_img_id, $wcfm_products_manage_form_data['genre_tag'], 'genre_tag' );
						}
						// Set Product Custom Taxonomies Flat
						if(isset($wcfm_products_manage_form_data['product_custom_taxonomies_flat']) && !empty($wcfm_products_manage_form_data['product_custom_taxonomies_flat'])) {
							foreach($wcfm_products_manage_form_data['product_custom_taxonomies_flat'] as $taxonomy => $taxonomy_values) {
								if( !empty( $taxonomy_values ) ) {
									wp_set_post_terms( $gallery_img_id, $taxonomy_values, $taxonomy );
								}
							}
						}

						$gallery[] = $gallery_img_id;
						if( $gallerylimit == count( $gallery ) ) break;
					}
				}
				if ( ! empty( $gallery ) ) {
					update_post_meta( $new_product_id, '_product_image_gallery', implode( ',', $gallery ) );
				} else {
					update_post_meta( $new_product_id, '_product_image_gallery', '' );
				}
			} elseif( isset($wcfm_products_manage_form_data['gallery_img']) && empty($wcfm_products_manage_form_data['gallery_img']) ) {
				update_post_meta( $new_product_id, '_product_image_gallery', '' );
			}
		}
	}

	/**
	 * Product Variation Additional Data Save
	 */
	function wcfmu_product_variation_data_factory( $wcfm_variation_data, $new_product_id, $variation_id, $variations, $wcfm_products_manage_form_data ) {
	 	  global $wpdb, $WCFM, $WCFMu;

	 	  // Variation Download options
	 	  $downloadables = array();
			if ( isset( $variations['is_downloadable'] ) && isset( $variations['downloadable_file'] ) && $variations['downloadable_file'] && !empty( $variations['downloadable_file'] ) ) {
				$downloadables[] = array(
					'name' => wc_clean( $variations['downloadable_file_name'] ),
					'file' => wp_unslash( trim( $variations['downloadable_file'] ) ),
					'download_id' => md5( $variations['downloadable_file'] ),
				);
			}

	 	  $wcfmu_variation_data = array(
											  'virtual'           => isset( $variations['is_virtual'] ),
												'downloadable'      => isset( $variations['is_downloadable'] ),
												'date_on_sale_from' => wc_clean( $variations['sale_price_dates_from'] ),
												'date_on_sale_to'   => wc_clean( $variations['sale_price_dates_to'] ),
											  'description'       => wp_kses_post( $variations['description'] ),
											  'download_limit'    => wc_clean( $variations['download_limit'] ),
												'download_expiry'   => wc_clean( $variations['download_expiry'] ),
												'downloads'         => $downloadables,
												'weight'            => isset( $variations['weight'] ) ? wc_clean( $variations['weight'] ) : '',
												'length'            => isset( $variations['length'] ) ? wc_clean( $variations['length'] ) : '',
												'width'             => isset( $variations['width'] ) ? wc_clean( $variations['width'] )   : '',
												'height'            => isset( $variations['height'] ) ? wc_clean( $variations['height'] ) : '',
												'shipping_class_id' => wc_clean( $variations['shipping_class'] ),
												'tax_class'         => isset( $variations['tax_class'] ) ? wc_clean( $variations['tax_class'] ) : null,
											  );
	 	  $wcfm_variation_data = array_merge( $wcfm_variation_data, $wcfmu_variation_data );
	 	  return $wcfm_variation_data;
	 }
}
