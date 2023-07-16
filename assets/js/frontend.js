;(function ($) {
    // title and date order
    $(document).on("change", '#orderby', function (e) {
        e.preventDefault();

        let selectedCat = $(this).val();

        let data = {
            action: 'renderBySelect',
            selectId: selectedCat,
            nonce: wpProjects.nonce
        }

        sendAjaxRequest(data);
    });

    // Category wise filter
    $(document).on('change', '#project-category-filter', function () {
        let selectedCategory = $(this).val();

        let data = {
            action: 'renderBySelect',
            category: selectedCategory,
            nonce: wpProjects.nonce
        };

        sendAjaxRequest(data);
    });

    // Common method for handle ajax
    function sendAjaxRequest(data) {
        $.ajax({
            url: wpProjects.ajaxUrl,
            type: 'POST',
            data: data,
            beforeSend: function () {
                $('.project-grid').addClass('wp-loader');
            },
            success: function (response) {
                if (response.success) {
                    $('.project-grid').html(response.data.content);
                    $('.project-grid').removeClass('wp-loader');
                } else {
                    console.log(response.data.message);
                }
            },
            error: function (error) {
                console.log(wpProjects.error);
            }
        });
    }

    // Perform Modal close
    $(document).on('click', '.close-modal', function () {
        $('#projects-modal').hide();
        $(document.body).removeClass('wp-modal-active');
        $('#projects-modal .modal-content-inner').html('');
    });

    // Open Modal
    $(document).on('click', '.view-details', function () {
        let self = $(this),
        targetId = $(this).data('id');

        $('#projects-modal').show();

        let data = {
            action: 'renderPopupById',
            postId: targetId,
            nonce: wpProjectsPopup.nonce
        };

        $.ajax({
            url: wpProjectsPopup.ajaxUrl,
            type: 'POST',
            data: data,
            beforeSend: function () {
                $('#projects-modal .modal-content-inner').addClass('wp-loader');
            },
            success: function (response) {
                if (response.success) {
                    $(document.body).addClass('wp-modal-active');
                    $('#projects-modal .modal-content-inner').html(response.data.content);
                    $('#projects-modal .modal-content-inner').removeClass('wp-loader');

                    // Inside modal perform preview images slider
                    $('.wp-projects-preview-images').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 2000,
                        nextArrow: '<button type="button" class="slick-next"><span class="dashicons dashicons-arrow-right-alt2"></span></button>',
                        prevArrow: '<button type="button" class="slick-prev"><span class="dashicons dashicons-arrow-left-alt2"></span></button>',
                        responsive: [
                            {
                              breakpoint: 1024,
                              settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                              }
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                }
                            },
                        ]
                    });

                } else {
                    console.log(response.data.message);
                }
            },
            error: function (error) {
                console.log(wpProjectsPopup.error);
            }
        });
    });

})(jQuery);