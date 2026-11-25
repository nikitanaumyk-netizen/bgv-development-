/* BGV Development — інтерактив (меню, фільтри, квіз, форми, карта). */
(function () {
	'use strict';
	var $ = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
	var html = document.documentElement;

	/* ---- Шапка: тінь при прокрутці, бургер, підменю ---- */
	var header = $('.bgv-header');
	var onScroll = function () { if (header) { header.classList.toggle('is-scrolled', window.scrollY > 10); } };
	window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
	var burger = $('.bgv-burger');
	if (burger) {
		burger.addEventListener('click', function () {
			var open = !html.classList.contains('nav-open');
			html.classList.toggle('nav-open', open);
			burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
	$$('.bgv-nav .sub-toggle').forEach(function (b) {
		b.addEventListener('click', function () { b.parentNode.classList.toggle('sub-open'); });
	});
	$$('.bgv-nav a').forEach(function (a) {
		a.addEventListener('click', function () {
			if (a.getAttribute('href') && a.getAttribute('href').indexOf('#') !== -1 && !a.hasAttribute('data-bgv-pres')) { html.classList.remove('nav-open'); }
		});
	});

	/* ---- Фільтри-чипи ---- */
	function applyFilter(wrap, cat) {
		var target = $(wrap.getAttribute('data-filter'));
		if (!target) { return false; }
		$$('.bgv-chip', wrap).forEach(function (c) { c.classList.toggle('is-active', c.getAttribute('data-cat') === cat); });
		$$('[data-cat]', target).forEach(function (el) { el.hidden = !(cat === 'all' || el.getAttribute('data-cat') === cat); });
		return true;
	}
	$$('.bgv-chips[data-filter]').forEach(function (wrap) {
		wrap.addEventListener('click', function (e) {
			var c = e.target.closest('.bgv-chip');
			if (!c) { return; }
			if (applyFilter(wrap, c.getAttribute('data-cat'))) {
				e.preventDefault();
				if (wrap.closest('#our-projects') && history.replaceState) {
					var u = new URL(location.href);
					if (c.getAttribute('data-cat') === 'all') { u.searchParams.delete('cat'); } else { u.searchParams.set('cat', c.getAttribute('data-cat')); }
					history.replaceState(null, '', u.pathname + u.search + '#our-projects');
				}
			}
		});
	});
	// Пункти меню «Проєкти → категорія» на головній фільтрують без перезавантаження.
	$$('[data-bgv-cat]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			var wrap = $('#our-projects .bgv-chips');
			if (!wrap) { return; }
			e.preventDefault();
			applyFilter(wrap, a.getAttribute('data-bgv-cat'));
			html.classList.remove('nav-open');
			$('#our-projects').scrollIntoView({ behavior: 'smooth' });
		});
	});

	/* ---- Модальні вікна ---- */
	var lastFocus = null;
	function openModal(id, opts) {
		var m = document.getElementById(id);
		if (!m) { return; }
		opts = opts || {};
		lastFocus = document.activeElement;
		var form = $('form', m);
		if (form) {
			form.classList.remove('is-done');
			var msg = $('.bgv-form-msg', form); if (msg) { msg.textContent = ''; msg.className = 'bgv-form-msg'; }
			if (form.elements.type && opts.type) { form.elements.type.value = opts.type; }
			else if (form.elements.type && id === 'bgv-form') { form.elements.type.value = 'callback'; }
			if (form.elements.object) { form.elements.object.value = opts.object || ''; }
			if (form.elements.file) { form.elements.file.value = opts.file || 'company'; }
			if (form.elements.message) {
				var ta = form.elements.message, ph = ta.getAttribute(opts.type === 'partner' ? 'data-ph-partner' : 'data-ph');
				if (ph) { ta.placeholder = ph; }
			}
		}
		var t = $('.bgv-modal__title', m);
		if (t && t.hasAttribute('data-default')) { t.textContent = opts.title || t.getAttribute('data-default'); }
		if (id === 'bgv-quiz') { quizGo(1); }
		m.hidden = false;
		html.classList.add('modal-open');
		setTimeout(function () { var f = $('input:not([type=hidden]):not(.bgv-hp), button.bgv-opt', m); if (f) { f.focus(); } }, 30);
	}
	function closeModal(m) {
		m.hidden = true;
		html.classList.remove('modal-open');
		if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
	}
	document.addEventListener('click', function (e) {
		var o = e.target.closest('[data-bgv-open]');
		if (o) {
			e.preventDefault();
			html.classList.remove('nav-open');
			fab && fab.classList.remove('is-open');
			openModal(o.getAttribute('data-bgv-open'), { type: o.getAttribute('data-bgv-type'), title: o.getAttribute('data-bgv-title'), object: o.getAttribute('data-bgv-object') });
			return;
		}
		var p = e.target.closest('[data-bgv-pres]');
		if (p) {
			e.preventDefault();
			html.classList.remove('nav-open');
			openModal('bgv-pres', { file: p.getAttribute('data-bgv-pres'), object: p.getAttribute('data-bgv-title') || 'Презентація компанії' });
			return;
		}
		if (e.target.closest('[data-bgv-close]') || e.target.classList.contains('bgv-modal')) {
			var m = e.target.closest('.bgv-modal'); if (m) { closeModal(m); }
		}
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			$$('.bgv-modal').forEach(function (m) { if (!m.hidden) { closeModal(m); } });
			html.classList.remove('nav-open');
		}
	});

	/* ---- Квіз 3 кроки ---- */
	var quiz = $('.bgv-quiz'), step = 1;
	function quizGo(n) {
		if (!quiz) { return; }
		step = n;
		$$('.bgv-step', quiz).forEach(function (s) { s.classList.toggle('is-active', +s.getAttribute('data-step') === n); });
		quiz.setAttribute('data-step', n);
		var bar = $('.bgv-quiz__bar span', quiz); if (bar) { bar.style.width = (n / 3 * 100) + '%'; }
	}
	if (quiz) {
		quiz.classList.add('js');
		quizGo(1);
		$('[data-bgv-next]', quiz).addEventListener('click', function () {
			var cur = $('.bgv-step.is-active', quiz);
			if (!$('input:checked', cur)) { cur.classList.add('need'); return; }
			cur.classList.remove('need');
			quizGo(Math.min(3, step + 1));
		});
		$('[data-bgv-prev]', quiz).addEventListener('click', function () { quizGo(Math.max(1, step - 1)); });
		$$('.bgv-step[data-step="1"] input, .bgv-step[data-step="2"] input', quiz).forEach(function (i) {
			i.addEventListener('change', function () { setTimeout(function () { quizGo(Math.min(3, step + 1)); }, 180); });
		});
	}

	/* ---- Відправка форм без перезавантаження ---- */
	$$('form.bgv-ajax').forEach(function (form) {
		form.addEventListener('submit', function (e) {
			if (!window.fetch || !window.FormData) { return; }
			e.preventDefault();
			var msg = $('.bgv-form-msg', form), btn = $('[type=submit]', form);
			var fd = new FormData(form); fd.append('ajax', '1');
			btn && (btn.disabled = true);
			var win = form.elements.type && form.elements.type.value === 'presentation' ? window.open('', '_blank') : null;
			fetch(form.getAttribute('action'), { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (d) {
					if (!d || !d.ok) { throw new Error('fail'); }
					form.reset(); form.classList.add('is-done');
					if (msg) { msg.textContent = BGV.ok; msg.className = 'bgv-form-msg is-ok'; }
					if (d.file) { if (win) { win.location = d.file; } else { location.href = d.file; } }
					else if (win) { win.close(); }
					if (window.dataLayer) { window.dataLayer.push({ event: 'bgv_lead', lead_type: fd.get('type') }); }
				})
				.catch(function () {
					if (win) { win.close(); }
					if (msg) { msg.textContent = BGV.err; msg.className = 'bgv-form-msg is-err'; }
				})
				.then(function () { btn && (btn.disabled = false); if (quiz && form === quiz) { setTimeout(function () { quizGo(1); }, 50); } });
		});
	});

	/* ---- Плаваюча кнопка ---- */
	var fab = $('#bgv-fab');
	if (fab) {
		var fb = $('.bgv-fab__btn', fab);
		fb.addEventListener('click', function () {
			var o = !fab.classList.contains('is-open');
			fab.classList.toggle('is-open', o);
			fb.setAttribute('aria-expanded', o ? 'true' : 'false');
		});
	}

	/* ---- Перший екран: слайди ---- */
	var hero = $('.bgv-hero');
	if (hero) {
		var slides = $$('.bgv-hero__slide', hero), dots = $$('.bgv-hero__dots button', hero), cap = $('.bgv-hero__cap', hero), cur = 0, timer;
		var show = function (n) {
			if (!slides.length) { return; }
			cur = (n + slides.length) % slides.length;
			slides.forEach(function (s, i) { s.classList.toggle('is-active', i === cur); });
			dots.forEach(function (d, i) { d.classList.toggle('is-active', i === cur); });
			if (cap) { $('span', cap).textContent = slides[cur].getAttribute('data-cap'); cap.setAttribute('href', slides[cur].getAttribute('data-link') || '#our-projects'); }
		};
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var start = function () { clearInterval(timer); if (!reduce && slides.length > 1) { timer = setInterval(function () { show(cur + 1); }, 6000); } };
		dots.forEach(function (d, i) { d.addEventListener('click', function () { show(i); start(); }); });
		start();
	}
})();
