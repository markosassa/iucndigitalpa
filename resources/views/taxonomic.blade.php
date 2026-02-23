@extends('layouts.app')
@section('content')
@php
    $category = new App\Http\Controllers\CategoryController();

@endphp

<!-- Main Content -->
<main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full"><!-- Loading State -->

    <div id="content-container" style="" class="fade-in"><!-- Species Header -->
        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm mb-2">ID: <span id="species-id" class="font-mono"> {{ $taxasis['sis_id'] }}</span></p>
                    <h1 id="scientific-name" class="text-4xl font-bold text-gray-800 italic mb-2">{{$taxasis['taxon']['scientific_name']}}</h1>
                    <p id="taxon-name" class="text-xl text-gray-600 mb-6">{{$taxasis['taxon']['scientific_name']}}</p>
                </div>
                <button id="favorite-btn" data-sistaxa="{{ $taxasis['sis_id'] }}" class="heart-btn text-4xl hover:scale-110 transition" title="Aggiungi ai preferiti"> 🤍 </button>
            </div>

            <!-- Common Names -->
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Nomi Comuni</h2>
                <div id="common-names-container" class="space-y-2">
                    @foreach ($taxasis['taxon']['common_names'] as $item)
                        <p class="text-gray-500 text-sm {{ ($item['main'] == true) ? 'font-bold' : '' }}" >{{ $item['name'] }} {{ ($item['main'] == true) ? '(Main name)' : '' }}</p>
                    @endforeach

                </div>
            </div>
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-gray-500 text-sm">Regno</p>
                    <p id="kingdom" class="font-semibold text-gray-800">{{ $taxasis['taxon']['kingdom_name'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Phylum</p>
                    <p id="phylum" class="font-semibold text-gray-800">{{ $taxasis['taxon']['phylum_name'] }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Classe</p>
                    <p id="class" class="font-semibold text-gray-800">{{ $taxasis['taxon']['class_name'] }}</p>
                </div>
            </div>
        </section><!-- Assessments Section -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">📋 Valutazioni IUCN</h2>
                <span id="assessment-count" class="text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">{{ count($taxasis['assessments']) }} valutazioni</span>
            </div>
        <!-- Filters -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Filtra per Anno</label>
                        <input type="number" id="year-filter" placeholder="Es. 2024" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Filtra per Categoria</label>
                        <select id="category-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Tutte le categorie</option>
                            <option value="EX">EX - Estinto</option>
                            <option value="EW">EW - Estinto in Natura</option>
                            <option value="CR">CR - Criticamente in Pericolo</option>
                            <option value="EN">EN - In Pericolo</option>
                            <option value="VU">VU - Vulnerabile</option>
                            <option value="NT">NT - Quasi Minacciato</option>
                            <option value="LC">LC - Minima Preoccupazione</option>
                            <option value="DD">DD - Dati Insufficienti</option>
                            <option value="NE">NE - Non Valutato</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button id="apply-filters-btn" class="flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-semibold"> Applica Filtri </button> <button id="reset-filters-btn" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition font-semibold"> Ripristina </button>
                    </div>
                </div>
            </div>

        <!-- Assessments Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-emerald-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-bold ">ID Assessment</th>
                            <th class="px-6 py-4 text-left text-sm font-bold ">Anno di pubblicazione</th>
                            <th class="px-6 py-4 text-left text-sm font-bold ">Possibile estinto</th>
                            <th class="px-6 py-4 text-center text-sm font-bold ">Possibile estinto in natura</th>
                            <th class="px-6 py-4 text-left text-sm font-bold ">Categoria di conservazione</th>
                            <th class="px-6 py-4 text-center text-sm font-bold "></th>
                        </tr>
                    </thead>
                    <tbody id="assessments-table-body">
                        @foreach ($taxasis['assessments'] as $assessment)
                            <tr>
                                <td class="text-center "><a href="{{ route('valutazione', ['assessment_id'=>$assessment['assessment_id']]) }}" target="_blank" class=" btn btn-sm inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-semibold">{{ $assessment['assessment_id'] }}</a></td>
                                <td class="text-center">{{ $assessment['year_published'] }}</td>
                                <td class="text-center">{{ $assessment['possibly_extinct'] ? 'Estinto' : 'Non ancora estinto' }}</td>
                                <td class="text-center">{{ $assessment['possibly_extinct_in_the_wild'] ? 'Estinto' : 'Non ancora estinto' }}</td>
                                <td class="text-center">{!! $category->getCategory($assessment['red_list_category_code'])['class'] !!}</td>
                                <td class="text-center"><a href="{{ $assessment['url'] }}" target="_blank"  class=" btn btn-sm inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-semibold">Apri su iucnredlist.org ↗</a></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table><!-- Pagination -->
                <div id="pagination-container" class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-2"><label class="text-sm text-gray-700 font-semibold">Risultati per pagina:</label>
                        <select id="per-page-select" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="prev-page-btn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-semibold text-sm"> ← Precedente </button> <span id="page-info" class="text-sm text-gray-700 font-semibold px-4">Pagina 1</span> <button id="next-page-btn" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition font-semibold text-sm"> Successiva → </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

</main><!-- Toast Notification -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50"></div><!-- Footer -->


@endsection
