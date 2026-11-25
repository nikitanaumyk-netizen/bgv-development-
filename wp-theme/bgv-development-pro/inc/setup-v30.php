<?php
/**
 * Одноразово (v2.7.5): фото з Поліського напівмарафону в слайдер «Соціальна відповідальність» (до донорських).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v30_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v30_done', 1, false );
	$add = array();
	// З новин про напівмарафон.
	foreach ( get_posts( array( 'post_type' => 'post', 'numberposts' => 80, 'post_status' => 'publish' ) ) as $p ) {
		if ( false === mb_stripos( $p->post_title, 'марафон' ) ) { continue; }
		$t = (int) get_post_thumbnail_id( $p->ID );
		if ( $t ) { $add[] = $t; }
		$gal = get_post_meta( $p->ID, 'news_gallery', true );
		if ( is_array( $gal ) ) { $add = array_merge( $add, array_map( 'intval', $gal ) ); }
		if ( preg_match_all( '/wp-image-(\d+)/', $p->post_content, $m ) ) { $add = array_merge( $add, array_map( 'intval', $m[1] ) ); }
	}
	// З картки проєкту «Поліський напівмарафон».
	foreach ( bgv_all_projects() as $p ) {
		if ( false === mb_stripos( $p['title'], 'марафон' ) ) { continue; }
		$add[] = (int) $p['image'];
		$add   = array_merge( $add, array_map( 'intval', (array) ( $p['gallery'] ?? array() ) ) );
	}
	$main = (int) get_field( 'social_image', $id, false );
	$cur  = array_map( 'intval', (array) get_field( 'social_gallery', $id, false ) );
	$all  = array_values( array_unique( array_filter( array_merge( $cur, $add ), function ( $i ) use ( $main ) { return $i && $i !== $main && wp_attachment_is_image( $i ); } ) ) );
	update_field( 'field_bgv_social_gallery', array_slice( $all, 0, 12 ), $id );
} );
