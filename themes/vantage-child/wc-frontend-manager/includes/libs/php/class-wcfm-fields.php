<?php
if(!class_exists('WCFM_Fields')) {
class WCFM_Fields {
  /**
   * Start up
   */
  public function __construct() {

  }

  /**
   * Output a hidden input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  function hidden_input( $field ) {

    $field['value'] = isset( $field['value'] ) ? $field['value'] : '';
    $field['class'] = isset( $field['class'] ) ? $field['class'] : 'hidden';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) )
      foreach ( $field['custom_attributes'] as $attribute => $value )
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

    printf(
        '<input type="hidden" id="%s" name="%s" class="%s" value="%s" %s />',
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($field['value']),
        implode( ' ', $custom_attributes )
    );
  }
  /**
   * Output a Title
   *
   * @access public
   * @param array $field
   * @return void
   */
  function title_input( $field ) {
    $label =  isset($field['label']) ?  $field['label'] : '';
    echo "<h3>".$label."</h3>";
  }

  /**
   * Output a text input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function text_input($field) {
    $field['placeholder'] 	= isset( $field['placeholder'] ) ? $field['placeholder'] : '';
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'regular-text';
    $field['dfvalue'] 	= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : $field['dfvalue'];
    if(empty($field['value'])) {
    	$field['value'] =  $field['dfvalue'];
    }
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['type'] 			= isset( $field['type'] ) ? $field['type'] : 'text';
    if($field['type'] == 'numeric') { $field['type'] = 'number'; }

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    // attribute handling
    $attributes = array();

    if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) )
      foreach ( $field['attributes'] as $attribute => $value )
        $attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

    $field = $this->field_wrapper_start($field);

    printf(
        '<input type="%s" id="%s" name="%s" class="%s" value="%s" placeholder="%s" %s %s />',
        $field['type'],
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($this->string_wpml(''.$field['value'].'')),
        esc_attr($this->string_wpml(''.$field['placeholder'].'')),
        implode( ' ', $custom_attributes ),
        implode( ' ', $attributes )
    );
    $this->field_wrapper_end($field);
  }

  /**
   * Output a textarea input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  function textarea_input( $field ) {

    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['placeholder'] 	= isset( $field['placeholder'] ) ? $field['placeholder'] : '';
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'textarea';
    $field['rows'] 			= isset( $field['rows'] ) ? $field['rows'] : 2;
    $field['cols'] 			= isset( $field['cols'] ) ? $field['cols'] : 20;
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '<textarea id="%s" name="%s" class="%s" placeholder="%s" rows="%s" cols="%s" %s>%s</textarea>',
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($field['placeholder']),
        absint($field['rows']),
        absint($field['cols']),
        implode( ' ', $custom_attributes ),
        esc_textarea($this->string_wpml($field['value']))
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a wp editor box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  function wpeditor_input( $field ) {

    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['rows'] 			= isset( $field['rows'] ) ? $field['rows'] : 5;
    $field['cols'] 			= isset( $field['cols'] ) ? $field['cols'] : 10;
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';

    $field = $this->field_wrapper_start($field);

    wp_editor(stripslashes($field['value']), $field['id'], $settings = array('textarea_name' => $field['name'], 'textarea_rows' => $field['rows']));

    $this->field_wrapper_end($field);
  }

  /**
   * Output a checkbox.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function checkbox_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'checkbox';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['dfvalue'] 		= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';

    // Custom attribute handling
    $custom_attributes = array();
    $custom_tags = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    if ( ! empty( $field['custom_tags'] ) && is_array( $field['custom_tags'] ) ) {
      foreach ( $field['custom_tags'] as $tag => $value ){
        $custom_tags[] = esc_attr( $tag ) . '="' . esc_attr( $value ) . '"';
      }
    }
    $field = $this->field_wrapper_start($field);

    printf(
      '<input type="checkbox" id="%s" name="%s" class="%s" value="%s" %s %s %s />',
      esc_attr($field['id']),
      esc_attr($field['name']),
      esc_attr($field['class']),
      esc_attr($field['value']),
      checked( $field['value'], $field['dfvalue'], false ),
      implode( ' ', $custom_attributes ),
      implode(' ', $custom_tags)
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a checkbox  OFF-ON.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function checkbox_offon_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'checkbox';
    $field['class'] 		.= ' onoffswitch-checkbox';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['id']        = sanitize_title( $field['name'] ) . '-' . $field['id'];
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['dfvalue'] 	= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';

    // Custom attribute handling
    $custom_attributes = array();
    $custom_tags = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    if ( ! empty( $field['custom_tags'] ) && is_array( $field['custom_tags'] ) ){
      foreach ( $field['custom_tags'] as $tag => $value ){
        $custom_tags[] = esc_attr( $tag ) . '="' . esc_attr( $value ) . '"';
      }
    }
    $field = $this->field_wrapper_start($field);

    printf(
        '<div class="onoffswitch">
           <input type="checkbox" id="%s" name="%s" class="%s" value="%s" %s %s %s />
           <label class="onoffswitch-label" for="%s">
					   <span class="onoffswitch-inner"></span>
						 <span class="onoffswitch-switch"></span>
					</label>
        </div>
        ',
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($field['value']),
        checked( $field['value'], $field['dfvalue'], false ),
        implode( ' ', $custom_attributes ),
        implode(' ', $custom_tags),
        esc_attr($field['id'])
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a checklist gruop field.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function checklist_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['dfvalue'] 		= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';
    $field['value'] 		= ( $field['value'] ) ? $field['value'] : array();

    // Custom attribute handling
    $custom_attributes = array();
    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
    	foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }


    $options = '';
    foreach ( $field['options'] as $key => $value ) {
    	if( in_array( $key, $field['value'] ) )
    		$options .= '<label title="' . esc_attr($key) .'"><input class="' . esc_attr($field['class']) . '" type="checkbox" ' . checked( true, true, false ) . ' ' . implode( ' ', $custom_attributes ) . ' value="' . esc_attr($key) . '" name="' . esc_attr($field['name']) . '[]"> <span>' . esc_html($value) . '</span></label><br />';
    	else
    	  $options .= '<label title="' . esc_attr($key) .'"><input class="' . esc_attr($field['class']) . '" type="checkbox" ' . checked( false, true, false ) . ' ' . implode( ' ', $custom_attributes ) . ' value="' . esc_attr($key) . '" name="' . esc_attr($field['name']) . '[]"> <span>' . esc_html($value) . '</span></label><br />';
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '
        <fieldset id="%s" class="%s_field %s wcfm-checklist-group">
          <legend class="screen-reader-text"><span>%s</span></legend>
            %s
        </fieldset>
        ',
        esc_attr($field['id']),
        esc_attr($field['id']),
        esc_attr($field['wrapper_class']),
        esc_attr($field['label']),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a radio gruop field.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function radio_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['dfvalue'] 		= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';
    $field['value'] 		= ( $field['value'] ) ? $field['value'] : $field['dfvalue'];

    // Custom attribute handling
    $custom_attributes = array();
    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
    	foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    $options = '';
    foreach ( $field['options'] as $key => $value ) {
      $options .= '<label title="' . esc_attr($key) .'"><input class="' . esc_attr($field['class']) . '" type="radio" ' . checked( esc_attr($field['value']), esc_attr($key), false ) . ' value="' . esc_attr($key) . '" name="' . esc_attr($field['name']) . '"> <span>' . esc_html($value) . '</span></label><br />';
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '
        <fieldset id="%s" class="%s_field %s wcfm-radio-group">
          <legend class="screen-reader-text"><span>%s</span></legend>
            %s
        </fieldset>
        ',
        esc_attr($field['id']),
        esc_attr($field['id']),
        esc_attr($field['wrapper_class']),
        esc_attr($field['label']),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a radio gruop field.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function radio_offon_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['class'] 		.= ' onoffswitch-radio-checkbox';
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['dfvalue'] 		= isset( $field['dfvalue'] ) ? $field['dfvalue'] : '';
    $field['value'] 		= ( $field['value'] ) ? $field['value'] : $field['dfvalue'];

    $options = '';
    foreach ( $field['options'] as $key => $value ) {
      $options .= '<div class="onoffswitch-radio">
										 <input type="radio" id="option-' . esc_attr($key) . '-' . esc_attr($field['name']) . '" name="' . esc_attr($field['name']) . '" class="' . esc_attr($field['class']) . '" value="' . esc_attr($key) . '" ' . checked( esc_attr($field['value']), esc_attr($key), false ) . ' /><label class="onoffswitch-radio-label" for="option-' . esc_attr($key) . '-' . esc_attr($field['name']) . '"><span class="onoffswitch-radio-inner">' . esc_html($value) . '</span><span class="onoffswitch-radio-switch"></span></label></div>';



      //'<label title="' . esc_attr($key) .'"><input class="' . esc_attr($field['class']) . '" type="radio" ' . checked( esc_attr($field['value']), esc_attr($key), false ) . ' value="' . esc_attr($key) . '" name="' . esc_attr($field['name']) . '"> <span>' . esc_html($value) . '</span></label><br />';
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '
        <fieldset id="%s" class="%s_field %s wcfm-radio-group">
          <legend class="screen-reader-text"><span>%s</span></legend>
            %s
        </fieldset>
        ',
        esc_attr($field['id']),
        esc_attr($field['id']),
        esc_attr($field['wrapper_class']),
        esc_attr($field['label']),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a select input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function select_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    // attribute handling
    $attributes = array();
    $is_multiple = false;
    if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
     foreach ( $field['attributes'] as $attribute => $value ) {
        $attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
        if($attribute  == 'multiple') {
        	$is_multiple = true;
        	$field['name'] .= '[]';
        }
     }
    }

    $options = '';
    foreach ( $field['options'] as $key => $value ) {
    	if( $is_multiple || is_array( $field['value'] ) ) {
    		$options .=  '<option value="' . esc_attr( $key ) . '"' . selected( in_array( $key, (array)$field['value'] ), true, false ) . '>' . esc_html( $value ) . '</option>';
    	} else {
    		$options .= '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $this->string_wpml($value) ) . '</option>';
    	}
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '<select id="%s" name="%s" class="%s" %s %s>%s</select>',
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        implode( ' ', $custom_attributes ),
        implode( ' ', $attributes ),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a country input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function country_input($field) {

    $country_arr = array(
      'Afghanistan' => __('Afghanistan', 'woocommerce'),  'Albania' => __('Albania', 'woocommerce'),  'Algeria' => __('Algeria', 'woocommerce'),  'American Samoa' => __('American Samoa', 'woocommerce'),  'Andorra' => __('Andorra', 'woocommerce'),  'Angola' => __('Angola', 'woocommerce'),  'Anguilla' => __('Anguilla', 'woocommerce'),  'Antigua and Barbuda' => __('Antigua and Barbuda', 'woocommerce'),  'Argentina' => __('Argentina', 'woocommerce'),  'Armenia' => __('Armenia', 'woocommerce'),  'Aruba' => __('Aruba', 'woocommerce'),  'Australia' => __('Australia', 'woocommerce'),  'Austria' => __('Austria', 'woocommerce'),  'Azerbaijan' => __('Azerbaijan', 'woocommerce'),  'Bahamas' => __('Bahamas', 'woocommerce'),  'Bahrain' => __('Bahrain', 'woocommerce'),  'Bangladesh' => __('Bangladesh', 'woocommerce'),  'Barbados' => __('Barbados', 'woocommerce'),  'Belarus' => __('Belarus', 'woocommerce'),  'Belgium' => __('Belgium', 'woocommerce'),  'Belize' => __('Belize', 'woocommerce'),  'Benin' => __('Benin', 'woocommerce'),  'Bermuda' => __('Bermuda', 'woocommerce'),  'Bhutan' => __('Bhutan', 'woocommerce'),  'Bolivia' => __('Bolivia', 'woocommerce'),  'Bosnia-Herzegovina' => __('Bosnia-Herzegovina', 'woocommerce'),  'Botswana' => __('Botswana', 'woocommerce'),  'Bouvet Island' => __('Bouvet Island', 'woocommerce'),  'Brazil' => __('Brazil', 'woocommerce'),  'Brunei' => __('Brunei', 'woocommerce'),  'Bulgaria' => __('Bulgaria', 'woocommerce'),  'Burkina Faso' => __('Burkina Faso', 'woocommerce'),  'Burundi' => __('Burundi', 'woocommerce'),  'Cambodia' => __('Cambodia', 'woocommerce'),  'Cameroon' => __('Cameroon', 'woocommerce'),  'Canada' => __('Canada', 'woocommerce'),  'Cape Verde' => __('Cape Verde', 'woocommerce'),  'Cayman Islands' => __('Cayman Islands', 'woocommerce'),  'Central African Republic' => __('Central African Republic', 'woocommerce'),  'Chad' => __('Chad', 'woocommerce'),  'Chile' => __('Chile', 'woocommerce'),  'China' => __('China', 'woocommerce'),  'Christmas Island' => __('Christmas Island', 'woocommerce'),  'Cocos (Keeling) Islands' => __('Cocos (Keeling) Islands', 'woocommerce'),  'Colombia' => __('Colombia', 'woocommerce'),  'Comoros' => __('Comoros', 'woocommerce'),  'Congo, Democratic Republic of the (Zaire)' => __('Congo, Democratic Republic of the (Zaire)', 'woocommerce'),  'Congo, Republic of' => __('Congo, Republic of', 'woocommerce'),  'Cook Islands' => __('Cook Islands', 'woocommerce'),  'Costa Rica' => __('Costa Rica', 'woocommerce'),  'Croatia' => __('Croatia', 'woocommerce'),  'Cuba' => __('Cuba', 'woocommerce'),  'Cyprus' => __('Cyprus', 'woocommerce'),  'Czech Republic' => __('Czech Republic', 'woocommerce'),  'Denmark' => __('Denmark', 'woocommerce'),  'Djibouti' => __('Djibouti', 'woocommerce'),  'Dominica' => __('Dominica', 'woocommerce'),  'Dominican Republic' => __('Dominican Republic', 'woocommerce'),  'Ecuador' => __('Ecuador', 'woocommerce'),  'Egypt' => __('Egypt', 'woocommerce'),  'El Salvador' => __('El Salvador', 'woocommerce'),  'Equatorial Guinea' => __('Equatorial Guinea', 'woocommerce'),  'Eritrea' => __('Eritrea', 'woocommerce'),  'Estonia' => __('Estonia', 'woocommerce'),  'Ethiopia' => __('Ethiopia', 'woocommerce'),  'Falkland Islands' => __('Falkland Islands', 'woocommerce'),  'Faroe Islands' => __('Faroe Islands', 'woocommerce'),  'Fiji' => __('Fiji', 'woocommerce'),  'Finland' => __('Finland', 'woocommerce'),  'France' => __('France', 'woocommerce'),  'French Guiana' => __('French Guiana', 'woocommerce'),  'Gabon' => __('Gabon', 'woocommerce'),  'Gambia' => __('Gambia', 'woocommerce'),  'Georgia' => __('Georgia', 'woocommerce'),  'Germany' => __('Germany', 'woocommerce'),  'Ghana' => __('Ghana', 'woocommerce'),  'Gibraltar' => __('Gibraltar', 'woocommerce'),  'Greece' => __('Greece', 'woocommerce'),  'Greenland' => __('Greenland', 'woocommerce'),  'Grenada' => __('Grenada', 'woocommerce'),  'Guadeloupe (French)' => __('Guadeloupe (French)', 'woocommerce'),  'Guam (USA)' => __('Guam (USA)', 'woocommerce'),  'Guatemala' => __('Guatemala', 'woocommerce'),  'Guinea' => __('Guinea', 'woocommerce'),  'Guinea Bissau' => __('Guinea Bissau', 'woocommerce'),  'Guyana' => __('Guyana', 'woocommerce'),  'Haiti' => __('Haiti', 'woocommerce'),  'Holy See' => __('Holy See', 'woocommerce'),  'Honduras' => __('Honduras', 'woocommerce'),  'Hong Kong' => __('Hong Kong', 'woocommerce'),  'Hungary' => __('Hungary', 'woocommerce'),  'Iceland' => __('Iceland', 'woocommerce'),  'India' => __('India', 'woocommerce'),  'Indonesia' => __('Indonesia', 'woocommerce'),  'Iran' => __('Iran', 'woocommerce'),  'Iraq' => __('Iraq', 'woocommerce'),  'Ireland' => __('Ireland', 'woocommerce'),  'Israel' => __('Israel', 'woocommerce'),  'Italy' => __('Italy', 'woocommerce'),  'Ivory Coast' => __('Ivory Coast', 'woocommerce'),  'Jamaica' => __('Jamaica', 'woocommerce'),  'Japan' => __('Japan', 'woocommerce'),  'Jordan' => __('Jordan', 'woocommerce'),  'Kazakhstan' => __('Kazakhstan', 'woocommerce'),  'Kenya' => __('Kenya', 'woocommerce'),  'Kiribati' => __('Kiribati', 'woocommerce'),  'Kuwait' => __('Kuwait', 'woocommerce'),  'Kyrgyzstan' => __('Kyrgyzstan', 'woocommerce'),  'Laos' => __('Laos', 'woocommerce'),  'Latvia' => __('Latvia', 'woocommerce'),  'Lebanon' => __('Lebanon', 'woocommerce'),  'Lesotho' => __('Lesotho', 'woocommerce'),  'Liberia' => __('Liberia', 'woocommerce'),  'Libya' => __('Libya', 'woocommerce'),  'Liechtenstein' => __('Liechtenstein', 'woocommerce'),  'Lithuania' => __('Lithuania', 'woocommerce'),  'Luxembourg' => __('Luxembourg', 'woocommerce'),  'Macau' => __('Macau', 'woocommerce'),  'Macedonia' => __('Macedonia', 'woocommerce'),  'Madagascar' => __('Madagascar', 'woocommerce'),  'Malawi' => __('Malawi', 'woocommerce'),  'Malaysia' => __('Malaysia', 'woocommerce'),  'Maldives' => __('Maldives', 'woocommerce'),  'Mali' => __('Mali', 'woocommerce'),  'Malta' => __('Malta', 'woocommerce'),  'Marshall Islands' => __('Marshall Islands', 'woocommerce'),  'Martinique (French)' => __('Martinique (French)', 'woocommerce'),  'Mauritania' => __('Mauritania', 'woocommerce'),  'Mauritius' => __('Mauritius', 'woocommerce'),  'Mayotte' => __('Mayotte', 'woocommerce'),  'Mexico' => __('Mexico', 'woocommerce'),  'Micronesia' => __('Micronesia', 'woocommerce'),  'Moldova' => __('Moldova', 'woocommerce'),  'Monaco' => __('Monaco', 'woocommerce'),  'Mongolia' => __('Mongolia', 'woocommerce'),  'Montenegro' => __('Montenegro', 'woocommerce'),  'Montserrat' => __('Montserrat', 'woocommerce'),  'Morocco' => __('Morocco', 'woocommerce'),  'Mozambique' => __('Mozambique', 'woocommerce'),  'Myanmar' => __('Myanmar', 'woocommerce'),  'Namibia' => __('Namibia', 'woocommerce'),  'Nauru' => __('Nauru', 'woocommerce'),  'Nepal' => __('Nepal', 'woocommerce'),  'Netherlands' => __('Netherlands', 'woocommerce'),  'Netherlands Antilles' => __('Netherlands Antilles', 'woocommerce'),  'New Caledonia (French)' => __('New Caledonia (French)', 'woocommerce'),  'New Zealand' => __('New Zealand', 'woocommerce'),  'Nicaragua' => __('Nicaragua', 'woocommerce'),  'Niger' => __('Niger', 'woocommerce'),  'Nigeria' => __('Nigeria', 'woocommerce'),  'Niue' => __('Niue', 'woocommerce'),  'Norfolk Island' => __('Norfolk Island', 'woocommerce'),  'North Korea' => __('North Korea', 'woocommerce'),  'Northern Mariana Islands' => __('Northern Mariana Islands', 'woocommerce'),  'Norway' => __('Norway', 'woocommerce'),  'Oman' => __('Oman', 'woocommerce'),  'Pakistan' => __('Pakistan', 'woocommerce'),  'Palau' => __('Palau', 'woocommerce'),  'Panama' => __('Panama', 'woocommerce'),  'Papua New Guinea' => __('Papua New Guinea', 'woocommerce'),  'Paraguay' => __('Paraguay', 'woocommerce'),  'Peru' => __('Peru', 'woocommerce'),  'Philippines' => __('Philippines', 'woocommerce'),  'Pitcairn Island' => __('Pitcairn Island', 'woocommerce'),  'Poland' => __('Poland', 'woocommerce'),  'Polynesia (French)' => __('Polynesia (French)', 'woocommerce'),  'Portugal' => __('Portugal', 'woocommerce'),  'Puerto Rico' => __('Puerto Rico', 'woocommerce'),  'Qatar' => __('Qatar', 'woocommerce'),  'Reunion' => __('Reunion', 'woocommerce'),  'Romania' => __('Romania', 'woocommerce'),  'Russia' => __('Russia', 'woocommerce'),  'Rwanda' => __('Rwanda', 'woocommerce'),  'Saint Helena' => __('Saint Helena', 'woocommerce'),  'Saint Kitts and Nevis' => __('Saint Kitts and Nevis', 'woocommerce'),  'Saint Lucia' => __('Saint Lucia', 'woocommerce'),  'Saint Pierre and Miquelon' => __('Saint Pierre and Miquelon', 'woocommerce'),  'Saint Vincent and Grenadines' => __('Saint Vincent and Grenadines', 'woocommerce'),  'Samoa' => __('Samoa', 'woocommerce'),  'San Marino' => __('San Marino', 'woocommerce'),  'Sao Tome and Principe' => __('Sao Tome and Principe', 'woocommerce'),  'Saudi Arabia' => __('Saudi Arabia', 'woocommerce'),  'Senegal' => __('Senegal', 'woocommerce'),  'Serbia' => __('Serbia', 'woocommerce'),  'Seychelles' => __('Seychelles', 'woocommerce'),  'Sierra Leone' => __('Sierra Leone', 'woocommerce'),  'Singapore' => __('Singapore', 'woocommerce'),  'Slovakia' => __('Slovakia', 'woocommerce'),  'Slovenia' => __('Slovenia', 'woocommerce'),  'Solomon Islands' => __('Solomon Islands', 'woocommerce'),  'Somalia' => __('Somalia', 'woocommerce'),  'South Africa' => __('South Africa', 'woocommerce'),  'South Georgia and South Sandwich Islands' => __('South Georgia and South Sandwich Islands', 'woocommerce'),  'South Korea' => __('South Korea', 'woocommerce'),  'South Sudan' => __('South Sudan', 'woocommerce'),  'Spain' => __('Spain', 'woocommerce'),  'Sri Lanka' => __('Sri Lanka', 'woocommerce'),  'Sudan' => __('Sudan', 'woocommerce'),  'Suriname' => __('Suriname', 'woocommerce'),  'Svalbard and Jan Mayen Islands' => __('Svalbard and Jan Mayen Islands', 'woocommerce'),  'Swaziland' => __('Swaziland', 'woocommerce'),  'Sweden' => __('Sweden', 'woocommerce'),  'Switzerland' => __('Switzerland', 'woocommerce'),  'Syria' => __('Syria', 'woocommerce'),  'Taiwan' => __('Taiwan', 'woocommerce'),  'Tajikistan' => __('Tajikistan', 'woocommerce'),  'Tanzania' => __('Tanzania', 'woocommerce'),  'Thailand' => __('Thailand', 'woocommerce'),  'Timor-Leste (East Timor)' => __('Timor-Leste (East Timor)', 'woocommerce'),  'Togo' => __('Togo', 'woocommerce'),  'Tokelau' => __('Tokelau', 'woocommerce'),  'Tonga' => __('Tonga', 'woocommerce'),  'Trinidad and Tobago' => __('Trinidad and Tobago', 'woocommerce'),  'Tunisia' => __('Tunisia', 'woocommerce'),  'Turkey' => __('Turkey', 'woocommerce'),  'Turkmenistan' => __('Turkmenistan', 'woocommerce'),  'Turks and Caicos Islands' => __('Turks and Caicos Islands', 'woocommerce'),  'Tuvalu' => __('Tuvalu', 'woocommerce'),  'Uganda' => __('Uganda', 'woocommerce'),  'Ukraine' => __('Ukraine', 'woocommerce'),  'United Arab Emirates' => __('United Arab Emirates', 'woocommerce'),  'United Kingdom' => __('United Kingdom', 'woocommerce'),  'United States' => __('United States', 'woocommerce'),  'Uruguay' => __('Uruguay', 'woocommerce'),  'Uzbekistan' => __('Uzbekistan', 'woocommerce'),  'Vanuatu' => __('Vanuatu', 'woocommerce'),  'Venezuela' => __('Venezuela', 'woocommerce'),  'Vietnam' => __('Vietnam', 'woocommerce'),  'Virgin Islands' => __('Virgin Islands', 'woocommerce'),  'Wallis and Futuna Islands' => __('Wallis and Futuna Islands', 'woocommerce'),  'Yemen' => __('Yemen', 'woocommerce'),  'Zambia' => __('Zambia', 'woocommerce'),  'Zimbabwe' => __('Zimbabwe', 'woocommerce')
    );

    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : '';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    // attribute handling
    $attributes = array();
    $is_multiple = false;
    if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
      foreach ( $field['attributes'] as $attribute => $value ) {
        $attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
      }
    }

    $options = '<option class="options_placeholder" value="">' . __( 'Country of Production (leave blank if your own)', 'wc-frontend-manager' ) . '</option><optgroup label="-------------------------------------">';
    foreach ( $country_arr as $key => $value ) {
    	if( $is_multiple || is_array( $field['value'] ) ) {
    		$options .=  '<option class="choices" value="' . esc_attr( $key ) . '"' . selected( in_array( $key, (array)$field['value'] ), true, false ) . '>' . esc_html( $value ) . '</option>';
    	} else {
    		$options .= '<option class="choices" value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
    	}
    }
    $options .= '</optgroup>';

    $field = $this->field_wrapper_start($field);

    printf(
        '<select onchange="%s" id="%s" name="%s" class="country_select %s" %s %s>%s</select>',
        "this.className=this.className+' '+this.options[this.selectedIndex].className",
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        implode( ' ', $custom_attributes ),
        implode( ' ', $attributes ),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a timezone input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function timezone_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'select short';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    $options = wp_timezone_choice($field['value']);

    $field = $this->field_wrapper_start($field);

    printf(
        '<select id="%s" name="%s" class="%s" %s />%s</select>',
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        implode( ' ', $custom_attributes ),
        $options
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a upload input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function upload_input($field) {
  	global $WCFM;

    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'upload_input';
    $field['mime'] 		= isset( $field['mime'] ) ? $field['mime'] : 'image';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['prwidth'] 			= isset( $field['prwidth'] ) ? $field['prwidth'] : 75;
    $customStyle 		= isset( $field['value'] ) ? 'display: none;' : '';
    $placeHolder 		= ( $field['value'] ) ? '' : 'placeHolder';
    if($field['mime'] == 'image') {
    	$field['class'] .= ' wcfm_img_uploader';
    	$img_src = $field['value'];
    	$placeholder = $WCFM->plugin_url . 'includes/libs/upload/images/Placeholder.png';
    	if( !$img_src ) $img_src = $placeholder;
      $mimeProp = '<img id="'.esc_attr($field['id']).'_display" data-placeholder="'.$placeholder.'" src="'.esc_attr( $img_src ).'" width="'.absint( $field['prwidth'] ).'" class="'.$placeHolder.'" />';
    } else {
      if($field['value'])
        $field['mime'] = pathinfo($field['value'], PATHINFO_EXTENSION);
      $placeHolder	= 'placeHolder'.$field['mime'];
      $mimeProp = '<a target="_blank" style="width: '.absint( $field['prwidth'] ).'px; height: '.absint( $field['prwidth'] ).'px;" id="'.esc_attr($field['id']).'_display" href="'.esc_attr( $field['value'] ).'"><span style="width: '.absint( $field['prwidth'] ).'px; height: '.absint( $field['prwidth'] ).'px; display: inline-block;" class="'.$placeHolder.'"></span></a>';
    }

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '
        <span class="wcfm-wp-fields-uploader %s">
          %s
          <input type="text" name="%s" id="%s" style="%s" class="%s" readonly value="%s" %s data-mime="%s" />
          <input type="button" class="upload_button button button-secondary" name="%s_button" id="%s_button" data-mime="%s" value="Upload" />
          <input type="button" class="remove_button button button-secondary" name="%s_remove_button" id="%s_remove_button" data-mime="%s" value="Remove" />
        </span>
        ',
        esc_attr( $field['class'] ),
        $mimeProp,
        esc_attr($field['name']),
        esc_attr($field['id']),
        $customStyle,
        esc_attr( $field['class'] ),
        esc_attr( $field['value'] ),
        implode( ' ', $custom_attributes ),
        $field['mime'],
        esc_attr($field['id']),
        esc_attr($field['id']),
        $field['mime'],
        esc_attr($field['id']),
        esc_attr($field['id']),
        $field['mime']
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a colorpicker box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function colorpicker_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'colorpicker';
    $field['default'] 		= isset( $field['default'] ) ? $field['default'] : 'fbfbfb';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : $field['default'];
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];

    $field = $this->field_wrapper_start($field);

    printf(
        '<input type="%s" id="%s" name="%s" class="%s" data-default="%s" value="%s" />',
        $field['type'],
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($field['default']),
        esc_attr($field['value'])
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a date input box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function datepicker_input($field) {
    $field['placeholder'] 	= isset( $field['placeholder'] ) ? $field['placeholder'] : '';
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : 'regular-text';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : '';
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['type'] 			= isset( $field['type'] ) ? $field['type'] : 'text';
    $field['class'] .= ' wcfm_datepicker';

    // Custom attribute handling
    $custom_attributes = array();

    if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    } else {
      $custom_attributes[] = 'data-date_format="dd/mm/yy"';
    }

    $field = $this->field_wrapper_start($field);

    printf(
        '<input type="%s" id="%s" name="%s" class="%s" value="%s" placeholder="%s" %s />',
        $field['type'],
        esc_attr($field['id']),
        esc_attr($field['name']),
        esc_attr($field['class']),
        esc_attr($field['value']),
        esc_attr($field['placeholder']),
        implode( ' ', $custom_attributes )
    );

    $this->field_wrapper_end($field);
  }

  /**
   * Output a multiinput box.
   *
   * @access public
   * @param array $field
   * @return void
   */
  public function multi_input($field) {
    $field['class'] 		= isset( $field['class'] ) ? $field['class'] : '';
    $field['value'] 		= isset( $field['value'] ) ? $field['value'] : array();
    $field['name'] 			= isset( $field['name'] ) ? $field['name'] : $field['id'];
    $field['options'] 	= isset( $field['options'] ) ? $field['options'] : array();
    $field['value']     = array_values($field['value']);
    $field              = $this->field_wrapper_start($field);
    $has_dummy          = isset( $field['has_dummy'] ) ? 1 : 0;

    // Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
      foreach ( $field['custom_attributes'] as $attribute => $value ) {
        $custom_attributes[] = 'data-' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';

        // Required Option
        if( $attribute == 'required' ) {
        	if( !isset( $field['custom_attributes']['required_message'] ) ) {
        		$custom_attributes[] = 'data-required_message="' . esc_attr( $field['label'] ) . ': ' . __( 'This field is required.', 'wc-frontend-manager' ) . '"';
        	}
        	$field['label'] .= '<span class="required">*</span>';
        }
      }
    }

		$eleCount = count($field['value']);
    if( !$eleCount ) $eleCount = 1;

    printf(
      '<div id="%s" class="%s multi_input_holder" data-name="%s" data-length="%s" data-has-dummy="%s" %s>',
      $field['id'],
      $field['class'],
      $field['name'],
      count($field['value']),
      $has_dummy,
      implode(' ', $custom_attributes)
    );

    $has_dummy_class = 'multi_input_block_dummy';
    if( !$has_dummy || count($field['value']) ) $has_dummy_class = '';

    if(!empty($field['options'])) {
      for($blockCount = 0; $blockCount < $eleCount; $blockCount++) {
        printf('<div class="multi_input_block ' . $has_dummy_class . '">');
        foreach($field['options'] as $optionKey => $optionField) {
          $optionField = $this->check_field_id_name($optionKey, $optionField);
          if($optionField['type'] == 'checkbox') {
            if(isset($field['value']) && isset($field['value'][$blockCount]) && isset($field['value'][$blockCount][$optionField['name']])) $optionField['dfvalue'] = $field['value'][$blockCount][$optionField['name']];
          } else {
            if(isset($field['value']) && isset($field['value'][$blockCount]) && isset($field['value'][$blockCount][$optionField['name']])) $optionField['value'] = $field['value'][$blockCount][$optionField['name']];
          }
          $option_values = array();
          if($optionField['type'] == 'select') {
          	if(isset($field['value']) && isset($field['value'][$blockCount]) && isset($field['value'][$blockCount]['option_values'])) $optionField['options'] = $field['value'][$blockCount]['option_values'];
          }
          $optionField['custom_attributes']['name'] = $optionField['name'];
          if(!isset($optionField['class'])) $optionField['class'] = '';
          $optionField['class'] .= ' multi_input_block_element';
          $optionField['id'] = $field['id'] . '_' . $optionField['name'] . '_' . $blockCount;
          $optionField['name'] = $field['name'].'['.$blockCount.']['.$optionField['name'].']';
          if(!empty($optionField['type'])) {
            switch($optionField['type']) {
              case 'input':
              case 'text':
              case 'email':
              case 'number':
              case 'numeric':
              case 'time':
              case 'file':
              case 'url':
              case 'phone':
              case 'password':
							case 'textfield':
                $this->text_input($optionField);
                break;

              case 'hidden':
                $this->hidden_input($optionField);
                break;

              case 'textarea':
              case 'wysiwyg':
                $this->textarea_input($optionField);
                break;

              case 'checkbox':
                $this->checkbox_input($optionField);
                break;

              case 'checklist':
              	$this->checklist_input($field);
              	break;

              case 'checkboxoffon':
                $this->checkbox_offon_input($optionField);
                break;

              case 'radio':
                $this->radio_input($optionField);
                break;

              case 'radiooffon':
                $this->radio_offon_input($optionField);
                break;

              case 'select':
                $this->select_input($optionField);
                break;

              case 'country':
                $this->country_input($optionField);
                break;

              case 'timezone':
                $this->timezone_input($optionField);
                break;

              case 'upload':
                $this->upload_input($optionField);
                break;

              case 'colorpicker':
                $this->colorpicker_input($optionField);
                break;

              case 'datepicker':
              case 'date':
                $this->datepicker_input($optionField);
                break;

              case 'multiinput':
                $this->multi_input($optionField);
                break;

              case 'title':
								$this->title_input($field);
								break;

              default:

                break;

            }
          }
        }
        printf('<span class="multi_input_block_manupulate remove_multi_input_block fa fa-times-circle-o"></span>
                <span class="add_multi_input_block multi_input_block_manupulate fa fa-plus-circle"></span></div>');
      }
    }

    printf('</div>');

    $this->field_wrapper_end($field);
  }

  /**************************************** Help Functions ************************************************/

  public function field_wrapper_start($field) {
    $field['wrapper_class'] = isset( $field['wrapper_class'] ) ? ($field['wrapper_class'] . ' ' . $field['id'] . '_wrapper') : ($field['id'] . '_wrapper');
    $field['label_holder_class'] = isset( $field['label_holder_class'] ) ? ($field['label_holder_class']. ' ' . $field['id'] . '_label_holder') : ($field['id'] . '_label_holder');
    $field['label_for'] = isset( $field['label_for'] ) ? ($field['label_for']. ' ' . $field['id']) : $field['id'];
    $field['label_class'] = isset( $field['label_class'] ) ? ($field['label_for']. ' ' . $field['label_class']) : $field['label_for'];

    do_action('before_field_wrapper');
    do_action('before_field_wrapper_' . $field['id']);

    if(isset($field['in_table'])) {
      printf(
        '<tr class="%s">',
        $field['wrapper_class']
      );
    }

    do_action('field_wrapper_start');
    do_action('field_wrapper_start_' . $field['id']);

    if(isset($field['label'])) {
      do_action('before_field_label');
      do_action('before_field_label_' . $field['id']);

      if(isset($field['in_table'])) {
        printf(
          '<th class="%s">',
          $field['label_holder_class']
        );
      }
      do_action('field_label_start');
      do_action('field_label_start_' . $field['id']);
      printf(
        '<p class="%s"><strong>%s</strong>',
        $field['label_class'],
        $field['label']
      );
      if( isset( $field['hints'] ) && !empty( $field['hints'] ) ) {
        printf(
          '<span class="img_tip fa fa-question-circle-o" data-tip="%s"></span>',
          wp_kses_post ( $field['hints'] )
        );
      }
      printf(
        '</p><label class="screen-reader-text" for="%s">%s</label>',
        $field['label_for'],
        $field['label']
      );

      // Description
      if( in_array( $field['type'], array( 'checklist', 'radio' ) ) ) {
				if( isset( $field['desc'] ) && !empty( $field['desc'] ) ) {
					do_action('before_desc');
					do_action('before_desc_' . $field['id']);
					if( !isset($field['desc_class']) ) $field['desc_class'] = '';

					printf(
						'<p class="description instructions %s">%s</p>',
						wp_kses_post ( $field['desc_class'] ),
						wp_kses_post ( $field['desc'] )
					);

					do_action('after_desc_' . $field['id']);
					do_action('after_desc');
				}
			}

      do_action('field_label_end_' . $field['id']);
      do_action('field_label_end');
      if(isset($field['in_table'])) printf('</th>');

      do_action('after_field_label_' . $field['id']);
      do_action('after_field_label');
    }

    do_action('before_field');
    do_action('before_field_' . $field['id']);

    if(isset($field['in_table']) && isset($field['label'])) printf('<td>');
    else if(isset($field['in_table']) && !isset($field['label'])) printf('<td colspan="2">');

    do_action('field_start');
    do_action('field_start_' . $field['id']);

    if(!isset($field['custom_attributes'])) $field['custom_attributes'] = array();
    $field['custom_attributes'] = apply_filters('manupulate_custom_attributes', $field['custom_attributes']);
    $field['custom_attributes'] = apply_filters('manupulate_custom_attributes_' . $field['id'], $field['custom_attributes']);

    return $field;
  }

  public function field_wrapper_end($field) {

    // Help message
    if( !isset( $field['label'] ) && isset( $field['hints'] ) && !empty( $field['hints'] ) ) {
      do_action('before_hints');
      do_action('before_hints_' . $field['id']);

      printf(
        '<span class="img_tip fa fa-question-circle-o" data-tip="%s"></span>',
        wp_kses_post ( $field['hints'] )
      );

      do_action('after_hints_' . $field['id']);
      do_action('after_hints');
    }

    // Description
    if( !in_array( $field['type'], array( 'checklist', 'radio' ) ) ) {
			if( isset( $field['desc'] ) && !empty( $field['desc'] ) ) {
				do_action('before_desc');
				do_action('before_desc_' . $field['id']);
				if( !isset($field['desc_class']) ) $field['desc_class'] = '';

				printf(
					'<p class="description %s">%s</p>',
					wp_kses_post ( $field['desc_class'] ),
					wp_kses_post ( $field['desc'] )
				);

				do_action('after_desc_' . $field['id']);
				do_action('after_desc');
			}
		}

    do_action('field_end_' . $field['id']);
    do_action('field_end');

    if(isset($field['in_table'])) printf('</td>');

    do_action('after_field_' . $field['id']);
    do_action('after_field');

    do_action('field_wrapper_end_' . $field['id']);
    do_action('field_wrapper_end');

    if(isset($field['in_table'])) printf('</tr>');

    do_action('afet_field_wrapper_' . $field['id']);
    do_action('after_field_wrapper');
  }

  public function wcfm_generate_form_field($fields, $common_attrs = array()) {
    if(!empty($fields)) {
    	foreach($fields as $fieldID => $field) {
    	  $field = $this->check_field_id_name($fieldID, $field);
    	  if(!empty($common_attrs))
    	    foreach($common_attrs as $common_attr_key => $common_attr_val)
    	      $field[$common_attr_key] = $common_attr_val;
    		if(!empty($field['type'])) {
					switch($field['type']) {
						case 'input':
						case 'text':
						case 'email':
						case 'number':
						case 'time':
						case 'file':
						case 'button':
						case 'url':
						case 'phone':
						case 'password':
						case 'textfield':
						case 'numeric':
							$this->text_input($field);
							break;

						case 'hidden':
							$this->hidden_input($field);
							break;

						case 'textarea':
						case 'wysiwyg':
							$this->textarea_input($field);
							break;

						case 'wpeditor':
							$this->wpeditor_input($field);
							break;

						case 'checkbox':
							$this->checkbox_input($field);
							break;

						case 'checklist':
							$this->checklist_input($field);
							break;

						case 'checkboxoffon':
                $this->checkbox_offon_input($field);
                break;

						case 'radio':
							$this->radio_input($field);
							break;

						case 'radiooffon':
                $this->radio_offon_input($field);
                break;

						case 'select':
							$this->select_input($field);
							break;

					 case 'country':
              $this->country_input($field);
              break;

					  case 'timezone':
							$this->timezone_input($field);
							break;

						case 'upload':
							$this->upload_input($field);
							break;

						case 'colorpicker':
							$this->colorpicker_input($field);
							break;

						case 'datepicker':
						case 'date':
							$this->datepicker_input($field);
							break;

						case 'multiinput':
							$this->multi_input($field);
							break;

						case 'title':
							$this->title_input($field);
							break;

						default:

							break;

					}
				}
			}
    }
  }

  public function check_field_id_name($fieldID, $field) {
    if(empty($fieldID)) return $field;

    if(!isset($field['id']) || empty($field['id'])) $field['id'] = $fieldID;
    if(!isset($field['name']) || empty($field['name'])) $field['name'] = $fieldID;

    return $field;
  }

  public function string_wpml($input) {
    if( function_exists( 'icl_register_string' ) ) {
      icl_register_string('WCfM', ''.$input.'', ''.$input.'');
    }
    if (function_exists( 'icl_t' )) {
      return icl_t('WCfM', ''.$input.'', ''.$input.'');
    } else {
      return $input;
    }
  }
}
}
