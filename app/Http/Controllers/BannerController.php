<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function show(Request $request)
    {
        $banners = Banner::where('active',true)->orderBy('created_at','DESC')->limit(4)->get();
        return response()->json([
            'banners' => $banners,
        ]);
    }
}
