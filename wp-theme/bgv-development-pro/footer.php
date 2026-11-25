<?php if ( ! defined( 'ABSPATH' ) ) { exit; }
$bgv_dir   = get_template_directory();
$bgv_email = bgv_opt( 'footer_email' );
$bgv_phone = bgv_opt( 'footer_phone' );
$bgv_soc   = array(
	'facebook'  => array( 'Facebook', '<path d="M14 8h2.5V5H14c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h2.5l.5-3h-3V9c0-.6.4-1 1-1Z"/>' ),
	'instagram' => array( 'Instagram', '<path d="M12 8.2a3.8 3.8 0 1 0 0 7.6 3.8 3.8 0 0 0 0-7.6Zm0 6.2a2.4 2.4 0 1 1 0-4.8 2.4 2.4 0 0 1 0 4.8Zm4-7.3a.9.9 0 1 0 0 1.8.9.9 0 0 0 0-1.8ZM16.2 4H7.8A3.8 3.8 0 0 0 4 7.8v8.4A3.8 3.8 0 0 0 7.8 20h8.4a3.8 3.8 0 0 0 3.8-3.8V7.8A3.8 3.8 0 0 0 16.2 4Zm2.4 12.2a2.4 2.4 0 0 1-2.4 2.4H7.8a2.4 2.4 0 0 1-2.4-2.4V7.8a2.4 2.4 0 0 1 2.4-2.4h8.4a2.4 2.4 0 0 1 2.4 2.4v8.4Z"/>' ),
	'linkedin'  => array( 'LinkedIn', '<path d="M6.9 8.6H4V20h2.9V8.6ZM5.4 4a1.7 1.7 0 1 0 0 3.4 1.7 1.7 0 0 0 0-3.4ZM20 13.4c0-3-1.6-4.9-4.2-4.9-1.4 0-2.4.8-2.8 1.5V8.6h-2.9V20H13v-5.9c0-1.5.6-2.7 2.1-2.7s1.9 1.2 1.9 2.8V20H20v-6.6Z"/>' ),
	'youtube'   => array( 'YouTube', '<path d="M21 8.2a2.6 2.6 0 0 0-1.8-1.8C17.6 6 12 6 12 6s-5.6 0-7.2.4A2.6 2.6 0 0 0 3 8.2 27 27 0 0 0 2.6 12c0 1.3.1 2.6.4 3.8.2.9.9 1.6 1.8 1.8C6.4 18 12 18 12 18s5.6 0 7.2-.4c.9-.2 1.6-.9 1.8-1.8.3-1.2.4-2.5.4-3.8s-.1-2.6-.4-3.8ZM10.2 14.6V9.4l4.6 2.6-4.6 2.6Z"/>' ),
	'telegram'  => array( 'Telegram', '<path d="M20.6 4.4 3.4 11c-1.2.5-1.1 1.2-.2 1.5l4.4 1.4 1.7 5.2c.2.6.4.8.8.8s.6-.2.9-.5l2.2-2.1 4.5 3.3c.8.5 1.4.2 1.6-.8l2.9-13.7c.3-1.2-.5-1.8-1.6-1.3Zm-3 3.2-7.9 7.1-.3 3.3-1.5-4.6 9.4-5.9c.4-.3.7.1.3.1Z"/>' ),
	'tiktok'    => array( 'TikTok', '<path d="M16.6 5.8A4 4 0 0 1 15.6 3h-3v12.2a2.5 2.5 0 1 1-1.8-2.4V9.7a5.6 5.6 0 1 0 4.8 5.5V9a7 7 0 0 0 4.1 1.3V7.3a4 4 0 0 1-3.1-1.5Z"/>' ),
);
$bgv_legal = array();
foreach ( array( 'privacy_page' => 'Політика конфіденційності', 'terms_page' => 'Умови користування' ) as $k => $l ) {
	$pid = (int) bgv_opt( $k );
	if ( $pid && 'publish' === get_post_status( $pid ) ) { $bgv_legal[] = array( get_permalink( $pid ), bgv_t( $l ) ); }
}
$bgv_copy = bgv_opt( 'copyright' );
?>
	</main>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-content">
				<div class="footer-logo">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/footer-logo.svg' ); ?>" alt="BGV Development">
					<ul class="social-list bgv-social">
						<?php foreach ( $bgv_soc as $k => $s ) : if ( ! bgv_opt( $k ) ) { continue; } ?>
							<li><a href="<?php echo esc_url( bgv_opt( $k ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s[0] ); ?>"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><?php echo $s[1]; // phpcs:ignore ?></svg></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="footer-contact">
					<ul class="footer-contact-list contact-list">
						<?php if ( $bgv_email ) : ?>
							<li class="contact-list-item"><a class="contact-list-link" href="mailto:<?php echo esc_attr( $bgv_email ); ?>"><?php echo esc_html( $bgv_email ); ?></a></li>
						<?php endif; ?>
						<?php if ( $bgv_phone ) : ?>
							<li class="contact-list-item"><a class="contact-list-link" href="<?php echo esc_attr( bgv_tel( $bgv_phone ) ); ?>"><?php echo esc_html( $bgv_phone ); ?></a></li>
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
			<div class="copy-right bgv-copy">
				<span><?php echo esc_html( $bgv_copy ? $bgv_copy : '© ' . gmdate( 'Y' ) . ' BGV Development. ' . bgv_t( 'Усі права захищено.' ) ); ?></span>
				<?php if ( $bgv_legal ) : ?>
					<span class="bgv-legal"><?php foreach ( $bgv_legal as $l ) : ?><a href="<?php echo esc_url( $l[0] ); ?>"><?php echo esc_html( $l[1] ); ?></a><?php endforeach; ?></span>
				<?php endif; ?>
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
