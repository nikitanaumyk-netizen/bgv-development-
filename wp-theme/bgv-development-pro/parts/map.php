<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_pins = array();
foreach ( bgv_all_projects() as $p ) {
	$lat = (float) str_replace( ',', '.', (string) ( $p['lat'] ?? '' ) );
	$lng = (float) str_replace( ',', '.', (string) ( $p['lng'] ?? '' ) );
	if ( ! $lat || ! $lng ) { continue; }
	$bgv_pins[] = array(
		'lat'    => $lat,
		'lng'    => $lng,
		'title'  => $p['title'],
		'img'    => bgv_img_url( $p['image'], 'medium' ),
		'status' => $p['status'] ?? '',
		'label'  => bgv_status_label( $p['status'] ?? '' ),
		'loc'    => $p['location'] ?? '',
		'url'    => $p['_url'],
		'cat'    => bgv_group_meta()[ $p['_group'] ][0],
	);
}
if ( ! $bgv_pins ) { return; }
?>
<section class="bgv-map" id="map">
	<div class="container">
		<div class="bgv-map__head">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'map_title' ) ?: bgv_t( 'Карта об’єктів' ) ); ?></h2>
			<ul class="bgv-map__legend">
				<li><i class="st-building"></i><?php bgv_e( 'Будується' ); ?></li>
				<li><i class="st-done"></i><?php bgv_e( 'Здано / реалізовано' ); ?></li>
				<li><i class="st-concept"></i><?php bgv_e( 'Проєктування / концепція' ); ?></li>
			</ul>
		</div>
		<div class="bgv-map__wrap">
			<div class="bgv-map__canvas" id="bgv-map-canvas" data-pins="<?php echo esc_attr( wp_json_encode( $bgv_pins ) ); ?>" data-more="<?php bgv_e( 'Детальніше' ); ?>"></div>
			<ul class="bgv-map__list">
				<?php foreach ( $bgv_pins as $i => $pin ) : ?>
					<li><a href="<?php echo esc_url( $pin['url'] ); ?>" data-pin="<?php echo (int) $i; ?>"><i class="st-<?php echo esc_attr( $pin['status'] ?: 'none' ); ?>"></i><span><?php echo esc_html( $pin['title'] ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
