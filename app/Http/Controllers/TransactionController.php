<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $auth = $request->user();
        $request->validate(['search' => 'nullable|string']);
        // dd($request->input('search'));
        $transactions = Transaction::with(['user', 'order'])
            ->when(!$auth->is_admin, function ($q) use ($auth) {
                $q->where('user_id', $auth->id);
            })

            ->when($request->input('search'), function ($q) use ($request) {
                $search = '%' . $request->input('search') . '%';

                $q->where(function ($query) use ($search) {
                    $query->where('header', 'like', $search)
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', $search);
                        })
                        ->orWhereHas('order', function ($subQuery) use ($search) {
                            $subQuery->where('id', 'like', $search)
                                ->orWhere('total_amount', 'like', $search);
                        })
                        ->orWhere('id', 'like', $search)
                        ->orWhere('created_at', 'like', $search);
                });
            })
            ->latest()
            ->get();

        return Inertia::render('auth/transaction/Transactions', [
            'transactions' => $transactions,
        ]);
    }


    public function destroy(Transaction $transaction)
    {
        //
    }
}
