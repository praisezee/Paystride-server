<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        // Retrieve all transactions
    }

    public function create(){
        // Show the form to create a new transaction
    }

    public function store(Request $request){
        // Store a new transaction in the database
    }

    public function show(Transaction $transaction){
        // Show details of a specific transaction
    }

    public function edit(Transaction $transaction){
        // Show the form to edit a transaction
    }

    public function update(Request $request, Transaction $transaction){
        // Update the function in the database
    }

    public function destroy(Transaction $transaction){
        // Restrict deletion of transactions abort(403, 'Deletion of transactions is not allowed.');
    }
}
