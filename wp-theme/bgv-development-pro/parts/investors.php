<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! bgv_opt( 'inv_title' ) ) { return; }
$bgv_items = array_filter( (array) bgv_opt( 'inv_items' ), function ( $a ) { return ! empty( $a['title'] ); } );
?>
<?php $bgv_inv_bg = array_values( array_filter( array_map( 'intval', (array) bgv_opt( 'inv_images' ) ) ) ); ?>
<section class="bgv-inv<?php echo $bgv_inv_bg ? ' bgv-inv--photo' : ''; ?>" id="investors">
	<?php if ( $bgv_inv_bg ) : ?>
		<div class="bgv-inv__bg" data-slider>
			<?php foreach ( $bgv_inv_bg as $i => $img ) : ?>
				<div class="bgv-soc-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( bgv_img_url( $img, 'full' ) ); ?>')"></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="container bgv-inv__in">
		<div class="bgv-inv__text">
			<p class="bgv-inv__kicker"><?php echo esc_html( bgv_opt( 'inv_kicker' ) ?: bgv_t( 'Інвесторам' ) ); ?></p>
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
