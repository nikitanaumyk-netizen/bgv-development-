<?php
/**
 * Одноразово (v2.7.3): повернути попередні фото на першому екрані та в «Нагородах»
 * (фото без надписів лишаються тільки в «Інвесторам»).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v29_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v29_done', 1, false );
	$img = function ( $w ) {
		foreach ( bgv_all_projects() as $p ) { if ( false !== mb_stripos( $p['title'], $w ) && ! empty( $p['image'] ) ) { return (int) $p['image']; } }
		return 0;
	};
	$boyarka = $img( 'Боярка' );
	$zhyto   = $img( 'ЖИТО, Житомир' );
	$sl = get_field( 'hero_slides', $id, false );
	if ( is_array( $sl ) ) {
		foreach ( $sl as $i => $r ) {
			$cap = (string) $r['field_bgv_hero_slides_meta'] . ' ' . $r['field_bgv_hero_slides_caption'];
			if ( $boyarka && false !== mb_stripos( $cap, 'Боярка' ) ) { $sl[ $i ]['field_bgv_hero_slides_image'] = $boyarka; }
			if ( $zhyto && false !== mb_stripos( $cap, 'Житомир' ) && false !== mb_stripos( $cap, 'ЖИТО' ) ) { $sl[ $i ]['field_bgv_hero_slides_image'] = $zhyto; }
		}
		update_field( 'field_bgv_hero_slides', $sl, $id );
	}
	$aw = get_field( 'awards', $id, false );
	if ( is_array( $aw ) && $boyarka ) {
		foreach ( $aw as $i => $r ) {
			if ( 'EPA' === trim( (string) ( $r['field_bgv_awards_short'] ?? '' ) ) ) { $aw[ $i ]['field_bgv_awards_photo'] = $boyarka; }
		}
		update_field( 'field_bgv_awards', $aw, $id );
	}
} );
