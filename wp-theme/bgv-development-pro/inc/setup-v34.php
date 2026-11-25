<?php
/**
 * Одноразово (v2.16.4): слайд «ТЦ ЖИТО, Житомир» на першому екрані — знову головне фото проєкту.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v34_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v34_done', 1, false );
	$zhyto = 0;
	foreach ( bgv_all_projects() as $p ) { if ( false !== mb_stripos( $p['title'], 'ЖИТО, Житомир' ) && ! empty( $p['image'] ) ) { $zhyto = (int) $p['image']; break; } }
	if ( ! $zhyto ) { return; }
	$sl = get_field( 'hero_slides', $id, false );
	if ( ! is_array( $sl ) ) { return; }
	foreach ( $sl as $i => $r ) {
		$cap = (string) $r['field_bgv_hero_slides_meta'] . ' ' . $r['field_bgv_hero_slides_caption'];
		if ( false !== mb_stripos( $cap, 'Житомир' ) && false !== mb_stripos( $cap, 'ЖИТО' ) ) { $sl[ $i ]['field_bgv_hero_slides_image'] = $zhyto; }
	}
	update_field( 'field_bgv_hero_slides', $sl, $id );
} );
