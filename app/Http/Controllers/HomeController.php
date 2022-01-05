<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;


class HomeController extends Controller
{

    /**
     * home method that shows a React DataTable from
     * http://feeds.spotahome.com/ads-housinganywhere.json
     */
    public function index(Request $request)
    {
        return view('home');
    }
}
