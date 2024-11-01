jQuery(function($){

    // Set all variables to be used in scope
    var frame,
      metaBox = $('#meta-box-id'), // Your meta box id here
      addImgLink = metaBox.find('.tclci-upload-image-button'),
      delImgLink = metaBox.find( '.tclci-remove-image-button'),
      imgContainer = metaBox.find( '.tclci-taxonomy-image-container'),
      imgIdInput = metaBox.find( '.tclci-taxonomy-val' );
    
      console.log(addImgLink);
  
	// add image link
    addImgLink.on( 'click', function( event ){
    
	    event.preventDefault();
	    
	    // If the media frame already exists, reopen it.
	    if ( frame ) {
	      frame.open();
	      return;
        }
    
	    // Create a new media frame
	    frame = wp.media({
		    title: media_upload_object._media_upload_title,
		    button: {
		    text: media_upload_object._media_button_text
		    },
		    multiple: media_upload_object._enable_multiple_select  // Set to true to allow multiple files to be selected
	    });

	    
	    // When an image is selected in the media frame...
	    frame.on( 'select', function() {
	    	console.log(media_upload_object._placeholder_image);
	      
		    // Get media attachment details from the frame state
		    var attachment = frame.state().get('selection').first().toJSON();

		    // Send the attachment URL to our custom image input field.

		    imgContainer.html( '<img  width="250px" src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

		    // Send the attachment id to our hidden input
		    imgIdInput.val( attachment.id );

		    // Unhide the remove image link
		    delImgLink.removeClass( 'hidden' );
	    });

	    // Finally, open the modal on click
	    frame.open();
    });
  
  
    // Delete image link
    delImgLink.on( 'click', function( event ){

	    event.preventDefault();

	    // Clear out the preview image
	    imgContainer.html( '<img  width="250px" src="'+media_upload_object._placeholder_image+'" alt="" style="max-width:100%;"/>' );

	    // Un-hide the add image link
	    addImgLink.removeClass( 'hidden' );

	    // Hide the delete image link
	    delImgLink.addClass( 'hidden' );

	    // Delete the image id from the hidden input
	    imgIdInput.val( '' );
    });

});