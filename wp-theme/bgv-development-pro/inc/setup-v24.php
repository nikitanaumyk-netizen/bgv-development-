<?php
/**
 * Одноразово: фото для «Досягнень» і «Нагород» з фото наших проєктів.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v24_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v24_done', 1, false );
	$find = function ( $w ) {
		foreach ( bgv_all_projects() as $p ) { if ( false !== mb_stripos( $p['title'], $w ) && ! empty( $p['image'] ) ) { return (int) $p['image']; } }
		return 0;
	};
	$map = array( 'BOSTON' => 'BOSTON', 'ЖИТО, Житомир' => 'ЖИТО, Житомир', 'школа' => 'школа', 'Equides' => 'Equides', 'OZON' => 'OZON', 'Боярка' => 'Боярка', 'Circle' => 'Circle', 'ЦУМ' => 'ЦУМ' );
	$rows = get_field( 'achievements', $id, false );
	if ( is_array( $rows ) ) {
		foreach ( $rows as $i => $r ) {
			if ( ! empty( $r['field_bgv_achievements_image'] ) ) { continue; }
			foreach ( $map as $needle => $proj ) {
				if ( false !== mb_stripos( (string) $r['field_bgv_achievements_name'], $needle ) ) { $rows[ $i ]['field_bgv_achievements_image'] = $find( $proj ); break; }
			}
		}
		update_field( 'field_bgv_achievements', $rows, $id );
	}
	$aw = get_field( 'awards', $id, false );
	if ( is_array( $aw ) ) {
		foreach ( $aw as $i => $r ) {
			if ( ! empty( $r['field_bgv_awards_photo'] ) ) { continue; }
			$txt = $r['field_bgv_awards_title'] . ' ' . $r['field_bgv_awards_note'];
			if ( false !== mb_stripos( $txt, 'Боярка' ) ) { $aw[ $i ]['field_bgv_awards_photo'] = $find( 'Боярка' ); }
			elseif ( false !== mb_stripos( $txt, 'Equides' ) ) { $aw[ $i ]['field_bgv_awards_photo'] = $find( 'Equides' ); }
		}
		update_field( 'field_bgv_awards', $aw, $id );
	}
} );
