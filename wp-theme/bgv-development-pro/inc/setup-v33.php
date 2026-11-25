<?php
/**
 * Одноразово (v2.13): фон розділу «Нагороди» — нічний рендер ТРЦ «ЖИТО» Боярка.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v33_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v33_done', 1, false );
	global $wpdb;
	$img = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s ORDER BY post_id ASC LIMIT 1", '%' . $wpdb->esc_like( 'trts_zhyto_boiarka_bgv_development_5' ) . '%' ) );
	if ( $img && ! get_field( 'awards_bg', $id, false ) ) { update_field( 'field_bgv_awards_bg', $img, $id ); }
} );
