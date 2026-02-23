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



    let isCardView = false;

    $('#viewToggle').on('click', function(){
        isCardView = !isCardView;
        $(this).toggleClass('active');
        if(isCardView){
            $('#cards-view').removeClass('d-none')
            $('#table-view').addClass('d-none')
            $('#view-cards').addClass('active');
            $('#view-table').removeClass('active');
        }else{
            $('#cards-view').addClass('d-none')
            $('#table-view').removeClass('d-none')
            $('#view-table').addClass('active');
            $('#view-card').removeClass('active');
        }
        console.log('Vista cambiata a:', isCardView ? 'Card' : 'Tabella');
    })
    $('#per-page').on('change', function(){
        console.log($(this).val())
        window.location.replace('/systems?system=0&per_page='+$(this).val())
        //document.URL.replace ='?system=0&per-page='+$(this).val()
    })
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


    $('#favorite-btn').on('click', function(e){
       e.preventDefault();

        const $btn = $(this);
        const assessmentId = parseInt($btn.data('sistaxa'), 10);

        $.ajax({
            url: '/favorites/toggle',
            method: 'POST',
            data: { assessment_id: assessmentId },
            success: function (res) {
                if (!res || !res.ok) return;

                if (res.favorited) {
                    $btn.text('❤️');
                } else {
                    $btn.text('🤍');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText || xhr.statusText);
            }
        });
    })
    const $btn = $('#favorite-btn');
    if (!$btn.length) return;

    const assessmentId = parseInt($btn.data('sistaxa'), 10);

    $.get('/favorites/has', { assessment_id: assessmentId }, function (res) {
        if (res && res.ok && res.favorited) $btn.text('❤️');
    });


    

});
