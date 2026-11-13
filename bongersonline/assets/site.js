(function () {
	// Live filter op het handleidingen-overzicht
	var input = document.querySelector('[data-guide-filter]');
	var list = document.querySelector('[data-guide-list]');
	var empty = document.querySelector('[data-guide-empty]');
	if (input && list) {
		input.addEventListener('input', function () {
			var q = input.value.trim().toLowerCase();
			var shown = 0;
			list.querySelectorAll('[data-search]').forEach(function (el) {
				var hit = !q || el.getAttribute('data-search').indexOf(q) !== -1;
				el.classList.toggle('d-none', !hit);
				if (hit) shown++;
			});
			if (empty) empty.classList.toggle('d-none', shown > 0);
		});
	}

	// Lightbox voor projectfoto's
	var lb = document.getElementById('projectLightbox');
	if (lb) {
		var items = lb.querySelectorAll('.carousel-item');
		document.querySelectorAll('[data-bo-lightbox]').forEach(function (a) {
			a.addEventListener('click', function (e) {
				if (!window.bootstrap) return;
				e.preventDefault();
				var i = parseInt(a.getAttribute('data-bo-lightbox'), 10) || 0;
				items.forEach(function (it, n) { it.classList.toggle('active', n === i); });
				window.bootstrap.Modal.getOrCreateInstance(lb).show();
			});
		});
	}

	// Actieve sectie in de inhoudsopgave markeren
	var links = document.querySelectorAll('.toc a[href^="#"]');
	if (links.length && 'IntersectionObserver' in window) {
		var map = {};
		links.forEach(function (a) { map[decodeURIComponent(a.getAttribute('href').slice(1))] = a; });
		var obs = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting && map[e.target.id]) {
					links.forEach(function (l) { l.classList.remove('active'); });
					map[e.target.id].classList.add('active');
				}
			});
		}, { rootMargin: '-90px 0px -70% 0px' });
		Object.keys(map).forEach(function (id) {
			var h = document.getElementById(id);
			if (h) obs.observe(h);
		});
	}
})();
