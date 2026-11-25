/* Заставка і перший екран — ідентично референсу (GSAP). */
(function () {
	'use strict';
	var preloader = document.getElementById('preloader');
	if (!preloader) { return; }
	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var html = document.documentElement;

	/* Фолбек без GSAP: все видно, прелоадер іде */
	if (typeof window.gsap === 'undefined') {
		setTimeout(function () {
			preloader.classList.add('is-hidden');
			setTimeout(function () { preloader.style.display = 'none'; }, 950);
		}, 1200);
		return;
	}

	/* ---------- ПРЕЛОАДЕР + вхід першого екрана ---------- */
	var reveals = gsap.utils.toArray('.bgv-ihero-reveal');
	var indicator = document.getElementById('bgv-scroll-ind');
	gsap.set(reveals, { y: 60, opacity: 0 });
	if (indicator) { gsap.set(indicator, { opacity: 0 }); }
	html.classList.add('bgv-locked');

	var intro = gsap.timeline();
	intro
		.fromTo('#preloader-logo',
			{ opacity: 0, scale: 0.82 },
			{ opacity: 1, scale: 1, duration: reduceMotion ? 0.2 : 0.9, ease: 'power3.out' })
		.to(preloader, {
			yPercent: -100,
			duration: reduceMotion ? 0.3 : 0.9,
			ease: 'power4.inOut',
			delay: reduceMotion ? 0.2 : 1.1
		})
		.add(function () {
			preloader.style.display = 'none';
			html.classList.remove('bgv-locked');
		})
		.to(reveals, {
			y: 0,
			opacity: 1,
			duration: 1,
			ease: 'power3.out',
			stagger: 0.3
		}, '-=0.35')
		.to(indicator, { opacity: 1, duration: 0.6 }, '-=0.3');

	if (!reduceMotion) {
		/* ---------- Діагональні фігури: повільний нескінченний рух ---------- */
		gsap.to('#bgv-shape-1', { x: 60, y: 40, rotation: 6, transformOrigin: '50% 50%', duration: 16, ease: 'sine.inOut', yoyo: true, repeat: -1 });
		gsap.to('#bgv-shape-2', { x: -50, y: 60, rotation: -10, transformOrigin: '50% 50%', duration: 19, ease: 'sine.inOut', yoyo: true, repeat: -1 });
		gsap.to('#bgv-shape-3', { x: -70, y: -40, rotation: 5, transformOrigin: '50% 50%', duration: 22, ease: 'sine.inOut', yoyo: true, repeat: -1 });

		/* Індикатор прокрутки: точка рухається вниз */
		gsap.fromTo('#bgv-scroll-dot',
			{ y: 0, opacity: 0 },
			{ keyframes: [
				{ opacity: 1, duration: 0.3 },
				{ y: 53, duration: 1.2, ease: 'power1.inOut' },
				{ opacity: 0, duration: 0.3 }
			], repeat: -1, repeatDelay: 0.3 });
	}
})();
