<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Transaction\StoreTransactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $transaction = Transaction::create($validatedData);

            $point = floor($transaction->amount / 1000);

            $transaction->point()->create([
                'points' => $point,
            ]);

            return response()->json();
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'message' => 'Kesalahan dalam server',
            ], 500);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $transaction = Transaction::select([
                'user_id',
                'amount',
                'points',
                'transacted_at',
            ])
                ->leftJoin('points', 'transactions.id', 'points.transaction_id')
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $query->whereBetween('transacted_at', [$startDate, $endDate]);
                });

            if ($request->page === 'all') {
                $transaction = $transaction->get();

                return response()->json([
                    'current_page' => 1,
                    'data' => $transaction,
                    'per_page' => $transaction->count(),
                    'total' => $transaction->count(),
                    'last_page' => 1
                ]);
            } else {
                $transaction = $transaction->paginate($request->per_page ?? 10);
            }

            return response()->json($transaction);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'message' => 'Kesalahan dalam server',
            ], 500);
        }
    }
}
