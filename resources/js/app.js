import './bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function(){
    const $items = $('.country-badge');
    const $noResults = $('#countryNoResults');

    // Prepara stringa di ricerca una sola volta (performance)
    $items.each(function () {
        const name = $(this).find('span.text-sm').text().trim();
        const iso  = $(this).find('span.text-xs').text().trim();

        $(this).data('search', (name + ' ' + iso).toLowerCase());
    });

    $('#countrySearch').on('keyup', function () {

        const query = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        $items.each(function () {

            const searchable = $(this).data('search');

            if (query === '' || searchable.includes(query)) {
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).addClass('hidden');
            }

        });

        // Mostra messaggio se nessun risultato
        if (visibleCount === 0) {
            $noResults.removeClass('hidden');
        } else {
            $noResults.addClass('hidden');
        }

    });

});
