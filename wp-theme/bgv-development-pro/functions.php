<?php
/**
 * BGV Development Pro — функції теми.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BGV_VER', '1.0.1' );

require get_template_directory() . '/inc/fields.php';
if ( file_exists( get_template_directory() . '/inc/migrate.php' ) ) {
	require get_template_directory() . '/inc/migrate.php';
}

/* ---------- Базові налаштування ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bgv', get_template_directory_uri() . '/assets/bgv.css', array(), BGV_VER );
} );

/* ---------- Допоміжні функції ---------- */
function bgv_front_id() {
	return (int) get_option( 'page_on_front' );
}
function bgv_opt( $name ) {
	if ( ! function_exists( 'get_field' ) ) { return ''; }
	$v = get_field( $name, bgv_front_id() );
	return $v ? $v : '';
}
function bgv_img_url( $id, $size = 'full' ) {
	if ( ! $id ) { return ''; }
	$u = wp_get_attachment_image_url( (int) $id, $size );
	return $u ? $u : '';
}
function bgv_project_url( $slug ) {
	return home_url( '/proekt/' . rawurlencode( $slug ) . '/' );
}
function bgv_project_groups() {
	return array( 'real_estate', 'commercial_estate', 'wellbeing_estate' );
}
function bgv_project_slug( $row, $group, $i ) {
	$s = isset( $row['slug'] ) ? sanitize_title( $row['slug'] ) : '';
	return $s ? $s : $group . '-' . ( $i + 1 );
}
function bgv_find_project( $slug ) {
	foreach ( bgv_project_groups() as $g ) {
		$rows = bgv_opt( $g );
		if ( ! is_array( $rows ) ) { continue; }
		foreach ( $rows as $i => $row ) {
			if ( bgv_project_slug( $row, $g, $i ) === $slug ) {
				$row['_group'] = $g;
				return $row;
			}
		}
	}
	return null;
}

/* ---------- Сторінки проєктів: /proekt/адреса/ ---------- */
add_action( 'init', function () {
	add_rewrite_rule( '^proekt/([^/]+)/?$', 'index.php?bgv_project=$matches[1]', 'top' );
} );
add_filter( 'query_vars', function ( $v ) { $v[] = 'bgv_project'; return $v; } );
add_action( 'after_switch_theme', function () {
	add_rewrite_rule( '^proekt/([^/]+)/?$', 'index.php?bgv_project=$matches[1]', 'top' );
	flush_rewrite_rules();
} );
add_action( 'template_redirect', function () {
	$slug = get_query_var( 'bgv_project' );
	if ( ! $slug ) { return; }
	$project = bgv_find_project( sanitize_title( $slug ) );
	if ( ! $project ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		return;
	}
	$GLOBALS['bgv_project'] = $project;
	status_header( 200 );
	add_filter( 'pre_get_document_title', function () use ( $project ) {
		return $project['title'] . ' – ' . get_bloginfo( 'name' );
	} );
	include get_template_directory() . '/templates/project.php';
	exit;
} );

/* ---------- Форма заявки ---------- */
function bgv_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'sent', strtok( $back, '#' ) );
	if ( ! isset( $_POST['bgv_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bgv_nonce'] ) ), 'bgv_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) . '#contact-us' );
		exit;
	}
	if ( ! empty( $_POST['website'] ) ) { // пастка для ботів
		wp_safe_redirect( add_query_arg( 'sent', 'ok', $back ) . '#contact-us' );
		exit;
	}
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	if ( '' === $name || ( '' === $phone && '' === $email ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) . '#contact-us' );
		exit;
	}
	$to = bgv_opt( 'form_email' );
	if ( ! is_email( $to ) ) { $to = get_option( 'admin_email' ); }
	$body  = "Нова заявка з сайту BGV Development\n\n";
	$body .= "ПІБ: {$name}\nТелефон: {$phone}\nEmail: {$email}\n\nПовідомлення:\n{$message}\n\nСторінка: {$back}\n";
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) { $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>'; }
	$ok = wp_mail( $to, 'Заявка з сайту BGV Development — ' . $name, $body, $headers );
	wp_safe_redirect( add_query_arg( 'sent', $ok ? 'ok' : 'error', $back ) . '#contact-us' );
	exit;
}
add_action( 'admin_post_nopriv_bgv_contact', 'bgv_handle_contact' );
add_action( 'admin_post_bgv_contact', 'bgv_handle_contact' );

