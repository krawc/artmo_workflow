<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-friends-m" data-max="<?php echo $max; ?>">
	<?php $total_friends_count = 0;

  $max = 10;

	if ( $friends ) {
		foreach ( $friends as $k => $arr ) {
			extract( $arr );

      $friends_count = count($friends);
      $friends_remaining = $friends_count - $max;
			$total_friends_count++;

      if($total_friends_count>$max)break;

			if ( $user_id2 == $user_id ) {
				$user_id2 = $user_id1;
			}

			um_fetch_user( $user_id2 ); ?>

			<div class="um-friends-m-user">
				<div class="um-friends-m-pic">
					<a href="<?php echo um_user_profile_url(); ?>" class="um-tip-n" title="<?php echo um_user( 'display_name' ); ?>">
						<?php echo get_avatar( um_user('ID'), 40 ); ?>
					</a>
				</div>
			</div>

		<?php }

    if ( $friends_count > $max ) {

      $profile_id = um_profile_id();
      um_fetch_user( $profile_id );
      ?>
    <div class="um-friends-m-user-show-all">
      <div class="show-all">
        <a href="<?php echo um_user_profile_url(); ?>?profiletab=friends" class="um-tip-n">
          <?php echo '+' . $friends_remaining;?>
        </a>
      </div>
    </div>

  	<?php
    }
   } else { ?>

		<p>
			<?php echo ( $user_id == get_current_user_id() ) ? __( 'You do not have any friends yet.', 'um-friends') : __( 'This user does not have any friends yet.', 'um-friends' ); ?>
		</p>

	<?php } ?>

</div>
<div class="um-clear"></div>
