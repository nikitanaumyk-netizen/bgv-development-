<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_adv = array_values( array_filter( (array) bgv_opt( 'advantages' ), function ( $a ) { return ! empty( $a['title'] ); } ) );
if ( ! $bgv_adv ) { return; }
?>
<section class="bgv-adv2" id="advantages">
	<div class="container bgv-adv2__in">
		<div class="bgv-adv2__head">
			<p class="bgv-adv2__kicker"><?php echo esc_html( bgv_opt( 'adv_kicker' ) ?: bgv_t( 'Наші переваги' ) ); ?></p>
			<h2 class="bgv-adv2__title"><?php echo esc_html( bgv_opt( 'adv_title' ) ?: bgv_t( 'Ключові переваги' ) ); ?></h2>
		</div>
		<ol class="bgv-adv2__list">
			<?php foreach ( $bgv_adv as $i => $a ) : $img = bgv_img_url( $a['image'] ?? 0, 'large' ); ?>
				<li class="bgv-adv2__card"<?php echo $img ? ' style="background-image:url(\'' . esc_url( $img ) . '\')"' : ''; ?>>
					<span class="bgv-adv2__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<p class="bgv-adv2__t"><?php echo esc_html( $a['title'] ); ?></p>
					<?php if ( ! empty( $a['text'] ) ) : ?><p class="bgv-adv2__d"><?php echo esc_html( $a['text'] ); ?></p><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
