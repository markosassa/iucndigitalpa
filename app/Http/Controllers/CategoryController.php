<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private array $categories;
    // Inizializziamo le categorie con i dati fissi, includendo l'ID, il nome, il significato e la classe CSS per il badge colorato.
    public function __construct()
    {
        $this->categories = [
            ['id' => 'EX', 'name' => 'Extinct','meaning' => 'Estinto', 'class'=>'<span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">EX</span> '],
            ['id' => 'EW', 'name' => 'Extinct in the Wild','meaning' => 'Estinto in natura', 'class'=>'<span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">EW</span> '],
            ['id' => 'CR', 'name' => 'Critically Endangered','meaning' => 'In pericolo critico','class'=>'<span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">CR</span> '],
            ['id' => 'EN', 'name' => 'Endangered','meaning' => 'In pericolo', 'class'=>'<span class="inline-block px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded">EN</span> '],
            ['id' => 'VU', 'name' => 'Vulnerable','meaning' => 'Vulnerabile', 'class'=>'<span class="inline-block px-3 py-1 bg-orange-600 text-white text-xs font-bold rounded">VU</span> '],
            ['id' => 'NT', 'name' => 'Near Threatened','meaning' => 'Quasi minacciato', 'class'=>'<span class="inline-block px-3 py-1 bg-yellow-600 text-white text-xs font-bold rounded">NT</span> '],
            ['id' => 'LC', 'name' => 'Least Concern','meaning' => 'Minima preoccupazione', 'class'=>'<span class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">LC</span> '],
            ['id' => 'DD', 'name' => 'Data Deficient','meaning' => 'Dati insufficienti', 'class'=>'<span class="inline-block px-3 py-1 bg-gray-600 text-white text-xs font-bold rounded">DD</span> '],
            ['id' => 'NE', 'name' => 'Not Evaluated','meaning' => 'Non valutato', 'class'=>'<span class="inline-block px-3 py-1 bg-gray-600 text-white text-xs font-bold rounded">NE</span> '],
        ];

    }
    // Restituisce tutte le categorie in formato JSON
    public function getCategories()
    {
        $categories = $this->categories;
        return response()->json($categories);
    }
    // Restituisce una categoria specifica in base all'ID, se non trovata restituisce una categoria di default con l'ID richiesto e un badge grigio.
    public function getCategory($id)
    {
        // Logica per ottenere una categoria specifica
        $category = collect($this->categories)->firstWhere('id', $id);
        if($category == null){
            return ['id' => $id, 'name' => $id,'meaning' => '', 'class'=>'<span class="inline-block px-3 py-1 bg-gray-600 text-white text-xs font-bold rounded">'.$id.'</span> '];
        }
        return $category;
    }
}
