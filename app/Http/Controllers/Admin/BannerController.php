<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('order')->get();

        return Inertia::render('Admin/Banners/Index', [
            'banners' => $banners
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|string|max:255',
            'position' => 'required|in:top,bottom',
            'order' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        $path = $request->file('image')->store('public/banners');

        Banner::create([
            'name' => $request->name,
            'image' => str_replace('public/', '', $path),
            'url' => $request->url,
            'position' => $request->position,
            'order' => $request->order,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Banner created successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|string|max:255',
            'position' => 'required|in:top,bottom',
            'order' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image) {
                Storage::delete('public/' . $banner->image);
            }

            $path = $request->file('image')->store('public/banners');
            $data['image'] = str_replace('public/', '', $path);
        }

        $banner->update($data);

        return redirect()->back()->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::delete('public/' . $banner->image);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully.');
    }
}
