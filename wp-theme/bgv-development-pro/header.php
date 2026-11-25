<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_phone = bgv_phone();
$bgv_en    = bgv_en_page_id();
?><!doctype html>
<html <?php echo 'en' === bgv_lang() ? 'lang="en-US"' : get_language_attributes(); // phpcs:ignore ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bgv2' ); ?>>
<?php wp_body_open(); ?>
<?php if ( is_front_page() ) : ?>
<div class="bgv-intro" aria-hidden="true">
	<svg class="bgv-intro__logo" viewBox="0 0 120 64" role="img" aria-label="BGV Development">
		<rect x="21" y="2" width="78" height="40" fill="none" stroke="currentColor" stroke-width="2.5"/>
		<text x="60" y="31" text-anchor="middle" font-family="Qanelas, Manrope, Arial, sans-serif" font-size="26" font-weight="700" fill="currentColor">BGV</text>
		<text x="60" y="60" text-anchor="middle" font-family="Qanelas, Manrope, Arial, sans-serif" font-size="10" font-weight="700" letter-spacing="-0.3" fill="currentColor">DEVELOPMENT</text>
	</svg>
</div>
<script>document.documentElement.classList.add('bgv-intro-lock');setTimeout(function(){document.documentElement.classList.remove('bgv-intro-lock');},2900);</script>
<?php endif; ?>
<div id="page" class="site">
	<header id="masthead" class="site-header bgv-header">
		<div class="container bgv-header__in">
			<div class="site-branding">
				<a href="<?php echo esc_url( bgv_home( '/' ) ); ?>" class="custom-logo-link" rel="home">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.svg' ); ?>" class="custom-logo logo-dark" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="170" height="20">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/footer-logo.svg' ); ?>" class="custom-logo logo-light" alt="" aria-hidden="true" width="76" height="50">
				</a>
			</div>
			<nav id="site-navigation" class="main-navigation bgv-nav" aria-label="<?php bgv_e( 'Меню' ); ?>">
				<?php bgv_menu( 'primary-menu' ); ?>
				<div class="bgv-nav__extra">
					<?php if ( $bgv_phone ) : ?><a class="bgv-phone" href="<?php echo esc_attr( bgv_tel( $bgv_phone ) ); ?>"><?php echo esc_html( $bgv_phone ); ?></a><?php endif; ?>
					<?php if ( $bgv_en ) : ?>
						<div class="bgv-lang">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo 'uk' === bgv_lang() ? 'is-active' : ''; ?>" hreflang="uk">UA</a>
							<a href="<?php echo esc_url( get_permalink( $bgv_en ) ); ?>" class="<?php echo 'en' === bgv_lang() ? 'is-active' : ''; ?>" hreflang="en">EN</a>
						</div>
					<?php endif; ?>
				</div>
			</nav>
			<button class="bgv-btn bgv-header__cta" type="button" data-bgv-open="bgv-quiz"><?php bgv_e( 'Підібрати об’єкт' ); ?></button>
			<button class="bgv-burger" type="button" aria-label="<?php bgv_e( 'Меню' ); ?>" aria-expanded="false" aria-controls="site-navigation"><span></span><span></span><span></span></button>
		</div>
	</header>
	<main>
