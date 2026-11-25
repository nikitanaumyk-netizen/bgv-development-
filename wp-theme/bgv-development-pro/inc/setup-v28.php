<?php
/**
 * Одноразово (v2.7.2): у «Досягненнях» для ТЦ ЖИТО Житомир — знову головне фото проєкту.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v28_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v28_done', 1, false );
	$img = 0;
	foreach ( bgv_all_projects() as $p ) {
		if ( false !== mb_stripos( $p['title'], 'ЖИТО, Житомир' ) && ! empty( $p['image'] ) ) { $img = (int) $p['image']; break; }
	}
	$rows = get_field( 'achievements', $id, false );
	if ( ! $img || ! is_array( $rows ) ) { return; }
	foreach ( $rows as $i => $r ) {
		if ( false !== mb_stripos( (string) $r['field_bgv_achievements_name'], 'ЖИТО' ) ) { $rows[ $i ]['field_bgv_achievements_image'] = $img; }
	}
	update_field( 'field_bgv_achievements', $rows, $id );
} );
