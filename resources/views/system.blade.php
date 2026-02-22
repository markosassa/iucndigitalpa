@extends('layouts.app')
@section('content')
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 py-8"><!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-8 fade-in"><a href="/" class="text-teal-600 hover:text-teal-700 font-semibold">Dashboard</a> <span class="text-gray-400">/</span> <a href="#" class="text-teal-600 hover:text-teal-700 font-semibold">Sistemi</a> <span class="text-gray-400">/</span> <span class="text-gray-700 font-semibold">Terrestre</span>
    </nav><!-- Card principale sistema -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 card-hover">
     <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-6">
      <div class="flex items-center gap-3">
       <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center"><i class="fas fa-globe text-2xl"></i>
       </div>
       <div>
        <h1 class="text-3xl font-bold">Terrestre</h1>
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
         Valutazioni totali nel sistema
        </div>
       </div>
             </div>
     </div>
    </div><!-- Sezione Assessments -->

    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 card-hover">
     
     <div class="card-body p-0"><!-- Tabella Assessments -->
      <div class="overflow-x-auto">
       <table class="w-full table-striped" id="assessments-table">
        <thead class="bg-gray-100 border-b border-gray-200">
         <tr>
          <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">ID Assessment</th>
          <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Anno di pubblicazione</th>
          <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Possibile estitno</th>
          <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Possibile estinto in natura</th>
          <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 cursor-pointer hover:bg-gray-200">Categoria di conservazione</th>
          <th class="px-6 py-4 text-center text-sm font-bold text-gray-700">Link IUCN</th>
         </tr>
        </thead>
        <tbody id="assessments-tbody">
            @php
                $category = new App\Http\Controllers\CategoryController();
            @endphp
            @foreach ($assessmentsData as $assessment)
            <tr class="text-center py-8">
                
                <td class="text-center">{{ $assessment['assessment_id'] }}</td>            
                <td class="text-center">{{ $assessment['year_published'] }}</td>            
                <td class="text-center">{{ $assessment['possibly_extinct'] ? 'Estinto' : 'Non ancora estinto' }}</td>            
                <td class="text-center">{{ $assessment['possibly_extinct_in_the_wild'] ? 'Estinto' : 'Non ancora estinto' }}</td>            
                <td class="text-center">{!! $category->getCategory($assessment['red_list_category_code'])['class'] !!}</td>            
                <td class="text-center"><a href="{{ $assessment['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">Vedi</a></td>            
                
            </tr>
            @endforeach
        
        </tbody>
       </table>
      </div><!-- Paginazione -->
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 d-none">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4"><!-- Selezione risultati per pagina -->
            <div class="flex items-center gap-3">
                <label for="per-page" class="text-sm font-semibold text-gray-700">Risultati per pagina:</label> 
                <select id="per-page" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                     <option value="10">10</option> <option value="25" selected>25</option>
                      <option value="50">50</option> <option value="100">100</option>
                </select>
            </div><!-- Pulsanti paginazione -->
            <div class="flex it ems-center gap-2">
                <button id="btn-prev" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-semibold"> 
                    <i class="fas fa-chevron-left mr-2"></i>Precedente </button>
                <div id="pagination-info" class="px-4 py-2 bg-white rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 min-w-[150px] text-center">
                    Pagina 0 di 0
                </div>
                <button id="btn-next" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition text-sm font-semibold"> Successiva<i class="fas fa-chevron-right ml-2"></i> </button>
            </div><!-- Indicatore pagine -->
            <div id="pagination-pages" class="flex gap-1 flex-wrap"><!-- Pagine aggiunte dinamicamente -->
            </div>
       </div>
      </div>
     </div>
    </div><!-- Legenda categorie IUCN -->
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
       <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200"><span class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded">NT</span> <span class="text-sm text-gray-700"><strong>Near Threatened</strong> - Quasi minacciato</span>
       </div>
       <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200"><span class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">LC</span> <span class="text-sm text-gray-700"><strong>Least Concern</strong> - Minor preoccupazione</span>
       </div>
       <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200"><span class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded">DD</span> <span class="text-sm text-gray-700"><strong>Data Deficient</strong> - Dati insufficienti</span>
       </div>
       <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200"><span class="inline-block px-3 py-1 bg-gray-400 text-white text-xs font-bold rounded">NE</span> <span class="text-sm text-gray-700"><strong>Not Evaluated</strong> - Non valutato</span>
       </div>
      </div>
     </div>
    </div>s
    
  </main><!-- Footer -->


@endsection