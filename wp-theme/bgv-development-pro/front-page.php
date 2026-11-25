<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<?php get_template_part( 'parts/hero' ); ?>


<?php $bgv_stats = bgv_opt( 'stats' ); if ( $bgv_stats ) : ?>
<section class="bgv-stats" id="stats">
	<div class="container">
		<h2 class="vh"><?php bgv_e( 'Цифри' ); ?></h2>
		<ul class="bgv-stats__list">
			<?php foreach ( $bgv_stats as $s ) : ?>
				<li class="bgv-stats__item">
					<p class="bgv-stats__num"><?php echo esc_html( $s['number'] ); ?></p>
					<p class="bgv-stats__label"><?php echo esc_html( $s['label'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<?php $bgv_adv = bgv_opt( 'advantages' ); if ( $bgv_adv ) : ?>
<section class="bgv-adv" id="advantages">
	<div class="container">
		<h2 class="section-title"><?php echo esc_html( bgv_opt( 'adv_title' ) ?: bgv_t( 'Переваги' ) ); ?></h2>
		<ol class="bgv-adv__list">
			<?php foreach ( $bgv_adv as $i => $a ) : if ( empty( $a['title'] ) ) { continue; } ?>
				<li class="bgv-adv__item">
					<span class="bgv-adv__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<p class="bgv-adv__t"><?php echo esc_html( $a['title'] ); ?></p>
					<?php if ( ! empty( $a['text'] ) ) : ?><p class="bgv-adv__d"><?php echo esc_html( $a['text'] ); ?></p><?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php endif; ?>

<section class="about" id="about">
	<div class="container">
		<h2 class="vh"><?php bgv_e( 'Про нас' ); ?></h2>
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

<?php get_template_part( 'parts/projects' ); ?>

<?php get_template_part( 'parts/achievements' ); ?>

<?php get_template_part( 'parts/investors' ); ?>

<section class="news" id="news">
	<div class="container">
		<div class="news-header">
			<h2 class="section-title"><?php echo esc_html( bgv_opt( 'news_title' ) ); ?></h2>
			<?php bgv_news_chips( true ); ?>
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
