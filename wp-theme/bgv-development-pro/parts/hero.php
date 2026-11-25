<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_slides = array();
foreach ( (array) bgv_opt( 'hero_slides' ) as $s ) {
	$u = bgv_img_url( $s['image'] ?? 0, 'full' );
	if ( $u ) { $bgv_slides[] = array( 'img' => $u, 'cap' => $s['caption'] ?? '', 'link' => $s['link'] ?? '' ); }
}
if ( ! $bgv_slides ) {
	foreach ( array_slice( bgv_all_projects(), 0, 4 ) as $p ) {
		$u = bgv_img_url( $p['image'], 'full' );
		if ( $u ) { $bgv_slides[] = array( 'img' => $u, 'cap' => $p['title'], 'link' => $p['_url'] ); }
	}
}
$bgv_video = bgv_file_url( bgv_opt( 'hero_video' ) );
$bgv_title = bgv_opt( 'hero_title' );
$bgv_sub   = bgv_opt( 'hero_lead' ) ? bgv_opt( 'hero_lead' ) : bgv_opt( 'hero_description' );
$bgv_stats = array_filter( (array) bgv_opt( 'stats' ), function ( $s ) { return ! empty( $s['number'] ); } );
?>
<section class="bgv-hero" aria-label="<?php echo esc_attr( wp_strip_all_tags( $bgv_title ) ); ?>">
	<div class="bgv-hero__media">
		<?php if ( $bgv_video ) : ?>
			<video class="bgv-hero__video" src="<?php echo esc_url( $bgv_video ); ?>"<?php echo $bgv_slides ? ' poster="' . esc_url( $bgv_slides[0]['img'] ) . '"' : ''; ?> autoplay muted loop playsinline preload="metadata"></video>
		<?php else : foreach ( $bgv_slides as $i => $sl ) : ?>
			<div class="bgv-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-cap="<?php echo esc_attr( $sl['cap'] ); ?>" data-link="<?php echo esc_url( $sl['link'] ); ?>">
				<img src="<?php echo esc_url( $sl['img'] ); ?>" alt="<?php echo esc_attr( $sl['cap'] ); ?>" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
			</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="container bgv-hero__in">
		<div class="bgv-hero__text">
			<?php if ( bgv_opt( 'hero_subtitle' ) ) : ?><p class="bgv-hero__kicker"><?php echo esc_html( bgv_opt( 'hero_subtitle' ) ); ?></p><?php endif; ?>
			<h1 class="bgv-hero__title"><?php echo esc_html( $bgv_title ); ?></h1>
			<?php if ( $bgv_sub ) : ?><p class="bgv-hero__lead"><?php echo esc_html( $bgv_sub ); ?></p><?php endif; ?>
			<div class="bgv-hero__cta">
				<a class="bgv-btn bgv-btn--light bgv-btn--arrow" href="#about"><?php echo esc_html( bgv_opt( 'hero_button' ) ?: bgv_t( 'Про компанію' ) ); ?></a>
			</div>
		</div>
		<?php if ( count( $bgv_slides ) > 1 && ! $bgv_video ) : ?>
			<div class="bgv-hero__dots" aria-label="Слайди">
				<?php foreach ( $bgv_slides as $i => $sl ) : ?>
					<button type="button" class="<?php echo 0 === $i ? 'is-active' : ''; ?>" data-slide="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' — ' . $sl['cap'] ); ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $bgv_stats ) : ?>
			<ul class="bgv-hero__stats" id="stats">
				<?php foreach ( $bgv_stats as $s ) : ?>
					<li><b><?php echo esc_html( $s['number'] ); ?></b><span><?php echo esc_html( $s['label'] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
