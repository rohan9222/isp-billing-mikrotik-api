<?php

namespace App\Http\Controllers;

use App\Models\MainSiteData;
use App\Models\PackageList;
use Illuminate\Http\Request;

class MainSiteController extends Controller
{
    public function index()
    {
        $siteData = MainSiteData::getActive();
        $packages = \App\Models\PackageList::orderBy('sort_order')
            ->where('show_on_site', true)
            ->limit(4)
            ->get();

        return view('main-site', compact('siteData', 'packages'));
    }

    public function allPackages()
    {
        $siteData = \App\Models\MainSiteData::getActive();
        $packages = \App\Models\PackageList::orderBy('sort_order')
            ->where('show_on_site', true)
            ->get();

        return view('all-packages', compact('siteData', 'packages'));
    }
}
