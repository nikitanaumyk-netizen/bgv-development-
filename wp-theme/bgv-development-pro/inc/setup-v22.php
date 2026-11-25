<?php
/**
 * Одноразове оновлення даних v2.2: 60+ спеціалістів, одна рубрика новин, фото для слайдера.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v22_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v22_done', 1, false );
	// 60+ спеціалістів.
	$stats = get_field( 'stats', $id, false );
	if ( is_array( $stats ) ) {
		foreach ( $stats as $i => $r ) {
			if ( false !== mb_stripos( (string) ( $r['field_bgv_stats_label'] ?? '' ), 'спеціаліст' ) ) { $stats[ $i ]['field_bgv_stats_number'] = '60+'; }
		}
		update_field( 'field_bgv_stats', $stats, $id );
	}
	// Одна рубрика «Новини».
	$nov = get_term_by( 'slug', 'novyny', 'category' );
	if ( $nov ) {
		foreach ( array( 'aktsii', 'zmi-pro-nas', 'khid-budivnytstva' ) as $slug ) {
			$t = get_term_by( 'slug', $slug, 'category' );
			if ( ! $t ) { continue; }
			foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids', 'category' => $t->term_id ) ) as $pid ) {
				wp_set_post_categories( $pid, array( $nov->term_id ) );
			}
			wp_delete_term( $t->term_id, 'category' );
		}
	}
	// Слайдер соцвідповідальності: фото соціальних проєктів.
	if ( ! get_field( 'social_gallery', $id, false ) ) {
		$main = (int) get_field( 'social_image', $id, false );
		$ids  = array();
		foreach ( (array) get_field( 'wellbeing_estate', $id ) as $p ) {
			$ids = array_merge( $ids, array( (int) ( $p['image'] ?? 0 ) ), array_map( 'intval', (array) ( $p['gallery'] ?? array() ) ) );
		}
		$ids = array_values( array_diff( array_unique( array_filter( $ids ) ), array( $main ) ) );
		if ( $ids ) { update_field( 'field_bgv_social_gallery', array_slice( $ids, 0, 6 ), $id ); }
	}
} );
