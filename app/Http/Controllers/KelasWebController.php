<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\kelas_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with('user');

        // Search functionality
        if ($request->has('search') && $request->search !== null) {
            $query->where('class_name', 'like', '%' . $request->search . '%')
                ->orWhere('class_description', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $kelas = $query->paginate(10);

        return view('pages.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $users = \App\Models\User::where('roles', 'STAFF')->get();

        return view('pages.kelas.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'class_description' => 'nullable|string|max:500',
            'user_id' => 'required|exists:users,id',
        ]);
        $newPin = str_pad(random_int(00000, 99999), 5, '0', STR_PAD_RIGHT);
        $randomColor = '#' . str_pad(dechex(mt_rand(0, 0x7F7F7F)), 6, '0', STR_PAD_LEFT);

        $class = Kelas::create([
            'class_name' => $validatedData['class_name'],
            'class_description' => $validatedData['class_description'],
            'class_color' => $randomColor,
            'class_pin' => $newPin,
            'user_id' => $validatedData['user_id']
        ]);

        if ($class) {
            kelas_detail::create([
                'class_id' => $class->id,
                'user_id' => $validatedData['user_id'],
            ]);
            return redirect()->route('kelas.index')->with('success', 'Class successfully created.');
        }

        // If class creation fails, redirect back with an error message
        return redirect()->back()->with('error', 'Failed to create class. Please try again.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $users = User::where('roles', 'STAFF')->get();

        return view('pages.kelas.edit', compact('kelas', 'users'));
    }

    public function update(Request $request, $id)
    {
        // Find the class by ID
        $kelas = Kelas::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'class_description' => 'nullable|string|max:500',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $classUpdated = $kelas->update([
                'class_name' => $validatedData['class_name'],
                'class_description' => $validatedData['class_description'],
                'user_id' => $validatedData['user_id'],
            ]);
            if ($classUpdated) {
                $kelasDetail = kelas_detail::where('class_id', $kelas->id)->first();

                if ($kelasDetail) {
                    $kelasDetail->update([
                        'user_id' => $validatedData['user_id'],
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('kelas.index')->with('success', 'Class successfully updated.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update class. Please try again.');
        }
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Class successfully deleted.');
    }
}
