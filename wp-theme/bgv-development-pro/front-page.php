<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<?php get_template_part( 'parts/hero' ); ?>


<section class="bgv-about" id="about">
	<div class="container bgv-about__in">
		<?php
		// Слайдер «Про нас»: перше — фото розділу, далі ТЦ «ЖИТО» Житомир, потім інші проєкти.
		$bgv_as = array(); $bgv_seen = array();
		$bgv_add = function ( $img, $cap, $url ) use ( &$bgv_as, &$bgv_seen ) {
			$img = (int) $img; if ( ! $img || isset( $bgv_seen[ $img ] ) || ! bgv_img_url( $img ) ) { return; }
			$bgv_seen[ $img ] = 1; $bgv_as[] = array( 'img' => $img, 'cap' => $cap, 'url' => $url );
		};
		$bgv_projects = bgv_all_projects();
		$bgv_first_cap = '';
		foreach ( $bgv_projects as $p ) { if ( (int) $p['image'] === (int) bgv_opt( 'about_image' ) || false !== mb_stripos( $p['title'], 'BOSTON' ) ) { $bgv_first_cap = $p; break; } }
		$bgv_add( bgv_opt( 'about_image' ), $bgv_first_cap ? $bgv_first_cap['title'] : '', $bgv_first_cap ? $bgv_first_cap['_url'] : '' );
		foreach ( $bgv_projects as $p ) { if ( false !== mb_stripos( $p['title'], 'ЖИТО, Житомир' ) ) { $bgv_add( $p['image'], $p['title'], $p['_url'] ); } }
		foreach ( $bgv_projects as $p ) { if ( $bgv_first_cap && $p['title'] === $bgv_first_cap['title'] ) { continue; } $bgv_add( $p['image'], $p['title'], $p['_url'] ); }
		?>
		<div class="bgv-about__img bgv-aslider" data-aslider>
			<?php foreach ( $bgv_as as $i => $sl ) : ?>
				<div class="bgv-aslide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-cap="<?php echo esc_attr( $sl['cap'] ); ?>" data-link="<?php echo esc_url( $sl['url'] ); ?>">
					<img src="<?php echo esc_url( bgv_img_url( $sl['img'], 'large' ) ); ?>" alt="<?php echo esc_attr( $sl['cap'] ); ?>" loading="lazy">
				</div>
			<?php endforeach; ?>
			<?php if ( count( $bgv_as ) > 1 ) : ?>
				<div class="bgv-aslider__bar">
					<a class="bgv-aslider__cap" href="<?php echo esc_url( $bgv_as[0]['url'] ?: '#our-projects' ); ?>"><?php echo esc_html( $bgv_as[0]['cap'] ); ?></a>
					<div class="bgv-aslider__nav">
						<button type="button" class="bgv-aslider__arrow" data-dir="-1" aria-label="Попереднє фото">&larr;</button>
						<span class="bgv-aslider__count"><b>01</b> / <?php echo esc_html( sprintf( '%02d', count( $bgv_as ) ) ); ?></span>
						<button type="button" class="bgv-aslider__arrow" data-dir="1" aria-label="Наступне фото">&rarr;</button>
					</div>
					<div class="bgv-aslider__progress"><span></span></div>
				</div>
			<?php endif; ?>
		</div>
		<div class="bgv-about__body">
			<h2 class="bgv-about__title"><?php echo esc_html( bgv_opt( 'about_title' ) ?: bgv_t( 'Про нас' ) ); ?></h2>
			<div class="bgv-about__text"><?php echo wp_kses_post( bgv_opt( 'about_text' ) ); ?></div>
			<?php $bgv_stats = array_filter( (array) bgv_opt( 'stats' ), function ( $x ) { return ! empty( $x['number'] ); } ); if ( $bgv_stats ) : ?>
				<ul class="bgv-about__stats" id="stats">
					<?php foreach ( $bgv_stats as $x ) : ?>
						<li><p class="bgv-stats__num"><?php echo esc_html( $x['number'] ); ?></p><p class="bgv-about__label"><?php echo esc_html( $x['label'] ); ?></p></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="bgv-about__cta">
				<button class="bgv-btn bgv-btn--ghost" type="button" data-bgv-open="bgv-form" data-bgv-title="<?php bgv_e( 'Зв’язатися з нами' ); ?>"><?php bgv_e( 'Зв’язатися з нами' ); ?></button>
			</div>
		</div>
	</div>
