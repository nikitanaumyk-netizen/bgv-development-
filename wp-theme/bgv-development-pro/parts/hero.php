<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_slides = array();
foreach ( (array) bgv_opt( 'hero_slides' ) as $s ) {
	$u = bgv_img_url( $s['image'] ?? 0, 'full' );
	if ( $u ) {
		$bgv_slides[] = array( 'img' => $u, 'cap' => $s['caption'] ?? '', 'meta' => $s['meta'] ?? '', 'tab' => ( $s['tab'] ?? '' ) ?: ( $s['caption'] ?? '' ), 'link' => $s['link'] ?? '' );
	}
}
if ( ! $bgv_slides ) {
	foreach ( array_slice( bgv_all_projects(), 0, 4 ) as $p ) {
		$u = bgv_img_url( $p['image'], 'full' );
		if ( $u ) { $bgv_slides[] = array( 'img' => $u, 'cap' => $p['title'], 'meta' => bgv_status_label( $p['status'] ?? '' ), 'tab' => $p['title'], 'link' => $p['_url'] ); }
	}
}
$bgv_video = bgv_file_url( bgv_opt( 'hero_video' ) );
$bgv_title = bgv_opt( 'hero_title' );
$bgv_n     = count( $bgv_slides );
?>
<section class="bgv-hero bgv-hero--v5" aria-label="<?php echo esc_attr( wp_strip_all_tags( $bgv_title ) ); ?>">
	<div class="bgv-hero__media">
		<?php if ( $bgv_video ) : ?>
			<video class="bgv-hero__video" src="<?php echo esc_url( $bgv_video ); ?>"<?php echo $bgv_slides ? ' poster="' . esc_url( $bgv_slides[0]['img'] ) . '"' : ''; ?> autoplay muted loop playsinline preload="metadata"></video>
		<?php endif; ?>
		<?php foreach ( $bgv_slides as $i => $sl ) : ?>
			<div class="bgv-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"<?php echo $bgv_video ? ' hidden' : ''; ?>>
				<img src="<?php echo esc_url( $sl['img'] ); ?>" alt="<?php echo esc_attr( $sl['cap'] ); ?>" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="container bgv-hero__in">
		<div class="bgv-hero__text">
			<?php if ( bgv_opt( 'hero_subtitle' ) ) : ?><p class="bgv-hero__kicker"><?php echo esc_html( bgv_opt( 'hero_subtitle' ) ); ?></p><?php endif; ?>
			<h1 class="bgv-hero__title"><?php echo esc_html( $bgv_title ); ?></h1>
			<?php if ( bgv_opt( 'hero_description' ) ) : ?><p class="bgv-hero__desc"><?php echo esc_html( bgv_opt( 'hero_description' ) ); ?></p><?php endif; ?>
			<div class="bgv-hero__cta">
				<button class="bgv-btn" type="button" data-bgv-open="bgv-quiz"><?php bgv_e( 'Підібрати об’єкт' ); ?></button>
				<a class="bgv-btn bgv-btn--outline-light" href="#our-projects"><?php bgv_e( 'Наші проєкти' ); ?></a>
			</div>
		</div>
	</div>
	<?php if ( $bgv_n && ! $bgv_video ) : ?>
		<div class="bgv-hero__panel" data-total="<?php echo (int) $bgv_n; ?>">
			<div class="bgv-hero__panel-top">
				<div class="bgv-hero__info">
					<p class="bgv-hero__name"><?php echo esc_html( $bgv_slides[0]['cap'] ); ?></p>
					<p class="bgv-hero__meta"><span><?php echo esc_html( $bgv_slides[0]['meta'] ); ?></span>
						<a class="bgv-hero__open" href="<?php echo esc_url( $bgv_slides[0]['link'] ?: '#our-projects' ); ?>"><?php bgv_e( 'Відкрити проєкт' ); ?></a></p>
				</div>
				<?php if ( $bgv_n > 1 ) : ?>
					<div class="bgv-hero__arrows">
						<button type="button" class="bgv-hero__arrow" data-dir="-1" aria-label="Попередній проєкт">←</button>
						<span class="bgv-hero__count"><b>01</b> / <?php echo esc_html( sprintf( '%02d', $bgv_n ) ); ?></span>
						<button type="button" class="bgv-hero__arrow" data-dir="1" aria-label="Наступний проєкт">→</button>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $bgv_n > 1 ) : ?>
				<div class="bgv-hero__tabs" role="tablist">
					<?php foreach ( $bgv_slides as $i => $sl ) : ?>
						<button type="button" role="tab" class="bgv-hero__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide="<?php echo (int) $i; ?>" data-cap="<?php echo esc_attr( $sl['cap'] ); ?>" data-meta="<?php echo esc_attr( $sl['meta'] ); ?>" data-link="<?php echo esc_url( $sl['link'] ); ?>"><?php echo esc_html( $sl['tab'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="bgv-hero__progress"><span></span></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<a class="bgv-hero__scroll" href="#about"><?php bgv_e( 'Гортайте вниз' ); ?></a>
</section>
