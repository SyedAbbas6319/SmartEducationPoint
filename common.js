// common.js

$(document).ready(function () {
    $('#searchBar').on('input', function () {
        var searchText = $(this).val().trim().toLowerCase();
        $('.highlight').each(function () {
            var originalText = $(this).data('originalText');
            $(this).replaceWith(originalText);
        });

        if (searchText.length > 0) {
            $('body').find('*').each(function () {
                var element = $(this);
                if (element.children().length === 0 && element.text().trim().length > 0) {
                    var text = element.text().toLowerCase();
                    if (text.includes(searchText)) {
                        var highlightedText = element.html().replace(
                            new RegExp(searchText, 'gi'),
                            function (match) {
                                return '<span class="highlight" data-original-text="' + match + '">' + match + '</span>';
                            }
                        );
                        element.html(highlightedText);
                    }
                }
            });
        }
    });
});
