<?php
/**
 * Одноразово (v2.7): фото без надписів для «Інвесторам», «Досягнень», першого екрана й нагород.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v27_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v27_done', 1, false );
	// Пошук фото за назвою файлу (стабільно на будь-якому сайті).
	$by = function ( $needle ) {
		global $wpdb;
		$like = '%' . $wpdb->esc_like( $needle ) . '%';
		$id   = (int) $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s ORDER BY post_id ASC LIMIT 1", $like ) );
		return ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;
	};
	$aerial  = $by( 'Cam_05_v2_J' );                          // Equides з висоти
	$winter  = $by( 'VIEW-1-WINTER-FAR' );                    // BOSTON узимку
	$ozon    = $by( 'озон2' );                              // OZON
	$zhyto   = $by( '/5.webp' );                 // ТЦ ЖИТО Житомир, без вивіски
	$boyarka = $by( 'trts_zhyto_boiarka_bgv_development_6' );  // ТРЦ ЖИТО Боярка, без вивіски

	$bg = array_values( array_filter( array( $aerial, $winter, $ozon ) ) );
	if ( $bg ) { update_field( 'field_bgv_inv_images', $bg, $id ); }

	$rows = get_field( 'achievements', $id, false );
	if ( is_array( $rows ) && $zhyto ) {
		foreach ( $rows as $i => $r ) {
			if ( false !== mb_stripos( (string) $r['field_bgv_achievements_name'], 'ЖИТО' ) ) { $rows[ $i ]['field_bgv_achievements_image'] = $zhyto; }
		}
		update_field( 'field_bgv_achievements', $rows, $id );
	}

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
