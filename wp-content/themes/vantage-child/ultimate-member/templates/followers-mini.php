<div class="um-followers-m" data-max="<?php echo $max; ?>">

<?php if ( $followers ) {

  $total_followers_count = 0;
  $max = 10;

  $followers_count = count($followers);
  $followers_remaining = $followers_count - $max;

  ?>

	<?php foreach( $followers as $k => $arr ) { extract( $arr ); um_fetch_user( $user_id2 );



    $total_followers_count++;
    if($total_followers_count>$max)break;

    ?>

	<div class="um-followers-m-user">
		<div class="um-followers-m-pic"><a href="<?php echo um_user_profile_url(); ?>" class="um-tip-n" title="<?php echo um_user('display_name'); ?>"><?php echo get_avatar( um_user('ID'), 40 ); ?></a></div>
	</div>

	<?php }

  if ( $followers_count > $max ) {

    $profile_id = um_profile_id();
    um_fetch_user( $profile_id );

    ?>
  <div class="um-friends-m-user-show-all">
    <div class="show-all">
      <a href="<?php echo um_user_profile_url(); ?>?profiletab=followers" class="um-tip-n">
        <?php echo '+' . $followers_remaining;?>
      </a>
    </div>
  </div>
  <?php
  }
 } else { ?>

	<p><?php echo ( $user_id == get_current_user_id() ) ? __('You do not have any followers yet.', 'um-followers' ) : __( 'This user do not have any followers yet.', 'um-followers' ); ?></p>

<?php } ?>

</div><div class="um-clear"></div>
