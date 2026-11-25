/* Перший екран — вступна анімація як на bgv-development-site610v4.vercel.app:
   дві скошені шторки (темна й синя) їдуть вправо → лінія і слоган → заголовок по літерах із розмиття →
   текст по літерах → кнопки → панель проєкту виїжджає справа → меню і індикатор прокрутки. */
(function () {
	'use strict';
	var hero = document.querySelector('.bgv-hero');
	if (!hero) { return; }
	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	document.body.classList.add('has-hero');

	/* ---------- розбивка тексту на літери (рядок за рядком, зліва направо) ---------- */
	var TX = {
		title: { char: 30, line: 180, span: 760, dur: 1100 },
		hero: { char: 12, line: 110, span: 560, dur: 900 }
	};
	var esc = function (v) { return v.replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };
	function split(el, kind) {
		if (!el || el.children.length) { return null; }
		var html = el.innerHTML, words = el.textContent.split(/\s+/).filter(Boolean);
		if (!words.length) { return null; }
		var plain = words.join(' ');
		el.innerHTML = '<span class="tx-sr">' + esc(plain) + '</span><span class="tx" aria-hidden="true">' + words.map(function (w) {
			return '<span class="tw">' + Array.from(w).map(function (c) { return '<span class="tc">' + esc(c) + '</span>'; }).join('') + '</span>';
		}).join(' ') + '</span>';
		var cfg = TX[kind], lines = [], top = null, max = 0;
		el.querySelectorAll('.tw').forEach(function (w) {
			var y = w.offsetTop;
			if (top === null || Math.abs(y - top) > 2) { lines.push([]); top = y; }
			lines[lines.length - 1].push.apply(lines[lines.length - 1], w.children);
		});
		lines.forEach(function (chars, k) {
			var step = Math.min(cfg.char, cfg.span / Math.max(1, chars.length - 1));
			chars.forEach(function (c, n) {
				var t = Math.round(k * cfg.line + n * step);
				c.style.setProperty('--cd', t + 'ms');
				if (t > max) { max = t; }
			});
		});
		return { html: html, max: max, dur: cfg.dur };
	}

	var title = hero.querySelector('.bgv-hero__title');
	var sub = hero.querySelector('.bgv-hero__desc, .bgv-hero__lead');
	var tTitle = null, tSub = null;
	if (!reduced) {
		tTitle = split(title, 'title'); if (tTitle) { title.classList.add('tx-on'); }
		tSub = split(sub, 'hero'); if (tSub) { sub.classList.add('tx-hero'); }
	}
	// після появи повертаємо звичайний текст
	var revert = function (el, info, base) {
		if (!el || !info) { return; }
		setTimeout(function () { el.innerHTML = info.html; el.classList.remove('tx-on', 'tx-hero'); }, base + info.max + info.dur + 150);
	};

	/* ---------- старт: чекаємо перше фото (не довше 1,2 с) ---------- */
	var started = false;
	var go = function () {
		if (started) { return; }
		started = true;
		requestAnimationFrame(function () {
			hero.classList.add('is-played');
			document.body.classList.add('hero-in');
			revert(title, tTitle, 860);
			revert(sub, tSub, 1220);
		});
	};
	if (reduced) { hero.classList.add('is-quick'); document.body.classList.add('intro-quick'); go(); return; }
	var img = hero.querySelector('.bgv-hero__slide.is-active img');
	if (!img || (img.complete && img.naturalWidth)) { go(); }
	else {
		img.addEventListener('load', go, { once: true });
		img.addEventListener('error', function () { setTimeout(go, 300); }, { once: true });
	}
	setTimeout(go, 1200);
})();
