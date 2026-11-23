<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_dir   = get_template_directory();
$bgv_email = bgv_opt( 'footer_email' );
$bgv_phone = bgv_opt( 'footer_phone' );
?>
	</main>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-content">
				<div class="footer-logo">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/footer-logo.svg' ); ?>" alt="BGV Development">
					<ul class="social-list">
						<?php if ( bgv_opt( 'facebook' ) ) : ?>
							<li><a href="<?php echo esc_url( bgv_opt( 'facebook' ) ); ?>" target="_blank" rel="noopener" aria-label="Facebook"><?php echo file_get_contents( $bgv_dir . '/assets/icon-facebook.svg' ); // phpcs:ignore ?></a></li>
						<?php endif; ?>
						<?php if ( bgv_opt( 'instagram' ) ) : ?>
							<li><a href="<?php echo esc_url( bgv_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo file_get_contents( $bgv_dir . '/assets/icon-instagram.svg' ); // phpcs:ignore ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="footer-contact">
					<ul class="footer-contact-list contact-list">
						<?php if ( $bgv_email ) : ?>
							<li class="contact-list-item"><a class="contact-list-link" href="mailto:<?php echo esc_attr( $bgv_email ); ?>"><?php echo esc_html( $bgv_email ); ?></a></li>
						<?php endif; ?>
						<?php if ( $bgv_phone ) : ?>
							<li class="contact-list-item"><a class="contact-list-link" href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $bgv_phone ) ); ?>"><?php echo esc_html( $bgv_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( bgv_opt( 'footer_address' ) ) : ?>
							<li class="contact-list-item"><p class="contact-list-link"><?php echo nl2br( esc_html( bgv_opt( 'footer_address' ) ) ); ?></p></li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="footer-menu main-navigation">
					<?php bgv_menu( 'footer-menu' ); ?>
				</div>
			</div>
			<div class="copy-right"><?php echo esc_html( bgv_opt( 'copyright' ) ); ?></div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
