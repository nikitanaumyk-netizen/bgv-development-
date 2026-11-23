<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$cats = get_the_category();
	?>
<section class="post-page">
	<div class="container">
		<div class="post-page__main">
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array( 'class' => 'post-page__image' ) ); } ?>
			<div class="post-page__content-container">
				<h1 class="post-page__title"><?php the_title(); ?></h1>
				<div class="post-page__content"><?php the_content(); ?></div>
			</div>
		</div>
		<div class="post-page__sidebar">
			<div class="post-page__author">
				<div class="post-page__author-top">
					<img class="post-page__author-image" src="<?php echo esc_url( get_template_directory_uri() . '/assets/author.webp' ); ?>" alt="">
					<div class="post-page__author-info">
						<p class="post-page__author-name">BGV Development</p>
						<p class="post-page__author-position">Автор новини</p>
					</div>
				</div>
				<div class="post-page__author-meta">
					<div class="post-page__category-container"><p class="post-page__category"><?php echo esc_html( $cats ? $cats[0]->name : 'Новини' ); ?></p></div>
					<div class="post-page__date-container"><p class="post-page__date"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></p></div>
				</div>
			</div>
			<div class="post-page__other-posts">
				<h4 class="post-page__other-posts-title">Вас може зацікавити</h4>
				<div class="post-page__other-posts-list">
					<?php
					$other = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 4, 'post__not_in' => array( get_the_ID() ), 'ignore_sticky_posts' => true ) );
					while ( $other->have_posts() ) : $other->the_post();
						?>
						<a class="post-page__other-post-item" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail', array( 'class' => 'post-page__other-post-item-img' ) ); } ?>
							<p class="post-page__other-post-item-img-title"><?php the_title(); ?></p>
						</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
	<?php
endwhile;
get_footer();
