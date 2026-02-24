@extends('layouts.app')

@section('content')
@php
    $category = new App\Http\Controllers\CategoryController();


    $assessmentId = data_get($assessment, 'assessment_id');
    $scientificName = data_get($assessment, 'taxon.scientific_name', '--');

    $commonNames = data_get($assessment, 'taxon.common_names', []);
    $iucnUrl = data_get($assessment, 'url');

    $populationTrendRaw = data_get($assessment, 'population_trend');

    // Mappatura trend (se arriva già descrittivo, lo stampiamo com'è)
    $populationTrendRaw = $populationTrendRaw['description'];

    $populationTrend = $populationTrendRaw
        ? ($trendMap[strtolower(trim($populationTrendRaw['en']))] ?? $populationTrendRaw)
        : '--';

    $conservationInPlace = data_get($assessment, 'supplementary_info.conservation_actions_in_place', []);

    // Documentation è un array di sezioni (range, population, habitats, threats, measures, rationale, ...)
    $documentation = data_get($assessment, 'documentation', []);

    // Sistemi “più ampi”
    $systems = data_get($assessment, 'systems', []);
@endphp

<main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">

    <div id="content-container" class="fade-in">


        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <p class="text-gray-500 text-sm mb-2">
                        ID Valutazione:
                        <span id="species-id" class="font-mono font-semibold text-gray-800">
                            {{ $assessmentId ?? '--' }}
                        </span>
                    </p>

                    <h1 id="scientific-name" class="text-4xl font-bold text-gray-800 italic mb-2">
                        {{ $scientificName }}
                    </h1>



                    {{-- Link IUCN --}}
                    <div class="flex flex-wrap items-center gap-3">
                        @if(!empty($iucnUrl))
                            <a href="{{ $iucnUrl }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-semibold">
                                Apri su iucnredlist.org ↗
                            </a>
                        @else
                            <span class="text-sm text-gray-500">Link IUCN: --</span>
                        @endif

                        {{-- Trend popolazione --}}
                        <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                            Trend popolazione:
                            <span class="font-normal">{{ $populationTrend }}</span>
                        </span>
                    </div>
                </div>


            </div>

            {{-- NOMI COMUNI --}}
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

            {{-- BASIC INFO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-gray-500 text-sm">Regno</p>
                    <p class="font-semibold text-gray-800">{{ data_get($assessment, 'taxon.kingdom_name', '--') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Phylum</p>
                    <p class="font-semibold text-gray-800">{{ data_get($assessment, 'taxon.phylum_name', '--') }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Classe</p>
                    <p class="font-semibold text-gray-800">{{ data_get($assessment, 'taxon.class_name', '--') }}</p>
                </div>
            </div>
        </section>

        {{-- CONSERVATION ACTIONS IN PLACE --}}
        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🛡️ Azioni di conservazione svolte sul posto</h2>

            @if(is_array($conservationInPlace) && count($conservationInPlace) > 0)
                <div class="space-y-4">
                    @foreach($conservationInPlace as $block)
                        @php
                            $blockName = data_get($block, 'name', '--');
                            $actions = data_get($block, 'actions', []);
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="font-semibold text-gray-800 mb-2">{{ $blockName }}</p>

                            @if(is_array($actions) && count($actions) > 0)
                                <ul class="list-disc pl-6 text-gray-700 space-y-1">
                                    @foreach($actions as $a)
                                        <li>
                                            <span class="font-semibold">{{ data_get($a, 'name', '--') }}:</span>
                                            <span>{{ data_get($a, 'value', '--') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 text-sm">--</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">--</p>
            @endif
        </section>

        {{-- DOCUMENTATION (interpretando HTML) --}}
        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📚 Documentation</h2>

            @php
                // Teniamo solo campi valorizzati e non vuoti
                $docFiltered = [];
                if (is_array($documentation)) {
                    foreach ($documentation as $k => $v) {
                        if (is_string($v)) {
                            $trim = trim($v);
                            if ($trim !== '') $docFiltered[$k] = $trim;
                        }
                    }
                }

                // Etichette più “umane”
                $docLabels = [
                    'range' => 'Distribuzione (Range)',
                    'population' => 'Popolazione',
                    'habitats' => 'Habitat',
                    'threats' => 'Minacce',
                    'measures' => 'Misure',
                    'use_trade' => 'Uso e commercio',
                    'rationale' => 'Razionale',
                    'trend_justification' => 'Giustificazione trend',
                    'taxonomic_notes' => 'Note tassonomiche',
                ];
            @endphp

            @if(count($docFiltered) > 0)
                <div class="space-y-6">
                    @foreach($docFiltered as $key => $html)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-2">
                                {{ $docLabels[$key] ?? ucfirst(str_replace('_',' ', $key)) }}
                            </h3>

                            {{-- Interpreta HTML in modo safe: solo quello che arriva.
                                 Se vuoi “sanitizzare” davvero, fallo lato controller con strip_tags consentiti. --}}
                            <div class="prose max-w-none text-gray-700">
                                {!! $html !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">--</p>
            @endif
        </section>

        <section class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🌍 Sistemi "più ampi"</h2>

            @if(is_array($systems) && count($systems) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($systems as $sys)
                        @php
                            $code = data_get($sys, 'code');
                            $label = data_get($sys, 'description.en') ?? data_get($sys, 'description', '--');
                        @endphp

                        @if($code !== null && $code !== '')
                            <a href="{{ route('system', ['systemCode' => $code]) }}"
                               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold">
                                {{ $label }}
                            </a>
                        @else
                            <span class="inline-flex items-center bg-gray-100 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                                {{ $label }}
                            </span>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">--</p>
            @endif
        </section>

    </div>


</main>

@endsection
