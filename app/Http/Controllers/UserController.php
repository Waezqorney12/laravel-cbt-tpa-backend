<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\PersonalInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

            auth()->login($user); // Log the user in
            return redirect()->route('home'); // Redirect to the dashboard
        }
    }

    public function index(Request $request)
    {
        $users = DB::table('users')
            ->leftJoin('personal_information', 'users.personal_id', '=', 'personal_information.id')
            ->select(
                'users.id',
                'users.email',
                'users.username',
                'users.roles',
                'personal_information.matrix_id',
                'personal_information.first_name',
                'personal_information.last_name',
                'personal_information.birth_date',
                'personal_information.gender',
                'personal_information.address',
                'personal_information.departement',
                'personal_information.study_program',
                'personal_information.entry_year',
                'personal_information.phone_number',
            )
            ->whereIn('users.roles', ['STAFF', 'USER']) // Exclude ADMIN role
            ->when($request->input('name'), function ($query, $name) {
                return $query->where(DB::raw("CONCAT(personal_information.first_name, ' ', personal_information.last_name)"), 'like', '%' . $name . '%');
            })
            ->orderBy('users.id', 'asc')
            ->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }
    public function store(StoreUserRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return redirect()->route('users.index')->with('success', 'User successfully created');

    }
    public function edit($id)
    {
        $user = User::with('dataPribadi')->findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::with('dataPribadi')->findOrFail($id);

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|max:10|unique:users,username,' . $user->id,
            'roles' => 'required|in:ADMIN,STAFF,USER',
            'matrix_id' => 'required|string|unique:personal_information,matrix_id,' . $user->dataPribadi->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:woman,man',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'departement' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'entry_year' => 'required|integer',
        ]);

        // Only update user fields if changed
        $user->update([
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'roles' => $validatedData['roles'],
        ]);

        // Only update personal_information fields if changed
        $user->dataPribadi->update([
            'matrix_id' => $validatedData['matrix_id'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'full_name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'birth_date' => $validatedData['birth_date'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'departement' => $validatedData['departement'],
            'study_program' => $validatedData['study_program'],
            'entry_year' => $validatedData['entry_year'],
        ]);

        return redirect()->route('users.index')->with('success', 'User successfully updated.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User successfully deleted');
    }
}
