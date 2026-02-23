<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Iucn\IucnApiService;
class ApiController extends Controller
{

    public function index(){

        $iucnService = new IucnApiService();
        $systems = $iucnService->getSystems();
        //dd($systems);
        $countries = $iucnService->getCountries();
        return view('dashboard', compact('countries','systems'));
    }

    public function getSingleSystem(Request $request){
       // dd($request->all());
        $iucnService = new IucnApiService();
        $system = $request->input('system');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page',20);


        $iucnService = new IucnApiService();
        $assessmentsData = $iucnService->getAssessmentsBySystem($system, null, $page, $perPage);
    //dd($assessmentsData);
        $systemData = $assessmentsData['items']['system'] ?? [];
       // dd($systemData);
        $headers = $assessmentsData['raw_headers'] ?? [];
        //dd($headers);
        $assessmentsData = $assessmentsData['items']['assessments'] ?? [];
        return view('system', compact('systemData','assessmentsData','headers'));
    }
    public function getAssessmentsBySystem(Request $request){
         $iucnService = new IucnApiService();
        $system = $request->input('system');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $iucnService = new IucnApiService();
        $assessmentsData = $iucnService->getAssessmentsBySystem($system, null, $page, $perPage);
        dd($assessmentsData);

       // return response()->json($assessmentsData);
    }
}
