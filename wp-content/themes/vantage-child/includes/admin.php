<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


// Hooks near the bottom of profile page (if current user)
add_action('show_user_profile', 'custom_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user)
add_action('edit_user_profile', 'custom_user_profile_fields');

// @param WP_User $user
function custom_user_profile_fields( $user ) {
  global $WCFM, $WCFMvm;
  $wcfm_memberships_list = get_wcfm_memberships();

?>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Membership' ); ?></label>
            </th>
            <td>
              <select name="wcfm_membership">
                <option value="" selected>None</option>
                <?php
                foreach ($wcfm_memberships_list as $membership) {
                  $selected = ((esc_attr( get_the_author_meta( 'wcfm_membership', $user->ID ) ) == $membership->ID) ? 'selected' : '');
                  echo '<option value="' . $membership->ID . '" ' . $selected .'>' . $membership->post_title . '</option>';
                }
                ?>
              </select>
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Membership Mode' ); ?></label>
            </th>
            <td>
                <input type="text" name="wcfm_membership_paymode" id="wcfm_membership_paymode" value="<?php echo esc_attr( get_the_author_meta( 'wcfm_membership_paymode', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Membership Next Schedule' ); ?></label>
            </th>
            <td>
                <input type="text" name="wcfm_membership_next_schedule" id="wcfm_membership_next_schedule" value="<?php echo esc_attr( get_the_author_meta( 'wcfm_membership_next_schedule', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Membership Billing Period' ); ?></label>
            </th>
            <td>
                <input type="text" name="wcfm_membership_billing_period" id="wcfm_membership_billing_period" value="<?php echo esc_attr( get_the_author_meta( 'wcfm_membership_billing_period', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Membership Billing Cycle' ); ?></label>
            </th>
            <td>
                <input type="text" name="wcfm_membership_billing_cycle" id="wcfm_membership_billing_cycle" value="<?php echo esc_attr( get_the_author_meta( 'wcfm_membership_billing_cycle', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'PayPal Subscription ID' ); ?></label>
            </th>
            <td>
                <input type="text" name="wcfm_paypal_subscription_id" id="wcfm_paypal_subscription_id" value="<?php echo esc_attr( get_the_author_meta( 'wcfm_paypal_subscription_id', $user->ID ) ); ?>" class="regular-text" />
            </td>
        </tr>
    </table>

<?php
}

//add_filter( 'um_instagram_code_in_user_meta', false );

// Hook is used to save custom fields that have been added to the WordPress profile page (if current user)
add_action( 'personal_options_update', 'update_extra_profile_fields' );

// Hook is used to save custom fields that have been added to the WordPress profile page (if not current user)
add_action( 'edit_user_profile_update', 'update_extra_profile_fields' );

function update_extra_profile_fields( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) )
        update_user_meta( $user_id, 'wcfm_membership', $_POST['wcfm_membership'] );
        update_user_meta( $user_id, 'wcfm_membership_paymode', $_POST['wcfm_membership_paymode'] );
        update_user_meta( $user_id, 'wcfm_membership_next_schedule', $_POST['wcfm_membership_next_schedule'] );
        update_user_meta( $user_id, 'wcfm_membership_billing_period', $_POST['wcfm_membership_billing_period'] );
        update_user_meta( $user_id, 'wcfm_membership_billing_cycle', $_POST['wcfm_membership_billing_cycle'] );
        update_user_meta( $user_id, 'wcfm_paypal_subscription_id', $_POST['wcfm_paypal_subscription_id'] );
}
