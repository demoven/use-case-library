// jQuery(document).ready(function($) {
//     $('#filter-form input[type="checkbox"]').on('change', function() {
//         var selectedMinors = [];
//         $('#filter-form input[type="checkbox"]:checked').each(function() {
//             selectedMinors.push($(this).val());
//         });

//         $.ajax({
//             url: window.location.href.split('?')[0],
//             type: 'GET',
//             data: {
//                 w_minor: selectedMinors
//             },
//             success: function(response) {
//                 var newContent = $(response).find('.use-cases').html();
//                 $('.use-cases').html(newContent);
//             }
//         });
//     });
// });
jQuery(document).ready(function($) {
    $('.collapsible-button').on('click', function() {
        $(this).toggleClass('active');
        var content = $(this).next('.collapsible-content');
        if (content.css('max-height') !== '0px') {
            content.css('max-height', '0px');
        } else {
            content.css('max-height', content.prop('scrollHeight') + 'px');
        }
    });
});