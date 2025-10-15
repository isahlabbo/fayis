<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\SectionClassStudent;
use App\Models\SectionClassStudentTerm;
use App\Models\SectionClass;
use App\Models\Invoice;
use App\Models\Student;
use EdwardMuss\Rave\Facades\Rave as Flutterwave;
use App\Notifications\SchoolFeesPaymentCollectedSMS;
use App\Services\HasPayment;
use Modules\Admin\Entities\Admin;
use Auth;

class PaymentController extends Controller
{
    use HasPayment;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('payment.index');
    }

    public function students($class, $type)
    {
        return view('payment.students',['class'=>SectionClass::find($class),'type'=>$type]);
    }

    public function verifyClass(Request $request)
    {
       $request->validate([
           'class'=>'required',
           'type'=>'required',
           ]);
        $class = SectionClass::find($request->class);
       
        if(count($class->sectionClassStudents)>0) {
            return redirect()->route('payment.students',[$class->id, $request->type]);
        } else{
            return back()->withWarning('No students record found');
        }
    }

    public function invoice($sectionClassStudentId)
    {
        return view('payment.invoice',['sectionClassStudent'=>SectionClassStudent::find($sectionClassStudentId)]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function generateInvoice($sectionClassStudentId)
    {
        $sectionClassStudent = SectionClassStudent::find($sectionClassStudentId);

        foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
            $sectionClassStudentTerm->generateInvoice();
        }
        // redirect to invoice payment

        return redirect()->route('payment.invoice',[$sectionClassStudent->id])->withSuccess('Proceed to Payment');
    }

    public function updateAndGenerateInvoice(Request $request, $sectionClassStudentId)
    {
        
        $request->validate([
            'lga'=>'required',
            'student_type'=>'required',
            'gender'=>'required',
            'terms'=>'required',
            ]);

        $sectionClassStudent = SectionClassStudent::find($sectionClassStudentId);
        
        $sectionClassStudent->student->update([
            'lga_id'=>$request->lga,
            'student_type_id'=>$request->student_type,
            'gender_id'=>$request->gender,
        ]);

        // generate in voice
        foreach($request->terms as $key=>$value){
            SectionClassStudentTerm::find($value)->generateInvoice();
        }
       
        // redirect to invoice payment

        return redirect()->route('payment.invoice',[$sectionClassStudent->id])->withSuccess('Proceed to Payment');
    }
    public function generateInvoiceOnly(Request $request, $sectionClassStudentId)
    {

        // generate in voice
        foreach([1,2,3] as $termId){
            SectionClassStudentTerm::find($termId)->generateInvoice();
        }
       
        // redirect to invoice payment

        return redirect()->route('payment.invoice',[$sectionClassStudentId])->withSuccess('Proceed to Payment');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function search(Request $request)
    {
        $request->validate(['class'=>'required']);
        $class = SectionClass::find($request->class);
        
        if(count($class->sectionClassStudents)>0){
            return redirect()->route('payment.generate.invoice',[$class->id]);
        }
        return redirect()->route('payment.index')->withWarning('Student record not found');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function receipt($invoiceId)
    {
        return view('payment.receipt',['invoice'=>Invoice::find($invoiceId)]);
    }

    public function cancelled($invoiceId)
    {
        return view('payment.cancelled',['invoice'=>Invoice::find($invoiceId)]);
    }

    public function getPayableAmount($data)
    {
        $amount = 0;
        $invoices = [];
        $sectionClassStudent = SectionClassStudent::find($data['section_class_student_id']);

        foreach($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm){
         
            switch ($sectionClassStudentTerm->academicSessionTerm->term_id) {
                case '1':
                    if(isset($data['first'])){
                        $amount += $sectionClassStudentTerm->invoice->payableAmount();
                    }
                    break;

                case '2':
                    if(isset($data['second'])){
                        $amount += $sectionClassStudentTerm->invoice->payableAmount();
                    }
                    break;
                
                default:
                    if(isset($data['third'])){
                        $amount += $sectionClassStudentTerm->invoice->payableAmount();
                    }
                    break;
            }
        }
        return $amount;
    }
    public function pay(Request $request)
    {
        try {
            $request->validate([
                'email'     => 'required|email'
            ]);

            // Generate a unique payment reference
            $reference = Flutterwave::generateReference();

            // Get payable amount
            $amount = $this->getPayableAmount($request->all());
            if ($amount == 0) {
                $amount = $request->amount;
            }

            $data = [
                'payment_options' => 'card,banktransfer',
                'amount'          => $amount,
                'currency'        => "NGN",
                'tx_ref'          => $reference,
                'redirect_url'    => route('payment.callback', [$request->id]),
                'subaccounts'     => [
                    [
                        'id'                      => "RS_09C4D795F68407225BF9EEC2A91BF787",
                        'transaction_split_ratio' => 100,
                        'transaction_charge_type' => "flat",
                        'transaction_charge'      => $request->developer,
                    ],
                ],
                'customer' => [
                    'email'        => $request->email,
                    "phone_number" => $request->phone ?? '',
                    "name"         => $request->name ?? 'Guest',
                ],
                "customizations" => [
                    "title"       => 'SMMIQGS ' . $request->title,
                    "description" => "Payment for " . $request->title,
                    "logo"        => asset('assets/images/logo-main.png'),
                ]
            ];

            // Initialize payment
            $payment = Flutterwave::initializePayment($data);

            if (!isset($payment['status']) || $payment['status'] !== 'success') {
                return redirect()->route('payment.index')->withError($payment['message'] ?? 'Unable to initialize payment.');
            }

            // If everything is good, redirect user to payment page
            return redirect($payment['data']['link']);

        } catch (\Throwable $e) {
            // Log error for debugging
            \Log::error('Payment initialization error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Show user-friendly message
            return redirect()->route('payment.index')->withError('Something went wrong while initializing payment. Please try again.');
        }
    }

    public function callback($invoiceId)
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
            
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            $invoice = Invoice::find($invoiceId);
            $invoice->update(['status'=>'paid']);

            // save transaction details
            $transaction = $this->saveTransactionDetails($data, $invoice);

            $invoice->update(['status'=>'paid']);
            
            
            // redirect to the application route
            return redirect()->route('payment.receipt',[$invoice->id])->withSuccess('Invoice Paid Successfully');
        }
        elseif ($status ==  'cancelled'){
            Invoice::find($invoiceId)->update(['status'=>'cancelled']);
            return redirect()->route('payment.cancelled',[$invoice->id])->withWarning('This transaction has been cancelled');
        } else{
            //Put desired action/code after transaction has failed here
        }
    }
}
