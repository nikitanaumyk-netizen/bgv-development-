<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_ach = array_filter( (array) bgv_opt( 'achievements' ), function ( $a ) { return ! empty( $a['name'] ); } );
$bgv_aw  = array_filter( (array) bgv_opt( 'awards' ), function ( $a ) { return ! empty( $a['title'] ); } );
?>
<?php if ( $bgv_ach ) : ?>
<section class="bgv-ach" id="achievements">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( bgv_opt( 'ach_title' ) ?: bgv_t( 'Досягнення' ) ); ?></h2>
		<ul class="bgv-ach__list">
			<?php foreach ( $bgv_ach as $a ) : $pc = max( 0, min( 100, (int) $a['percent'] ) ); ?>
				<li class="bgv-ach__item">
					<div class="bgv-ring" style="--p:<?php echo (int) $pc; ?>">
						<svg viewBox="0 0 120 120" aria-hidden="true"><circle cx="60" cy="60" r="52" class="bgv-ring__bg"/><circle cx="60" cy="60" r="52" class="bgv-ring__fg" pathLength="100"/></svg>
						<div class="bgv-ring__txt"><b><?php echo (int) $pc; ?>%</b><span><?php echo esc_html( $a['label'] ?: bgv_t( 'збудовано' ) ); ?></span></div>
					</div>
					<p class="bgv-ach__name"><?php echo esc_html( $a['name'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>
<?php if ( $bgv_aw ) : ?>
<section class="bgv-awards" id="awards">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( bgv_opt( 'awards_heading' ) ?: bgv_t( 'Нагороди' ) ); ?></h2>
		<?php if ( bgv_opt( 'awards_text' ) ) : ?><p class="bgv-awards__intro"><?php echo esc_html( bgv_opt( 'awards_text' ) ); ?></p><?php endif; ?>
		<ul class="bgv-awards__list">
			<?php foreach ( $bgv_aw as $a ) : $tag = ! empty( $a['link'] ) ? 'a' : 'div'; ?>
				<li>
					<<?php echo $tag; // phpcs:ignore ?> class="bgv-award"<?php echo 'a' === $tag ? ' href="' . esc_url( $a['link'] ) . '"' : ''; ?>>
						<span class="bgv-award__logo">
							<?php if ( bgv_img_url( $a['image'] ) ) : ?><img src="<?php echo esc_url( bgv_img_url( $a['image'], 'medium' ) ); ?>" alt="" loading="lazy">
							<?php else : ?><svg viewBox="0 0 48 48" width="44" height="44" aria-hidden="true"><path d="M24 4l5.5 11.2 12.3 1.8-8.9 8.7 2.1 12.3L24 32.2 13 38l2.1-12.3-8.9-8.7 12.3-1.8z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/></svg><?php endif; ?>
						</span>
						<span class="bgv-award__title"><?php echo esc_html( $a['title'] ); ?></span>
						<?php if ( ! empty( $a['note'] ) ) : ?><span class="bgv-award__note"><?php echo esc_html( $a['note'] ); ?></span><?php endif; ?>
					</<?php echo $tag; // phpcs:ignore ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>
