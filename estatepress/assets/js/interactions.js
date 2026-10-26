( function () {
	'use strict';

	var mq = window.matchMedia;
	var finePointer = mq ? mq( '(hover: hover) and (pointer: fine)' ).matches : false;
	var reduceMotion = mq ? mq( '(prefers-reduced-motion: reduce)' ).matches : false;
	var pointerFX = finePointer && ! reduceMotion;

	function onReady( fn ) {
		if ( document.readyState !== 'loading' ) { fn(); }
		else { document.addEventListener( 'DOMContentLoaded', fn ); }
	}

	function clamp( v, min, max ) { return Math.max( min, Math.min( max, v ) ); }

	onReady( function () {

		/* ------------------------------------------------------------------
		 * Scroll progress bar
		 * ---------------------------------------------------------------- */
		var progress = document.querySelector( '.ep-scroll-progress' );
		if ( progress ) {
			var updateProgress = function () {
				var h = document.documentElement.scrollHeight - window.innerHeight;
				var p = h > 0 ? ( window.scrollY / h ) * 100 : 0;
				progress.style.width = clamp( p, 0, 100 ) + '%';
			};
			updateProgress();
			window.addEventListener( 'scroll', updateProgress, { passive: true } );
			window.addEventListener( 'resize', updateProgress );
		}

		/* ------------------------------------------------------------------
		 * Section dot navigation (built from existing sections, no menu change)
		 * ---------------------------------------------------------------- */
		var dotnav = document.querySelector( '.ep-dotnav' );
		var navSections = document.querySelectorAll( '[data-nav]' );
		if ( dotnav && navSections.length ) {
			navSections.forEach( function ( section ) {
				var id = section.getAttribute( 'id' );
				if ( ! id ) { return; }
				var a = document.createElement( 'a' );
				a.href = '#' + id;
				a.setAttribute( 'aria-label', section.getAttribute( 'data-nav' ) || id );
				a.innerHTML = '<span>' + ( section.getAttribute( 'data-nav' ) || id ) + '</span>';
				dotnav.appendChild( a );
			} );
			var dots = Array.prototype.slice.call( dotnav.querySelectorAll( 'a' ) );
			dotnav.classList.add( 'is-ready' );

			if ( 'IntersectionObserver' in window ) {
				var spy = new IntersectionObserver( function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							dots.forEach( function ( d ) {
								d.classList.toggle( 'is-active', d.getAttribute( 'href' ) === '#' + entry.target.id );
							} );
						}
					} );
				}, { rootMargin: '-45% 0px -45% 0px', threshold: 0 } );
				navSections.forEach( function ( s ) { spy.observe( s ); } );
			}
		}

		/* ------------------------------------------------------------------
		 * Hero: spotlight, parallax, skyline pins
		 * ---------------------------------------------------------------- */
		var hero = document.querySelector( '.ep-hero' );
		if ( hero && pointerFX ) {
			var heroPins = hero.querySelector( '.ep-hero-pins' );
			hero.addEventListener( 'mousemove', function ( e ) {
				var r = hero.getBoundingClientRect();
				var px = ( e.clientX - r.left ) / r.width;
				var py = ( e.clientY - r.top ) / r.height;
				hero.style.setProperty( '--mx', ( px * 100 ).toFixed( 2 ) + '%' );
				hero.style.setProperty( '--my', ( py * 100 ).toFixed( 2 ) + '%' );
				hero.style.setProperty( '--px', ( ( px - 0.5 ) * 2 ).toFixed( 3 ) );
				hero.style.setProperty( '--py', ( ( py - 0.5 ) * 2 ).toFixed( 3 ) );
				hero.classList.add( 'is-armed' );
				if ( heroPins ) { heroPins.classList.add( 'is-armed' ); }
			} );
			hero.addEventListener( 'mouseleave', function () {
				hero.style.setProperty( '--px', '0' );
				hero.style.setProperty( '--py', '0' );
			} );
		}

		/* ------------------------------------------------------------------
		 * Magnetic buttons
		 * ---------------------------------------------------------------- */
		if ( pointerFX ) {
			document.querySelectorAll( '[data-magnetic]' ).forEach( function ( el ) {
				el.addEventListener( 'mouseenter', function () { el.style.transition = 'transform .12s var(--ep-ease-out), box-shadow .3s ease'; } );
				el.addEventListener( 'mousemove', function ( e ) {
					var r = el.getBoundingClientRect();
					var dx = ( e.clientX - ( r.left + r.width / 2 ) ) / ( r.width / 2 );
					var dy = ( e.clientY - ( r.top + r.height / 2 ) ) / ( r.height / 2 );
					el.style.transform = 'translate(' + ( dx * 8 ).toFixed( 2 ) + 'px,' + ( dy * 6 ).toFixed( 2 ) + 'px)';
				} );
				el.addEventListener( 'mouseleave', function () {
					el.style.transition = '';
					el.style.transform = '';
				} );
			} );

			/* Button arrow follows cursor direction */
			document.querySelectorAll( '.ep-btn' ).forEach( function ( btn ) {
				btn.addEventListener( 'mousemove', function ( e ) {
					var r = btn.getBoundingClientRect();
					btn.style.setProperty( '--bx', ( ( e.clientX - ( r.left + r.width / 2 ) ) / ( r.width / 2 ) ).toFixed( 2 ) );
					btn.style.setProperty( '--by', ( ( e.clientY - ( r.top + r.height / 2 ) ) / ( r.height / 2 ) ).toFixed( 2 ) );
				} );
			} );
		}

		/* ------------------------------------------------------------------
		 * Property cards: tilt + light sweep
		 * ---------------------------------------------------------------- */
		if ( pointerFX ) {
			document.querySelectorAll( '.ep-card' ).forEach( function ( card ) {
				card.addEventListener( 'mouseenter', function () { card.style.transition = 'transform .12s var(--ep-ease-out), box-shadow .35s var(--ep-ease), border-color .35s'; } );
				card.addEventListener( 'mousemove', function ( e ) {
					var r = card.getBoundingClientRect();
					var px = ( e.clientX - r.left ) / r.width;
					var py = ( e.clientY - r.top ) / r.height;
					card.style.setProperty( '--mx', ( px * 100 ).toFixed( 2 ) + '%' );
					card.style.setProperty( '--my', ( py * 100 ).toFixed( 2 ) + '%' );
					card.style.transform = 'translateY(-8px) rotateX(' + ( ( 0.5 - py ) * 5 ).toFixed( 2 ) + 'deg) rotateY(' + ( ( px - 0.5 ) * 6 ).toFixed( 2 ) + 'deg)';
				} );
				card.addEventListener( 'mouseleave', function () {
					card.style.transition = '';
					card.style.transform = '';
				} );
			} );
		}

		/* ------------------------------------------------------------------
		 * Directional menu underline
		 * ---------------------------------------------------------------- */
		if ( pointerFX ) {
			document.querySelectorAll( '.ep-menu a' ).forEach( function ( link ) {
				link.addEventListener( 'mouseenter', function ( e ) {
					var r = link.getBoundingClientRect();
					link.style.setProperty( '--ox', clamp( e.clientX - r.left, 0, r.width ) + 'px' );
				} );
			} );
		}

		/* ------------------------------------------------------------------
		 * CTA spotlight
		 * ---------------------------------------------------------------- */
		document.querySelectorAll( '.ep-cta' ).forEach( function ( cta ) {
			if ( ! pointerFX ) { return; }
			cta.addEventListener( 'mousemove', function ( e ) {
				var r = cta.getBoundingClientRect();
				cta.style.setProperty( '--mx', ( ( e.clientX - r.left ) / r.width * 100 ).toFixed( 2 ) + '%' );
				cta.style.setProperty( '--my', ( ( e.clientY - r.top ) / r.height * 100 ).toFixed( 2 ) + '%' );
			} );
		} );

		/* ------------------------------------------------------------------
		 * Back-to-top progress ring
		 * ---------------------------------------------------------------- */
		var backtop = document.querySelector( '.ep-backtop' );
		if ( backtop ) {
			var updateRing = function () {
				var h = document.documentElement.scrollHeight - window.innerHeight;
				backtop.style.setProperty( '--p', ( h > 0 ? clamp( window.scrollY / h * 100, 0, 100 ) : 0 ).toFixed( 1 ) );
			};
			updateRing();
			window.addEventListener( 'scroll', updateRing, { passive: true } );
		}

		/* ------------------------------------------------------------------
		 * Touch: tap-to-reveal discovery for locations
		 * ---------------------------------------------------------------- */
		if ( ! pointerFX ) {
			document.querySelectorAll( '.ep-location' ).forEach( function ( loc ) {
				loc.addEventListener( 'touchstart', function () { loc.classList.add( 'is-touched' ); }, { passive: true } );
			} );
		}
	} );
}() );
