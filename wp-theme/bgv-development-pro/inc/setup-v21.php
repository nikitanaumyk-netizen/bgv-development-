<?php
/**
 * Одноразове оновлення даних для v2.1 (перший екран за ТЗ). Виконується один раз.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v21_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v21_done', 1, false );
	update_field( 'field_bgv_hero_subtitle', 'BGV Development', $id );
	update_field( 'field_bgv_hero_title', 'Створюємо простір для життя, бізнесу та майбутнього', $id );
	if ( ! get_field( 'hero_slides', $id, false ) ) {
		$want   = array( 'Equides', 'Боярка', 'BOSTON', 'OZON', 'ЖИТО, Житомир' );
		$slides = array();
		foreach ( $want as $w ) {
			foreach ( bgv_all_projects() as $p ) {
				if ( false !== mb_stripos( $p['title'], $w ) && ! empty( $p['image'] ) ) {
					$slides[] = array( 'field_bgv_hero_slides_image' => (int) $p['image'], 'field_bgv_hero_slides_caption' => $p['title'], 'field_bgv_hero_slides_link' => $p['_url'] );
					break;
				}
			}
		}
		if ( $slides ) { update_field( 'field_bgv_hero_slides', $slides, $id ); }
	}
} );
