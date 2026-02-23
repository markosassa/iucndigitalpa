@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Carbon;
@endphp

<main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
    <div class="fade-in">

        <section class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">❤️ Preferiti</h1>
                    <p class="text-gray-500 text-sm mt-2">
                        Totale: <span class="font-semibold text-gray-800">{{ is_array($favorites) ? count($favorites) : 0 }}</span>
                    </p>
                </div>

                <a href="#" onclick="window.history.back(); return false;"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition font-semibold">
                    ← Indietro
                </a>
            </div>
        </section>

        <section class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Lista preferiti</h2>
            </div>

            <table class="w-full">
                <thead class="bg-emerald-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">Assessment ID</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Data di aggiunta</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">Link</th>
                    </tr>
                </thead>

                <tbody>
                    @if(is_array($favorites) && count($favorites) > 0)
                        @foreach($favorites as $fav)
                            @php
                                $id = data_get($fav, 'assessment_id');
                                $addedAt = data_get($fav, 'added_at');
                                $addedAtFmt = '--';

                                if (!empty($addedAt)) {
                                    try {
                                        $addedAtFmt = Carbon::parse($addedAt)->timezone(config('app.timezone'))->format('d/m/Y H:i');
                                    } catch (\Throwable $e) {
                                        $addedAtFmt = $addedAt; // fallback
                                    }
                                }
                            @endphp

                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800 font-mono">
                                    {{ $id ?? '--' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $addedAtFmt }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if(!empty($id))
                                        <a href="{{ route('taxasis', ['sis_taxon_id' => $id]) }}"
                                           class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition font-semibold text-sm">
                                            Visualizza ↗
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">--</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                Nessun preferito salvato.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </section>

    </div>
</main>
@endsection