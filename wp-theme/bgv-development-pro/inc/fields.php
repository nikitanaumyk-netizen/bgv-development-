<?php
/**
 * Поля сторінок (Secure Custom Fields / ACF) — структура як на офіційному сайті.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bgv_f( $name, $label, $type = 'text', $extra = array() ) {
	return array_merge( array( 'key' => 'field_bgv_' . $name, 'name' => $name, 'label' => $label, 'type' => $type ), $extra );
}
function bgv_sub( $parent, $name, $label, $type = 'text', $extra = array() ) {
	return array_merge( array( 'key' => 'field_bgv_' . $parent . '_' . $name, 'name' => $name, 'label' => $label, 'type' => $type ), $extra );
}
function bgv_tab( $key, $label ) {
	return array( 'key' => 'field_bgv_tab_' . $key, 'label' => $label, 'type' => 'tab', 'placement' => 'top' );
}
function bgv_img( $extra = array() ) {
	return array_merge( array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ), $extra );
}

function bgv_project_fields( $p ) {
	return array(
		bgv_sub( $p, 'title', 'Назва', 'text', array( 'required' => 1 ) ),
		bgv_sub( $p, 'slug', 'Адреса сторінки (латиницею, напр. ozon-house)', 'text', array( 'instructions' => 'Сторінка проєкту відкриється за адресою /proekt/адреса/. Без пробілів, тільки латиниця, цифри та дефіс.' ) ),
		bgv_sub( $p, 'image', 'Головне фото (картка)', 'image', bgv_img( array( 'required' => 1 ) ) ),
		bgv_sub( $p, 'card_info', 'Плашки на картці (дата, площа)', 'repeater', array(
			'layout' => 'table', 'button_label' => 'Додати плашку',
			'sub_fields' => array( bgv_sub( $p . '_card', 'text', 'Текст плашки' ) ),
		) ),
		bgv_sub( $p, 'gallery', 'Галерея', 'gallery', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
		bgv_sub( $p, 'description', 'Опис', 'wysiwyg', array( 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ) ),
		bgv_sub( $p, 'location', 'Адреса / локація' ),
		bgv_sub( $p, 'features', 'Характеристики', 'repeater', array(
			'layout' => 'table', 'button_label' => 'Додати рядок',
			'sub_fields' => array(
				bgv_sub( $p . '_feat', 'icon', 'Іконка', 'image', bgv_img( array( 'preview_size' => 'thumbnail' ) ) ),
				bgv_sub( $p . '_feat', 'text', 'Текст' ),
			),
		) ),
		bgv_sub( $p, 'website_link', 'Посилання «Перейти на сайт»', 'text', array( 'instructions' => 'Повна адреса з https://. Якщо порожньо — кнопки не буде.' ) ),
		bgv_sub( $p, 'agent_name', 'Менеджер: ім’я' ),
		bgv_sub( $p, 'agent_position', 'Менеджер: посада' ),
		bgv_sub( $p, 'agent_phone', 'Менеджер: телефон' ),
		bgv_sub( $p, 'agent_email', 'Менеджер: email' ),
		bgv_sub( $p, 'agent_image', 'Менеджер: фото', 'image', bgv_img( array( 'preview_size' => 'thumbnail' ) ) ),
	);
}

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	acf_add_local_field_group( array(
		'key'      => 'group_bgv_home',
		'title'    => 'Вміст головної сторінки',
		'location' => array( array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ) ),
		'position' => 'acf_after_title',
		'style'    => 'default',
		'hide_on_screen' => array( 'the_content' ),
		'fields'   => array(
			bgv_tab( 'hero', 'Перший екран' ),
			bgv_f( 'hero_description', 'Опис компанії', 'textarea', array( 'rows' => 4 ) ),
			bgv_f( 'hero_subtitle', 'Слоган (над заголовком)' ),
			bgv_f( 'hero_title', 'Заголовок' ),
			bgv_f( 'hero_image_1', 'Фото 1 (комп’ютер)', 'image', bgv_img() ),
			bgv_f( 'hero_image_2', 'Фото 2 (комп’ютер)', 'image', bgv_img() ),
			bgv_f( 'hero_mob_1', 'Фото 1 (телефон)', 'image', bgv_img() ),
			bgv_f( 'hero_mob_2', 'Фото 2 (телефон)', 'image', bgv_img() ),

			bgv_tab( 'about', 'Про нас' ),
			bgv_f( 'about_image', 'Фото', 'image', bgv_img() ),
			bgv_f( 'about_text', 'Текст', 'wysiwyg', array( 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0 ) ),
			bgv_f( 'about_footer', 'Підсумкова фраза' ),

			bgv_tab( 'stats', 'Статистика' ),
			bgv_f( 'stats', 'Цифри', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати показник',
				'sub_fields' => array(
					bgv_sub( 'stats', 'icon', 'Іконка', 'image', bgv_img( array( 'preview_size' => 'thumbnail' ) ) ),
					bgv_sub( 'stats', 'number', 'Число' ),
					bgv_sub( 'stats', 'label', 'Підпис' ),
				),
			) ),

			bgv_tab( 'projects', 'Проєкти' ),
			bgv_f( 'projects_title', 'Заголовок розділу' ),
			bgv_f( 'tab_real_estate', 'Назва вкладки 1' ),
			bgv_f( 'real_estate', 'Проєкти житлової нерухомості', 'repeater', array( 'layout' => 'block', 'collapsed' => 'field_bgv_real_estate_title', 'button_label' => 'Додати проєкт', 'sub_fields' => bgv_project_fields( 'real_estate' ) ) ),
			bgv_f( 'tab_commercial_estate', 'Назва вкладки 2' ),
			bgv_f( 'commercial_estate', 'Проєкти комерційної нерухомості', 'repeater', array( 'layout' => 'block', 'collapsed' => 'field_bgv_commercial_estate_title', 'button_label' => 'Додати проєкт', 'sub_fields' => bgv_project_fields( 'commercial_estate' ) ) ),
			bgv_f( 'tab_wellbeing_estate', 'Назва вкладки 3' ),
			bgv_f( 'wellbeing_estate', 'Спортивні, рекреаційні та благодійні проєкти', 'repeater', array( 'layout' => 'block', 'collapsed' => 'field_bgv_wellbeing_estate_title', 'button_label' => 'Додати проєкт', 'sub_fields' => bgv_project_fields( 'wellbeing_estate' ) ) ),

			bgv_tab( 'news', 'Новини' ),
			array( 'key' => 'field_bgv_news_msg', 'label' => '', 'name' => '', 'type' => 'message', 'message' => 'Новини додаються в меню ліворуч: <b>Новини → Додати новину</b>. На головній автоматично показуються 10 останніх.' ),
			bgv_f( 'news_title', 'Заголовок розділу' ),
			bgv_f( 'news_link', 'Текст посилання на всі новини' ),

			bgv_tab( 'social', 'Соціальна відповідальність' ),
			bgv_f( 'social_title', 'Заголовок' ),
			bgv_f( 'social_items', 'Пункти', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати пункт',
				'sub_fields' => array( bgv_sub( 'social_items', 'text', 'Текст пункту', 'textarea', array( 'rows' => 3 ) ) ),
			) ),
			bgv_f( 'social_image', 'Фото праворуч', 'image', bgv_img() ),

			bgv_tab( 'contacts', 'Контакти' ),
			bgv_f( 'contacts_title', 'Заголовок' ),
			bgv_f( 'company_title', 'Заголовок колонки контактів' ),
			bgv_f( 'company', 'Контакти компанії', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати контакт',
				'sub_fields' => array(
					bgv_sub( 'company', 'label', 'Підпис' ),
					bgv_sub( 'company', 'value', 'Значення' ),
					bgv_sub( 'company', 'link', 'Посилання (mailto:/tel:, необов’язково)' ),
				),
			) ),
			bgv_f( 'team_title', 'Заголовок колонки команди' ),
			bgv_f( 'team', 'Команда', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати людину',
				'sub_fields' => array(
					bgv_sub( 'team', 'name', 'Ім’я' ),
					bgv_sub( 'team', 'position', 'Посада' ),
					bgv_sub( 'team', 'email', 'Email' ),
				),
			) ),
			bgv_f( 'team_note', 'Примітка під командою' ),
			bgv_f( 'form_title', 'Форма: заголовок' ),
			bgv_f( 'form_text', 'Форма: текст', 'textarea', array( 'rows' => 3 ) ),
			bgv_f( 'form_email', 'Куди надсилати заявки (email)', 'email' ),

			bgv_tab( 'footer', 'Підвал' ),
			bgv_f( 'footer_email', 'Email' ),
			bgv_f( 'footer_phone', 'Телефон' ),
			bgv_f( 'footer_address', 'Адреса', 'textarea', array( 'rows' => 2 ) ),
			bgv_f( 'facebook', 'Facebook (посилання)' ),
			bgv_f( 'instagram', 'Instagram (посилання)' ),
			bgv_f( 'copyright', 'Копірайт' ),
		),
	) );
} );

/* ---------- Новина: прості поля замість блочного редактора ---------- */
add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	acf_add_local_field_group( array(
		'key'      => 'group_bgv_news',
		'title'    => 'Новина',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ) ) ),
		'position' => 'acf_after_title',
		'style'    => 'default',
		'hide_on_screen' => array( 'the_content', 'excerpt', 'discussion', 'comments', 'trackbacks', 'custom_fields', 'format', 'featured_image', 'tags', 'send-trackbacks', 'author', 'categories' ),
		'fields'   => bgv_news_fields(),
	) );
} );
function bgv_news_fields() {
	$f = array(
			bgv_tab( 'news_main', 'Основне' ),
			array( 'key' => 'field_bgv_news_hint', 'label' => '', 'name' => '', 'type' => 'message', 'message' => 'Заголовок новини — поле «Назва» вгорі. Дата публікації — у блоці «Опублікувати» праворуч (посилання «Редагувати» біля дати). Після змін натисніть <b>«Опублікувати»</b> або <b>«Оновити»</b> праворуч.' ),
			bgv_f( 'news_cover', 'Обкладинка (фото на картці та вгорі новини)', 'image', bgv_img( array( 'required' => 1 ) ) ),
			bgv_f( 'news_excerpt', 'Короткий опис (показується у списку новин)', 'textarea', array( 'rows' => 3, 'instructions' => 'Необов’язково. Якщо порожньо — візьметься початок тексту.' ) ),
			bgv_tab( 'news_text', 'Текст новини' ),
			bgv_f( 'news_body', 'Текст', 'wysiwyg', array( 'tabs' => 'visual', 'toolbar' => 'full', 'media_upload' => 1, 'instructions' => 'Щоб вставити фото в текст — кнопка «Додати медіафайл».' ) ),
			bgv_tab( 'news_photos', 'Фотогалерея' ),
			bgv_f( 'news_gallery', 'Фото під текстом (необов’язково)', 'gallery', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
	);
	if ( ! defined( 'WPSEO_VERSION' ) ) { // без Yoast — прості SEO-поля Jetpack
		$f[] = bgv_tab( 'news_seo', 'SEO' );
		$f[] = array( 'key' => 'field_bgv_news_seo_title', 'name' => 'jetpack_seo_html_title', 'label' => 'Заголовок для Google', 'type' => 'text' );
		$f[] = array( 'key' => 'field_bgv_news_seo_desc', 'name' => 'advanced_seo_description', 'label' => 'Опис для Google', 'type' => 'textarea', 'rows' => 3 );
	}
	return $f;
}

// Поля показують наявні дані новини (текст, обкладинку, опис).
add_filter( 'acf/load_value/key=field_bgv_news_body', function ( $v, $post_id ) {
	return ( '' === (string) $v && is_numeric( $post_id ) ) ? get_post_field( 'post_content', $post_id ) : $v;
}, 10, 2 );
add_filter( 'acf/load_value/key=field_bgv_news_cover', function ( $v, $post_id ) {
	return ( ! $v && is_numeric( $post_id ) ) ? get_post_thumbnail_id( $post_id ) : $v;
}, 10, 2 );
add_filter( 'acf/load_value/key=field_bgv_news_excerpt', function ( $v, $post_id ) {
	return ( '' === (string) $v && is_numeric( $post_id ) ) ? get_post_field( 'post_excerpt', $post_id ) : $v;
}, 10, 2 );

// Після збереження — переносимо поля у стандартні дані запису.
add_action( 'acf/save_post', function ( $post_id ) {
	if ( ! is_numeric( $post_id ) || 'post' !== get_post_type( $post_id ) || wp_is_post_revision( $post_id ) ) { return; }
	$cover = (int) get_field( 'news_cover', $post_id, false );
	if ( $cover ) { set_post_thumbnail( $post_id, $cover ); } else { delete_post_thumbnail( $post_id ); }
	$data = array( 'ID' => (int) $post_id );
	$body = get_field( 'news_body', $post_id, false );
	if ( null !== $body ) { $data['post_content'] = $body; }
	$data['post_excerpt'] = (string) get_field( 'news_excerpt', $post_id, false );
	$cat = get_term_by( 'slug', 'novyny', 'category' );
	if ( $cat ) { wp_set_post_categories( $post_id, array( $cat->term_id ) ); }
	remove_action( 'acf/save_post', __FUNCTION__ );
	wp_update_post( wp_slash( $data ) );
}, 20 );
