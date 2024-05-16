<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class serviceController extends Controller
{
    public function allServicesPage(){
        return view('services');
    }

    public function serviceRegistrationPage(){
        return view('createNewService');
    }

    public function registerNewService(Request $request){
        $newServiceValidatedInformation = $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        try{
            $user = Service::create([
                'name' => $newServiceValidatedInformation['name'],
                'price' => $newServiceValidatedInformation['price'],
                'description' => $newServiceValidatedInformation['description'], 
            ]);
    
            return redirect()->route('owner.dashboard')->with('success', 'Service registered successfully');
        }catch(\Exception $e){
            return back()->withInput()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
