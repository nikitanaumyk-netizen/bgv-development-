<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$p   = $GLOBALS['bgv_project'];
$dir = get_template_directory_uri() . '/assets/';
$gal = ! empty( $p['gallery'] ) ? array_values( array_filter( (array) $p['gallery'] ) ) : array();
if ( ! $gal && ! empty( $p['image'] ) ) { $gal = array( $p['image'] ); }
$main = $gal ? array_shift( $gal ) : 0;
$thumbs = array_slice( $gal, 0, 3 );
$more   = array_slice( $gal, 3 );
get_header();
?>
<section class="estate-page">
	<h1 class="vh"><?php echo esc_html( $p['title'] ); ?></h1>
	<div class="container">
		<div class="estate-popup estate-popup--page">
			<div class="estate-popup__content">
				<div class="estate-popup__main">
					<div class="estate-popup__gallery-container">
						<?php if ( $main ) : ?>
							<div class="estate-popup__gallery-main"><img src="<?php echo esc_url( bgv_img_url( $main, 'large' ) ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>"></div>
						<?php endif; ?>
						<?php if ( $thumbs ) : ?>
							<div class="estate-popup__gallery-items">
								<?php foreach ( $thumbs as $n => $id ) : ?>
									<a href="<?php echo esc_url( bgv_img_url( $id ) ); ?>"><img src="<?php echo esc_url( bgv_img_url( $id, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( $p['title'] . ' — фото ' . ( $n + 2 ) ); ?>" loading="lazy"></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( $more ) : ?>
							<div class="estate-popup__gallery-items estate-popup__gallery-more">
								<?php foreach ( $more as $n => $id ) : ?>
									<a href="<?php echo esc_url( bgv_img_url( $id ) ); ?>"><img src="<?php echo esc_url( bgv_img_url( $id, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( $p['title'] . ' — фото ' . ( $n + 5 ) ); ?>" loading="lazy"></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $p['description'] ) ) : ?>
						<div class="estate-popup__description">
							<p class="estate-popup__description-title"><?php bgv_e( 'Опис' ); ?></p>
							<div class="estate-popup__description-text"><?php echo wp_kses_post( $p['description'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="estate-popup__sidebar">
					<div class="estate-popup__building-info">
						<p class="estate-popup__building-title"><?php echo esc_html( $p['title'] ); ?></p>
						<?php if ( ! empty( $p['location'] ) ) : ?>
							<div class="estate-popup__building-location-container">
								<img src="<?php echo esc_url( $dir . 'icon-location.svg' ); ?>" alt="">
								<p class="estate-popup__building-location-text"><?php echo esc_html( $p['location'] ); ?></p>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $p['features'] ) ) : ?>
							<div class="estate-popup__building-features-list">
								<?php foreach ( $p['features'] as $f ) : if ( empty( $f['text'] ) ) { continue; } ?>
									<div class="estate-popup__building-feature-item">
										<?php if ( bgv_img_url( $f['icon'] ) ) : ?><img src="<?php echo esc_url( bgv_img_url( $f['icon'] ) ); ?>" alt=""><?php endif; ?>
										<p class="estate-popup__building-feature-text"><?php echo esc_html( $f['text'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $p['status'] ) ) : ?><p class="bgv-badge bgv-badge--inline st-<?php echo esc_attr( $p['status'] ); ?>"><?php echo esc_html( bgv_status_label( $p['status'] ) ); ?></p><?php endif; ?>
					<?php if ( ! empty( $p['website_link'] ) ) : ?>
						<a class="estate-popup__button" href="<?php echo esc_url( $p['website_link'] ); ?>" target="_blank" rel="noopener"><p class="estate-popup__button-text"><?php bgv_e( 'Перейти на сайт' ); ?></p></a>
					<?php endif; ?>
					<?php if ( bgv_file_url( $p['presentation'] ?? 0 ) ) : ?>
						<a class="estate-popup__button bgv-pres-btn" href="#bgv-pres" data-bgv-pres="<?php echo esc_attr( sanitize_title( get_query_var( 'bgv_project' ) ) ); ?>" data-bgv-title="<?php echo esc_attr( $p['title'] ); ?>"><p class="estate-popup__button-text"><?php bgv_e( 'Презентація об’єкта' ); ?> (PDF)</p></a>
					<?php endif; ?>
					<button class="estate-popup__button bgv-pres-btn" type="button" data-bgv-open="bgv-form" data-bgv-title="<?php echo esc_attr( $p['title'] ); ?>" data-bgv-object="<?php echo esc_attr( $p['title'] ); ?>"><p class="estate-popup__button-text"><?php bgv_e( 'Замовити дзвінок' ); ?></p></button>
					<?php if ( ! empty( $p['agent_name'] ) || ! empty( $p['agent_phone'] ) || ! empty( $p['agent_email'] ) ) : ?>
						<div class="estate-popup__agent">
							<?php if ( bgv_img_url( $p['agent_image'] ) ) : ?><img class="estate-popup__agent-photo" src="<?php echo esc_url( bgv_img_url( $p['agent_image'], 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $p['agent_name'] ); ?>"><?php endif; ?>
							<div class="estate-popup__agent-info">
								<p class="estate-popup__agent-name"><?php echo esc_html( $p['agent_name'] ?: bgv_t( 'Відділ продажу' ) ); ?></p>
								<p class="estate-popup__agent-position"><?php echo esc_html( $p['agent_position'] ); ?></p>
								<?php if ( ! empty( $p['agent_phone'] ) ) : ?>
									<a class="estate-popup__agent-phone-container" href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $p['agent_phone'] ) ); ?>"><img src="<?php echo esc_url( $dir . 'icon-phone.svg' ); ?>" alt=""><p class="estate-popup__phone-text"><?php echo esc_html( $p['agent_phone'] ); ?></p></a>
								<?php endif; ?>
								<?php if ( ! empty( $p['agent_email'] ) ) : ?>
									<a class="estate-popup__agent-email-container" href="mailto:<?php echo esc_attr( trim( $p['agent_email'] ) ); ?>"><img src="<?php echo esc_url( $dir . 'icon-email.svg' ); ?>" alt=""><p class="estate-popup__email-text"><?php echo esc_html( trim( $p['agent_email'] ) ); ?></p></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<a class="estate-popup__exit" href="<?php echo esc_url( bgv_home( '/' ) . '#our-projects' ); ?>" aria-label="Закрити"><img src="<?php echo esc_url( $dir . 'icon-close.svg' ); ?>" alt=""></a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
