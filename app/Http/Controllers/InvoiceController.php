<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
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
            //->orderBy('updated_at', 'desc')
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
}
