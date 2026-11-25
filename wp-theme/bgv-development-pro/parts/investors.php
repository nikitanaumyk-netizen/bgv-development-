<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! bgv_opt( 'inv_title' ) ) { return; }
$bgv_items = array_filter( (array) bgv_opt( 'inv_items' ), function ( $a ) { return ! empty( $a['title'] ); } );
?>
<section class="bgv-inv" id="investors">
	<div class="container bgv-inv__in">
		<div class="bgv-inv__text">
			<p class="bgv-inv__kicker"><?php bgv_e( 'Інвесторам' ); ?></p>
			<h2 class="bgv-inv__title"><?php echo esc_html( bgv_opt( 'inv_title' ) ); ?></h2>
			<?php if ( bgv_opt( 'inv_text' ) ) : ?><p class="bgv-inv__desc"><?php echo nl2br( esc_html( bgv_opt( 'inv_text' ) ) ); ?></p><?php endif; ?>
			<button class="bgv-btn bgv-btn--light" type="button" data-bgv-open="bgv-form" data-bgv-type="partner" data-bgv-title="<?php echo esc_attr( bgv_opt( 'inv_button' ) ?: bgv_t( 'Стати партнером' ) ); ?>"><?php echo esc_html( bgv_opt( 'inv_button' ) ?: bgv_t( 'Стати партнером' ) ); ?></button>
		</div>
		<?php if ( $bgv_items ) : ?>
			<ul class="bgv-inv__list">
				<?php foreach ( $bgv_items as $it ) : ?>
					<li><p class="bgv-inv__t"><?php echo esc_html( $it['title'] ); ?></p><?php if ( ! empty( $it['text'] ) ) : ?><p class="bgv-inv__d"><?php echo esc_html( $it['text'] ); ?></p><?php endif; ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
