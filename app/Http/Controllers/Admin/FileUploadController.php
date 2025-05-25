<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120' // 5MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('products', 'public');
        
        return response()->json([
            'success' => true,
            'url' => Storage::url($path)
        ]);
    }
} 