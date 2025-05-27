<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\PersonalInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User not found']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Invalid credentials']);
        }

        if ($request->expectsJson() === false) {
            if ($user->roles !== 'ADMIN') {
                return redirect()->back()->withErrors(['email' => 'Access restricted to admin users only.']);
            }

            Auth::guard('web')->login($user);
            return redirect()->route('home'); // Redirect to the dashboard
        }
    }
    /**
     * Display a listing of the personal information.
     */
    public function index(Request $request)
    {
        $personalInformation = PersonalInformation::query()
            ->when($request->input('name'), function ($query, $name) {
                return $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$name%"]);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('pages.users.index', compact('personalInformation'));
    }

    /**
     * Show the form for creating new personal information.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created personal information in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'matrix_id' => 'required|string|unique:personal_information,matrix_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:woman,man',
            'address' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'regex:/^\+\d{1,3}\d{8,11}$/',
                'max:14',
            ],
            'departement' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'entry_year' => 'required|integer',
        ]);

        // Handle nullable last name
        $validatedData['full_name'] = $validatedData['last_name']
            ? $validatedData['first_name'] . ' ' . $validatedData['last_name']
            : $validatedData['first_name'];

        PersonalInformation::create($validatedData);

        return redirect()->route('users.index')->with('success', 'Personal information successfully created.');
    }

    /**
     * Show the form for editing the specified personal information.
     */
    public function edit($id)
    {
        $personalInformation = PersonalInformation::findOrFail($id);
        return view('pages.users.edit', compact('personalInformation'));
    }

    /**
     * Update the specified personal information in storage.
     */
    public function update(Request $request, $id)
    {
        $personalInformation = PersonalInformation::findOrFail($id);

        $validatedData = $request->validate([
            'matrix_id' => 'required|string|unique:personal_information,matrix_id,' . $personalInformation->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:woman,man',
            'address' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'regex:/^\+\d{1,3}\d{8,11}$/',
                'max:14',
                'unique:personal_information,phone_number,' . $personalInformation->id,
            ],
            'departement' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'entry_year' => 'required|integer',
        ]);

        // Handle nullable last name
        $validatedData['full_name'] = $validatedData['last_name']
            ? $validatedData['first_name'] . ' ' . $validatedData['last_name']
            : $validatedData['first_name'];

        $personalInformation->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Personal information successfully updated.');
    }

    /**
     * Remove the specified personal information from storage.
     */
    public function destroy($id)
    {
        $personalInformation = PersonalInformation::findOrFail($id);
        $personalInformation->delete();

        return redirect()->route('users.index')->with('success', 'Personal information successfully deleted.');
    }
}
