@extends('layouts.app')
@section('content')

<main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full"><!-- Welcome Section -->
    <section class="mb-12 fade-in">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Benvenuto nella Dashboard IUCN</h2>
            <p class="text-gray-600">Esplora i dati sulla conservazione delle specie dai Sistemi Ecologici o seleziona una nazione</p>
        </div>
    </section>

<!-- Sistemi Ecologici Section -->
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-6"><span class="text-3xl"><i class="fa-solid fa-earth-europe"></i></span>
            <h2 class="text-2xl font-bold text-gray-800">Sistemi Ecologici</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($systems["systems"] as $item)

                <a href="{{ route('system', ['system' => $item['code']]) }}" class="system-card group">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-8 rounded-xl shadow-lg">
                    <h3 class="text-2xl font-bold mb-2">{{ $item["description"]["en"] }}</h3>

                    <div class="flex items-center gap-2 text-green-200 group-hover:translate-x-2 transition-transform">
                        <span class="text-lg">→</span>
                        <span class="text-sm font-semibold">Guarda valutazioni</span>
                    </div>
                </div>
            </a>
            @endforeach



        </div>

    </section>


    <!-- Nazioni Section -->
    <section class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <span class="text-3xl">🗺️</span>
            <h2 class="text-2xl font-bold text-gray-800">Esplora per Nazione</h2>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6"><!-- Search Bar -->
            <div class="mb-6">
                <input type="text" placeholder="🔍 Cerca una nazione..." id="countrySearch" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>
        <!-- Countries Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-96 overflow-y-auto">
                <!-- Country Item Template -->
                @foreach ($countries["countries"] as $item)
                <a href="{{ route('country', ['country' => $item['code']]) }}" class="country-badge group">
                    <div class="flex flex-col items-center p-4 rounded-lg bg-gray-50 hover:bg-emerald-50 transition-colors border border-gray-200 hover:border-emerald-400">
                        <span class="fi fi-{{ strtolower($item['code']) }}"></span>

                        <span class="text-sm font-medium text-gray-700 text-center truncate">{{ $item["description"]["en"] }}</span>
                        <span class="text-xs text-gray-400 mt-1">{{ $item["code"] }}</span>
                    </div>
                </a>
                @endforeach


            </div>
            <p id="countryNoResults" class="hidden text-sm text-gray-500 mt-2">
                Nessun risultato.
            </p>

        </div>
    </section>



</main>
@endsection
