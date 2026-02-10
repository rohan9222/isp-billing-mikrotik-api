<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class MainSiteController extends Controller
{
    public function index()
    {
        return view('main-site');
    }
}
