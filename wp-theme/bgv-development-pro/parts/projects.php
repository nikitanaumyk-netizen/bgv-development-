<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_all = bgv_all_projects();
$bgv_gm  = bgv_group_meta();
$bgv_cur = isset( $_GET['cat'] ) ? sanitize_key( $_GET['cat'] ) : 'all'; // phpcs:ignore
?>
<section class="bgv-projects" id="our-projects">
	<div class="container">
		<div class="bgv-projects__head">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'projects_title' ) ?: bgv_t( 'Наші проєкти' ) ); ?></h2>
			<div class="bgv-chips" role="tablist" data-filter="#bgv-grid">
				<a class="bgv-chip<?php echo 'all' === $bgv_cur ? ' is-active' : ''; ?>" href="<?php echo esc_url( bgv_home( '/' ) ); ?>#our-projects" data-cat="all"><?php bgv_e( 'Всі' ); ?> <sup><?php echo count( $bgv_all ); ?></sup></a>
				<?php foreach ( $bgv_gm as $g => $m ) :
					$n = count( array_filter( $bgv_all, function ( $p ) use ( $g ) { return $p['_group'] === $g; } ) );
					if ( ! $n ) { continue; }
					$label = bgv_opt( 'tab_' . $g ); ?>
					<a class="bgv-chip<?php echo $m[0] === $bgv_cur ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'cat', $m[0], bgv_home( '/' ) ) ); ?>#our-projects" data-cat="<?php echo esc_attr( $m[0] ); ?>"><?php echo esc_html( $label ? $label : bgv_t( $m[1] ) ); ?> <sup><?php echo (int) $n; ?></sup></a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="bgv-grid" id="bgv-grid">
			<?php foreach ( $bgv_all as $p ) :
				$cat  = $bgv_gm[ $p['_group'] ][0];
				$hide = 'all' !== $bgv_cur && $cat !== $bgv_cur;
				$pres = bgv_file_url( $p['presentation'] ?? 0 );
				?>
				<article class="bgv-card" data-cat="<?php echo esc_attr( $cat ); ?>"<?php echo $hide ? ' hidden' : ''; ?>>
					<a class="bgv-card__img" href="<?php echo esc_url( $p['_url'] ); ?>">
						<img src="<?php echo esc_url( bgv_img_url( $p['image'], 'large' ) ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy">
						<?php if ( ! empty( $p['status'] ) ) : ?><span class="bgv-badge st-<?php echo esc_attr( $p['status'] ); ?>"><?php echo esc_html( bgv_status_label( $p['status'] ) ); ?></span><?php endif; ?>
					</a>
					<div class="bgv-card__body">
						<h3 class="bgv-card__title"><a href="<?php echo esc_url( $p['_url'] ); ?>"><?php echo esc_html( $p['title'] ); ?></a></h3>
						<?php if ( ! empty( $p['location'] ) ) : ?><p class="bgv-card__loc"><?php echo esc_html( $p['location'] ); ?></p><?php endif; ?>
						<?php if ( ! empty( $p['card_info'] ) ) : ?>
							<ul class="bgv-card__facts">
								<?php foreach ( $p['card_info'] as $ci ) : if ( '' === trim( (string) $ci['text'] ) ) { continue; } ?>
									<li><?php echo esc_html( trim( $ci['text'] ) ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<div class="bgv-card__actions">
							<a class="bgv-btn bgv-btn--sm" href="<?php echo esc_url( $p['_url'] ); ?>"><?php bgv_e( 'Детальніше' ); ?></a>
							<?php if ( ! empty( $p['website_link'] ) ) : ?><a class="bgv-btn bgv-btn--sm bgv-btn--ghost" href="<?php echo esc_url( $p['website_link'] ); ?>" target="_blank" rel="noopener"><?php bgv_e( 'Перейти на сайт' ); ?></a><?php endif; ?>
							<?php if ( $pres ) : ?><a class="bgv-btn bgv-btn--sm bgv-btn--ghost" href="#bgv-pres" data-bgv-pres="<?php echo esc_attr( $p['_slug'] ); ?>" data-bgv-title="<?php echo esc_attr( $p['title'] ); ?>">PDF</a><?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
