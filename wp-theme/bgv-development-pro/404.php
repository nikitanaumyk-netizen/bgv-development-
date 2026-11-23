<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<section class="post-page">
	<div class="container">
		<div class="post-page__main">
			<div class="post-page__content-container">
				<h1 class="post-page__title">Сторінку не знайдено</h1>
				<div class="post-page__content"><p>Можливо, її перенесли або видалили. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Повернутися на головну</a></p></div>
			</div>
		</div>
	</div>
</section>
<?php get_footer();
