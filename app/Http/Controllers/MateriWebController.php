<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Materi::with(['image', 'detailMateri', 'class']);

        // Check if the search parameter exists
        if ($request->has('search') && $request->search !== null) {
            $query->where('materi_title', 'like', '%' . $request->search . '%')
                ->orWhere('materi_description', 'like', '%' . $request->search . '%')
                ->orWhere('materi_kategori', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $materi = $query->paginate(10);

        // Pass the materi to the view
        return view('pages.materi.index', compact('materi'));
    }

    public function create()
    {
        $classes = \App\Models\kelas::all();
        return view('pages.materi.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|exists:kelas,id',
            'materi_title' => 'required|string|max:255',
            'materi_description' => 'required|string',
            'materi_kategori' => 'required|string|max:255',
            'materi_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the materi
        $materi = Materi::create([
            'class_id' => $validatedData['class_id'],
            'materi_title' => $validatedData['materi_title'],
            'materi_description' => $validatedData['materi_description'],
            'materi_kategori' => $validatedData['materi_kategori'],
        ]);

        // Handle image uploads
        if ($request->hasFile('materi_images')) {
            foreach ($request->file('materi_images') as $image) {
                $path = $image->store('materi_images', 'public');
                MateriImage::create([
                    'materi_id' => $materi->id,
                    'materi_image_path' => $path,
                ]);
            }
        }

        return redirect()->route('materi.index')->with('success', 'Materi successfully created.');
    }

    public function edit($id)
    {
        $materi = Materi::with('image')->findOrFail($id);
        $classes = \App\Models\kelas::all();
        return view('pages.materi.edit', compact('materi', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|exists:kelas,id',
            'materi_title' => 'required|string|max:255',
            'materi_description' => 'required|string',
            'materi_kategori' => 'required|string|max:255',
            'materi_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $materi = Materi::findOrFail($id);

        // Update the materi
        $materi->update([
            'class_id' => $validatedData['class_id'],
            'materi_title' => $validatedData['materi_title'],
            'materi_description' => $validatedData['materi_description'],
            'materi_kategori' => $validatedData['materi_kategori'],
        ]);

        // Handle image uploads
        if ($request->hasFile('materi_images')) {
            foreach ($request->file('materi_images') as $image) {
                $path = $image->store('materi_images', 'public');
                MateriImage::create([
                    'materi_id' => $materi->id,
                    'materi_image_path' => $path,
                ]);
            }
        }

        return redirect()->route('materi.index')->with('success', 'Materi successfully updated.');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);

        // Delete related images
        foreach ($materi->images as $image) {
            if (Storage::exists('public/' . $image->materi_image_path)) {
                Storage::delete('public/' . $image->materi_image_path);
            }
            $image->delete();
        }

        // Delete the materi
        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi successfully deleted.');
    }
}
