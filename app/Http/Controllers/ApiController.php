<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Iucn\IucnApiService;
class ApiController extends Controller
{
    public function __construct(){
        $this->iucnService = new IucnApiService();
    }
    public function index(){


        $systems = $this->iucnService->getSystems();
        //dd($systems);
        $countries = $this->iucnService->getCountries();
        return view('dashboard', compact('countries','systems'));
    }

    public function getSingleSystem(Request $request){
       // dd($request->all());

        $system = $request->input('system');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);


        $filter = [
            'year'=>$request->input('published_year'),
            'pe'=>$request->input('pe'),
            'pew'=>$request->input('pew')

        ];


        $assessmentsData = $this->iucnService->getAssessmentsBySystem($system, $filter, $page, $perPage);
        //dd($assessmentsData);
        $systemData = $assessmentsData['items']['system'] ?? [];
        // dd($systemData);
        $headers = $assessmentsData['raw_headers'] ?? [];
        //dd($headers);
        $assessmentsData = $assessmentsData['items']['assessments'] ?? [];
        return view('system', compact('systemData','assessmentsData','headers'));
    }
    public function getAssessmentsBySystem(Request $request){

        $system = $request->input('system');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);


        $assessmentsData = $this->iucnService->getAssessmentsBySystem($system, null, $page, $perPage);
        dd($assessmentsData);

       // return response()->json($assessmentsData);
    }
    public function getSingleCountry(Request $request){
       // dd($request->all());

        $country = $request->input('country');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);



        $assessmentsData = $this->iucnService->getAssessmentsByCountry($country, null, $page, $perPage);

        $countryData = $assessmentsData['items']['country'] ?? [];
        // dd($countryData);
        $headers = $assessmentsData['raw_headers'] ?? [];
        //dd($headers);
        $assessmentsData = $assessmentsData['items']['assessments'] ?? [];

        return view('country', compact('countryData','assessmentsData','headers'));
    }


    public function getTaxaSis(Request $request){

        $taxonSis = $request->input('sis_taxon_id');
        $taxasis = $this->iucnService->getTaxonSis($taxonSis);
        return view('taxonomic', compact('taxasis'));

    }

    public function getSingleAssessment(Request $request){
        $assessment_id = $request->input('assessment_id');
        $assessment = $this->iucnService->getAssessment($assessment_id);
        //echo "<pre>";print_r($assessment);echo "</pre>";die();
        //dd($assessment);
        return view('dettaglioassessment', compact('assessment'));
    }
}
