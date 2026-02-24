<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Services\Iucn\IucnApiService;
class ApiController extends Controller
{
    // Iniettiamo il servizio IUCN tramite il costruttore, in modo da poterlo utilizzare in tutti i metodi del controller.
    // Questo facilita anche eventuali test unitari, permettendo di mockare il servizio se necessario.
    public function __construct(){
        $this->iucnService = new IucnApiService();
    }
    // Dashboard, con caching per 1 ora, chiave fissa 'dashboard.systems' e 'dashboard.countries' per i dati dei sistemi e dei paesi.
    public function index(){

        $systems = Cache::remember(
            'dashboard.systems',
            now()->addHour(),
            fn() => $this->iucnService->getSystems()
        );

        $countries = Cache::remember(
            'dashboard.countries',
            now()->addHour(),
            fn() => $this->iucnService->getCountries()
        );
        return view('dashboard', compact('countries','systems'));
    }

    // Dettaglio sistema, con caching per 5 minuti, chiave basata su system, page, per_page e filtri (year, pe, pew).
    // Restituisce i dati del sistema e le valutazioni associate.
    public function getSingleSystem(Request $request){

        $system = $request->input('system');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);


        $filter = [
            'year'=>$request->input('published_year'),
            'pe'=>$request->input('pe'),
            'pew'=>$request->input('pew')

        ];


        $cacheKey = 'list.system.' . $system
            . '.p' . $page
            . '.pp' . $perPage
            . '.f' . md5(json_encode($filter));

        $assessmentsData = Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            fn() => $this->iucnService->getAssessmentsBySystem($system, $filter, $page, $perPage)
        );
        $systemData = $assessmentsData['items']['system'] ?? [];
        $headers = $assessmentsData['raw_headers'] ?? [];
        $assessmentsData = $assessmentsData['items']['assessments'] ?? [];
        return view('system', compact('systemData','assessmentsData','headers'));
    }

    // Dettaglio paese, con caching per 5 minuti, chiave basata su country, page, per_page e filtri (year, pe, pew).
    //Restituisce i dati del paese e le valutazioni associate.
    public function getSingleCountry(Request $request){

        $country = $request->input('country');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);

        $filter = [
            'year'=>$request->input('published_year'),
            'pe'=>$request->input('pe'),
            'pew'=>$request->input('pew')

        ];

        $cacheKey = 'list.country.' . $country
            . '.p' . $page
            . '.pp' . $perPage
            . '.f' . md5(json_encode($filter));

        $assessmentsData = Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            fn() => $this->iucnService->getAssessmentsByCountry($country, $filter, $page, $perPage)
        );

        $countryData = $assessmentsData['items']['country'] ?? [];
        $headers = $assessmentsData['raw_headers'] ?? [];
        $assessmentsData = $assessmentsData['items']['assessments'] ?? [];


        return view('country', compact('countryData','assessmentsData','headers'));
    }


    // Dettaglio specie, con caching per 5 minuti, chiave basata su sis_taxon_id
    public function getTaxaSis(Request $request){

        $taxonSis = $request->input('sis_taxon_id');
        $cacheKey = 'sis_taxon_id.' . $taxonSis;
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);

        $filter = [
            'year'=>$request->input('published_year'),
            'pe'=>$request->input('pe'),
            'pew'=>$request->input('pew')

        ];
        $taxasis = Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            fn() => $this->iucnService->getTaxonSis($taxonSis)
        );

        return view('taxonomic', compact('taxasis'));

    }

    // Dettaglio valutazione, con caching per 5 minuti, chiave basata su assessment_id
    public function getSingleAssessment(Request $request){
        $assessment_id = $request->input('assessment_id');

        $cacheKey = 'assessment.' . $assessment_id;

        $assessment = Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            fn() => $this->iucnService->getAssessment($assessment_id)
        );
        $trendMap = [
            'increasing' => 'In aumento',
            'decreasing' => 'In diminuzione',
            'stable' => 'Stabile',
            'unknown' => 'Sconosciuto',
            'no_data' => 'Nessun dato'
        ];
        return view('dettaglioassessment', compact('assessment','trendMap'));
    }
}
