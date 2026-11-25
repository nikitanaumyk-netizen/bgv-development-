<?php
/**
 * BGV v2: мови, меню, заявки (ліди), презентації, скрипти.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------- Мова (UA / EN) ---------- */
function bgv_en_page_id() {
	static $id = null;
	if ( null === $id ) {
		$p  = get_page_by_path( 'en' );
		$id = ( $p && 'publish' === $p->post_status ) ? (int) $p->ID : 0;
	}
	return $id;
}
function bgv_lang() {
	if ( ! empty( $GLOBALS['bgv_lang'] ) ) { return $GLOBALS['bgv_lang']; }
	$en = bgv_en_page_id();
	if ( $en && is_page( $en ) ) { return 'en'; }
	return 'uk';
}
function bgv_home( $path = '/' ) {
	return 'en' === bgv_lang() && bgv_en_page_id() ? home_url( '/en' . $path ) : home_url( $path );
}
function bgv_t( $s ) {
	if ( 'en' !== bgv_lang() ) { return $s; }
	static $d = null;
	if ( null === $d ) {
		$d = array(
			'Про нас' => 'About', 'Про компанію' => 'About the company', 'Наші переваги' => 'Our advantages', 'Відкрити проєкт' => 'Open project', 'Дивитися проєкти' => 'View projects', 'Гортайте вниз' => 'Scroll down', 'Нагорода' => 'Award', 'Медіа' => 'Media', 'Членство' => 'Membership', 'Ключові переваги' => 'Key advantages', 'Цифри' => 'Key figures', 'Переваги' => 'Advantages',
			'Досягнення' => 'Achievements', 'Нагороди' => 'Awards', 'Завантажити презентацію' => 'Download presentation',
			'Проєкти' => 'Projects', 'Усі проєкти' => 'All projects', 'Інвесторам' => 'Investors', 'Прес-центр' => 'Press centre',
			'Контакти' => 'Contacts', 'Підібрати об’єкт' => 'Find a property', 'Замовити дзвінок' => 'Request a call',
			'Всі' => 'All', 'Будується' => 'Under construction', 'Здано / реалізовано' => 'Completed', 'Проєктування / концепція' => 'Concept',
			'Детальніше' => 'Details', 'Перейти на сайт' => 'Visit website', 'Картка проєкту' => 'Project page',
			'Презентація об’єкта' => 'Project presentation', 'Наші проєкти' => 'Our projects', 'Карта об’єктів' => 'Projects map',
			'Підібрати об’єкт за 3 кроки' => 'Find a property in 3 steps', 'Крок' => 'Step', 'з' => 'of',
			'Тип нерухомості' => 'Property type', 'Житлова' => 'Residential', 'Комерційна' => 'Commercial', 'Вілли' => 'Villas',
			'Мета' => 'Goal', 'Власне проживання' => 'To live in', 'Орендний бізнес' => 'Rental business', 'Інвестиція' => 'Investment',
			'Куди надіслати добірку?' => 'Where should we send the selection?', 'Telegram / Viber / дзвінок' => 'Telegram / Viber / call',
			'Далі' => 'Next', 'Назад' => 'Back', 'Отримати добірку' => 'Get the selection',
			'Ім’я' => 'Name', 'Телефон' => 'Phone', 'Email' => 'Email', 'Повідомлення / мета звернення' => 'Message / purpose',
			'Надіслати запит' => 'Send request', 'Подзвонити' => 'Call us', 'Заповнити форму' => 'Fill in the form',
			'Зв’язатися з нами' => 'Contact us', 'Дякуємо! Ми зв’яжемося з вами найближчим часом.' => 'Thank you! We will contact you shortly.',
			'Не вдалося надіслати. Спробуйте ще раз або зателефонуйте нам.' => 'Sending failed. Please try again or call us.',
			'Телефон або email' => 'Phone or email', 'Отримати презентацію' => 'Get the presentation',
			'Залиште контакт — і файл відкриється одразу.' => 'Leave your contact and the file will open right away.',
			'Стати партнером' => 'Become a partner', 'Подати пропозицію' => 'Submit a proposal', 'Опишіть пропозицію (ділянка, площа, формат)' => 'Describe your proposal (land plot, area, format)',
			'Усі права захищено.' => 'All rights reserved.', 'Політика конфіденційності' => 'Privacy policy', 'Умови користування' => 'Terms of use',
			'Акції та новини' => 'News', 'Усі новини' => 'All news', 'Новини' => 'News', 'Акції' => 'Offers', 'ЗМІ про нас' => 'Media about us', 'Хід будівництва' => 'Construction progress',
			'Відкрити' => 'Open', 'Меню' => 'Menu', 'Закрити' => 'Close', 'збудовано' => 'built',
			'Надіслати' => 'Send', 'Ваші ПІБ' => 'Your name', 'Ваш номер мобільного' => 'Your mobile number', 'Ваша електронна адреса' => 'Your email',
			'Опишіть коротко питання, з яким ви бажаєте звернутися до нашої компанії?' => 'Briefly describe your question',
			'Опис' => 'Description', 'Відділ продажу' => 'Sales department', 'Вас може зацікавити' => 'You may also like', 'Автор новини' => 'Author',
		);
	}
	return isset( $d[ $s ] ) ? $d[ $s ] : $s;
}
function bgv_e( $s ) { echo esc_html( bgv_t( $s ) ); }

