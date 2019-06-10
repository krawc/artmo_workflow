<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function nelio_max_image_size( $file ) {

  $size = $file['size'];
  $size = $size / 1024;
  $type = $file['type'];
  $is_image = strpos( $type, 'image' ) !== false;
  $limit = 5120;
  $limit_output = '5MB';
  if ( $is_image && $size > $limit ) {
    $file['error'] = 'Image files must be smaller than ' . $limit_output;
  }
  return $file;

}

add_filter( 'wp_handle_upload_prefilter', 'nelio_max_image_size' );

function wt_handle_upload_callback( $data ) {

  $file_path = $data['file'];
  $image = false;
  $size = filesize($file_path);
  $sizeKb = $size / 1024;
  $units = $sizeKb / 300;
  $sqrt = sqrt($units);
  list($width, $height, $type, $attr) = getimagesize($data['file']);

  switch ( $data['type'] ) {
      case 'image/jpeg': {
          if ($sizeKb < 300) {
            $image_quality = 90;
            $image = imagecreatefromjpeg( $file_path );
            imagejpeg( $image, $file_path, $image_quality );
          } else {
            $image_quality = 90 - ($sqrt*3); // Change this according to your needs
            $image = imagecreatefromjpeg( $file_path );
            $imageResized = imagescale($image, intval($width / $sqrt));
            imagejpeg( $imageResized, $file_path, intval($image_quality) );
          }
          break;
      }

      case 'image/png': {
        if ($sizeKb < 300) {
          $image_quality = 9;
          $image = imagecreatefrompng( $file_path );
          imagepng( $image, $file_path, $image_quality );
        } else {
          $image_quality = (90 - ($sqrt*6)) / 10; // Change this according to your needs
          $image = imagecreatefrompng( $file_path );
          $imageResized = imagescale($image, intval($width / $sqrt));
          imagepng( $imageResized, $file_path, intval($image_quality) );
        }
          break;
      }

      case 'image/gif': {
          break;
      }
  }

    return $data;
}
add_filter( 'wp_handle_upload', 'wt_handle_upload_callback' );
