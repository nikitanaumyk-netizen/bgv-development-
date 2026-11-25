<?php
/**
 * BGV Development Pro — функції теми.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'BGV_VER', '2.0.2' );

require get_template_directory() . '/inc/fields.php';
require get_template_directory() . '/inc/v2.php';
if ( file_exists( get_template_directory() . '/inc/bgv-api.php' ) ) { require get_template_directory() . '/inc/bgv-api.php'; }
if ( file_exists( get_template_directory() . '/inc/migrate-v2.php' ) ) { require get_template_directory() . '/inc/migrate-v2.php'; }
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
	wp_enqueue_style( 'bgv2', get_template_directory_uri() . '/assets/bgv2.css', array( 'bgv' ), BGV_VER );
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
	$ptitle = $project['title'] . ' – ' . get_bloginfo( 'name' );
	$pdesc  = wp_trim_words( wp_strip_all_tags( (string) ( $project['description'] ?? '' ) ), 26, '…' );
	$purl   = bgv_project_url( sanitize_title( $slug ) );
	add_filter( 'pre_get_document_title', function () use ( $ptitle ) { return $ptitle; }, 99 );
	foreach ( array( 'wpseo_title', 'wpseo_opengraph_title' ) as $hook ) { add_filter( $hook, function () use ( $ptitle ) { return $ptitle; }, 99 ); }
	foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc' ) as $hook ) { add_filter( $hook, function () use ( $pdesc ) { return $pdesc; }, 99 ); }
	foreach ( array( 'wpseo_canonical', 'wpseo_opengraph_url' ) as $hook ) { add_filter( $hook, function () use ( $purl ) { return $purl; }, 99 ); }
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
// Старі форми (з кешованих сторінок) теж ідуть у загальний обробник заявок.
add_action( 'admin_post_nopriv_bgv_contact', 'bgv_handle_lead' );
add_action( 'admin_post_bgv_contact', 'bgv_handle_lead' );

/* ---------- Зручність адмінки ---------- */
// Головна сторінка редагується тільки полями (без блочного редактора).
function bgv_is_special_page( $id ) {
	$id = (int) $id;
	return $id && ( $id === bgv_front_id() || $id === (int) get_option( 'page_for_posts' ) );
}
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	if ( $post && 'post' === $post->post_type ) { return false; }
	return ( $post && bgv_is_special_page( $post->ID ) ) ? false : $use;
}, 10, 2 );
add_action( 'add_meta_boxes_page', function ( $post ) {
	if ( bgv_is_special_page( $post->ID ) ) { remove_post_type_support( 'page', 'editor' ); }
} );
add_action( 'admin_init', function () {
	if ( isset( $_GET['post'] ) && bgv_is_special_page( (int) $_GET['post'] ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
} );

// Сторінка «Акції та новини»: замість порожнього редактора — список усіх новин.
add_action( 'add_meta_boxes_page', function ( $post ) {
	if ( (int) $post->ID !== (int) get_option( 'page_for_posts' ) ) { return; }
	add_meta_box( 'bgv_news_list', 'Новини на сторінці «Акції та новини»', 'bgv_render_news_box', 'page', 'normal', 'high' );
} );
function bgv_render_news_box() {
	$q = new WP_Query( array( 'post_type' => 'post', 'post_status' => array( 'publish', 'draft', 'pending', 'future', 'private' ), 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );
	$statuses = array( 'publish' => 'Опубліковано', 'draft' => 'Чернетка', 'pending' => 'На розгляді', 'future' => 'Заплановано', 'private' => 'Приватна' );
	?>
	<div class="bgv-news-box">
		<p class="bgv-news-intro">Ця сторінка показує всі новини автоматично — найновіші зверху. Щоб змінити список, додайте або відредагуйте новину нижче. Заголовок сторінки — поле «Назва» вгорі.</p>
		<p>
			<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">+ Додати новину</a>
			<a class="button button-hero" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" target="_blank">Переглянути сторінку на сайті</a>
		</p>
		<p><strong>Усього новин: <?php echo (int) $q->post_count; ?></strong></p>
		<table class="widefat striped bgv-news-table">
			<thead><tr><th style="width:96px">Фото</th><th>Заголовок</th><th style="width:110px">Дата</th><th style="width:120px">Статус</th><th style="width:210px">Дії</th></tr></thead>
			<tbody>
			<?php while ( $q->have_posts() ) : $q->the_post(); $id = get_the_ID(); ?>
				<tr>
					<td><?php if ( has_post_thumbnail( $id ) ) { echo get_the_post_thumbnail( $id, array( 80, 60 ), array( 'style' => 'width:80px;height:60px;object-fit:cover;border-radius:4px' ) ); } else { echo '<span style="color:#999">немає</span>'; } ?></td>
					<td><a href="<?php echo esc_url( get_edit_post_link( $id ) ); ?>" style="font-weight:600;font-size:14px"><?php echo esc_html( get_the_title() ); ?></a></td>
					<td><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></td>
					<td><?php echo esc_html( $statuses[ get_post_status() ] ?? get_post_status() ); ?></td>
					<td>
						<a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $id ) ); ?>">Редагувати</a>
						<a class="button button-small" href="<?php echo esc_url( get_permalink( $id ) ); ?>" target="_blank">Переглянути</a>
					</td>
				</tr>
			<?php endwhile; wp_reset_postdata(); ?>
			</tbody>
		</table>
		<p style="margin-top:12px"><a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">Відкрити розділ «Новини» (пошук, кошик, масові дії) →</a></p>
	</div>
	<?php
}

// Пояснення у списку сторінок.
add_filter( 'display_post_states', function ( $states, $post ) {
	if ( (int) $post->ID === bgv_front_id() ) { $states['bgv'] = 'редагується полями'; }
	if ( (int) $post->ID === (int) get_option( 'page_for_posts' ) ) { $states['bgv'] = 'список новин'; }
	return $states;
}, 10, 2 );

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

/* ---------- Перенаправлення зі старих адрес (bgv-development.com.ua) ---------- */
add_action( 'template_redirect', function () {
	if ( ! is_404() ) { return; }
	$path = rawurldecode( strtok( $_SERVER['REQUEST_URI'] ?? '', '?' ) );
	$path = '/' . trim( $path, '/' ) . '/';
	$map  = array(
		'/поліський-напівмарафон-спорт-що-обєд/' => '/poliskyi-napivmarafon-sport-shcho-obiednuie/',
		'/sitemap_index.xml/'                     => '/sitemap.xml',
		'/proekt/https-www-manhattan-zt-ua/'      => '/proekt/manhattan-zhytomyr/',
		'/post-sitemap.xml/'                      => '/sitemap.xml',
		'/page-sitemap.xml/'                      => '/sitemap.xml',
	);
	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
} );

/* ---------- Іконка сайту ---------- */
add_action( 'wp_head', function () {
	if ( has_site_icon() ) { return; }
	$u = get_template_directory_uri() . '/assets/favicon.png';
	echo '<link rel="icon" type="image/png" href="' . esc_url( $u ) . '">' . "\n" . '<link rel="apple-touch-icon" href="' . esc_url( $u ) . '">' . "\n";
}, 2 );

// Новини редагуються полями (без блочного редактора).
add_filter( 'use_block_editor_for_post_type', function ( $use, $type ) {
	return 'post' === $type ? false : $use;
}, 10, 2 );

/* ---------- Yoast SEO: одноразове перенесення SEO-даних ---------- */
add_action( 'admin_init', function () {
	if ( ! defined( 'WPSEO_VERSION' ) || get_option( 'bgv_yoast_synced' ) ) { return; }
	update_option( 'bgv_yoast_synced', 1, false );
	$ids = get_posts( array( 'post_type' => array( 'post', 'page' ), 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );
	$front = bgv_front_id();
	foreach ( $ids as $id ) {
		$t = get_post_meta( $id, 'jetpack_seo_html_title', true );
		$d = get_post_meta( $id, 'advanced_seo_description', true );
		if ( (int) $id === $front ) {
			$t = 'BGV Development – Будівництво в Україні';
			$d = $d ? $d : get_option( 'advanced_seo_front_page_description' );
			if ( ! get_post_meta( $id, '_yoast_wpseo_focuskw', true ) ) { update_post_meta( $id, '_yoast_wpseo_focuskw', 'bgv development' ); }
		}
		$changed = false;
		if ( $t && ! get_post_meta( $id, '_yoast_wpseo_title', true ) ) { update_post_meta( $id, '_yoast_wpseo_title', $t ); $changed = true; }
		if ( $d && ! get_post_meta( $id, '_yoast_wpseo_metadesc', true ) ) { update_post_meta( $id, '_yoast_wpseo_metadesc', $d ); $changed = true; }
		if ( $changed ) { do_action( 'wp_insert_post', $id, get_post( $id ), true ); } // оновити індекс Yoast
	}
} );

/* ---------- Гарантовані <title> і meta description ----------
 * Якщо SEO-плагін з якоїсь причини нічого не вивів (наприклад, у режимі превʼю),
 * тема сама додає заголовок і опис із даних Yoast / Jetpack. */
function bgv_seo_title_desc() {
	$title = wp_get_document_title();
	$desc  = '';
	if ( ! empty( $GLOBALS['bgv_project'] ) ) {
		$pr = $GLOBALS['bgv_project'];
		return array( $pr['title'] . ' – ' . get_bloginfo( 'name' ), wp_trim_words( wp_strip_all_tags( (string) ( $pr['description'] ?? '' ) ), 26, '…' ) );
	}
	if ( is_front_page() ) {
		$id = bgv_front_id();
	} elseif ( is_home() ) {
		$id = (int) get_option( 'page_for_posts' );
	} elseif ( is_singular() ) {
		$id = get_queried_object_id();
	} else {
		$id = 0;
	}
	if ( $id ) {
		$t = get_post_meta( $id, '_yoast_wpseo_title', true ) ?: get_post_meta( $id, 'jetpack_seo_html_title', true );
		if ( $t && false === strpos( $t, '%%' ) ) { $title = $t; }
		$desc = get_post_meta( $id, '_yoast_wpseo_metadesc', true ) ?: get_post_meta( $id, 'advanced_seo_description', true );
		if ( ! $desc && is_singular( 'post' ) ) { $desc = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $id ) ), 28, '…' ); }
	}
	if ( ! $desc && is_front_page() ) { $desc = get_option( 'advanced_seo_front_page_description' ); }
	if ( is_front_page() && ( ! $title || $title === get_bloginfo( 'name' ) ) ) { $title = 'BGV Development – Будівництво в Україні'; }
	return array( $title, $desc );
}
add_action( 'wp_head', function () { ob_start(); }, -1000 );
add_action( 'wp_head', function () {
	$head = ob_get_clean();
	if ( false === stripos( $head, '<title' ) || false === stripos( $head, 'name="description"' ) ) {
		list( $t, $d ) = bgv_seo_title_desc();
		if ( false === stripos( $head, '<title' ) ) { $head = '<title>' . esc_html( $t ) . "</title>\n" . $head; }
		if ( $d && false === stripos( $head, 'name="description"' ) ) { $head = '<meta name="description" content="' . esc_attr( $d ) . "\">\n" . $head; }
	}
	echo $head; // phpcs:ignore
}, 10000 );

