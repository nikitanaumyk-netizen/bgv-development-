<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_it = bgv_opt( 'intro_title' ) ?: 'Build Your Future';
$bgv_ix = bgv_opt( 'intro_text' ) ?: 'Девелоперська компанія BGV Development. Створюємо простір для життя, бізнесу та майбутнього.';
$bgv_ib = bgv_opt( 'intro_button' ) ?: bgv_t( 'Дивитися проєкти' );
?>
<section class="bgv-ihero" id="top-hero" aria-labelledby="bgv-ihero-title">
	<svg class="bgv-ihero__shapes" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
		<g id="bgv-shape-1"><polygon points="-120,120 520,-60 300,560" fill="#FFFFFF" fill-opacity="0.12"/></g>
		<g id="bgv-shape-2"><polygon points="1080,140 1360,420 1080,700 800,420" fill="#BEC8FF" fill-opacity="0.14"/></g>
		<g id="bgv-shape-3"><polygon points="980,980 1560,520 1560,1020" fill="#FFFFFF" fill-opacity="0.1"/></g>
	</svg>
	<div class="container bgv-ihero__content">
		<h1 class="bgv-ihero__title bgv-ihero-reveal" id="bgv-ihero-title"><?php echo esc_html( $bgv_it ); ?></h1>
		<p class="bgv-ihero__subtitle bgv-ihero-reveal"><?php echo esc_html( $bgv_ix ); ?></p>
		<a class="bgv-ihero__btn bgv-ihero-reveal" href="#projects-hero"><?php echo esc_html( $bgv_ib ); ?></a>
	</div>
	<a class="bgv-ihero__scroll" id="bgv-scroll-ind" href="#projects-hero" aria-label="<?php bgv_e( 'Гортайте вниз' ); ?>">
		<span class="bgv-ihero__track"><span class="bgv-ihero__dot" id="bgv-scroll-dot"></span></span>
	</a>
</section>