/* ---------- Зручність адмінки ---------- */
// Головна сторінка редагується тільки полями (без блочного редактора).
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	return ( $post && (int) $post->ID === bgv_front_id() ) ? false : $use;
}, 10, 2 );
add_action( 'add_meta_boxes_page', function ( $post ) {
	if ( (int) $post->ID === bgv_front_id() ) { remove_post_type_support( 'page', 'editor' ); }
} );
add_action( 'admin_init', function () {
	if ( isset( $_GET['post'] ) && (int) $_GET['post'] === bgv_front_id() ) {
		remove_post_type_support( 'page', 'editor' );
	}
} );

// Пункт меню «Головна сторінка» для швидкого доступу.
add_action( 'admin_menu', function () {
	$id = bgv_front_id();
	if ( ! $id ) { return; }
	add_menu_page( 'Головна сторінка', 'Головна сторінка', 'edit_pages', 'post.php?post=' . $id . '&action=edit', '', 'dashicons-admin-home', 3 );
} );

// «Записи» → «Новини».
add_action( 'init', function () {
	global $wp_post_types;
	if ( empty( $wp_post_types['post'] ) ) { return; }
	$l = $wp_post_types['post']->labels;
	$l->name = 'Новини'; $l->singular_name = 'Новина'; $l->menu_name = 'Новини'; $l->name_admin_bar = 'Новина';
	$l->add_new = 'Додати новину'; $l->add_new_item = 'Додати новину'; $l->all_items = 'Усі новини';
	$l->edit_item = 'Редагувати новину'; $l->new_item = 'Нова новина'; $l->view_item = 'Переглянути новину';
	$l->search_items = 'Шукати новини'; $l->not_found = 'Новин не знайдено';
} );

// Дозволити SVG-іконки адміністраторам.
add_filter( 'upload_mimes', function ( $m ) {
	if ( current_user_can( 'manage_options' ) || defined( 'BGV_MIGRATING' ) ) { $m['svg'] = 'image/svg+xml'; }
	return $m;
} );
add_filter( 'wp_check_filetype_and_ext', function ( $data, $file, $filename ) {
	if ( ( current_user_can( 'manage_options' ) || defined( 'BGV_MIGRATING' ) ) && preg_match( '/\.svg$/i', $filename ) ) {
		$data['ext'] = 'svg'; $data['type'] = 'image/svg+xml'; $data['proper_filename'] = false;
	}
	return $data;
}, 10, 3 );

// Повідомлення, якщо плагін полів вимкнено.
add_action( 'admin_notices', function () {
	if ( ! function_exists( 'get_field' ) && current_user_can( 'activate_plugins' ) ) {
		echo '<div class="notice notice-error"><p><b>BGV:</b> увімкніть плагін <b>Secure Custom Fields</b> — без нього поля сайту не працюють.</p></div>';
	}
} );

/* ---------- Шаблонні частини ---------- */
function bgv_chevron() {
	return '<svg fill="none" height="24" viewBox="0 0 12 24" width="12" xmlns="http://www.w3.org/2000/svg"><path d="M2.45199 6.57999L3.51299 5.51999L9.29199 11.297C9.38514 11.3896 9.45907 11.4996 9.50952 11.6209C9.55997 11.7421 9.58594 11.8722 9.58594 12.0035C9.58594 12.1348 9.55997 12.2648 9.50952 12.3861C9.45907 12.5073 9.38514 12.6174 9.29199 12.71L3.51299 18.49L2.45299 17.43L7.87699 12.005L2.45199 6.57999Z" fill="#4057CB"></path></svg>';
}

function bgv_menu( $id ) {
	$home = home_url( '/' );
	$items = array(
		array( $home . '#our-projects', 'Наші проєкти' ),
		array( $home . '#news', 'Акції та новини' ),
		array( $home . '#contact-us', 'Контакти' ),
	);
	echo '<ul id="' . esc_attr( $id ) . '" class="menu">';
	foreach ( $items as $it ) {
		echo '<li class="menu-item"><a href="' . esc_url( $it[0] ) . '">' . esc_html( $it[1] ) . '</a></li>';
	}
	echo '</ul>';
}
