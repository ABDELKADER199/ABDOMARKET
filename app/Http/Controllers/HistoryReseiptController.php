<?php

namespace App\Http\Controllers;

use App\Models\HistoryReseipt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB ;

class HistoryReseiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = HistoryReseipt::all(); // يستخدم Eloquent بدلاً من DB::table()
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoryReseipt $historyReseipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoryReseipt $historyReseipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoryReseipt $historyReseipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoryReseipt $historyReseipt)
    {
        //
    }
}
