<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$bgv_tabs = array(
	'real_estate'       => array( 'cat-res', bgv_opt( 'tab_real_estate' ) ),
	'commercial_estate' => array( 'cat-com', bgv_opt( 'tab_commercial_estate' ) ),
	'wellbeing_estate'  => array( 'cat-soc', bgv_opt( 'tab_wellbeing_estate' ) ),
);
?>
<section class="hero">
	<div class="container">
		<p class="hero-description"><?php echo esc_html( bgv_opt( 'hero_description' ) ); ?></p>
		<p class="hero-sub-title"><?php echo esc_html( bgv_opt( 'hero_subtitle' ) ); ?></p>
		<h1 class="hero-title"><?php echo esc_html( bgv_opt( 'hero_title' ) ); ?></h1>
	</div>
	<?php
	$bgv_hero = array(
		array( 'hero_image_1', 'hero-bg hero-bg-1 not-for-mob' ),
		array( 'hero_image_2', 'hero-bg hero-bg-2 not-for-mob' ),
		array( 'hero_mob_1', 'hero-bg hero-bg-1 not-for-tab' ),
		array( 'hero_mob_2', 'hero-bg hero-bg-2 not-for-tab' ),
	);
	foreach ( $bgv_hero as $h ) {
		$u = bgv_img_url( bgv_opt( $h[0] ) );
		if ( $u ) {
			echo '<img src="' . esc_url( $u ) . '" class="' . esc_attr( $h[1] ) . '" alt="" fetchpriority="high">';
		}
	}
	?>
</section>

<section class="about" id="about">
	<div class="container">
		<h2 class="vh">Про нас</h2>
		<div class="about-content">
			<div class="about-image-wrapp">
				<?php if ( bgv_img_url( bgv_opt( 'about_image' ) ) ) : ?>
					<img src="<?php echo esc_url( bgv_img_url( bgv_opt( 'about_image' ), 'large' ) ); ?>" class="about-image-1" alt="" loading="lazy">
				<?php endif; ?>
			</div>
			<div class="about-text">
				<div class="main-about-text"><?php echo wp_kses_post( bgv_opt( 'about_text' ) ); ?></div>
				<div class="about-footer-text"><p><?php echo esc_html( bgv_opt( 'about_footer' ) ); ?></p></div>
			</div>
		</div>
	</div>
</section>

<?php $bgv_stats = bgv_opt( 'stats' ); if ( $bgv_stats ) : ?>
<section class="statistics">
	<div class="container">
		<h2 class="vh">Статистика</h2>
		<ul class="statistics-list">
			<?php foreach ( $bgv_stats as $s ) : ?>
				<li class="statistics-item">
					<?php if ( bgv_img_url( $s['icon'] ) ) : ?><img src="<?php echo esc_url( bgv_img_url( $s['icon'] ) ); ?>" class="statistics-icon" alt=""><?php endif; ?>
					<p class="statistics-text"><span class="styled-text"><?php echo esc_html( $s['number'] ); ?></span> <?php echo esc_html( $s['label'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<section class="projects" id="our-projects">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( bgv_opt( 'projects_title' ) ); ?></h2>
		<div class="projects-content">
			<div class="swiper-tabs">
				<?php foreach ( $bgv_tabs as $g => $t ) : ?>
					<a href="#<?php echo esc_attr( $t[0] ); ?>" class="swiper-tabs__item cursor tab-<?php echo esc_attr( $t[0] ); ?>"><?php echo esc_html( $t[1] ); ?></a>
				<?php endforeach; ?>
			</div>
			<div class="swiper-container projects-type-slider">
				<div class="swiper-wrapper">
					<?php foreach ( $bgv_tabs as $g => $t ) : $rows = bgv_opt( $g ); ?>
						<div class="swiper-slide ptab" id="<?php echo esc_attr( $t[0] ); ?>">
							<div class="swiper-container projects-slider">
								<div class="swiper-wrapper">
									<?php if ( is_array( $rows ) ) : foreach ( $rows as $i => $p ) : $url = bgv_project_url( bgv_project_slug( $p, $g, $i ) ); ?>
										<div class="swiper-slide">
											<div class="project-card">
												<a class="project-image-link" href="<?php echo esc_url( $url ); ?>" aria-label="Картка проєкту">
													<div class="project-image">
														<img src="<?php echo esc_url( bgv_img_url( $p['image'], 'large' ) ); ?>" class="project-image" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy">
														<?php if ( ! empty( $p['card_info'] ) ) : ?>
															<div class="project-additional-info">
																<?php foreach ( $p['card_info'] as $ci ) : if ( '' === trim( (string) $ci['text'] ) ) { continue; } ?>
																	<div class="project-end-date"><p class="project-end-date-text"><?php echo esc_html( $ci['text'] ); ?></p></div>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
													</div>
												</a>
												<div class="project-info">
													<h3 class="project-title"><?php echo esc_html( $p['title'] ); ?></h3>
													<div class="project-actions">
														<?php if ( ! empty( $p['website_link'] ) ) : ?>
															<a class="project-link" href="<?php echo esc_url( $p['website_link'] ); ?>" target="_blank" rel="noopener">Перейти на сайт</a>
														<?php endif; ?>
														<a class="project-link project-link--blue cursor" href="<?php echo esc_url( $url ); ?>">Картка проєкту</a>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

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
						<div class="swiper-slide">
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
		<div class="philantropy-side-img" style="background-image:url('<?php echo esc_url( bgv_img_url( bgv_opt( 'social_image' ), 'large' ) ); ?>')"></div>
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
			<form class="contact-form-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="bgv_contact">
				<?php wp_nonce_field( 'bgv_contact', 'bgv_nonce' ); ?>
				<input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
				<input class="contact-form-input" name="name" type="text" placeholder="Ваші ПІБ" required minlength="3">
				<input class="contact-form-input" name="phone" type="tel" placeholder="Ваш номер мобільного" required pattern="[+0-9()\s-]{10,20}">
				<input class="contact-form-input" name="email" type="email" placeholder="Ваша електронна адреса" required>
				<textarea class="contact-form-input contact-form-textarea" name="message" rows="2" placeholder="Опишіть коротко питання, з яким ви бажаєте звернутися до нашої компанії?"></textarea>
				<button class="contact-form-submit project-link project-link--blue" type="submit">Надіслати</button>
			</form>
		</div>
	</div>
</section>
<?php
get_footer();
