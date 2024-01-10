<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(Request $request): Response
    {
        $filtered = Invoice::where('user_id', $request->user()->id);
        $itemsPerPage = 10;
        $count = $filtered->count();
        $numPages = (int) ceil($count / $itemsPerPage);
        $currentPage = (int) $request->query('page', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        } else if ($currentPage > $numPages) {
            $currentPage = $numPages;
        }

        $collection = $filtered
            ->orderBy('updated_at', 'desc')
            ->offset($itemsPerPage * ($currentPage - 1))
            ->limit($itemsPerPage)
            ->get()
            ->transform( fn ($item) => $item->toArray());

        return Inertia::render('Invoices', [
            'list'          => $collection->all(),
            'numPages'      => $numPages,
            'currentPage'   => $currentPage,
            'href'          => url()->current()
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number'    => ['required', 'max:64'],
            'customer_tax_id'   => ['required', 'digits:10'],
            'seller_tax_id'     => ['required', 'digits:10'],
            'product_name'      => ['required', 'max:128'],
            'net_price'         => ['required', 'decimal:0,2']
        ]);
        $invoiceId = (int) $request->input('id', 0);
        if ($invoiceId > 0) {
            $invoice = Invoice::find($invoiceId);
        } else {
            $invoice = new Invoice();
        }

        $invoice->invoice_number = $validated['invoice_number'];
        $invoice->customer_tax_id = $validated['customer_tax_id'];
        $invoice->seller_tax_id = $validated['seller_tax_id'];
        $invoice->product_name = $validated['product_name'];
        $invoice->net_price = $validated['net_price'];
        $invoice->user_id = Auth::user()->id;
        $invoice->save();

        return redirect()->route('invoices.index');
    }

    public function delete(int $id): RedirectResponse
    {
        Invoice::destroy($id);
        return redirect()->route('invoices.index');
    }
}
