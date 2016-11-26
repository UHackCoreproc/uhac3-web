<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Transformers\TransactionTransformer;

class TransactionsController extends ApiController
{

    public function index(Request $request, $accountId = null)
    {
        $user = $request->user();
        $transactions = $user->transactions();

        if ( ! $accountId) {
            $transactions->where('source_account_id', $accountId);
        }

        return $this->response->withPaginator($transactions->paginate(10), new TransactionTransformer);
    }

}