/* ---------- Блоки з полями не згортаються і не переміщуються ---------- */
foreach ( array( 'page', 'post' ) as $bgv_pt ) {
	add_filter( 'get_user_option_closedpostboxes_' . $bgv_pt, function ( $closed ) {
		return is_array( $closed ) ? array_values( array_diff( $closed, array( 'acf-group_bgv_home', 'acf-group_bgv_news' ) ) ) : $closed;
	} );
}
add_action( 'admin_head', function () {
	?>
	<style>
		#acf-group_bgv_home .handle-actions, #acf-group_bgv_news .handle-actions { display: none !important; }
		#acf-group_bgv_home .postbox-header, #acf-group_bgv_news .postbox-header { pointer-events: none; cursor: default; }
		#acf-group_bgv_home.closed .inside, #acf-group_bgv_news.closed .inside { display: block !important; }
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			['acf-group_bgv_home', 'acf-group_bgv_news'].forEach(function (id) {
				var box = document.getElementById(id);
				if (!box) { return; }
				box.classList.remove('closed');
			});
		});
	</script>
	<?php
} );

// Порядок блоків: Yoast SEO угорі, під ним поля (без переміщення DOM, щоб не скидати редактори тексту).
foreach ( array( 'page', 'post' ) as $bgv_pt2 ) {
	add_filter( 'get_user_option_meta-box-order_' . $bgv_pt2, function () {
		return array( 'normal' => 'wpseo_meta,acf-group_bgv_home,acf-group_bgv_news', 'side' => '', 'advanced' => '' );
	} );
}
