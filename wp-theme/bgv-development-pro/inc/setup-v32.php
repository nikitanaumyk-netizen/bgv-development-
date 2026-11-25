<?php
/**
 * Одноразово (v2.12.1): слайд «ТЦ ЖИТО, Житомир» на першому екрані — фото без вивіски АТБ / ЖИТО.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v32_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v32_done', 1, false );
	global $wpdb;
	$img = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s ORDER BY post_id ASC LIMIT 1", '%' . $wpdb->esc_like( '/5.webp' ) ) );
	if ( ! $img ) { return; }
	$sl = get_field( 'hero_slides', $id, false );
	if ( ! is_array( $sl ) ) { return; }
	foreach ( $sl as $i => $r ) {
		$cap = (string) $r['field_bgv_hero_slides_meta'] . ' ' . $r['field_bgv_hero_slides_caption'];
		if ( false !== mb_stripos( $cap, 'Житомир' ) && false !== mb_stripos( $cap, 'ЖИТО' ) ) { $sl[ $i ]['field_bgv_hero_slides_image'] = $img; }
	}
	update_field( 'field_bgv_hero_slides', $sl, $id );
} );
