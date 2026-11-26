/* BGV Development — інтерактив (меню, фільтри, квіз, форми, карта). */
(function () {
	'use strict';
	var $ = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
	var html = document.documentElement;

	/* ---- Завжди починати з першого екрана при відкритті та оновленні сторінки ----
	 * Виняток: перехід з іншої сторінки сайту за посиланням меню на розділ (напр. «Контакти»). */
	(function () {
		var nav = (performance.getEntriesByType && performance.getEntriesByType('navigation')[0]) || {};
		var type = nav.type || (performance.navigation && performance.navigation.type === 1 ? 'reload' : 'navigate');
		var fromSite = document.referrer && document.referrer.indexOf(location.host) !== -1;
		var keepHash = location.hash && type === 'navigate' && fromSite;
		if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }
		if (keepHash) {
			// Докрутити до розділу після завантаження фото (висота сторінки змінюється).
			var go = function () { var t = document.getElementById(decodeURIComponent(location.hash.slice(1))); if (t) { t.scrollIntoView(); } };
			window.addEventListener('load', function () { go(); setTimeout(go, 400); });
			return;
		}
		if (location.hash && history.replaceState) { history.replaceState(null, '', location.pathname + location.search); }
		var top = function () { window.scrollTo(0, 0); };
		top();
		window.addEventListener('load', top);
		window.addEventListener('pageshow', function (e) { if (e.persisted) { top(); } });
	})();

	/* ---- Шапка: тінь при прокрутці, бургер, підменю ---- */
	var header = $('.bgv-header');
	var heroEl = document.querySelector('.bgv-hero');
	var onScroll = function () { if (header) { header.classList.toggle('is-scrolled', window.scrollY > 10); } html.classList.toggle('past-hero', !heroEl || window.scrollY > heroEl.offsetHeight * 0.6); };
	window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
	var burger = $('.bgv-burger');
	if (burger) {
		burger.addEventListener('click', function () {
			if (window.bgvMenu) { window.bgvMenu.open(); return; }
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
		// Закрити меню кнопки натисканням будь-де поза нею або клавішею Esc.
		var fabClose = function () { fab.classList.remove('is-open'); fb.setAttribute('aria-expanded', 'false'); };
		document.addEventListener('pointerdown', function (e) {
			if (fab.classList.contains('is-open') && !fab.contains(e.target)) { fabClose(); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && fab.classList.contains('is-open')) { fabClose(); }
		});
	}

	/* ---- Перший екран: слайди ---- */
	var hero = $('.bgv-hero');
	if (hero) {
		var slides = $$('.bgv-hero__slide', hero), tabs = $$('.bgv-hero__tab', hero), cur = 0, timer;
		var nameEl = $('.bgv-hero__name', hero), metaEl = $('.bgv-hero__meta span', hero), openEl = $('.bgv-hero__open', hero), countEl = $('.bgv-hero__count b', hero), prog = $('.bgv-hero__progress span', hero);
		var DUR = 7000;
		var pad = function (n) { return (n < 10 ? '0' : '') + n; };
		var show = function (n) {
			if (!slides.length) { return; }
			cur = (n + slides.length) % slides.length;
			slides.forEach(function (s, i) { s.classList.toggle('is-active', i === cur); });
			tabs.forEach(function (t, i) { t.classList.toggle('is-active', i === cur); t.setAttribute('aria-selected', i === cur ? 'true' : 'false'); });
			var t = tabs[cur];
			if (t) {
				if (nameEl) { nameEl.textContent = t.getAttribute('data-cap'); }
				if (metaEl) { metaEl.textContent = t.getAttribute('data-meta'); }
				if (openEl) { openEl.setAttribute('href', t.getAttribute('data-link') || '#our-projects'); }
			}
			if (countEl) { countEl.textContent = pad(cur + 1); }
			if (prog) { prog.style.transition = 'none'; prog.style.width = '0'; void prog.offsetWidth; prog.style.transition = 'width ' + DUR + 'ms linear'; prog.style.width = '100%'; }
		};
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var start = function () { clearInterval(timer); if (!reduce && slides.length > 1) { timer = setInterval(function () { show(cur + 1); }, DUR); } };
		tabs.forEach(function (t, i) { t.addEventListener('click', function () { show(i); start(); }); });
		$$('.bgv-hero__arrow', hero).forEach(function (a) { a.addEventListener('click', function () { show(cur + (+a.getAttribute('data-dir'))); start(); }); });
		var hx = null;
		hero.addEventListener('touchstart', function (e) { hx = e.touches[0].clientX; }, { passive: true });
		hero.addEventListener('touchend', function (e) { if (hx === null) { return; } var dx = e.changedTouches[0].clientX - hx; if (Math.abs(dx) > 50) { show(cur + (dx < 0 ? 1 : -1)); start(); } hx = null; });
		if (slides.length > 1) { show(0); }
		start();
	}

	/* ---- Слайдер «Про нас» ---- */
	$$('[data-aslider]').forEach(function (box) {
		var sl = $$('.bgv-aslide', box), cap = $('.bgv-aslider__cap', box), cnt = $('.bgv-aslider__count b', box), prog = $('.bgv-aslider__progress span', box), i = 0, t, DUR = 5000;
		if (sl.length < 2) { return; }
		var pad = function (n) { return (n < 10 ? '0' : '') + n; };
		var go = function (n) {
			i = (n + sl.length) % sl.length;
			sl.forEach(function (s, k) { s.classList.toggle('is-active', k === i); });
			if (cap) { cap.textContent = sl[i].getAttribute('data-cap'); cap.setAttribute('href', sl[i].getAttribute('data-link') || '#our-projects'); }
			if (cnt) { cnt.textContent = pad(i + 1); }
			if (prog) { prog.style.transition = 'none'; prog.style.width = '0'; void prog.offsetWidth; prog.style.transition = 'width ' + DUR + 'ms linear'; prog.style.width = '100%'; }
		};
		var run = function () { clearInterval(t); t = setInterval(function () { go(i + 1); }, DUR); };
		$$('.bgv-aslider__arrow', box).forEach(function (a) { a.addEventListener('click', function () { go(i + (+a.getAttribute('data-dir'))); run(); }); });
		var x0 = null;
		box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		box.addEventListener('touchend', function (e) { if (x0 === null) { return; } var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) { go(i + (dx < 0 ? 1 : -1)); run(); } x0 = null; });
		go(0);
		if ('IntersectionObserver' in window) {
			var vio = new IntersectionObserver(function (en) { en.forEach(function (x) { if (x.isIntersecting) { go(0); run(); vio.disconnect(); } }); }, { threshold: .35 });
			vio.observe(box);
		} else { run(); }
	});

	/* ---- Слайдер «Соціальна відповідальність» ---- */
	$$('[data-slider]').forEach(function (box) {
		var sl = $$('.bgv-soc-slide', box), dt = $$('.bgv-soc-dots button', box), i = 0, t;
		if (sl.length < 2) { return; }
		var go = function (n) {
			i = (n + sl.length) % sl.length;
			sl.forEach(function (s, k) { s.classList.toggle('is-active', k === i); });
			dt.forEach(function (d, k) { d.classList.toggle('is-active', k === i); });
		};
		var run = function () { clearInterval(t); t = setInterval(function () { go(i + 1); }, 5000); };
		dt.forEach(function (d, k) { d.addEventListener('click', function () { go(k); run(); }); });
		$$('.bgv-soc-arrow', box).forEach(function (a) { a.addEventListener('click', function () { go(i + (+a.getAttribute('data-dir'))); run(); }); });
		var x0 = null;
		box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		box.addEventListener('touchend', function (e) { if (x0 === null) { return; } var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) { go(i + (dx < 0 ? 1 : -1)); run(); } x0 = null; });
		run();
	});

	/* ---- Анімації: поява тексту та «набігання» цифр ---- */
	var still = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	/* «Досягнення» і «Нагороди»: поява як на першому екрані — шторки, заголовок по літерах, картки по черзі */
	var showSecs = $$('#advantages, #achievements, #colophon');
	if (!still && 'IntersectionObserver' in window) {
		showSecs.forEach(function (sec) {
			sec.classList.add('bgv-show');
			var w = document.createElement('div'); w.className = 'bgv-show__wipe'; w.setAttribute('aria-hidden', 'true'); w.innerHTML = '<i></i><i></i>';
			sec.insertBefore(w, sec.firstChild);
			var h = sec.querySelector('h2');
			if (h && !h.children.length) {
				var txt = h.textContent.trim(), words = txt.split(/\s+/), k = 0;
				h.setAttribute('aria-label', txt);
				h.innerHTML = words.map(function (wd) {
					return '<span class="tw" aria-hidden="true">' + Array.from(wd).map(function (c) {
						var span = document.createElement('span'); span.className = 'tc'; span.style.setProperty('--cd', Math.min(26 * (k++), 900) + 'ms'); span.textContent = c;
						return span.outerHTML;
					}).join('') + '</span>';
				}).join(' ');
			}
			$$('.bgv-ach2__card, .bgv-aw3__list > li, .bgv-adv2__card, .footer-content > *, .bgv-copy', sec).forEach(function (c, i) { c.style.setProperty('--si', i); });
		});
		var sio = new IntersectionObserver(function (en) {
			en.forEach(function (x) { if (x.isIntersecting) { x.target.classList.add('is-played'); sio.unobserve(x.target); } });
		}, { threshold: .15 });
		showSecs.forEach(function (sec) { sio.observe(sec); });
	}
	/* «Нагороди»: фон із фото, що змінюються (як на першому екрані) */
	['#awards', '#our-projects'].forEach(function (id) {
		var sl = $$(id + ' .bgv-aw3__slide'), i = 0;
		if (sl.length < 2 || still) { return; }
		setInterval(function () {
			sl[i].classList.remove('is-active'); i = (i + 1) % sl.length; sl[i].classList.add('is-active');
		}, 6000);
	});
	/* «Нагороди»: власна поява — рендер виходить із розмиття, заголовок з-під маски, картки влітають у 3D з бліком */
	// Те саме для «Наших проєктів».
	['awards', 'our-projects'].forEach(function (id) {
		var aw = document.getElementById(id);
		if (!aw || still || !('IntersectionObserver' in window)) { return; }
		aw.classList.add('bgv-awx');
		$$('.bgv-aw3__list > li', aw).forEach(function (c, i) { c.style.setProperty('--ai', Math.min(i, 5)); });
		var aio = new IntersectionObserver(function (en) {
			en.forEach(function (x) { if (x.isIntersecting) { aw.classList.add('is-played'); aio.disconnect(); } });
		}, { threshold: .12 });
		aio.observe(aw);
	});
	if (!('IntersectionObserver' in window) || still) { return; }

	// Лічильник: «80 300 м²» → від 0 до 80300 з тим самим форматом і підписом.
	function countUp(el) {
		var src = el.getAttribute('data-count') || el.textContent;
		var m = src.match(/^(\D*)([\d\s .,]*\d)(.*)$/);
		if (!m) { return; }
		var pre = m[1], num = m[2], post = m[3];
		var sep = /\d[\s ]\d/.test(num) ? ' ' : '';
		var target = parseFloat(num.replace(/[\s ]/g, '').replace(',', '.'));
		if (!isFinite(target)) { return; }
		var fmt = function (v) {
			var s = String(Math.round(v));
			return sep ? s.replace(/\B(?=(\d{3})+(?!\d))/g, sep) : s;
		};
		var dur = Math.min(2200, 900 + String(Math.round(target)).length * 250), t0 = null;
		var from = target >= 1 ? 1 : 0;
		el.textContent = pre + fmt(from) + post;
		var step = function (t) {
			if (!t0) { t0 = t; }
			var k = Math.min(1, (t - t0) / dur), e = 1 - Math.pow(1 - k, 3);
			el.textContent = pre + fmt(from + (target - from) * e) + post;
			if (k < 1) { requestAnimationFrame(step); } else { el.textContent = src; }
		};
		requestAnimationFrame(step);
	}
	var counters = $$('.bgv-stats__num, .bgv-ring__txt b[data-count], .bgv-ach2__num');
	// Кількості в картках проєктів і на сторінках об’єктів: «5733 м²», «1000 + квартир», «51 заміська вілла».
	$$('.bgv-card__facts li, .estate-popup__building-feature-text').forEach(function (el) {
		var t = el.textContent.trim();
		if (/\d{1,2}[.\/]\d{1,2}[.\/]\d{2,4}|\d\s*[–—-]\s*\d|кв\.|\b(19|20)\d\d\b/i.test(t)) { return; } // дати, діапазони, роки
		// «5733 м²» або «Площа забудови: 5733 м2»
		var m = t.match(/^([^\d:]*:\s*)?([\d\s\u00a0]*\d)(\s*(?:м2|м²|m2)?[^\d]*)$/);
		if (!m) { return; }
		var span = document.createElement('span');
		span.className = 'bgv-num';
		span.textContent = m[2];
		el.textContent = '';
		if (m[1]) { el.appendChild(document.createTextNode(m[1])); }
		el.appendChild(span);
		el.appendChild(document.createTextNode(m[3]));
		counters.push(span);
	});
	var cio = new IntersectionObserver(function (en) {
		en.forEach(function (x) {
			if (!x.isIntersecting) { return; }
			cio.unobserve(x.target);
			countUp(x.target);
			var ring = x.target.closest('.bgv-ring');
			if (ring) { ring.classList.add('is-run'); }
		});
	}, { threshold: .4 });
	counters.forEach(function (c) {
		var ring = c.closest('.bgv-ring'); if (ring) { ring.classList.add('is-wait'); }
		cio.observe(c);
	});

	// Поява тексту і карток під час прокрутки (знизу вгору, по черзі).
	var sel = [
		'main .section-title', 'main .bgv-kicker', '.bgv-sec-head__text', 
		'.bgv-stats__item', '.bgv-adv2__head > *', '.bgv-adv2__card', '.about-text p', '.about-image-wrapp',
		'.bgv-projects__head .bgv-chips', '.bgv-card', '.bgv-ach2__card', '.bgv-aw2__list > li',
		'.bgv-inv__text > *', '.bgv-inv__list li', '.news-content', '.philantropy-item', '.contact-list', '.contact-form-title', '.contact-form-text',
		'.blog-list__item', '.estate-popup__main', '.estate-popup__sidebar'
	].join(',');
	var items = $$(sel).filter(function (el) { return !el.closest('.bgv-modal') && !el.closest('.bgv-header') && !el.closest('.bgv-show'); });
	html.classList.add('bgv-anim');
	var rio = new IntersectionObserver(function (en) {
		en.forEach(function (x) {
			if (!x.isIntersecting) { return; }
			rio.unobserve(x.target);
			var sib = x.target.parentNode ? Array.prototype.indexOf.call(x.target.parentNode.children, x.target) : 0;
			x.target.style.transitionDelay = Math.min(sib, 5) * 90 + 'ms';
			x.target.classList.add('is-in');
		});
	}, { threshold: .12, rootMargin: '0px 0px -6% 0px' });
	items.forEach(function (el) { el.classList.add('bgv-rv'); rio.observe(el); });
	// Страховка: якщо щось не потрапило в поле зору (друк, якорі) — показати через 4 с.
	setTimeout(function () { items.forEach(function (el) { if (el.getBoundingClientRect().top < window.innerHeight) { el.classList.add('is-in'); } }); }, 4000);

	// Фото з’являються плавно зліва направо (окрім першого екрана).
	var photoSel = [
		'.bgv-about__img', '.bgv-adv2__card', '.bgv-card__img', '.bgv-ach2__card', '.bgv-aw3__img', 'div.news-image',
		'.philantropy-side-img', '.blog-list__item-image', '.estate-popup__gallery-main', '.estate-popup__gallery-items > a',
		'.post-page img', '.about-image-wrapp'
	].join(',');
	var photos = $$(photoSel).filter(function (el) { return !el.closest('.bgv-hero') && !el.closest('.bgv-modal') && !el.closest('.bgv-show'); });
	// Спостерігаємо за батьківським блоком: повністю «зрізане» фото браузер вважає невидимим.
	var holders = [];
	var showPhoto = function (el) {
		var sib = el.parentNode ? Array.prototype.indexOf.call(el.parentNode.children, el) : 0;
		var card = el.closest('.bgv-card, .bgv-aw3__list > li, .swiper-slide');
		if (card && card.parentNode) { sib = Array.prototype.indexOf.call(card.parentNode.children, card); }
		el.style.transitionDelay = (Math.min(sib, 5) * 200) + 'ms';
		el.classList.add('is-in');
	};
	var pio = new IntersectionObserver(function (en) {
		en.forEach(function (x) {
			if (!x.isIntersecting) { return; }
			pio.unobserve(x.target);
			(x.target._bgvPhotos || []).forEach(showPhoto);
		});
	}, { threshold: .1, rootMargin: '0px 0px -5% 0px' });
	photos.forEach(function (el) {
		el.classList.add('bgv-ph');
		var h = el.closest('.news-content') || el.parentElement || el; // новини — горизонтальна стрічка
		if (!h._bgvPhotos) { h._bgvPhotos = []; holders.push(h); pio.observe(h); }
		h._bgvPhotos.push(el);
	});
	setTimeout(function () { photos.forEach(function (el) { var r = el.getBoundingClientRect(); if (r.top < window.innerHeight && r.bottom > 0) { el.classList.add('is-in'); } }); }, 4000);
})();
