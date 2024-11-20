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

    window.applyFilters = function () {
        var searchQuery = $('#search').val().toLowerCase();
        var selectedMinors = $('#windesheim-minor input:checked').map(function () {
            return $(this).val();
        }).get();
        var selectedValueChains = $('#value-chain input:checked').map(function () {
            return $(this).val();
        }).get();
        var selectedThemes = $('#lib-themes input:checked').map(function () {
            return $(this).val();
        }).get();
        var selectedSDGs = $('#lib-sdgs input:checked').map(function () {
            return $(this).val();
        }).get();
        var selectedInnovationSectors = $('#innovation-sectors input:checked').map(function () {
            return $(this).val();
        }).get();

        $('.use-case').each(function () {
            var useCase = $(this);
            var projectName = useCase.find('h2').text().toLowerCase();
            var minor = useCase.data('minor');
            var valueChain = useCase.data('value-chain');
            var themes = useCase.data('themes');
            var sdgs = useCase.data('sdgs');
            var innovationSectors = useCase.data('innovation-sectors');

            var matchesSearch = projectName.includes(searchQuery);
            var matchesMinor = selectedMinors.length === 0 || selectedMinors.includes(minor);
            var matchesValueChain = selectedValueChains.length === 0 || selectedValueChains.some(function (value) {
                return valueChain.includes(value);
            });
            var matchesThemes = selectedThemes.length === 0 || selectedThemes.some(function (value) {
                return themes.includes(value);
            });
            var matchesSDGs = selectedSDGs.length === 0 || selectedSDGs.some(function (value) {
                return sdgs.includes(value);
            });
            var matchesInnovationSectors = selectedInnovationSectors.length === 0 || selectedInnovationSectors.includes(innovationSectors);

            if (matchesSearch && matchesMinor && matchesValueChain && matchesThemes && matchesSDGs && matchesInnovationSectors) {
                useCase.show();
            } else {
                useCase.hide();
            }
        });
    };
});