<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_ach = array_values( array_filter( (array) bgv_opt( 'achievements' ), function ( $a ) { return ! empty( $a['name'] ); } ) );
$bgv_aw  = array_values( array_filter( (array) bgv_opt( 'awards' ), function ( $a ) { return ! empty( $a['title'] ); } ) );
// Фото за назвою проєкту, якщо в рядку фото не задано.
$bgv_proj_img = function ( $name ) {
	foreach ( bgv_all_projects() as $p ) {
		$t = mb_strtolower( $p['title'] );
		foreach ( preg_split( '/[\s,«»"()|]+/u', mb_strtolower( $name ) ) as $w ) {
			if ( mb_strlen( $w ) > 3 && ! in_array( $w, array( 'житомир', 'будинок', 'школа', 'дитяча' ), true ) && false !== mb_strpos( $t, $w ) ) { return (int) $p['image']; }
		}
	}
	return 0;
};
?>
<?php if ( $bgv_ach ) : ?>
<section class="bgv-ach2" id="achievements">
	<div class="container">
		<div class="bgv-sec-head">
			<p class="bgv-kicker"><?php bgv_e( 'Готовність об’єктів' ); ?></p>
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'ach_title' ) ?: bgv_t( 'Досягнення' ) ); ?></h2>
		</div>
		<ul class="bgv-ach2__list">
			<?php foreach ( $bgv_ach as $a ) :
				$pc  = max( 0, min( 100, (int) $a['percent'] ) );
				$img = bgv_img_url( ! empty( $a['image'] ) ? $a['image'] : $bgv_proj_img( $a['name'] ), 'large' );
				?>
				<li class="bgv-ach2__card"<?php echo $img ? ' style="background-image:url(\'' . esc_url( $img ) . '\')"' : ''; ?>>
					<div class="bgv-ring" style="--p:<?php echo (int) $pc; ?>" data-p="<?php echo (int) $pc; ?>">
						<svg viewBox="0 0 120 120" aria-hidden="true"><circle cx="60" cy="60" r="52" class="bgv-ring__bg"/><circle cx="60" cy="60" r="52" class="bgv-ring__fg" pathLength="100"/></svg>
						<div class="bgv-ring__txt"><b data-count="<?php echo (int) $pc; ?>%"><?php echo (int) $pc; ?>%</b><span><?php echo esc_html( $a['label'] ?: bgv_t( 'збудовано' ) ); ?></span></div>
					</div>
					<p class="bgv-ach2__name"><?php echo esc_html( $a['name'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>
<?php if ( $bgv_aw ) : ?>
<section class="bgv-aw3" id="awards">
	<div class="container">
		<div class="bgv-sec-head">
			<p class="bgv-kicker"><?php bgv_e( 'Нагороди' ); ?></p>
			<h2 class="bgv-aw3__title"><?php echo esc_html( bgv_opt( 'awards_heading' ) ?: bgv_t( 'Нагороди' ) ); ?></h2>
			<?php if ( bgv_opt( 'awards_text' ) ) : ?><p class="bgv-sec-head__text"><?php echo esc_html( bgv_opt( 'awards_text' ) ); ?></p><?php endif; ?>
		</div>
		<ul class="bgv-aw3__list">
			<?php foreach ( $bgv_aw as $a ) :
				$photo = ! empty( $a['photo'] ) ? (int) $a['photo'] : 0;
				if ( ! $photo && ! empty( $a['link'] ) ) { $pid = url_to_postid( $a['link'] ); $photo = $pid ? (int) get_post_thumbnail_id( $pid ) : 0; }
				$tag = ! empty( $a['link'] ) ? 'a' : 'div';
				?>
				<li>
					<<?php echo $tag; // phpcs:ignore ?> class="bgv-aw3__card"<?php echo 'a' === $tag ? ' href="' . esc_url( $a['link'] ) . '"' : ''; ?>>
						<span class="bgv-aw3__img"<?php echo bgv_img_url( $photo ) ? ' style="background-image:url(\'' . esc_url( bgv_img_url( $photo, 'large' ) ) . '\')"' : ''; ?>>
							<?php if ( ! empty( $a['tag'] ) ) : ?><span class="bgv-aw3__tag"><?php echo esc_html( $a['tag'] ); ?></span><?php endif; ?>
							<span class="bgv-aw3__plate">
								<?php if ( bgv_img_url( $a['image'] ) ) : ?><img src="<?php echo esc_url( bgv_img_url( $a['image'], 'medium' ) ); ?>" alt=""><?php endif; ?>
								<b><?php echo esc_html( ! empty( $a['short'] ) ? $a['short'] : $a['title'] ); ?></b>
								<?php if ( ! empty( $a['year'] ) ) : ?><small><?php echo esc_html( $a['year'] ); ?></small><?php endif; ?>
							</span>
						</span>
						<span class="bgv-aw3__body">
							<?php if ( ! empty( $a['kind'] ) ) : ?><span class="bgv-aw3__kind"><?php echo esc_html( bgv_t( $a['kind'] ) ); ?></span><?php endif; ?>
							<span class="bgv-aw3__name"><?php echo esc_html( $a['title'] ); ?></span>
							<?php if ( ! empty( $a['note'] ) ) : ?><span class="bgv-aw3__note"><?php echo esc_html( $a['note'] ); ?></span><?php endif; ?>
						</span>
					</<?php echo $tag; // phpcs:ignore ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>