</section>

<?php get_template_part( 'parts/advantages' ); ?>

<?php get_template_part( 'parts/projects' ); ?>

<?php get_template_part( 'parts/achievements', null, array( 'only' => 'achievements' ) ); ?>

<?php get_template_part( 'parts/achievements', null, array( 'only' => 'awards' ) ); ?>

<section class="news" id="news">
	<div class="container">
		<div class="news-header">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'news_title' ) ); ?></h2>
			<a class="news-link cursor" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php echo esc_html( bgv_opt( 'news_link' ) ); ?> <?php echo bgv_chevron(); // phpcs:ignore ?></a>
		</div>
		<div class="news-content">
			<div class="news-swiper swiper-container">
				<div class="swiper-wrapper">
					<?php
					$bgv_news = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 10, 'ignore_sticky_posts' => true ) );
					while ( $bgv_news->have_posts() ) :
						$bgv_news->the_post();
						$cats = get_the_category();
						?>
						<div class="swiper-slide" data-cat="<?php echo esc_attr( $cats ? $cats[0]->slug : '' ); ?>">
							<a class="news-card cursor" href="<?php the_permalink(); ?>">
								<div class="news-image"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large', array( 'class' => 'news-image', 'loading' => 'lazy' ) ); } ?></div>
								<div class="news-info">
									<p class="news-category"><?php echo esc_html( $cats ? $cats[0]->name : 'Новини' ); ?></p>
									<p class="news-date"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></p>
								</div>
								<div class="news-text"><h3 class="news-title"><?php the_title(); ?></h3></div>
							</a>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</section>


<?php get_template_part( 'parts/investors' ); ?>

