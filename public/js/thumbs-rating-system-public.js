(function( $ ) {
	'use strict';

	var trs = $("#thumbs-rating-system");
	var post_id = trs.data( "id" );
	var itemName = "thumbs-rating-system-" + post_id;

	// Check if this content has localstorage
	if( localStorage.getItem( itemName ) ) {

		var itemData = JSON.parse(localStorage.getItem(itemName));

		trs.find( ".rating__btn_" + itemData.type ).addClass("voted");

	}

	trs.on( "click", ".rating__btn", function() {

		var type = $( this ).data( "type" );

		// For the LocalStorage 
	    var itemData = {
	        'post_id':  post_id,
	        'type': type
	    }

	    // Check if the LocalStorage value exist. If do nothing.
	    if ( ! localStorage.getItem( itemName ) ) {

	        // Set HTML5 LocalStorage so the user can not vote again unless he clears it.                                      
	        localStorage.setItem( itemName, JSON.stringify( itemData ) );

	        jQuery.ajax({
	            url: thumbs_rating_system_ajax.url,
	            type: 'POST',
	            data: {
	                action: 'thumbs_rating_system_add_vote',
	                post_id: post_id,
	                type: type,
	                nonce: thumbs_rating_system_ajax.nonce
	            },
	            success: function( response ) {

	                trs.html( response );

	                jQuery.notify( thumbs_rating_system_ajax.message.success, {
	                    position: 'bottom right',
	                    className: 'success'
	                });

	                trs.find( ".rating__btn_" + type ).addClass("voted");

	            }
	        });
	    } else {

	        jQuery.notify( thumbs_rating_system_ajax.message.error, {
	            position: 'bottom right',
	            className: 'error'
	        });
	    }

	} );

})( jQuery );
