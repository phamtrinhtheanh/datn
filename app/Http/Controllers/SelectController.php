<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function categories()
    {
        return Category::all();
    }
    public function brands()
    {
        return Brand::all();
    }
}