<section class="philantropy" id="social">
	<div class="philantropy-content">
		<div class="container">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'social_title' ) ); ?></h2>
			<ul class="philantropy-list">
				<?php foreach ( (array) bgv_opt( 'social_items' ) as $it ) : if ( empty( $it['text'] ) ) { continue; } ?>
					<li class="philantropy-item"><p class="philantropy-text"><?php echo esc_html( $it['text'] ); ?></p></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		$bgv_soc = array_values( array_unique( array_filter( array_merge( array( (int) bgv_opt( 'social_image' ) ), array_map( 'intval', (array) bgv_opt( 'social_gallery' ) ) ) ) ) );
		?>
		<div class="philantropy-side-img bgv-soc-slider" data-slider>
			<?php foreach ( $bgv_soc as $i => $img ) : ?>
				<div class="bgv-soc-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url( bgv_img_url( $img, 'large' ) ); ?>')" role="img" aria-label="<?php echo esc_attr( get_post_meta( $img, '_wp_attachment_image_alt', true ) ); ?>"></div>
			<?php endforeach; ?>
			<?php if ( count( $bgv_soc ) > 1 ) : ?>
				<div class="bgv-soc-nav">
					<button type="button" class="bgv-soc-arrow" data-dir="-1" aria-label="Попереднє фото">←</button>
					<div class="bgv-soc-dots"><?php foreach ( $bgv_soc as $i => $img ) : ?><button type="button" class="<?php echo 0 === $i ? 'is-active' : ''; ?>" data-slide="<?php echo (int) $i; ?>" aria-label="Фото <?php echo (int) $i + 1; ?>"></button><?php endforeach; ?></div>
					<button type="button" class="bgv-soc-arrow" data-dir="1" aria-label="Наступне фото">→</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="contacts" id="contact-us">
	<div class="container">
		<div class="contact-info">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'contacts_title' ) ); ?></h2>
			<div class="contact-list">
				<h3 class="contact-list-title"><?php echo esc_html( bgv_opt( 'company_title' ) ); ?></h3>
				<ul>
					<?php foreach ( (array) bgv_opt( 'company' ) as $c ) : if ( empty( $c['value'] ) ) { continue; } ?>
						<li class="contact-list-item">
							<p class="contact-list-label"><?php echo esc_html( $c['label'] ); ?></p>
							<?php if ( ! empty( $c['link'] ) ) : ?>
								<a class="contact-list-link" href="<?php echo esc_url( $c['link'], array( 'mailto', 'tel', 'http', 'https' ) ); ?>"><?php echo esc_html( $c['value'] ); ?></a>
							<?php else : ?>
								<p class="contact-list-link"><?php echo esc_html( $c['value'] ); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="contact-list">
				<h3 class="contact-list-title"><?php echo esc_html( bgv_opt( 'team_title' ) ); ?></h3>
				<ul>
					<?php foreach ( (array) bgv_opt( 'team' ) as $m ) : if ( empty( $m['name'] ) ) { continue; } ?>
						<li class="contact-list-item">
							<p class="contact-list-label"><?php echo esc_html( $m['name'] ); ?> <?php if ( ! empty( $m['position'] ) ) : ?><span class="styled">[<?php echo esc_html( $m['position'] ); ?>]</span><?php endif; ?></p>
							<?php if ( ! empty( $m['email'] ) ) : ?><a class="contact-list-link" href="mailto:<?php echo esc_attr( $m['email'] ); ?>"><?php echo esc_html( $m['email'] ); ?></a><?php endif; ?>
						</li>
					<?php endforeach; ?>
					<?php if ( bgv_opt( 'team_note' ) ) : ?>
						<li class="contact-list-item"><p class="contact-list-label"><span class="styled"><?php echo esc_html( bgv_opt( 'team_note' ) ); ?></span></p></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<div class="contact-form">
			<h3 class="contact-form-title"><?php echo esc_html( bgv_opt( 'form_title' ) ); ?></h3>
			<p class="contact-form-text"><?php echo nl2br( esc_html( bgv_opt( 'form_text' ) ) ); ?></p>
			<?php $bgv_sent = isset( $_GET['sent'] ) ? sanitize_key( $_GET['sent'] ) : ''; ?>
			<?php if ( 'ok' === $bgv_sent ) : ?>
				<p class="contact-form-text bgv-form-ok">Дякуємо! Ваша заявка надіслана, ми зв’яжемося з вами найближчим часом.</p>
			<?php elseif ( 'error' === $bgv_sent ) : ?>
				<p class="contact-form-text bgv-form-err">Не вдалося надіслати заявку. Перевірте поля або напишіть нам на <?php echo esc_html( bgv_opt( 'footer_email' ) ); ?>.</p>
			<?php endif; ?>
			<form class="contact-form-form bgv-ajax" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<?php bgv_lead_hidden( 'contact' ); ?>
				<input class="contact-form-input" name="name" type="text" placeholder="<?php bgv_e( 'Ваші ПІБ' ); ?>" required minlength="3">
				<input class="contact-form-input" name="phone" type="tel" placeholder="<?php bgv_e( 'Ваш номер мобільного' ); ?>" required pattern="[+0-9()\s-]{10,20}">
				<input class="contact-form-input" name="email" type="email" placeholder="<?php bgv_e( 'Ваша електронна адреса' ); ?>">
				<textarea class="contact-form-input contact-form-textarea" name="message" rows="2" placeholder="<?php bgv_e( 'Опишіть коротко питання, з яким ви бажаєте звернутися до нашої компанії?' ); ?>"></textarea>
				<button class="contact-form-submit project-link project-link--blue" type="submit"><?php bgv_e( 'Надіслати' ); ?></button>
					<p class="bgv-form-msg" aria-live="polite"></p>
			</form>
		</div>
	</div>
</section>
<?php
get_footer();
