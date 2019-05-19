<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function artmo_user_post_editor() {

  wp_enqueue_script('media-upload');
  wp_enqueue_script('thickbox');
  wp_enqueue_style('thickbox');


  $response = '';

  $settings = array(
    'wpautop' => true, // use wpautop?
    'media_buttons' => true, // show insert/upload button(s)
    'textarea_name' => 'artmocreateposteditor', // set the textarea name to something different, square brackets [] can be used here
    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
    'tabindex' => '',
    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
    'editor_class' => '', // add extra class(es) to the editor textarea
    'teeny' => true, // output the minimal editor config used in Press This
    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
  );?>
  <script type="text/javascript">
  jQuery(document).ready(function($){
      $('#upload-btn').click(function(e) {
          e.preventDefault();
          var image = wp.media({
              title: 'Upload Image',
              // mutiple: true if you want to upload multiple files at once
              multiple: false
          }).open()
          .on('select', function(e){
              // This will return the selected image from the Media Uploader, the result is an object
              var uploaded_image = image.state().get('selection').first();
              // We convert uploaded_image to a JSON object to make accessing it easier
              // Output to the console uploaded_image
              console.log(uploaded_image);
              var image_url = uploaded_image.toJSON().id;
              // Let's assign the url value to the input field
              $('#image_url').val(image_url);
          });
      });
  });
  </script>

  <div id="create-post-container" enctype="multipart/form-data">
    <form action="" method="post" name="artmo_create_post" enctype="multipart/form-data">
      <input name="post_title" class="create-post-title" placeholder="Title">
      <?php wp_editor( 'Hello World!', 'artmocreateposteditor', $settings); ?>
      <div class="create-post-cat-dropdown">
        <p>CHOOSE A CATEGORY:</p>
        <?php wp_dropdown_categories(); ?>
      </div><?php
      // This will enqueue the Media Uploader script
      wp_enqueue_media();
      ?>
          <div>
          <label for="image_url">Image</label>
          <input type="text" name="image_url" id="image_url" class="regular-text">
          <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">

      </div>
      <div class="create-post-buttons">
        <button type="submit" id="create_post_draft">DRAFT</button>
        <button type="submit" id="create_post_publish">PUBLISH</button>
      </div>
    </form>
  </div>
  <?php
}

add_shortcode('artmo_user_post_editor', 'artmo_user_post_editor');
