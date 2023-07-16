;(function ($) {
    // All selector handle
    let frame,
        gFrame,
        metaBox 		= $("#wp_projects_upload_image_wrapper"),
        addImgLink 		= metaBox.find("#upload_image"),
        delImgLink    	= metaBox.find("#delete_upload_image"),
        imgContainer 	= metaBox.find("#upload_image_container"),
        imgIdInput 		= metaBox.find("#wp_projects_upload_image_id"),
        imgURLInput 	= metaBox.find("#wp_projects_upload_image_url"),
        gMetaBox 		= $("#wp_project_preview_images_wrapper"),
        gAddImgLink 	= gMetaBox.find("#upload_preview_images"),
        gDelImgLink 	= gMetaBox.find("#delete_preview_images"),
        gImgContainer 	= gMetaBox.find("#preview_images_container"),
        gImgIdInput 	= gMetaBox.find("#wp_projects_preview_images_id"),
        gImgURLInput 	= gMetaBox.find("#wp_projects_preview_images_url");

    $(document).ready(function () {
        // for image upload
        let image_url = $("#wp_projects_upload_image_url").val();
        if (image_url.length > 1) {
            imgContainer.html(`<img src='${image_url}' />`);
        }

		
        // check if there is val display remove button
        if ($(imgURLInput).val() !== "") {
            $(delImgLink).removeClass("hidden");
            $(addImgLink).addClass("hidden");
        }

        // upload image
        addImgLink.on("click", function (event) {
            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: "Select Image",
                button: {
                    text: "Insert Image",
                },
                multiple: false,
            });

            // When an image is selected in the media frame...
            frame.on("select", function () {
                // Get media attachment details from the frame state
                let attachment = frame
                    .state()
                    .get("selection")
                    .first()
                    .toJSON();

                if (attachment) {
                    // Send the attachment URL to our custom image input field.
                    imgContainer.html(`<img src='${attachment.url}' />`);
                    imgIdInput.val(attachment.id);
                    imgURLInput.val(attachment.url);

                    // Hide the add image link
                    addImgLink.addClass("hidden");

                    // Unhide the remove image link
                    delImgLink.removeClass("hidden");
                }
            });

            frame.open();
            return false;
        });

		// remove upload image
		delImgLink.on("click", function (event) {
			event.preventDefault();
			$(imgContainer).html("");
			$(imgIdInput).val("");
			$(imgURLInput).val("");
			$(this).addClass('hidden')
			$('#upload_image').removeClass('hidden')
		});

    });

    $(document).ready(function () {
        // for gallery image
        var images_url = $("#wp_projects_preview_images_url").val();
        var ic_images_id = $("#wp_projects_preview_images_id").val();

        images_url = images_url ? images_url.split(";") : [];
        for (var i in images_url) {
            let _image_url = images_url[i];

            if( _image_url !== '/' ) {
				gImgContainer.append(
					`<img style="margin-right: 10px;" src='${_image_url}' />`
				);
            }
        }

        // check if there is val display remove button
        if ($(gImgIdInput).val().trim() !== "") {
            $(gDelImgLink).removeClass("hidden");
            $(gAddImgLink).addClass("hidden");
        }

        // Upload Gallery image
        gAddImgLink.on("click", function (event) {
            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (gFrame) {
                gFrame.open();
                return;
            }

            // Create a new media frame
            gFrame = wp.media({
                title: "Select Gallery Images",
                button: {
                    text: "Insert Image",
                },
                multiple: true, // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            gFrame.on("select", function () {
                let images_ids = [];
                let images_urls = [];

                // Get media attachment details from the frame state
                let attachments = gFrame.state().get("selection").toJSON();

                for (i in attachments) {
                    var attachment = attachments[i];

                    images_ids.push(attachment.id);
                    images_urls.push(attachment.url);

                    // Send the attachment URL to our custom image input field.
					if( images_ids.length > 0 ) {
						gImgContainer.append(
							`<img style="margin-right: 10px;" src='${attachment.sizes.full.url}' />`
						);
					}

                    // Hide the add image link
                    gAddImgLink.addClass("hidden");

                    // Unhide the remove image link
                    gDelImgLink.removeClass("hidden");
                }

                gImgIdInput.val(images_ids.join(";"));
                gImgURLInput.val(images_urls.join(";"));
            });

            gFrame.open();
            return false;
        });

		// remove gallery image
		gDelImgLink.on("click", function (event) {
			event.preventDefault();
			$(gImgContainer).html("");
			$(gImgIdInput).val("");
			$(gImgURLInput).val("");
		});
    });
})(jQuery);
