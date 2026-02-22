import { render } from 'sass-embedded';
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
    //loadSystemAssessments();
    let currentPage = 1;
    let itemsPerPage = 10;
    let allAssessments = [];
    let totalAssessments = 0;

    // Mapping categorie IUCN
    

    function loadSystemAssessments() {
        // Mostra spinner
        $('#loading-spinner').addClass('active');
        $('#table-container').hide();
        $('#pagination-section').hide();
        $('#empty-state').hide();

        // Qui dovresti fare una chiamata AJAX al tuo server
        // Per questo esempio, uso dati mock

        // Simulazione: dopo 1 secondo, carica i dati
        setTimeout(function() {
            // Dati di esempio - in produzione verranno dalla tua API
            let system = $('#systemCode').val();
            let itemsPerPage = $('#items-per-page-select').val();
            let currentPage = 1;
            //getSystemAssessmentsFromAPI(system, itemsPerPage, currentPage);
            //renderPage();
        }, 1000);
    }

    /*function renderPage() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const pageData = allAssessments.slice(startIndex, endIndex);

        // Ripulisci tabella
        const tbody = $('#assessments-body');
        tbody.empty();

        // Aggiungi righe
        pageData.forEach((assessment) => {
            const categoryCode = assessment.category_code || 'NE';
            const category = categoryMapping[categoryCode] || { text: categoryCode, badge: 'secondary' };

            const row = `
                <tr>
                    <td class="text-center"><code class="bg-light px-2 py-1 rounded">${assessment.assessment_id}</code></td>
                    <td>
                        <div class="fw-semibold">${assessment.species_name}</div>
                        <small class="text-muted"><em>${assessment.scientific_name}</em></small>
                    </td>
                    <td class="text-center"><span class="badge bg-secondary">${assessment.year_published}</span></td>
                    <td>
                        <span class="badge bg-${category.badge} me-2">${categoryCode}</span>
                        <span class="text-muted small">${category.text}</span>
                    </td>
                    <td class="text-center">
                        <a href="https://www.iucnredlist.org/species/${assessment.sis_taxon_id}/${assessment.assessment_id}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="tooltip"
                            title="Visualizza su IUCN Red List">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Aggiorna paginazione
        updatePaginationControls();

        // Reinizializza tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function updatePaginationControls() {
        const totalPages = Math.ceil(allAssessments.length / itemsPerPage);

        // Aggiorna testo paginazione
        $('#current-page').text(currentPage);
        $('#total-pages').text(totalPages);

        // Aggiorna pulsanti prev/next
        $('#prev-page').prop('disabled', currentPage === 1);
        $('#next-page').prop('disabled', currentPage === totalPages);

        // Genera numeri pagina
        const pageNumbersContainer = $('#page-numbers');
        pageNumbersContainer.empty();

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const isActive = i === currentPage;
            const btnClass = isActive ? 'btn-primary' : 'btn-outline-primary';
            const btn = `<button type="button" class="btn btn-sm ${btnClass} page-number" data-page="${i}">${i}</button>`;
            pageNumbersContainer.append(btn);
        }
    }*/
    /*function getSystemAssessmentsFromAPI(system, numRows, page) {
        
        $.ajax({
            url: '/getAssessmentsBySystem',
            method: 'GET',
            data: { system: system, page: page, per_page: numRows },
            success: function(response) {
                // Gestisci la risposta e aggiorna la tabella

                console.log(response); // Per debug
                allAssessments = response.data.assessments; // Assumendo che la risposta abbia questa struttura
                totalAssessments = allAssessments.length;
                $('#total-assessments').text(totalAssessments);
                $('#badge-assessments').text(totalAssessments + ' assessments');

                if (allAssessments.length === 0) {
                    $('#loading-spinner').removeClass('active');
                    $('#empty-state').show();
                } else {
                    $('#loading-spinner').removeClass('active');
                    $('#table-container').show();
                    $('#pagination-section').show();
                    renderPage();
                }
            },
            error: function() {
                alert('Errore nel caricamento degli assessments. Riprova più tardi.');
                $('#loading-spinner').removeClass('active');
            }
        });
    }*/
    // Funzione per generare dati mock
    /*function generateMockAssessments(count) {
        const species = [
            { common: 'Leone', scientific: 'Panthera leo' },
            { common: 'Tigre', scientific: 'Panthera tigris' },
            { common: 'Elefante africano', scientific: 'Loxodonta africana' },
            { common: 'Rinoceronte bianco', scientific: 'Ceratotherium simum' },
            { common: 'Orango', scientific: 'Pongo pygmaeus' },
            { common: 'Panda gigante', scientific: 'Ailuropoda melanoleuca' },
            { common: 'Lupo grigio', scientific: 'Canis lupus' },
            { common: 'Giaguaro', scientific: 'Panthera onca' },
            { common: 'Gorilla', scientific: 'Gorilla gorilla' },
            { common: 'Koala', scientific: 'Phascolarctos cinereus' }
        ];

        const categories = ['EX', 'EW', 'CR', 'EN', 'VU', 'NT', 'LC', 'DD', 'NE'];
        const assessments = [];

        for (let i = 1; i <= count; i++) {
            const species_data = species[Math.floor(Math.random() * species.length)];
            assessments.push({
                assessment_id: 10000 + i,
                sis_taxon_id: 15000 + Math.floor(Math.random() * 1000),
                species_name: species_data.common,
                scientific_name: species_data.scientific,
                category_code: categories[Math.floor(Math.random() * categories.length)],
                year_published: 2010 + Math.floor(Math.random() * 14)
            });
        }

        return assessments;
    }*/

});
