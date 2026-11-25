<?php
/**
 * Одноразово (v2.6): фото донорства у «Соціальній відповідальності», фото фону «Інвесторам».
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v26_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v26_done', 1, false );

	// Фото з новин про донорство крові.
	$imgs = array();
	foreach ( get_posts( array( 'post_type' => 'post', 'numberposts' => 80, 'post_status' => 'publish' ) ) as $p ) {
		$t = mb_strtolower( $p->post_title );
		if ( false === mb_strpos( $t, 'донор' ) && false === mb_strpos( $t, 'кров' ) ) { continue; }
		$thumb = (int) get_post_thumbnail_id( $p->ID );
		if ( $thumb ) { $imgs[] = $thumb; }
		$gal = get_post_meta( $p->ID, 'news_gallery', true );
		if ( is_array( $gal ) ) { $imgs = array_merge( $imgs, array_map( 'intval', $gal ) ); }
		if ( preg_match_all( '/wp-image-(\d+)/', $p->post_content, $m ) ) { $imgs = array_merge( $imgs, array_map( 'intval', $m[1] ) ); }
		foreach ( get_attached_media( 'image', $p->ID ) as $att ) { $imgs[] = (int) $att->ID; }
	}
	$imgs = array_values( array_unique( array_filter( $imgs, function ( $i ) { return $i && wp_attachment_is_image( $i ); } ) ) );
	if ( $imgs ) {
		update_field( 'field_bgv_social_image', $imgs[0], $id );
		update_field( 'field_bgv_social_gallery', array_slice( $imgs, 1, 7 ), $id );
	}

	// Фон «Інвесторам» — рендери проєктів.
	$bg = array();
	foreach ( array( 'Circle', 'OZON', 'Боярка' ) as $w ) {
		foreach ( bgv_all_projects() as $p ) {
			if ( false !== mb_stripos( $p['title'], $w ) && ! empty( $p['image'] ) ) { $bg[] = (int) $p['image']; break; }
		}
	}
	if ( $bg && ! get_field( 'inv_images', $id, false ) ) { update_field( 'field_bgv_inv_images', $bg, $id ); }
} );
