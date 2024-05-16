<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class invoiceController extends Controller
{
    public function allInvoicesPage(){
        return view('invoices');
    }

    public function createInvoicePage(){
        $services = Service::select('id','name','price')->get();
        return view('createInvoice',['services'=>$services]);
    }

    public function createInvoice(Request $request)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'service' => 'required|array',
            'service.*' => 'exists:services,id', 
            'date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'price' => 'required|numeric|min:0',
        ]);
        
        try {
            
            $invoiceNumber = mt_rand(100000, 999999);
            while (DB::table('invoices')->where('invoice_number', $invoiceNumber)->exists()) {
                $invoiceNumber = mt_rand(100000, 999999);
            }
            
            $invoice = new Invoice();
            $invoice->customer_name = $validatedData['customer_name'];
            $invoice->customer_phone = $validatedData['customer_phone'];
            $invoice->date = $validatedData['date'];
            $invoice->status = $validatedData['status'];
            $invoice->price = $validatedData['price'];
            $invoice->invoice_number = $invoiceNumber;
            $invoice->save();

            $invoice->services()->attach($validatedData['service']);

            return redirect()->route('owner.dashboard')->with('success', 'Invoice created successfully');
        } catch (\Exception $e) {
            
            return back()->withInput()->withErrors(['error' => 'Error creating invoice. Please try again.']);
        }
    }
}
