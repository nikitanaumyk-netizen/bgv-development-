<?php
/**
 * Одноразово (v2.5): слайди першого екрана з мітками, нагороди у форматі «плашки», заголовки.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'wp_loaded', function () {
	if ( get_option( 'bgv_v25_done' ) || ! function_exists( 'update_field' ) ) { return; }
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	update_option( 'bgv_v25_done', 1, false );
	$proj = function ( $w ) {
		foreach ( bgv_all_projects() as $p ) { if ( false !== mb_stripos( $p['title'], $w ) ) { return $p; } }
		return null;
	};
	update_field( 'field_bgv_hero_subtitle', 'Build Your Future', $id );
	// Слайди: об’єкт, статус і місто, вкладка.
	$defs = array(
		array( 'Equides', 'Котеджне містечко Equides Villas', 'Будується, Лісники', 'Вілли' ),
		array( 'BOSTON', 'ЖК BOSTON Creative House', 'Будується, Київ', 'Житловий комплекс' ),
		array( 'Боярка', 'ТРЦ «ЖИТО»', 'Будується, Боярка', 'ТРЦ' ),
		array( 'ЖИТО, Житомир', 'ТЦ «ЖИТО»', 'Здано, Житомир', 'Мережа ТЦ' ),
		array( 'OZON', 'ЖК OZON House', 'Будується, Київ', 'Бізнес-клас' ),
	);
	$slides = array();
	foreach ( $defs as $d ) {
		$p = $proj( $d[0] );
		if ( ! $p || empty( $p['image'] ) ) { continue; }
		$slides[] = array( 'field_bgv_hero_slides_image' => (int) $p['image'], 'field_bgv_hero_slides_caption' => $d[1], 'field_bgv_hero_slides_meta' => $d[2], 'field_bgv_hero_slides_tab' => $d[3], 'field_bgv_hero_slides_link' => $p['_url'] );
	}
	if ( $slides ) { update_field( 'field_bgv_hero_slides', $slides, $id ); }
	// Нагороди.
	$news = function ( $words ) {
		foreach ( get_posts( array( 'post_type' => 'post', 'numberposts' => 60, 'post_status' => 'publish' ) ) as $p ) {
			$ok = true;
			foreach ( $words as $w ) { if ( false === mb_stripos( $p->post_title, $w ) ) { $ok = false; break; } }
			if ( $ok ) { return get_permalink( $p ); }
		}
		return '';
	};
	$img = function ( $w ) use ( $proj ) { $p = $proj( $w ); return $p ? (int) $p['image'] : 0; };
	$aw  = array(
		array( 'short' => 'EPA', 'year' => '2026–2027', 'tag' => 'ТРЦ «ЖИТО», Боярка', 'kind' => 'Нагорода', 'title' => 'European Property Awards 2026–2027', 'note' => 'ТРЦ «ЖИТО», Боярка', 'link' => $news( array( 'European Property Awards' ) ), 'photo' => $img( 'Боярка' ) ),
	);
	$forbes = $news( array( 'Forbes' ) );
	if ( $forbes ) { $aw[] = array( 'short' => 'Forbes', 'year' => '', 'tag' => 'BGV Development', 'kind' => 'Медіа', 'title' => 'Forbes', 'note' => 'Серед бізнесів, які формують нову економіку України', 'link' => $forbes, 'photo' => $img( 'BOSTON' ) ); }
	$kbu = $news( array( 'КБУ' ) );
	$aw[] = array( 'short' => 'КБУ', 'year' => '2026', 'tag' => 'BGV Development', 'kind' => 'Членство', 'title' => 'Член КБУ', 'note' => 'Конфедерація будівельників України, 2026', 'link' => $kbu, 'photo' => $img( 'Equides' ) );
	$rows = array();
	foreach ( $aw as $a ) {
		$r = array( 'field_bgv_awards_image' => '' );
		foreach ( $a as $k => $v ) { $r[ 'field_bgv_awards_' . $k ] = $v; }
		$rows[] = $r;
	}
	update_field( 'field_bgv_awards', $rows, $id );
	update_field( 'field_bgv_awards_heading', 'BGV Development — член Конфедерації будівельників України', $id );
	update_field( 'field_bgv_awards_text', '', $id );
} );
