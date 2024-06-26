<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    //Submit a support request
    public function submitRequest(Request $request){
        //Validate request data
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string',
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        //Save support request to the database
        SupportRequest::create($validatedData);

        return response()->json(['message' => 'Support request submitted successfully'],201);
    }

    //Retrieve ahistory of support request and their resolution status
    public function getPastIssues(){
        //  Retrieve a historynof support requests and their resolution status
        $pastIssues = SupportRequest::all();

        return response()->json(['pastIssues' => $pastIssues],202);
    }
}
