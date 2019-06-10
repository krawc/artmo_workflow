<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


add_action( 'customize_register', 'mytheme_customize_register' );

function mytheme_customize_register( $wp_customize ) {

  class Text_Editor_Custom_Control extends WP_Customize_Control
  {
        /**
         * Render the content on the theme customizer page
         */
        public function render_content()
         {
              ?>
              <label>
              <span class="customize-text_editor"><?php echo esc_html( $this->label ); ?></span>
              <?php $settings = array(
                        'textarea_name' => $this->id
                    );
                    wp_editor($this->value(), $this->id, $settings ); ?>
              </label>
              <?php
         }
  }

  $wp_customize->add_section( 'global_admin_texts' , array(
    'title'      => __( 'ARTMO Global Admin Texts', 'vantage-child' ),
    'priority'   => 30,
  ) );

  $wp_customize->add_setting( 'vendor_notice' , array(
	 'default'   => '',
	 'transport' => 'refresh',
	) );

  $wp_customize->add_setting( 'global_shipping_policy' , array(
   'default'   => '',
   'transport' => 'refresh',
  ) );

    $wp_customize->add_setting( 'profile_embed_image_black_square' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_black_banner' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_black_logo' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_black_icon' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_white_square' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_white_banner' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_white_logo' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_setting( 'profile_embed_image_white_icon' , array(
     'default'   => '',
     'transport' => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'vendor_notice', array(
      'label'      => __( 'Notice to Vendors', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'vendor_notice',
      'type' => 'textarea'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'global_shipping_policy', array(
      'label'      => __( 'Shipping Policy', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'global_shipping_policy',
      'type' => 'textarea'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_black_square', array(
      'label'      => __( 'Profile Embed Image - Black, Square', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_black_square'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_black_banner', array(
      'label'      => __( 'Profile Embed Image - Black, Banner', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_black_banner'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_black_logo', array(
      'label'      => __( 'Profile Embed Image - Black, Logo', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_black_logo'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_black_icon', array(
      'label'      => __( 'Profile Embed Image - Black, Icon', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_black_icon'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_white_square', array(
      'label'      => __( 'Profile Embed Image - White, Square', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_white_square'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_white_banner', array(
      'label'      => __( 'Profile Embed Image - White, Banner', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_white_banner'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_white_logo', array(
      'label'      => __( 'Profile Embed Image - White, Logo', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_white_logo'
    ) ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'profile_embed_image_white_icon', array(
      'label'      => __( 'Profile Embed Image - White, Icon', 'vantage-child' ),
      'section'    => 'global_admin_texts',
      'settings'   => 'profile_embed_image_white_icon'
    ) ) );

}
