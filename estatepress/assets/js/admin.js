/* global jQuery, wp */
( function ( $ ) {
	'use strict';

	$( function () {
		var frame;
		var wrap = $( '#ep-gallery-wrap' );
		if ( ! wrap.length ) {
			return;
		}

		var input = $( '#_ep_gallery' );

		function refreshInput() {
			var ids = wrap.find( '.ep-gallery-preview li' ).map( function () {
				return $( this ).data( 'id' );
			} ).get();
			input.val( ids.join( ',' ) );
		}

		$( '#ep-gallery-add' ).on( 'click', function ( e ) {
			e.preventDefault();

			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: 'Select Property Images',
				button: { text: 'Add to gallery' },
				library: { type: 'image' },
				multiple: true
			} );

			frame.on( 'select', function () {
				var selection = frame.state().get( 'selection' );
				selection.map( function ( attachment ) {
					var data = attachment.toJSON();
					var url = ( data.sizes && data.sizes.thumbnail ) ? data.sizes.thumbnail.url : data.url;
					if ( wrap.find( 'li[data-id="' + data.id + '"]' ).length ) {
						return;
					}
					wrap.find( '.ep-gallery-preview' ).append(
						'<li data-id="' + data.id + '"><img src="' + url + '" alt="" /><span class="ep-gallery-remove">&times;</span></li>'
					);
				} );
				refreshInput();
			} );

			frame.open();
		} );

		wrap.on( 'click', '.ep-gallery-remove', function () {
			$( this ).closest( 'li' ).remove();
			refreshInput();
		} );

		// Make the gallery sortable if jQuery UI is available.
		if ( $.fn.sortable ) {
			wrap.find( '.ep-gallery-preview' ).sortable( {
				items: 'li',
				cursor: 'move',
				update: refreshInput
			} );
		}
	} );
}( jQuery ) );
