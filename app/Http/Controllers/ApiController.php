<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Iucn\IucnApiService;
class ApiController extends Controller
{
    //

    public function index(){
        $iucnService = new IucnApiService();
        dd($iucnService->getSystems());
    }
}
