<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="blog-list">
	<div class="container">
		<h1 class="blog-list__title"><?php echo esc_html( is_category() ? single_cat_title( '', false ) : ( get_the_title( get_option( 'page_for_posts' ) ) ?: 'Акції та новини' ) ); ?></h1>
		<?php bgv_news_chips(); ?>
		<div class="blog-list__news">
			<?php while ( have_posts() ) : the_post(); $cats = get_the_category(); ?>
				<a class="blog-list__item" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'class' => 'blog-list__item-image', 'loading' => 'lazy' ) ); } ?>
					<div class="blog-list__item-description">
						<div class="blog-list__meta-container">
							<p class="blog-list__meta-item"><?php echo esc_html( $cats ? $cats[0]->name : 'Новини' ); ?></p>
							<p class="blog-list__meta-item"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></p>
						</div>
						<div class="blog-list__item-title"><p class="post-title"><?php the_title(); ?></p></div>
						<div class="blog-list__item-subtitle"><p class="post-subtitle"><?php echo esc_html( ( has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 22, '...' ) ) ); ?></p></div>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="blog-list__show-more bgv-pagination">
			<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
