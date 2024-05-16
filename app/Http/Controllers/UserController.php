<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function ownerDashboardPage(){
        return view('dashboard');
    }

    public function userRegistrationPage(){
        return view('userRegistration');
    }

    public function registeringNewUser(Request $request){

        $validatedUserInformation = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try{
            $user = User::create([
                'name' => $validatedUserInformation['name'],
                'email' => $validatedUserInformation['email'],
                'password' => Hash::make($validatedUserInformation['password']), 
            ]);
    
            return redirect()->route('owner.dashboard')->with('success', 'User registered successfully');
        }catch(\Exception $e){
            return back()->withInput()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
