<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Locker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lockers = Locker::all();
        return response()->json($lockers);
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
    public function show($id)
    {
        $locker = Locker::find($id);

        if (!$locker) {
            return response()->json(['message' => 'Locker not found'], 404);
        }

        return response()->json($locker);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locker $locker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locker $locker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locker $locker)
    {
        //
    }
    public function checkLockerStatus(Request $request)
    {
        $locker = Locker::where('status', 'open')->first();
        if ($locker) {
            return response()->json([
                'status' => 'open',
                'locker' => $locker
            ], 200);
        } else {
            $locker = Locker::whereNull('status')->orWhere('status', 'close')->first();
            if ($locker) {
                return response()->json([
                    'status' => 'new',
                    'locker' => $locker
                ], 200);
            } else {
                return response()->json([
                    'status' => 'none'
                ], 200);
            }
        }
    }

    public function closeLocker(Request $request, $id)
    {
        $locker = Locker::find($id);
        if ($locker) {

            $totalComputer = DB::table('reseiptes_item')
            ->join('reseiptes_num', 'reseiptes_item.reseiptes_num_id', '=', 'reseiptes_num.id')
            ->where('reseiptes_num.locker_id', $id)
            ->sum('reseiptes_item.total');
            $totalCash = $request->input('total');
            $deficit = $totalCash - $totalComputer;
            $locker->update([
                'status' => 'close',
                'total_computer' =>$totalComputer,
                'total_cash' => $totalCash ,
                'visa' => $request->input('visa'),
                'Deficit' => $deficit

            ]);

            return response()->json($locker, 200);
        }else{
            return response()->json(['message' => 'Locker Not Found'],404);
        }
    }

    public function openNewLocker(Request $request)
    {
        $openLocker = Locker::where('status', 'open')->first();
        if ($openLocker) {
            return response()->json([
                'message' => 'There is already an open locker',
                'locker' => $openLocker
            ], 400);
        }

        $locker = Locker::create([
            'status' => 'open',
            // إضافة باقي الحقول المطلوبة مع القيم الأولية
        ]);

        return response()->json($locker, 201);
    }

    public function updateTotalComputerForOpenLocker()
    {
        // العثور على الخزنة المفتوحة
        $openLocker = Locker::where('status', 'open')->first();

        if ($openLocker) {
            // حساب مجموع total من جدول reseiptes_item
            $totalSum = DB::table('reseiptes_item')->sum('total');

            // تحديث عمود total_computer في الخزنة المفتوحة
            $openLocker->total_computer = $totalSum;
            $openLocker->save();

            return response()->json([
                'message' => 'تم تحديث قيمة total_computer بنجاح',
                'total_computer' => $totalSum
            ]);
        } else {
            return response()->json([
                'message' => 'لا توجد خزنة مفتوحة'
            ], 404);
        }
    }

    
}
