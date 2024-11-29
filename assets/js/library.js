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

    var displayedUseCases = [];
    var currentPage = 1;
    var itemsPerPage = 21;

    function applyFilters() {
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

        displayedUseCases = []; // Reset the array

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
                displayedUseCases.push(useCase); // Add to the array
            } else {
                useCase.hide();
            }
        });

        showPage(currentPage);
        updatePagination();
    }

    function showPage(page) {
        var startIndex = (page - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;

        $('.use-case').hide(); // Hide all use cases
        displayedUseCases.slice(startIndex, endIndex).forEach(function (useCase) {
            useCase.show(); // Show only the use cases for the current page
        });
    }

    function updatePagination() {
        var totalPages = Math.ceil(displayedUseCases.length / itemsPerPage);
        $('#page-info').text('Pagina ' + currentPage + ' van ' + totalPages);

        if (currentPage === 1) {
            $('#prev-page').addClass('disabled').prop('disabled', true);
        } else {
            $('#prev-page').removeClass('disabled').prop('disabled', false);
        }

        if (currentPage === totalPages) {
            $('#next-page').addClass('disabled').prop('disabled', true);
        } else {
            $('#next-page').removeClass('disabled').prop('disabled', false);
        }
    }

    function scrollToTop() {
        // Scroll to the top of the use cases div with an offset
        var useCasesDiv = document.getElementById('use-cases');
        var offset = -100; // Adjust this value as needed
        var bodyRect = document.body.getBoundingClientRect().top;
        var elementRect = useCasesDiv.getBoundingClientRect().top;
        var elementPosition = elementRect - bodyRect;
        var offsetPosition = elementPosition + offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }

    $('#prev-page').click(function () {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            updatePagination();
            scrollToTop();
        }
    });

    $('#next-page').click(function () {
        var totalPages = Math.ceil(displayedUseCases.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
            updatePagination();
            scrollToTop();
        }
    });

    // Initial call to apply filters and set up pagination
    applyFilters();

    // Attach applyFilters to filter change events
    $('#search, #windesheim-minor input, #value-chain input, #lib-themes input, #lib-sdgs input, #innovation-sectors input').on('change', applyFilters);
});