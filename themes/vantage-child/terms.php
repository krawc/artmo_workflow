<?php
/**
 * Template Name: Terms and Conditions
 */

get_header(); ?>

<section id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php if ( siteorigin_page_setting( 'page_title' ) ) : ?>
				<h1 id="page-title"><?php _e('TERMS AND CONDITIONS', 'vantage-child') ?></h1>
			<?php endif; ?>
		</header><!-- .page-header -->

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; ?>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'search' ); ?>

	<?php endif; ?>

	</div><!-- #content .site-content -->
</section><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
