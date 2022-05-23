<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    public function index(): View
    {
        return view('purchase/index', [
            'purchases' => Purchase::all(),
            'bgColor' => ['bg-warning text-dark', 'bg-success', 'bg-secondary']
        ]);
    }

    public function detail(int $id): View
    {
        /**
         * @var Purchase $purchase
         */
        $purchase = Purchase::findOrFail($id);

        return view('purchase/detail', [
            'purchase' => $purchase,
        ]);
    }

    public function updateStatus(int $id, Request $request): RedirectResponse
    {
        /**
         * @var Purchase $purchase
         */
        $purchase = Purchase::findOrFail($id);

        $newStatus = $request->get('status');

        if (is_null($newStatus)) {
            redirect()
                ->route('purchase_detail', ['id' => $id])
                ->with('danger', "The status cannot be null !");
        }

        $purchase->status = (int)$newStatus;
        $purchase->save();

        return redirect()
            ->route('purchase_detail', ['id' => $id])
            ->with('success', "The status is now : {$purchase->getStatusLabel()}");
    }
}
