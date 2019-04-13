<?php global $wpo_wcpdf, $document, $document_type; ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php echo $template_type; ?></title>
	<style type="text/css"><?php $document->template_styles(); ?></style>
	<style type="text/css"><?php do_action( 'wpo_wcpdf_custom_styles', $template_type ); ?></style>
</head>
<body class="<?php echo $template_type; ?>">
<?php echo $output_body; ?>
</body>
</html>