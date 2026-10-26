( function () {
	'use strict';

	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function debounce( fn, wait ) {
		var t;
		return function () {
			var ctx = this, args = arguments;
			clearTimeout( t );
			t = setTimeout( function () { fn.apply( ctx, args ); }, wait );
		};
	}

	document.addEventListener( 'DOMContentLoaded', function () {

		/* Mobile menu */
		var toggle = document.querySelector( '.ep-menu-toggle' );
		var nav = document.getElementById( 'ep-primary-nav' );
		if ( toggle && nav ) {
			toggle.addEventListener( 'click', function () {
				var open = nav.classList.toggle( 'is-open' );
				toggle.classList.toggle( 'is-active', open );
				toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			} );
		}

		/* Sticky header shadow */
		var header = document.querySelector( '.ep-header' );
		if ( header ) {
			var onScroll = function () { header.classList.toggle( 'is-stuck', window.scrollY > 12 ); };
			onScroll();
			window.addEventListener( 'scroll', onScroll, { passive: true } );
		}

		/* Reveal on scroll */
		var revealables = document.querySelectorAll( '[data-reveal]' );
		if ( revealables.length ) {
			if ( 'IntersectionObserver' in window && ! reduceMotion ) {
				var io = new IntersectionObserver( function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'is-visible' );
							io.unobserve( entry.target );
						}
					} );
				}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' } );
				revealables.forEach( function ( el ) { io.observe( el ); } );
			} else {
				revealables.forEach( function ( el ) { el.classList.add( 'is-visible' ); } );
			}
		}

		/* Animated counters */
		var counters = document.querySelectorAll( '[data-count]' );
		if ( counters.length ) {
			var runCounter = function ( el ) {
				var target = parseFloat( el.getAttribute( 'data-count' ) ) || 0;
				if ( reduceMotion ) { el.textContent = target.toLocaleString(); return; }
				var duration = 1600, start = null;
				var step = function ( ts ) {
					if ( ! start ) { start = ts; }
					var p = Math.min( ( ts - start ) / duration, 1 );
					var eased = 1 - Math.pow( 1 - p, 3 );
					el.textContent = Math.floor( eased * target ).toLocaleString();
					if ( p < 1 ) { requestAnimationFrame( step ); } else { el.textContent = target.toLocaleString(); }
				};
				requestAnimationFrame( step );
			};
			if ( 'IntersectionObserver' in window ) {
				var cio = new IntersectionObserver( function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) { runCounter( entry.target ); cio.unobserve( entry.target ); }
					} );
				}, { threshold: 0.4 } );
				counters.forEach( function ( el ) { cio.observe( el ); } );
			} else {
				counters.forEach( function ( el ) { el.textContent = ( parseFloat( el.getAttribute( 'data-count' ) ) || 0 ).toLocaleString(); } );
			}
		}

		/* Featured property carousel */
		document.querySelectorAll( '[data-carousel]' ).forEach( function ( wrap ) {
			var track = wrap.querySelector( '.ep-carousel' );
			if ( ! track ) { return; }
			var step = function () { return Math.min( track.clientWidth * 0.9, 520 ); };
			var prev = wrap.querySelector( '[data-carousel-prev]' );
			var next = wrap.querySelector( '[data-carousel-next]' );
			if ( prev ) { prev.addEventListener( 'click', function () { track.scrollBy( { left: -step(), behavior: reduceMotion ? 'auto' : 'smooth' } ); } ); }
			if ( next ) { next.addEventListener( 'click', function () { track.scrollBy( { left: step(), behavior: reduceMotion ? 'auto' : 'smooth' } ); } ); }
			track.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'ArrowLeft' ) { e.preventDefault(); track.scrollBy( { left: -step(), behavior: reduceMotion ? 'auto' : 'smooth' } ); }
				if ( e.key === 'ArrowRight' ) { e.preventDefault(); track.scrollBy( { left: step(), behavior: reduceMotion ? 'auto' : 'smooth' } ); }
			} );
		} );

		/* Testimonial slider */
		document.querySelectorAll( '[data-slider]' ).forEach( function ( slider ) {
			var track = slider.querySelector( '[data-slider-track]' );
			if ( ! track || ! track.children.length ) { return; }
			var slides = Array.prototype.slice.call( track.children );
			var prev = slider.querySelector( '[data-slider-prev]' );
			var next = slider.querySelector( '[data-slider-next]' );
			var dotsWrap = slider.querySelector( '[data-slider-dots]' );
			var dots = [], timer = null;

			var stepSize = function () {
				if ( slides.length < 2 ) { return track.clientWidth; }
				return slides[ 1 ].offsetLeft - slides[ 0 ].offsetLeft;
			};
			var maxScroll = function () { return track.scrollWidth - track.clientWidth; };
			var isScrollable = function () { return maxScroll() > 2; };
			var currentIndex = function () {
				var s = stepSize();
				return s > 0 ? Math.round( track.scrollLeft / s ) : 0;
			};

			function buildDots() {
				if ( ! dotsWrap ) { return; }
				dotsWrap.innerHTML = '';
				dots = [];
				slides.forEach( function ( _, i ) {
					var b = document.createElement( 'button' );
					b.type = 'button';
					b.className = 'ep-slider-dot';
					b.setAttribute( 'role', 'tab' );
					b.setAttribute( 'aria-label', 'Go to slide ' + ( i + 1 ) );
					b.addEventListener( 'click', function () {
						track.scrollTo( { left: i * stepSize(), behavior: reduceMotion ? 'auto' : 'smooth' } );
						restart();
					} );
					dotsWrap.appendChild( b );
					dots.push( b );
				} );
			}

			function sync() {
				var scrollable = isScrollable();
				slider.classList.toggle( 'is-single', ! scrollable );
				if ( prev ) { prev.disabled = ! scrollable || track.scrollLeft <= 2; }
				if ( next ) { next.disabled = ! scrollable || track.scrollLeft >= maxScroll() - 2; }
				var idx = currentIndex();
				dots.forEach( function ( d, n ) {
					d.classList.toggle( 'is-active', n === idx );
					d.setAttribute( 'aria-selected', n === idx ? 'true' : 'false' );
				} );
			}

			function move( dir ) {
				track.scrollBy( { left: dir * stepSize(), behavior: reduceMotion ? 'auto' : 'smooth' } );
			}

			if ( prev ) { prev.addEventListener( 'click', function () { move( -1 ); restart(); } ); }
			if ( next ) { next.addEventListener( 'click', function () { move( 1 ); restart(); } ); }
			slider.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'ArrowLeft' ) { e.preventDefault(); move( -1 ); restart(); }
				if ( e.key === 'ArrowRight' ) { e.preventDefault(); move( 1 ); restart(); }
			} );
			track.addEventListener( 'scroll', function () { window.requestAnimationFrame( sync ); }, { passive: true } );

			/* Drag-to-scroll (mouse); touch uses native swipe */
			var dragging = false, dragStartX = 0, dragStartScroll = 0, dragMoved = false;
			track.addEventListener( 'pointerdown', function ( e ) {
				if ( e.pointerType !== 'mouse' ) { return; }
				dragging = true; dragMoved = false;
				dragStartX = e.clientX; dragStartScroll = track.scrollLeft;
				track.style.scrollSnapType = 'none';
			} );
			window.addEventListener( 'pointermove', function ( e ) {
				if ( ! dragging ) { return; }
				var dx = e.clientX - dragStartX;
				if ( Math.abs( dx ) > 4 ) { dragMoved = true; }
				track.scrollLeft = dragStartScroll - dx;
			} );
			window.addEventListener( 'pointerup', function () {
				if ( ! dragging ) { return; }
				dragging = false;
				track.style.scrollSnapType = '';
				var s = stepSize();
				var target = s > 0 ? Math.round( track.scrollLeft / s ) * s : 0;
				track.scrollTo( { left: target, behavior: reduceMotion ? 'auto' : 'smooth' } );
			} );
			track.addEventListener( 'click', function ( e ) {
				if ( dragMoved ) { e.preventDefault(); e.stopPropagation(); dragMoved = false; }
			}, true );

			function stop() { if ( timer ) { clearInterval( timer ); timer = null; } }
			function start() {
				var delay = parseInt( slider.getAttribute( 'data-autoplay' ), 10 ) || 0;
				if ( reduceMotion || ! delay || ! isScrollable() ) { return; }
				stop();
				timer = setInterval( function () {
					if ( track.scrollLeft >= maxScroll() - 2 ) {
						track.scrollTo( { left: 0, behavior: 'smooth' } );
					} else {
						move( 1 );
					}
				}, delay );
			}
			function restart() { stop(); start(); }

			slider.addEventListener( 'mouseenter', stop );
			slider.addEventListener( 'mouseleave', start );
			slider.addEventListener( 'focusin', stop );
			slider.addEventListener( 'focusout', function ( e ) { if ( ! slider.contains( e.relatedTarget ) ) { start(); } } );
			document.addEventListener( 'visibilitychange', function () { if ( document.hidden ) { stop(); } else { start(); } } );
			window.addEventListener( 'resize', debounce( function () { buildDots(); sync(); }, 160 ) );

			buildDots();
			sync();
			start();
		} );

		/* Property gallery: thumbnail switching */
		var gallery = document.querySelector( '[data-gallery]' ) || document.querySelector( '.ep-gallery' );
		var mainImg = document.querySelector( '[data-ep-gallery-main]' );
		var thumbs = document.querySelectorAll( '[data-ep-gallery-thumb]' );
		if ( mainImg && thumbs.length ) {
			thumbs.forEach( function ( thumb ) {
				thumb.addEventListener( 'click', function () {
					var src = thumb.getAttribute( 'data-ep-gallery-thumb' );
					if ( ! src ) { return; }
					mainImg.setAttribute( 'src', src );
					mainImg.removeAttribute( 'srcset' );
					thumbs.forEach( function ( t ) { t.classList.remove( 'is-active' ); } );
					thumb.classList.add( 'is-active' );
				} );
			} );
		}

		/* Lightbox */
		if ( mainImg && gallery ) {
			var galleryMain = mainImg.closest( '.ep-gallery-main' );
			var images = Array.prototype.map.call( thumbs, function ( t ) { return t.getAttribute( 'data-ep-gallery-thumb' ); } ).filter( Boolean );
			if ( ! images.length ) { images = [ mainImg.getAttribute( 'src' ) ]; }

			if ( galleryMain ) {
				galleryMain.setAttribute( 'tabindex', '0' );
				galleryMain.setAttribute( 'role', 'button' );
				galleryMain.setAttribute( 'aria-label', 'Open image viewer' );
			}

			var box, imgEl, counterEl, lastFocus, current = 0;

			function render() {
				imgEl.src = images[ current ];
				if ( counterEl ) { counterEl.textContent = ( current + 1 ) + ' / ' + images.length; }
			}
			function onKey( e ) {
				if ( e.key === 'Escape' ) { closeBox(); }
				else if ( e.key === 'ArrowLeft' ) { step( -1 ); }
				else if ( e.key === 'ArrowRight' ) { step( 1 ); }
				else if ( e.key === 'Tab' ) { trapFocus( e ); }
			}
			function trapFocus( e ) {
				var focusables = box.querySelectorAll( 'button, [href], img' );
				if ( ! focusables.length ) { return; }
				var first = focusables[ 0 ], last = focusables[ focusables.length - 1 ];
				if ( e.shiftKey && document.activeElement === first ) { e.preventDefault(); last.focus(); }
				else if ( ! e.shiftKey && document.activeElement === last ) { e.preventDefault(); first.focus(); }
			}
			function onClick( e ) {
				if ( e.target.closest( '[data-lightbox-close]' ) ) { return closeBox(); }
				if ( e.target.closest( '[data-lightbox-prev]' ) ) { e.stopPropagation(); return step( -1 ); }
				if ( e.target.closest( '[data-lightbox-next]' ) ) { e.stopPropagation(); return step( 1 ); }
				if ( e.target === box || e.target === imgEl ) { return closeBox(); }
			}
			function step( d ) { current = ( current + d + images.length ) % images.length; render(); }

			function openBox( i ) {
				current = i < 0 ? 0 : i;
				lastFocus = document.activeElement;
				box = document.createElement( 'div' );
				box.className = 'ep-lightbox';
				box.setAttribute( 'role', 'dialog' );
				box.setAttribute( 'aria-modal', 'true' );
				box.setAttribute( 'aria-label', 'Image viewer' );
				box.innerHTML = '<button type="button" class="ep-lightbox-close" data-lightbox-close aria-label="Close">&times;</button>' +
					( images.length > 1 ? '<button type="button" class="ep-lightbox-nav" data-lightbox-prev aria-label="Previous image">&larr;</button><button type="button" class="ep-lightbox-nav" data-lightbox-next aria-label="Next image">&rarr;</button>' : '' ) +
					'<img alt="" /><p class="ep-lightbox-counter"></p>';
				document.body.appendChild( box );
				imgEl = box.querySelector( 'img' );
				counterEl = box.querySelector( '.ep-lightbox-counter' );
				render();
				document.body.style.overflow = 'hidden';
				box.addEventListener( 'click', onClick );
				document.addEventListener( 'keydown', onKey );
				box.querySelector( '[data-lightbox-close]' ).focus();
			}
			function closeBox() {
				if ( ! box ) { return; }
				document.removeEventListener( 'keydown', onKey );
				box.remove();
				box = null;
				document.body.style.overflow = '';
				if ( lastFocus && lastFocus.focus ) { lastFocus.focus(); }
			}
			function openFromMain() {
				var src = mainImg.currentSrc || mainImg.getAttribute( 'src' );
				var i = images.indexOf( mainImg.getAttribute( 'src' ) );
				if ( i === -1 ) { i = images.indexOf( src ); }
				openBox( i === -1 ? 0 : i );
			}
			if ( galleryMain ) {
				galleryMain.addEventListener( 'click', openFromMain );
				galleryMain.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); openFromMain(); }
				} );
			}
		}

		/* Back to top */
		var top = document.querySelector( '.ep-backtop' );
		if ( top ) {
			var toggleTop = function () { top.classList.toggle( 'is-visible', window.scrollY > 500 ); };
			toggleTop();
			window.addEventListener( 'scroll', toggleTop, { passive: true } );
			top.addEventListener( 'click', function () { window.scrollTo( { top: 0, behavior: reduceMotion ? 'auto' : 'smooth' } ); } );
		}

		/* Auto-hide notices */
		document.querySelectorAll( '.ep-form-note' ).forEach( function ( note ) {
			setTimeout( function () { note.style.transition = 'opacity .4s ease'; note.style.opacity = '0'; }, 6000 );
		} );

		/* Client-side demo filter (static previews) */
		var filterForm = document.querySelector( '[data-demo-filter]' );
		if ( filterForm ) {
			var cards = Array.prototype.slice.call( document.querySelectorAll( '[data-demo-cards] .ep-card' ) );
			var countEl = document.querySelector( '[data-demo-count]' );
			var val = function ( name ) {
				var el = filterForm.querySelector( '[name="' + name + '"]' );
				return el ? el.value : '';
			};
			var apply = function () {
				var kw = val( 's' ).toLowerCase();
				var status = val( 'status' );
				var type = val( 'type' );
				var loc = val( 'location' );
				var beds = parseInt( val( 'beds' ), 10 ) || 0;
				var min = parseInt( val( 'min' ), 10 ) || 0;
				var max = parseInt( val( 'max' ), 10 ) || 0;
				var shown = 0;
				cards.forEach( function ( card ) {
					var price = parseInt( card.getAttribute( 'data-price' ), 10 ) || 0;
					var cbeds = parseInt( card.getAttribute( 'data-beds' ), 10 ) || 0;
					var ok = true;
					if ( kw && card.textContent.toLowerCase().indexOf( kw ) === -1 ) { ok = false; }
					if ( status && card.getAttribute( 'data-status' ) !== status ) { ok = false; }
					if ( type && card.getAttribute( 'data-type' ) !== type ) { ok = false; }
					if ( loc && card.getAttribute( 'data-location' ) !== loc ) { ok = false; }
					if ( beds && cbeds < beds ) { ok = false; }
					if ( min && price < min ) { ok = false; }
					if ( max && price > max ) { ok = false; }
					card.style.display = ok ? '' : 'none';
					if ( ok ) { shown++; }
				} );
				if ( countEl ) { countEl.textContent = shown + ' properties found'; }
			};
			filterForm.addEventListener( 'input', apply );
			filterForm.addEventListener( 'change', apply );
			filterForm.addEventListener( 'submit', function ( e ) { e.preventDefault(); apply(); } );
			var reset = filterForm.querySelector( '[data-demo-reset]' );
			if ( reset ) { reset.addEventListener( 'click', function ( e ) { e.preventDefault(); filterForm.reset(); apply(); } ); }
		}
	} );
}() );