/* ---------- Статуси проєктів ---------- */
function bgv_status_label( $st ) {
	$m = array( 'building' => 'Будується', 'done' => 'Здано / реалізовано', 'concept' => 'Проєктування / концепція' );
	return isset( $m[ $st ] ) ? bgv_t( $m[ $st ] ) : '';
}
function bgv_group_meta() {
	return array(
		'real_estate'       => array( 'res', 'Житлова нерухомість & Вілли' ),
		'commercial_estate' => array( 'com', 'Комерція & ТРЦ' ),
		'wellbeing_estate'  => array( 'soc', 'Соціальні & Рекреаційні' ),
	);
}
function bgv_all_projects() {
	$out = array();
	foreach ( bgv_project_groups() as $g ) {
		$rows = bgv_opt( $g );
		if ( ! is_array( $rows ) ) { continue; }
		foreach ( $rows as $i => $r ) {
			$r['_group'] = $g;
			$r['_slug']  = bgv_project_slug( $r, $g, $i );
			$r['_url']   = bgv_project_url( $r['_slug'] );
			$out[]       = $r;
		}
	}
	return $out;
}
function bgv_file_url( $id ) {
	if ( ! $id ) { return ''; }
	if ( is_array( $id ) ) { $id = isset( $id['ID'] ) ? $id['ID'] : 0; }
	$u = wp_get_attachment_url( (int) $id );
	return $u ? $u : '';
}

/* ---------- Скрипти ---------- */
add_action( 'wp_enqueue_scripts', function () {
	$u = get_template_directory_uri() . '/assets/';
	wp_enqueue_script( 'bgv', $u . 'bgv.js', array(), BGV_VER, true );
	if ( is_front_page() ) {
		wp_enqueue_script( 'bgv-intro', $u . 'intro.js', array(), BGV_VER, true );
	}
	wp_localize_script( 'bgv', 'BGV', array(
		'ajax' => admin_url( 'admin-post.php' ),
		'ok'   => bgv_t( 'Дякуємо! Ми зв’яжемося з вами найближчим часом.' ),
		'err'  => bgv_t( 'Не вдалося надіслати. Спробуйте ще раз або зателефонуйте нам.' ),
	) );
}, 20 );

