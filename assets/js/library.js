jQuery(document).ready(function ($) {
    $('.collapsible-button').on('click', function () {
        $(this).toggleClass('active');
        var content = $(this).next('.collapsible-content');
        if (content.css('max-height') !== '0px') {
            content.css('max-height', '0px');
        } else {
            content.css('max-height', content.prop('scrollHeight') + 'px');
        }
    });

    // // Rendre l'ensemble du use case cliquable
    // $('.use-case').on('click', function () {
    //     var url = $(this).data('url');
    //     if (url) {
    //         window.open(url, '_blank');
    //     }
    // });
});