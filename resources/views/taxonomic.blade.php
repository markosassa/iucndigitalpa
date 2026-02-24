@extends('layouts.app')
@section('content')
@php
    $category = new App\Http\Controllers\CategoryController();

    $taxasisid = data_get($taxasis, 'sis_id');
    $scientificName = data_get($taxasis, 'taxon.scientific_name', '--');

    $commonNames = data_get($taxasis, 'taxon.common_names', []);

@endphp

<!-- Main Content -->
<main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full"><!-- Loading State -->

    <div id="content-container" style="" class="fade-in"><!-- Species Header -->
        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm mb-2">
                        ID Specie:
                        <span id="species-id" class="font-mono font-semibold text-gray-800">
                            {{ $taxasisid ?? '--' }}
                        </span>
                    </p>
                    <h1 id="scientific-name" class="text-4xl font-bold text-gray-800 italic mb-2"> {{$scientificName}}</h1>
                </div>
                <button id="favorite-btn" data-sistaxa="{{ $taxasisid }}" data-scname="{{ $scientificName }}"  class="heart-btn text-4xl hover:scale-110 transition" title="Aggiungi ai preferiti"> 🤍 </button>
            </div>

            <!-- Common Names -->

            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Nomi Comuni</h2>

                @if(is_array($commonNames) && count($commonNames) > 0)
                    <div id="common-names-container" class="space-y-2">
                        @foreach ($commonNames as $item)
                            @php
                                $isMain = (bool) data_get($item, 'main', false);
                                $name = data_get($item, 'name', '--');
                                $lang = data_get($item, 'language', '');
                            @endphp

                            <p class="text-gray-600 text-sm {{ $isMain ? 'font-bold text-gray-900' : '' }}">
                                {{ $name }}
                                @if($lang) <span class="text-gray-400">({{ $lang }})</span> @endif
                                @if($isMain) <span class="text-emerald-700 font-semibold">— Main</span> @endif
                            </p>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">--</p>
                @endif
            </div>
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-gray-500 text-sm">Regno</p>
                    <p id="kingdom" class="font-semibold text-gray-800">{{ data_get($taxasis, 'taxon.kingdom_name', '--')  }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Phylum</p>
                    <p id="phylum" class="font-semibold text-gray-800">{{ data_get($taxasis, 'taxon.phylum_name', '--')  }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Classe</p>
                    <p id="class" class="font-semibold text-gray-800">{{ data_get($taxasis, 'taxon.class_name', '--')  }}</p>
                </div>
            </div>
        </section><!-- Assessments Section -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">📋 Valutazioni IUCN</h2>
                <span id="assessment-count" class="text-sm text-gray-600 bg-white px-3 py-2 rounded-lg">{{ count(data_get($taxasis,'assessments')) }} valutazioni</span>
            </div>
        <!-- Filters -->


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

            </div>
        </section>
    </div>
    @include('legenda')
</main><!-- Toast Notification -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50"></div><!-- Footer -->


@endsection
