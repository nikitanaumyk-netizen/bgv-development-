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
		bgv_sub( $p, 'status', 'Статус (бейдж на картці та карті)', 'select', array( 'choices' => array( '' => '— не показувати —', 'building' => 'Будується', 'done' => 'Здано / реалізовано', 'concept' => 'Проєктування / концепція' ), 'default_value' => '', 'allow_null' => 0, 'ui' => 0 ) ),
		bgv_sub( $p, 'lat', 'Карта: широта (напр. 50.4501)', 'text', array( 'instructions' => 'Щоб отримати координати: Google Maps → правий клік по точці → натиснути на цифри (скопіюються). Перше число — широта, друге — довгота. Якщо порожньо — проєкт не показується на карті.', 'wrapper' => array( 'width' => 50 ) ) ),
		bgv_sub( $p, 'lng', 'Карта: довгота (напр. 30.5234)', 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),
		bgv_sub( $p, 'presentation', 'Презентація об’єкта (PDF)', 'file', array( 'return_format' => 'id', 'library' => 'all', 'mime_types' => 'pdf', 'instructions' => 'Необов’язково. Відвідувач отримає файл після того, як залишить телефон або email (заявка прийде вам).' ) ),
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
		'position' => 'normal',
		'style'    => 'default',
		'hide_on_screen' => array( 'the_content' ),
		'fields'   => array(
			bgv_tab( 'header', 'Шапка та заявки' ),
			bgv_f( 'header_phone', 'Телефон у шапці', 'text', array( 'instructions' => 'Напр. +38 (044) 277 14 14. Якщо порожньо — береться телефон з «Підвалу».' ) ),
			bgv_f( 'presentation_pdf', 'Презентація компанії (PDF)', 'file', array( 'return_format' => 'id', 'library' => 'all', 'mime_types' => 'pdf', 'instructions' => 'Пункт «Завантажити презентацію» у меню «Про нас». Файл видається після того, як відвідувач залишить телефон/email. Якщо файл не завантажено — пункт меню прихований.' ) ),
			array( 'key' => 'field_bgv_leads_msg', 'label' => '', 'name' => '', 'type' => 'message', 'message' => 'Усі заявки з сайту (форма, «Підібрати об’єкт», «Стати партнером», презентації, плаваюча кнопка) зберігаються в меню ліворуч <b>«Заявки»</b> і надсилаються на email з вкладки «Контакти».' ),

			bgv_tab( 'hero', 'Перший екран' ),
			bgv_f( 'hero_description', 'Опис компанії', 'textarea', array( 'rows' => 4 ) ),
			bgv_f( 'hero_subtitle', 'Слоган (над заголовком)' ),
			bgv_f( 'hero_title', 'Заголовок' ),
			bgv_f( 'hero_image_1', 'Фото 1 (комп’ютер)', 'image', bgv_img() ),
			bgv_f( 'hero_image_2', 'Фото 2 (комп’ютер)', 'image', bgv_img() ),
			bgv_f( 'hero_mob_1', 'Фото 1 (телефон)', 'image', bgv_img() ),
			bgv_f( 'hero_mob_2', 'Фото 2 (телефон)', 'image', bgv_img() ),
			bgv_f( 'hero_lead', 'Підзаголовок під описом', 'text' ),
			bgv_f( 'hero_video', 'Відео замість Фото 1 (MP4, необов’язково)', 'file', array( 'return_format' => 'id', 'library' => 'all', 'mime_types' => 'mp4,webm', 'instructions' => 'Коротке відео/рендер без звуку, до 10–15 МБ. Показується в тій самій фігурі, що й Фото 1; Фото 1 стає заставкою, поки відео вантажиться.' ) ),

			bgv_tab( 'map', 'Карта об’єктів' ),
			bgv_f( 'map_title', 'Заголовок розділу' ),
			array( 'key' => 'field_bgv_map_msg', 'label' => '', 'name' => '', 'type' => 'message', 'message' => 'Піни на карті беруться з проєктів (вкладка «Проєкти»): поля <b>«Карта: широта / довгота»</b> і <b>«Статус»</b>. Проєкт без координат на карті не показується.' ),

			bgv_tab( 'adv', 'Переваги' ),
			bgv_f( 'adv_title', 'Заголовок розділу' ),
			bgv_f( 'advantages', 'Ключові переваги', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати перевагу',
				'sub_fields' => array(
					bgv_sub( 'advantages', 'title', 'Назва' ),
					bgv_sub( 'advantages', 'text', 'Пояснення' ),
				),
			) ),

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

			bgv_tab( 'ach', 'Досягнення' ),
			bgv_f( 'ach_title', 'Заголовок розділу' ),
			bgv_f( 'achievements', 'Готовність об’єктів (кругові діаграми)', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати об’єкт', 'instructions' => 'Якщо список порожній — розділ на сайті не показується.',
				'sub_fields' => array(
					bgv_sub( 'achievements', 'name', 'Об’єкт' ),
					bgv_sub( 'achievements', 'percent', 'Готовність, %', 'number', array( 'min' => 0, 'max' => 100 ) ),
					bgv_sub( 'achievements', 'label', 'Підпис під %', 'text', array( 'placeholder' => 'збудовано' ) ),
				),
			) ),

			bgv_tab( 'awards', 'Нагороди' ),
			bgv_f( 'awards_heading', 'Заголовок розділу' ),
			bgv_f( 'awards_text', 'Вступний текст', 'textarea', array( 'rows' => 2 ) ),
			bgv_f( 'awards', 'Нагороди та членство', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати нагороду', 'instructions' => 'Логотип необов’язковий. Якщо список порожній — розділ не показується.',
				'sub_fields' => array(
					bgv_sub( 'awards', 'image', 'Логотип', 'image', bgv_img( array( 'preview_size' => 'thumbnail' ) ) ),
					bgv_sub( 'awards', 'title', 'Назва нагороди' ),
					bgv_sub( 'awards', 'note', 'За що / рік' ),
					bgv_sub( 'awards', 'link', 'Посилання (новина), необов’язково' ),
				),
			) ),

			bgv_tab( 'inv', 'Інвесторам' ),
			bgv_f( 'inv_title', 'Заголовок' ),
			bgv_f( 'inv_text', 'Опис', 'textarea', array( 'rows' => 4 ) ),
			bgv_f( 'inv_items', 'Можливості співпраці', 'repeater', array(
				'layout' => 'table', 'button_label' => 'Додати пункт',
				'sub_fields' => array(
					bgv_sub( 'inv_items', 'title', 'Назва' ),
					bgv_sub( 'inv_items', 'text', 'Опис', 'textarea', array( 'rows' => 2 ) ),
				),
			) ),
			bgv_f( 'inv_button', 'Текст кнопки' ),

			bgv_tab( 'news', 'Новини' ),
			array( 'key' => 'field_bgv_news_msg', 'label' => '', 'name' => '', 'type' => 'message', 'message' => 'Новини додаються в меню ліворуч: <b>Новини → Додати новину</b>. На головній автоматично показуються 10 останніх. Рубрика (Новини / Акції / ЗМІ про нас / Хід будівництва) обирається в самій новині.' ),
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
			bgv_f( 'linkedin', 'LinkedIn (посилання)' ),
			bgv_f( 'youtube', 'YouTube (посилання)' ),
			bgv_f( 'telegram', 'Telegram (посилання)' ),
			bgv_f( 'tiktok', 'TikTok (посилання)' ),
			bgv_f( 'copyright', 'Копірайт' ),
			bgv_f( 'privacy_page', 'Сторінка «Політика конфіденційності»', 'post_object', array( 'post_type' => array( 'page' ), 'return_format' => 'id', 'allow_null' => 1, 'ui' => 1, 'instructions' => 'Посилання в підвалі зʼявиться, коли сторінку буде опубліковано.' ) ),
			bgv_f( 'terms_page', 'Сторінка «Умови користування»', 'post_object', array( 'post_type' => array( 'page' ), 'return_format' => 'id', 'allow_null' => 1, 'ui' => 1 ) ),
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
		'position' => 'normal',
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
			bgv_f( 'news_category', 'Рубрика', 'taxonomy', array( 'taxonomy' => 'category', 'field_type' => 'select', 'return_format' => 'id', 'add_term' => 0, 'save_terms' => 0, 'load_terms' => 1, 'allow_null' => 0, 'instructions' => 'Новини, Акції, ЗМІ про нас або Хід будівництва — по ній працюють фільтри на сайті.' ) ),
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
	$cid = (int) get_field( 'news_category', $post_id, false );
	$cat = $cid ? get_term( $cid, 'category' ) : null;
	if ( ! $cat || is_wp_error( $cat ) || 'uncategorized' === $cat->slug ) { $cat = get_term_by( 'slug', 'novyny', 'category' ); }
	if ( $cat ) { wp_set_post_categories( $post_id, array( $cat->term_id ) ); }
	remove_action( 'acf/save_post', __FUNCTION__ );
	wp_update_post( wp_slash( $data ) );
}, 20 );