/* ---------- Меню ---------- */
function bgv_nav_items() {
	$h     = bgv_home( '/' );
	$items = array();
	$about = array(
		array( $h . '#about', bgv_t( 'Про компанію' ) ),
		array( $h . '#stats', bgv_t( 'Цифри' ) ),
		array( $h . '#advantages', bgv_t( 'Переваги' ) ),
	);
	$items[] = array( $h . '#about', bgv_t( 'Про нас' ), '', $about );
	$proj    = array();
	foreach ( bgv_group_meta() as $g => $m ) {
		$label  = bgv_opt( 'tab_' . $g );
		$proj[] = array( $h . '?cat=' . $m[0] . '#our-projects', $label ? $label : bgv_t( $m[1] ), 'data-bgv-cat="' . $m[0] . '"' );
	}
	$items[] = array( $h . '#our-projects', bgv_t( 'Проєкти' ), '', $proj );
	$items[] = array( $h . '#investors', bgv_t( 'Інвесторам' ) );
	if ( bgv_opt( 'achievements' ) ) { $items[] = array( $h . '#achievements', bgv_t( 'Досягнення' ) ); }
	if ( bgv_opt( 'awards' ) ) { $items[] = array( $h . '#awards', bgv_t( 'Нагороди' ) ); }
	$items[] = array( get_permalink( get_option( 'page_for_posts' ) ), bgv_t( 'Прес-центр' ) );
	$items[] = array( $h . '#contact-us', bgv_t( 'Контакти' ) );
	return $items;
}
function bgv_menu( $id ) {
	echo '<ul id="' . esc_attr( $id ) . '" class="menu">';
	foreach ( bgv_nav_items() as $it ) {
		$sub = ! empty( $it[3] ) && 'primary-menu' === $id;
		echo '<li class="menu-item' . ( $sub ? ' has-sub' : '' ) . '"><a href="' . esc_url( $it[0] ) . '"' . ( $sub ? ' aria-haspopup="true"' : '' ) . '>' . esc_html( $it[1] ) . '</a>';
		if ( $sub ) {
			echo '<button class="sub-toggle" type="button" aria-label="' . esc_attr( bgv_t( 'Відкрити' ) ) . '"></button><ul class="sub-menu">';
			foreach ( $it[3] as $s ) {
				echo '<li><a href="' . esc_url( $s[0] ) . '" ' . ( isset( $s[2] ) ? $s[2] : '' ) . '>' . esc_html( $s[1] ) . '</a></li>'; // phpcs:ignore
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
function bgv_phone() {
	$p = bgv_opt( 'header_phone' );
	return $p ? $p : bgv_opt( 'footer_phone' );
}
function bgv_tel( $p ) { return 'tel:' . preg_replace( '/[^+0-9]/', '', (string) $p ); }

/* ---------- Заявки: тип запису ---------- */
add_action( 'init', function () {
	register_post_type( 'bgv_lead', array(
		'labels'          => array( 'name' => 'Заявки', 'singular_name' => 'Заявка', 'menu_name' => 'Заявки', 'all_items' => 'Усі заявки', 'edit_item' => 'Заявка', 'search_items' => 'Шукати заявки', 'not_found' => 'Заявок ще немає' ),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_position'   => 4,
		'menu_icon'       => 'dashicons-email-alt',
		'supports'        => array( 'title' ),
		'capability_type' => 'page',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
	// Рубрики новин.
	if ( ! get_option( 'bgv_cats_v2' ) && taxonomy_exists( 'category' ) ) {
		foreach ( array( 'novyny' => 'Новини', 'aktsii' => 'Акції', 'zmi-pro-nas' => 'ЗМІ про нас', 'khid-budivnytstva' => 'Хід будівництва' ) as $slug => $name ) {
			if ( ! get_term_by( 'slug', $slug, 'category' ) ) { wp_insert_term( $name, 'category', array( 'slug' => $slug ) ); }
		}
		update_option( 'bgv_cats_v2', 1, false );
	}
} );
function bgv_lead_types() {
	return array( 'contact' => 'Форма контактів', 'quiz' => 'Підбір об’єкта (3 кроки)', 'partner' => 'Інвестор / партнер', 'presentation' => 'Запит презентації', 'callback' => 'Плаваюча кнопка' );
}
add_filter( 'manage_bgv_lead_posts_columns', function () {
	return array( 'cb' => '<input type="checkbox">', 'title' => 'Заявка', 'bgv_type' => 'Тип', 'bgv_phone' => 'Телефон', 'bgv_email' => 'Email', 'bgv_msg' => 'Деталі', 'date' => 'Дата' );
} );
add_action( 'manage_bgv_lead_posts_custom_column', function ( $col, $id ) {
	$t = bgv_lead_types();
	switch ( $col ) {
		case 'bgv_type':  $v = get_post_meta( $id, 'type', true ); echo esc_html( $t[ $v ] ?? $v ); break;
		case 'bgv_phone': $v = get_post_meta( $id, 'phone', true ); if ( $v ) { echo '<a href="' . esc_attr( bgv_tel( $v ) ) . '">' . esc_html( $v ) . '</a>'; } break;
		case 'bgv_email': $v = get_post_meta( $id, 'email', true ); if ( $v ) { echo '<a href="mailto:' . esc_attr( $v ) . '">' . esc_html( $v ) . '</a>'; } break;
		case 'bgv_msg':   echo nl2br( esc_html( wp_trim_words( get_post_field( 'post_content', $id ), 40 ) ) ); break;
	}
}, 10, 2 );
add_action( 'add_meta_boxes_bgv_lead', function ( $post ) {
	add_meta_box( 'bgv_lead_view', 'Дані заявки', function ( $post ) {
		echo '<div style="font-size:15px;line-height:1.7;white-space:pre-wrap">' . esc_html( $post->post_content ) . '</div>';
	}, 'bgv_lead', 'normal', 'high' );
} );
add_filter( 'post_row_actions', function ( $a, $post ) {
	if ( 'bgv_lead' === $post->post_type ) { unset( $a['inline hide-if-no-js'] ); }
	return $a;
}, 10, 2 );
// Лічильник нових заявок у меню.
add_action( 'admin_menu', function () {
	global $menu;
	$seen = (int) get_user_meta( get_current_user_id(), 'bgv_leads_seen', true );
	$n    = count( get_posts( array( 'post_type' => 'bgv_lead', 'post_status' => 'private', 'numberposts' => 50, 'fields' => 'ids', 'date_query' => array( array( 'after' => gmdate( 'Y-m-d H:i:s', $seen ), 'column' => 'post_date_gmt' ) ) ) ) );
	if ( ! $n || ! is_array( $menu ) ) { return; }
	foreach ( $menu as $k => $m ) {
		if ( isset( $m[2] ) && 'edit.php?post_type=bgv_lead' === $m[2] ) {
			$menu[ $k ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . (int) $n . '</span></span>'; // phpcs:ignore
		}
	}
}, 99 );
add_action( 'load-edit.php', function () {
	if ( isset( $_GET['post_type'] ) && 'bgv_lead' === $_GET['post_type'] ) { update_user_meta( get_current_user_id(), 'bgv_leads_seen', time() ); }
} );
add_filter( 'views_edit-bgv_lead', function ( $v ) { return array(); } );
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && $q->is_main_query() && 'bgv_lead' === $q->get( 'post_type' ) ) { $q->set( 'post_status', array( 'private' ) ); }
} );

/* ---------- Обробник заявок ---------- */
function bgv_presentation_url( $key ) {
	if ( 'company' === $key ) { return bgv_file_url( bgv_opt( 'presentation_pdf' ) ); }
	$p = bgv_find_project( sanitize_title( $key ) );
	return $p ? bgv_file_url( $p['presentation'] ?? 0 ) : '';
}
function bgv_handle_lead() {
	$ajax = ! empty( $_POST['ajax'] );
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'sent', strtok( $back, '#' ) );
	$done = function ( $ok, $extra = array() ) use ( $ajax, $back ) {
		if ( $ajax ) { wp_send_json( array_merge( array( 'ok' => $ok ), $extra ) ); }
		wp_safe_redirect( add_query_arg( 'sent', $ok ? 'ok' : 'error', $back ) . '#contact-us' );
		exit;
	};
	if ( ! empty( $_POST['website'] ) ) { $done( true ); } // пастка для ботів
	$g     = function ( $k ) { return sanitize_text_field( wp_unslash( $_POST[ $k ] ?? '' ) ); };
	$types = bgv_lead_types();
	$type  = $g( 'type' );
	if ( ! isset( $types[ $type ] ) ) { $type = 'contact'; }
	$name    = $g( 'name' );
	$contact = $g( 'contact' ); // одне поле «телефон або email»
	$phone   = $g( 'phone' );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( $contact ) {
		if ( is_email( $contact ) ) { $email = $contact; } else { $phone = $contact; }
	}
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$phone_ok = strlen( preg_replace( '/\D/', '', $phone ) ) >= 9;
	if ( ! $phone_ok && ! is_email( $email ) ) { $done( false ); }
	$lines = array();
	$lines[] = 'Тип: ' . $types[ $type ];
	if ( $name ) { $lines[] = 'Ім’я: ' . $name; }
	if ( $phone ) { $lines[] = 'Телефон: ' . $phone; }
	if ( $email ) { $lines[] = 'Email: ' . $email; }
	foreach ( array( 'step1' => 'Тип нерухомості', 'step2' => 'Мета', 'messenger' => 'Зручний месенджер', 'object' => 'Об’єкт / файл' ) as $k => $l ) {
		if ( $g( $k ) ) { $lines[] = $l . ': ' . $g( $k ); }
	}
	if ( $message ) { $lines[] = "Повідомлення:\n" . $message; }
	$lines[] = 'Сторінка: ' . $back;
	$body    = implode( "\n", $lines );
	$title   = $types[ $type ] . ' — ' . ( $name ? $name : ( $phone ? $phone : $email ) );
	$id = wp_insert_post( array( 'post_type' => 'bgv_lead', 'post_status' => 'private', 'post_title' => $title, 'post_content' => $body ) );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'type', $type );
		update_post_meta( $id, 'phone', $phone );
		update_post_meta( $id, 'email', $email );
	}
	$to = bgv_opt( 'form_email' );
	if ( ! is_email( $to ) ) { $to = get_option( 'admin_email' ); }
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) { $headers[] = 'Reply-To: ' . ( $name ? $name : $email ) . ' <' . $email . '>'; }
	wp_mail( $to, 'Заявка з сайту BGV Development: ' . $title, "Нова заявка з сайту BGV Development\n\n" . $body, $headers );
	$extra = array();
	if ( 'presentation' === $type ) {
		$f = bgv_presentation_url( $g( 'file' ) );
		if ( $f ) { $extra['file'] = $f; }
		if ( ! $ajax && $f ) { wp_redirect( $f ); exit; } // phpcs:ignore
	}
	$done( (bool) $id, $extra );
}
add_action( 'admin_post_nopriv_bgv_lead', 'bgv_handle_lead' );
add_action( 'admin_post_bgv_lead', 'bgv_handle_lead' );

