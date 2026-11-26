<?php
/**
 * «Наші проєкти» — у тому ж стилі, що й «Нагороди»: синій фон із фото проєктів, що змінюються,
 * картки з плашкою на фото та 3D-появою.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_all = bgv_all_projects();
$bgv_gm  = bgv_group_meta();
$bgv_cur = isset( $_GET['cat'] ) ? sanitize_key( $_GET['cat'] ) : 'all'; // phpcs:ignore
$bgv_pj_bgs = array();
foreach ( $bgv_all as $p ) {
	$u = bgv_img_url( $p['image'] ?? 0, 'full' );
	if ( $u && ! in_array( $u, $bgv_pj_bgs, true ) ) { $bgv_pj_bgs[] = $u; }
	if ( count( $bgv_pj_bgs ) >= 5 ) { break; }
}
?>
<section class="bgv-projects bgv-aw3 bgv-pj<?php echo $bgv_pj_bgs ? ' bgv-aw3--bg' : ''; ?>" id="our-projects">
	<?php if ( $bgv_pj_bgs ) : ?>
		<div class="bgv-aw3__bg" aria-hidden="true">
			<?php foreach ( $bgv_pj_bgs as $i => $u ) : ?><div class="bgv-aw3__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( $u ); ?>')"></div><?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="container">
		<div class="bgv-sec-head bgv-pj__head">
			<p class="bgv-kicker"><?php bgv_e( 'Проєкти' ); ?></p>
			<h2 class="bgv-aw3__title"><span class="bgv-aw3__mask"><span><?php echo esc_html( bgv_opt( 'projects_title' ) ?: bgv_t( 'Наші проєкти' ) ); ?></span></span></h2>
			<div class="bgv-chips bgv-pj__chips" role="tablist" data-filter="#bgv-grid">
				<a class="bgv-chip<?php echo 'all' === $bgv_cur ? ' is-active' : ''; ?>" href="<?php echo esc_url( bgv_home( '/' ) ); ?>#our-projects" data-cat="all"><?php bgv_e( 'Всі' ); ?> <sup><?php echo count( $bgv_all ); ?></sup></a>
				<?php foreach ( $bgv_gm as $g => $m ) :
					$n = count( array_filter( $bgv_all, function ( $p ) use ( $g ) { return $p['_group'] === $g; } ) );
					if ( ! $n ) { continue; }
					$label = bgv_opt( 'tab_' . $g ); ?>
					<a class="bgv-chip<?php echo $m[0] === $bgv_cur ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'cat', $m[0], bgv_home( '/' ) ) ); ?>#our-projects" data-cat="<?php echo esc_attr( $m[0] ); ?>"><?php echo esc_html( $label ? $label : bgv_t( $m[1] ) ); ?> <sup><?php echo (int) $n; ?></sup></a>
				<?php endforeach; ?>
			</div>
		</div>
		<ul class="bgv-aw3__list bgv-pj__list" id="bgv-grid">
			<?php foreach ( $bgv_all as $p ) :
				$cat   = $bgv_gm[ $p['_group'] ][0];
				$glab  = bgv_opt( 'tab_' . $p['_group'] );
				$glab  = $glab ? $glab : bgv_t( $bgv_gm[ $p['_group'] ][1] );
				$hide  = 'all' !== $bgv_cur && $cat !== $bgv_cur;
				$pres  = bgv_file_url( $p['presentation'] ?? 0 );
				$short = bgv_project_short( $p );
				$city  = bgv_project_city( $p['location'] ?? '' );
				$st    = ! empty( $p['status'] ) ? bgv_status_label( $p['status'] ) : '';
				?>
				<li data-cat="<?php echo esc_attr( $cat ); ?>"<?php echo $hide ? ' hidden' : ''; ?>>
					<div class="bgv-aw3__card bgv-pj__card">
						<a class="bgv-aw3__img" href="<?php echo esc_url( $p['_url'] ); ?>" aria-label="<?php echo esc_attr( $p['title'] ); ?>"<?php echo bgv_img_url( $p['image'] ?? 0 ) ? ' style="background-image:url(\'' . esc_url( bgv_img_url( $p['image'], 'large' ) ) . '\')"' : ''; ?>>
							<span class="bgv-aw3__tag"><?php echo esc_html( $glab ); ?></span>
							<span class="bgv-aw3__plate">
								<b><?php echo esc_html( $short ); ?></b>
								<?php if ( $city ) : ?><small><?php echo esc_html( $city ); ?></small><?php endif; ?>
							</span>
						</a>
						<span class="bgv-aw3__body">
							<?php if ( $st ) : ?><span class="bgv-aw3__kind"><?php echo esc_html( $st ); ?></span><?php endif; ?>
							<a class="bgv-aw3__name" href="<?php echo esc_url( $p['_url'] ); ?>"><?php echo esc_html( $p['title'] ); ?></a>
							<?php if ( ! empty( $p['location'] ) ) : ?><span class="bgv-aw3__note"><?php echo esc_html( $p['location'] ); ?></span><?php endif; ?>
							<?php if ( ! empty( $p['card_info'] ) ) : ?>
								<ul class="bgv-card__facts">
									<?php foreach ( $p['card_info'] as $ci ) : if ( '' === trim( (string) $ci['text'] ) ) { continue; } ?>
										<li><?php echo esc_html( trim( $ci['text'] ) ); ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<span class="bgv-card__actions">
								<a class="bgv-btn bgv-btn--sm" href="<?php echo esc_url( $p['_url'] ); ?>"><?php bgv_e( 'Детальніше' ); ?></a>
								<?php if ( ! empty( $p['website_link'] ) ) : ?><a class="bgv-btn bgv-btn--sm bgv-btn--ghost" href="<?php echo esc_url( $p['website_link'] ); ?>" target="_blank" rel="noopener"><?php bgv_e( 'Перейти на сайт' ); ?></a><?php endif; ?>
								<?php if ( $pres ) : ?><a class="bgv-btn bgv-btn--sm bgv-btn--ghost" href="#bgv-pres" data-bgv-pres="<?php echo esc_attr( $p['_slug'] ); ?>" data-bgv-title="<?php echo esc_attr( $p['title'] ); ?>">PDF</a><?php endif; ?>
							</span>
						</span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
