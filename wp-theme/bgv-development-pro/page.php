<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<section class="post-page">
	<div class="container">
		<div class="post-page__main">
			<div class="post-page__content-container">
				<h1 class="post-page__title"><?php the_title(); ?></h1>
				<div class="post-page__content"><?php the_content(); ?></div>
			</div>
		</div>
	</div>
</section>
<?php endwhile;
get_footer();