/* ---------- Спільні модальні вікна та плаваюча кнопка ---------- */
function bgv_lead_hidden( $type ) {
	echo '<input type="hidden" name="action" value="bgv_lead"><input type="hidden" name="type" value="' . esc_attr( $type ) . '">';
	echo '<input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="bgv-hp" aria-hidden="true">';
}
add_action( 'wp_footer', function () {
	$act   = esc_url( admin_url( 'admin-post.php' ) );
	$phone = bgv_phone();
	?>
	<div class="bgv-modal" id="bgv-quiz" role="dialog" aria-modal="true" aria-labelledby="bgv-quiz-t" hidden>
		<div class="bgv-modal__box">
			<button class="bgv-modal__x" type="button" data-bgv-close aria-label="<?php bgv_e( 'Закрити' ); ?>">×</button>
			<p class="bgv-modal__title" id="bgv-quiz-t"><?php bgv_e( 'Підібрати об’єкт за 3 кроки' ); ?></p>
			<form class="bgv-quiz bgv-ajax" action="<?php echo $act; // phpcs:ignore ?>" method="post">
				<?php bgv_lead_hidden( 'quiz' ); ?>
				<div class="bgv-quiz__bar"><span></span></div>
				<fieldset class="bgv-step is-active" data-step="1">
					<legend><?php echo esc_html( bgv_t( 'Крок' ) . ' 1 ' . bgv_t( 'з' ) . ' 3 · ' . bgv_t( 'Тип нерухомості' ) ); ?></legend>
					<div class="bgv-opts">
						<?php foreach ( array( 'Житлова', 'Комерційна', 'Вілли' ) as $o ) : ?>
							<label class="bgv-opt"><input type="radio" name="step1" value="<?php echo esc_attr( $o ); ?>" required><span><?php bgv_e( $o ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<fieldset class="bgv-step" data-step="2">
					<legend><?php echo esc_html( bgv_t( 'Крок' ) . ' 2 ' . bgv_t( 'з' ) . ' 3 · ' . bgv_t( 'Мета' ) ); ?></legend>
					<div class="bgv-opts">
						<?php foreach ( array( 'Власне проживання', 'Орендний бізнес', 'Інвестиція' ) as $o ) : ?>
							<label class="bgv-opt"><input type="radio" name="step2" value="<?php echo esc_attr( $o ); ?>" required><span><?php bgv_e( $o ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<fieldset class="bgv-step" data-step="3">
					<legend><?php echo esc_html( bgv_t( 'Крок' ) . ' 3 ' . bgv_t( 'з' ) . ' 3 · ' . bgv_t( 'Куди надіслати добірку?' ) ); ?></legend>
					<input class="bgv-input" type="text" name="name" placeholder="<?php bgv_e( 'Ім’я' ); ?>" autocomplete="name">
					<input class="bgv-input" type="tel" name="phone" placeholder="<?php bgv_e( 'Телефон' ); ?> *" required pattern="[+0-9()\s\-]{9,20}" autocomplete="tel">
					<div class="bgv-opts bgv-opts--sm">
						<?php foreach ( array( 'Telegram', 'Viber', 'WhatsApp', 'Дзвінок' ) as $i => $o ) : ?>
							<label class="bgv-opt"><input type="radio" name="messenger" value="<?php echo esc_attr( $o ); ?>" <?php checked( 0, $i ); ?>><span><?php echo esc_html( 'Дзвінок' === $o && 'en' === bgv_lang() ? 'Call' : $o ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<div class="bgv-quiz__nav">
					<button type="button" class="bgv-btn bgv-btn--ghost" data-bgv-prev><?php bgv_e( 'Назад' ); ?></button>
					<button type="button" class="bgv-btn" data-bgv-next><?php bgv_e( 'Далі' ); ?></button>
					<button type="submit" class="bgv-btn" data-bgv-submit><?php bgv_e( 'Отримати добірку' ); ?></button>
				</div>
				<p class="bgv-form-msg" aria-live="polite"></p>
			</form>
		</div>
	</div>

	<div class="bgv-modal" id="bgv-form" role="dialog" aria-modal="true" aria-labelledby="bgv-form-t" hidden>
		<div class="bgv-modal__box">
			<button class="bgv-modal__x" type="button" data-bgv-close aria-label="<?php bgv_e( 'Закрити' ); ?>">×</button>
			<p class="bgv-modal__title" id="bgv-form-t" data-default="<?php bgv_e( 'Зв’язатися з нами' ); ?>"><?php bgv_e( 'Зв’язатися з нами' ); ?></p>
			<?php if ( $phone ) : ?><a class="bgv-modal__phone" href="<?php echo esc_attr( bgv_tel( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a><?php endif; ?>
			<form class="bgv-ajax" action="<?php echo $act; // phpcs:ignore ?>" method="post">
				<?php bgv_lead_hidden( 'callback' ); ?>
				<input type="hidden" name="object" value="">
				<input class="bgv-input" type="text" name="name" placeholder="<?php bgv_e( 'Ім’я' ); ?>" required autocomplete="name">
				<input class="bgv-input" type="tel" name="phone" placeholder="<?php bgv_e( 'Телефон' ); ?> *" required pattern="[+0-9()\s\-]{9,20}" autocomplete="tel">
				<textarea class="bgv-input" name="message" rows="3" placeholder="<?php bgv_e( 'Повідомлення / мета звернення' ); ?>" data-ph="<?php bgv_e( 'Повідомлення / мета звернення' ); ?>" data-ph-partner="<?php bgv_e( 'Опишіть пропозицію (ділянка, площа, формат)' ); ?>"></textarea>
				<button type="submit" class="bgv-btn bgv-btn--wide"><?php bgv_e( 'Надіслати запит' ); ?></button>
				<p class="bgv-form-msg" aria-live="polite"></p>
			</form>
		</div>
	</div>

	<div class="bgv-modal" id="bgv-pres" role="dialog" aria-modal="true" aria-labelledby="bgv-pres-t" hidden>
		<div class="bgv-modal__box">
			<button class="bgv-modal__x" type="button" data-bgv-close aria-label="<?php bgv_e( 'Закрити' ); ?>">×</button>
			<p class="bgv-modal__title" id="bgv-pres-t"><?php bgv_e( 'Отримати презентацію' ); ?></p>
			<p class="bgv-modal__text"><?php bgv_e( 'Залиште контакт — і файл відкриється одразу.' ); ?></p>
			<form class="bgv-ajax" action="<?php echo $act; // phpcs:ignore ?>" method="post">
				<?php bgv_lead_hidden( 'presentation' ); ?>
				<input type="hidden" name="file" value="company"><input type="hidden" name="object" value="">
				<input class="bgv-input" type="text" name="name" placeholder="<?php bgv_e( 'Ім’я' ); ?>" autocomplete="name">
				<input class="bgv-input" type="text" name="contact" placeholder="<?php bgv_e( 'Телефон або email' ); ?> *" required minlength="6">
				<button type="submit" class="bgv-btn bgv-btn--wide"><?php bgv_e( 'Завантажити презентацію' ); ?></button>
				<p class="bgv-form-msg" aria-live="polite"></p>
			</form>
		</div>
	</div>

	<div class="bgv-fab" id="bgv-fab">
		<div class="bgv-fab__menu">
			<?php if ( $phone ) : ?><a class="bgv-fab__item" href="<?php echo esc_attr( bgv_tel( $phone ) ); ?>"><?php bgv_e( 'Подзвонити' ); ?></a><?php endif; ?>
			<button class="bgv-fab__item" type="button" data-bgv-open="bgv-form"><?php bgv_e( 'Заповнити форму' ); ?></button>
		</div>
		<button class="bgv-fab__btn" type="button" aria-label="<?php bgv_e( 'Зв’язатися з нами' ); ?>" aria-expanded="false">
			<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6.6 10.8a15.2 15.2 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2Z" fill="#fff"/></svg>
		</button>
	</div>
	<?php
}, 5 );

/* ---------- Рубрики новин (фільтр) ---------- */
function bgv_news_cats() {
	$out = array();
	foreach ( array( 'novyny', 'aktsii', 'zmi-pro-nas', 'khid-budivnytstva' ) as $slug ) {
		$t = get_term_by( 'slug', $slug, 'category' );
		if ( $t && $t->count > 0 ) { $out[] = $t; }
	}
	return $out;
}
function bgv_news_chips( $js = false ) {
	$cats = bgv_news_cats();
	if ( count( $cats ) < 2 ) { return; } // фільтр має сенс від двох рубрик
	$cur  = is_category() ? get_queried_object()->slug : 'all';
	$blog = get_permalink( get_option( 'page_for_posts' ) );
	echo '<div class="bgv-chips bgv-chips--news"' . ( $js ? ' data-filter="#news .swiper-wrapper"' : '' ) . '>';
	echo '<a class="bgv-chip' . ( 'all' === $cur ? ' is-active' : '' ) . '" href="' . esc_url( $blog ) . '" data-cat="all">' . esc_html( bgv_t( 'Всі' ) ) . '</a>';
	foreach ( $cats as $t ) {
		echo '<a class="bgv-chip' . ( $cur === $t->slug ? ' is-active' : '' ) . '" href="' . esc_url( get_term_link( $t ) ) . '" data-cat="' . esc_attr( $t->slug ) . '">' . esc_html( bgv_t( $t->name ) ) . '</a>';
	}
	echo '</div>';
}
add_filter( 'private_title_format', function ( $f, $post ) {
	return ( $post && 'bgv_lead' === $post->post_type ) ? '%s' : $f;
}, 10, 2 );
