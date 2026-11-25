<?php
/**
 * Одноразово: фото для карток «Ключові переваги» з фото наших проєктів.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v23_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v23_done', 1, false );
	$rows = get_field( 'advantages', $id, false );
	if ( ! is_array( $rows ) ) { return; }
	$find = function ( $w ) {
		foreach ( bgv_all_projects() as $p ) { if ( false !== mb_stripos( $p['title'], $w ) && ! empty( $p['image'] ) ) { return (int) $p['image']; } }
		return 0;
	};
	$pick = array( 'BOSTON', 'Equides', 'OZON', 'школа' );
	foreach ( $rows as $i => $r ) {
		if ( empty( $r['field_bgv_advantages_image'] ) && isset( $pick[ $i ] ) ) { $rows[ $i ]['field_bgv_advantages_image'] = $find( $pick[ $i ] ); }
	}
	update_field( 'field_bgv_advantages', $rows, $id );
} );
