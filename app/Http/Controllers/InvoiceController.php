<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ReseiptesNum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function SaveInvoice(Request $request){
        $data = $request->validate([
            'invoices' => 'required|array',
            'invoices.*.product_name' => 'required|string',
            'invoices.*.product_parcode' => 'required|string',
            'invoices.*.product_price' => 'required',
            'invoices.*.quantity' => 'required',
            'invoices.*.total_item' => 'required',
            'invoices.*.total' => 'required'
        ]);

        try{
            foreach($data['invoices'] as $invoiceData){
                Invoice::create($invoiceData);
            }
            $reseiptesNum = ReseiptesNum::create();

            $totalSum = DB::table('reseiptes_item')->sum('total');

            return response()->json(['message' => 'Invoices Created Successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to Create Invoices', 'error' => $e->getMessage()],500);
        }


    }
    public function uploadPDF(Request $request)
{
    if (!$request->hasFile('file')) {
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    $file = $request->file('file');
    $fileName = 'invoice_' . time() . '.pdf'; // إنشاء اسم فريد للملف
    $filePath = public_path('pdfs/' . $fileName);

    // قم بحفظ الملف في public/pdfs
    $file->move(public_path('pdfs'), $fileName);

    $fileUrl = url('pdfs/' . $fileName); // الحصول على رابط URL للملف

    return response()->json(['fileUrl' => $fileUrl], 200);
}





public function sendInvoice(Request $request)
{
    $fileUrl = $request->input('file_url');

    if (!$fileUrl) {
        return response()->json(['error' => 'File URL is required'], 400);
    }

    // تحقق من أن الرابط صحيح
    if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) {
        return response()->json(['error' => 'Invalid file URL'], 400);
    }

    // إعداد رسالة WhatsApp عبر Twilio
    $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    $message = $twilio->messages->create(
        'whatsapp:+201070276578', // رقم المستلم عبر WhatsApp
        [
            'from' => env('TWILIO_WHATSAPP_FROM'),
            'body' => "Here is your invoice: $fileUrl"
        ]
    );

    return response()->json(['message' => 'Message sent successfully'], 200);
}


}
