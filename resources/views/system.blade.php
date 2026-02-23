@extends('layouts.app')
@section('content')
@php
            $category = new App\Http\Controllers\CategoryController();

        @endphp
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8"><!-- Breadcrumb -->

    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 card-hover">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $systemData['description']['en'] }}</h1>
                    <p class="text-emerald-100 text-sm">Sistema di classificazione IUCN</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-lg p-4 border border-emerald-200">
                    <div class="text-gray-600 text-sm font-semibold mb-2">
                        Totale Assessments
                    </div>
                    <div class="text-4xl font-bold text-emerald-700" id="total-assessments">
                            {{ $headers['total-count'][0] }}
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        Valutazioni totali nella nazione
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Sezione Assessments -->

    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 card-hover">

        <div class="card-body p-0"><!-- Tabella Assessments -->
            <div class="bg-gradient-to-r from-emerald-100 to-teal-100 p-6 border-b border-gray-200">
                <form action="/systems" method="GET">


                    <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Filtri di Ricerca</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"><!-- Filtro Anno -->
                         <input type="hidden" name="system" value="{{ request('system') }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anno di Pubblicazione</label>
                            <input type="number" id="filter-year" name="published_year" placeholder="Es: 2023" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>
                        <!-- Filtro Possibile Estinto -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Possibile Estinto</label>
                            <select id="filter-possibly-extinct" name="pe" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="">Tutti</option>
                                <option value="true">Estinto</option>
                                <option value="false">Non estinto</option>
                            </select>
                        </div>
                        <!-- Filtro Possibile Estinto in Natura -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Estinto in Natura</label>
                            <select id="filter-possibly-extinct-wild" name="pew" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="">Tutti</option>
                                <option value="true">Estinto</option>
                                <option value="false">Non estinto</option>
                            </select>
                        </div>
                    </div><!-- Pulsanti Azione Filtri -->
                    <div class="flex gap-2 mt-4">
                        <button type="submit" id="btn-apply-filters" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold">✓ Applica Filtri</button>
                        <button id="btn-reset-filters" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition text-sm font-semibold">↻ Ripristina</button>
                    </div>
                 </form>
            </div>
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">📋 Valutazioni</h3><!-- Toggle Vista -->

            <div class="flex items-center gap-4"><span class="text-sm font-semibold text-gray-700">Visualizzazione:</span>
                <div class="toggle-switch" id="viewToggle">
                    <div class="toggle-icons">
                        <span class="toggle-icon-left">📊</span>
                        <span class="toggle-icon-right">🎴</span>
                    </div>
                    <div class="toggle-slider">

                    </div>
                </div>
            </div>
        </div>
        <div id="table-view" class=" overflow-x-auto">
            <table class="w-full table-striped" id="assessments-table">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">ID Assessment</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Anno di pubblicazione</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Possibile estinto</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Possibile estinto in natura</th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Categoria di conservazione</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Link IUCN</th>
                            <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Dettaglio</th>
                        </tr>
                    </thead>
                    <tbody id="assessments-tbody">

                        @foreach ($assessmentsData as $assessment)
                        <tr class="text-center py-8">

                            <td class="text-center">{{ $assessment['assessment_id'] }}</td>
                            <td class="text-center">{{ $assessment['year_published'] }}</td>
                            <td class="text-center">{{ $assessment['possibly_extinct'] ? 'Estinto' : 'Non ancora estinto' }}</td>
                            <td class="text-center">{{ $assessment['possibly_extinct_in_the_wild'] ? 'Estinto' : 'Non ancora estinto' }}</td>
                            <td class="text-center">{!! $category->getCategory($assessment['red_list_category_code'])['class'] !!}</td>
                            <td class="text-center"><a href="{{ $assessment['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">Vedi</a></td>
                            @if (array_key_exists('sis_taxon_id',$assessment))
                                <td class="text-center"><a href="{{ route('taxasis', ['sis_taxon_id' => $assessment['sis_taxon_id']] ) }}" target="_blank" class="btn btn-sm btn-outline-primary">Vedi</a></td>
                            @endif

                        </tr>
                        @endforeach

                    </tbody>
            </table>

        </div>
        <div id="cards-view" class="d-none p-6">
            <div id="cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($assessmentsData as $assessment)
                    <div class="assessment-card">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-gray-500">ID Assessment</p>
                            <p class="text-lg font-bold text-gray-800">{{ $assessment['assessment_id'] }}</p>
                            </div>
                        <span>{!! $category->getCategory($assessment['red_list_category_code'])['class'] !!}</span>
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        <div>
                            <p class="text-gray-600">Anno Pubblicazione</p>
                            <p class="font-semibold text-gray-800">{{ $assessment['year_published'] }}</p>
                        </div>

                        <div class="flex gap-2">
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs">Possibile Estinto</p>
                            <p class="font-semibold text-red-600">
                                {{ $assessment['possibly_extinct'] ? 'Estinto' : 'Non ancora estinto' }}
                            </p>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-600 text-xs">Estinto in Natura</p>
                            <p class="font-semibold ${item.possibly_extinct_in_the_wild ? 'text-red-600' : 'text-green-600'}">
                                {{ $assessment['possibly_extinct_in_the_wild'] ? 'Estinto' : 'Non ancora estinto' }}
                            </p>
                        </div>
                        </div>
                    </div>

                    <a href="{{ $assessment['url'] }}" target="_blank" rel="noopener noreferrer" class="inline-block w-full text-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold">
                        🔗 Vedi su IUCN
                    </a>
                    @if (array_key_exists('sis_taxon_id',$assessment))
                        <a href="{{ route('taxasis', ['sis_taxon_id' => $assessment['sis_taxon_id']] ) }}" rel="noopener noreferrer" class="mt-2 inline-block w-full text-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold">Dettaglio</a>
                    @endif

                </div>
                @endforeach

            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 ">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4"><!-- Selezione risultati per pagina -->
                <div class="flex items-center gap-3">
                    <label for="per-page" class="text-sm font-semibold text-gray-700">Risultati per pagina:</label>
                    <select id="per-page" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="10" {{ ($headers['page-items'][0] == '10') ? 'selected' : ''; }}>10</option>
                        <option value="20" {{ ($headers['page-items'][0] == '20') ? 'selected' : ''; }} >20</option>
                        <option value="50" {{ ($headers['page-items'][0] == '50') ? 'selected' : ''; }}>50</option>
                        <option value="100" {{ ($headers['page-items'][0] == '100') ? 'selected' : ''; }}>100</option>
                    </select>
                </div><!-- Pulsanti paginazione -->
                <div class="flex it ems-center gap-2">
                    @if ( $headers['current-page'][0] > 1)
                    <a href="{{ route('system', ['system' => $systemData['code'], 'page'=> ($headers['current-page'][0] > 1) ? $headers['current-page'][0] -1 : 1 , 'per_page'=> isset($_GET['per_page']) ? $_GET['per_page'] : '']) }}" id="btn-prev" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-semibold">
                        <i class="fas fa-chevron-left mr-2"></i>Precedente
                    </a>
                    @endif

                    <div id="pagination-info" class="px-4 py-2 bg-white rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 min-w-[150px] text-center">
                        Pagina {{ $headers['current-page'][0] }} di {{ $headers['total-pages'][0] }}
                    </div>
                    <a href="{{ route('system', ['system' => $systemData['code'], 'page'=> $headers['current-page'][0]+1, 'per_page'=> isset($_GET['per_page']) ? $_GET['per_page'] : '' ]) }}"
                         id="btn-next" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-semibold">
                        Successiva<i class="fas fa-chevron-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
     </div>
    </div>

    <!-- Legenda categorie IUCN -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-100 to-teal-100 p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-info-circle mr-2 text-teal-600"></i>Legenda Categorie IUCN Red List</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg border border-red-200">
                    <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">EX</span>
                    <span class="text-sm text-gray-700"><strong>Extinct</strong> - Estinto</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg border border-red-200">
                    <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">EW</span>
                    <span class="text-sm text-gray-700"><strong>Extinct in the Wild</strong> - Estinto in natura</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg border border-red-200">
                    <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">CR</span>
                    <span class="text-sm text-gray-700"><strong>Critically Endangered</strong> - In pericolo critico</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <span class="inline-block px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded">EN</span>
                    <span class="text-sm text-gray-700"><strong>Endangered</strong> - In pericolo</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <span class="inline-block px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded">VU</span>
                    <span class="text-sm text-gray-700"><strong>Vulnerable</strong> - Vulnerabile</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <span class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded">NT</span>
                    <span class="text-sm text-gray-700"><strong>Near Threatened</strong> - Quasi minacciato</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <span class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">LC</span>
                    <span class="text-sm text-gray-700"><strong>Least Concern</strong> - Minor preoccupazione</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded">DD</span>
                    <span class="text-sm text-gray-700"><strong>Data Deficient</strong> - Dati insufficienti</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="inline-block px-3 py-1 bg-gray-400 text-white text-xs font-bold rounded">NE</span>
                    <span class="text-sm text-gray-700"><strong>Not Evaluated</strong> - Non valutato</span>
                </div>
            </div>
        </div>
    </div>

  </main>

@endsection
