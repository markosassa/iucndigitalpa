<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Iucn\IucnApiService;
class ApiController extends Controller
{

    public function index(){
        $iucnService = new IucnApiService();
        $systems = $iucnService->getSystems();
        $countries = $iucnService->getCountries();
        return view('dashboard', compact('countries','systems'));
    }
}
