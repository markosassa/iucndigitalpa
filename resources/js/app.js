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

    const viewToggle = document.getElementById('viewToggle');
    let isCardView = true;

    viewToggle.addEventListener('click', function() {
      isCardView = !isCardView;
      this.classList.toggle('active');
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
    });

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
    function switchView(view) {
        currentView = view;
        currentPage = 1;

        document.getElementById('view-table').classList.toggle('active', view === 'table');
        document.getElementById('view-cards').classList.toggle('active', view === 'cards');

        document.getElementById('table-view').classList.toggle('hidden', view !== 'table');
        document.getElementById('cards-view').classList.toggle('hidden', view !== 'cards');
        console.log(view);
        renderData();
    }
    function setupEventListeners() {
        // Toggle Vista
        document.getElementById('view-table').addEventListener('click', () => switchView('table'));
        document.getElementById('view-cards').addEventListener('click', () => switchView('cards'));



    }
    //loadSystemAssessments();
    let currentPage = 1;
    let itemsPerPage = 10;
    let allAssessments = [];
    let totalAssessments = 0;

    // Mapping categorie IUCN





});
