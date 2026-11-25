/* Мобільне меню — хореографія як на bgv-development-site610v4.vercel.app (src/menu.js):
   шторка → логотип у центрі → логотип летить у куток (FLIP) → пункти проростають по черзі → кнопки. */
(function () {
	'use strict';
	var mnav = document.getElementById('mnav');
	if (!mnav) { return; }
	var timers = [];
	var clear = function () { while (timers.length) { clearTimeout(timers.pop()); } };
	var later = function (fn, t) { timers.push(setTimeout(fn, t)); };
	var reduced = function () { return window.matchMedia('(prefers-reduced-motion: reduce)').matches; };
	var html = document.documentElement;
	function ms(name, fb) {
		var v = getComputedStyle(mnav).getPropertyValue(name).trim(), n = parseFloat(v);
		if (!isFinite(n)) { return fb; }
		return /ms$/.test(v) ? n : /s$/.test(v) ? n * 1000 : n;
	}
	function measure() {
		var from = mnav.querySelector('.mnav__intro-logo'), to = mnav.querySelector('.mnav__top .mlogo');
		if (!from || !to) { return; }
		var a = from.getBoundingClientRect(), b = to.getBoundingClientRect();
		from.style.setProperty('--mx', ((b.left + b.width / 2) - (a.left + a.width / 2)).toFixed(1) + 'px');
		from.style.setProperty('--my', ((b.top + b.height / 2) - (a.top + a.height / 2)).toFixed(1) + 'px');
		from.style.setProperty('--ms', (b.width / (from.offsetWidth || a.width)).toFixed(4));
	}
	function toMorph() {
		if (mnav.dataset.phase !== 'intro') { return; }
		clear(); measure();
		mnav.dataset.phase = 'morph';
		var n = mnav.querySelectorAll('.mnav__row').length;
		var itemsEnd = ms('--mn-items-at', 260) + n * ms('--mn-stagger', 70) + ms('--mn-item', 900);
		var footEnd = ms('--mn-items-at', 260) + n * ms('--mn-stagger', 70) + ms('--mn-foot-gap', 380) + 960;
		later(function () { if (mnav.dataset.phase === 'morph') { mnav.dataset.phase = 'open'; } }, Math.max(itemsEnd, footEnd, ms('--mn-morph', 760)) + 60);
	}
	function open() {
		clear();
		mnav.style.setProperty('--mn-count', mnav.querySelectorAll('.mnav__row').length);
		mnav.scrollTop = 0;
		mnav.querySelectorAll('details[open]').forEach(function (d) { d.removeAttribute('open'); });
		var logo = mnav.querySelector('.mnav__intro-logo');
		if (logo) { ['--mx', '--my', '--ms'].forEach(function (p) { logo.style.removeProperty(p); }); }
		mnav.setAttribute('aria-hidden', 'false');
		html.classList.add('mnav-open');
		if (reduced()) { mnav.classList.add('is-open'); mnav.dataset.phase = 'open'; return; }
		mnav.dataset.phase = 'closed';
		void mnav.offsetWidth;
		mnav.classList.add('is-open');
		mnav.dataset.phase = 'intro';
		later(toMorph, ms('--mn-logo-delay', 200) + ms('--mn-logo-in', 550) + ms('--mn-hold', 600));
	}
	function close() {
		if (mnav.dataset.phase === 'closed') { return; }
		clear();
		html.classList.remove('mnav-open');
		mnav.setAttribute('aria-hidden', 'true');
		if (reduced()) { mnav.classList.remove('is-open'); mnav.dataset.phase = 'closed'; return; }
		mnav.dataset.phase = 'closing';
		mnav.classList.remove('is-open');
		later(function () { mnav.dataset.phase = 'closed'; }, 720);
	}
	mnav.querySelector('.mnav__intro').addEventListener('click', toMorph);
	mnav.querySelector('[data-mnav-close]').addEventListener('click', close);
	mnav.addEventListener('click', function (e) {
		var a = e.target.closest('a, [data-bgv-open], [data-bgv-pres]');
		if (a && !a.closest('summary')) { setTimeout(close, 10); }
	});
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { close(); } });
	window.addEventListener('resize', function () { if (window.innerWidth > 1180) { close(); } });
	window.bgvMenu = { open: open, close: close };
})();
