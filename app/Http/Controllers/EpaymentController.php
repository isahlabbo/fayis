<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SectionClass;
use App\Models\Student;
use App\Models\SectionClassStudent;
use App\Models\SectionClassStudentTerm;
use Illuminate\Http\Request;
use EdwardMuss\Rave\Facades\Rave as Flutterwave;
use App\Services\HasPayment;
use Auth;
use Excel;
use App\Exports\PaymentRecordExport;

class EpaymentController extends Controller
{
    use HasPayment;

    public function index()
    {
        return view('epayment.index');
    }

    public function search(Request $request)
    {
        
        $request->validate(['class'=>'required']);
        $class = SectionClass::find($request->class);
        
        if(count($class->sectionClassStudents)>0){
            return redirect()->route('dashboard.epayment.class',[$class->id]);
        }
        return redirect()->route('dashboard.epayment.index')->withWarning('Student record not found');
    }

    public function update(Request $request, $sectionClassStudentId)
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

        return redirect()->route('dashboard.epayment.invoice',[$sectionClassStudent->currentSessionTerm()->invoice->id])->withSuccess('Proceed to Payment');
    }

    public function addStudent(Request $request, $sectionClassId)
    {
        $request->validate([
            'name'=>'required',
            'lga'=>'required',
            'student_type'=>'required',
            'gender'=>'required',
            ]);
        $student = Student::create([
            'name'=>strtoupper($request->name),
            'lga_id'=>$request->lga,
            'gender_id'=>$request->gender,
            'student_type_id'=>$request->student_type,
            'academic_session_id'=>Student::find(1)->currentSession()->id,
            ]);

        $sectionClassStudent = $student->sectionClassStudents()->create([
            'section_class_id'=>$sectionClassId, 
            'status'=>'Active'
            ]);
        foreach($student->currentSession()->academicSessionTerms as $academicSessionTerm){
            $academicSessionTerm->sectionClassStudentTerms()->create(['section_class_student_id'=>$sectionClassStudent->id]);
        }

        $sectionClassStudent->updateActiveTerm();
        $this->generateAndUpdateProfileCode($sectionClassStudent);
         // generate in voice
       $invoice = $sectionClassStudent->currentStudentTerm()->generateInvoice();
       // redirect to invoice payment

       return redirect()->route('dashboard.epayment.invoice',[$invoice->id])->withSuccess('Student added Proceed to Payment');

    } 

    public function class($classId)
    {
        return view('epayment.class',['class'=>SectionClass::find($classId)]);
    }

    public function receipt($sectionClassStudentId)
    {
        return view('epayment.receipt',['sectionClassStudent'=>SectionClassStudent::find($sectionClassStudentId)]);
    }

    
    public function invoice($invoiceId)
    {
        return view('epayment.invoice',['invoice'=>Invoice::find($invoiceId)]);
    }

    

    public function pay(Request $request)
    {
        
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer',
            'amount' => $request->amount,
            'email' => Auth::user()->email,
            'tx_ref' => $reference,
            'currency' => "NGN",
            'subaccounts'=> [
                [
                    'id'=> "RS_634AFD44FBFFFC76D342C8F57B8DF1B0",
                    'transaction_split_ratio'=> 99.6299,
                    'transaction_charge_type'=> "flat",
                    'transaction_charge' => $request->developer/2,
                ],
                [
                    'id'=> "RS_5CBCFFED607B316CDA88840195E415CE",
                    'transaction_split_ratio'=> 0.3701,
                    'transaction_charge_type'=> "flat",
                    'transaction_charge' => $request->developer/2,
                ],
            ],
            'redirect_url' => route('dashboard.epayment.callback',[$request->id]),
            'customer' => [
                'email' => Auth::user()->email,
                "phone_number" => '',
                "name" => Auth::user()->name
            ],

            "customizations" => [
                "title" => 'SMMIQGS '.$request->title,
                "description" => "Termly School Fee Payment"
            ]
        ];

        $payment = Flutterwave::initializePayment($data);


        if ($payment['status'] !== 'success') {
            
            return redirect()->route('dashboard')->withError($payment['message']);
        }

        return redirect($payment['data']['link']);
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

            Auth::user()->userInvoices()->create([
                'invoice_id'=>$invoice->id,   
                'charges'=>$invoice->charges(),
            ]);
            
            $invoice->update(['status'=>'paid']);

            // redirect to the application route
            return redirect()->route('dashboard.epayment.receipt',[$invoice->sectionClassStudentTerm->sectionClassStudent->id])->withSuccess('Invoice Paid Successfully');
        }
        elseif ($status ==  'cancelled'){
            Invoice::find($invoiceId)->update(['status'=>'cancelled']);
        } else{
            //Put desired action/code after transaction has failed here
        }
        

    }

    public function generateAndUpdateProfileCode(SectionClassStudent $sectionClassStudent)
    {
        
        $codeExt = substr($sectionClassStudent->sectionClass->section->name,0,1);
        $year = date('y') - $this->getNumberSequence($sectionClassStudent->sectionClass->year_sequence);
        $serialNo = sprintf('%04d', count($sectionClassStudent->sectionClass->section->students()));
        $code = $codeExt.$year.$serialNo;
        $sectionClassStudent->student->update(['profile_code'=>$code]);
    }

    public function getNumberSequence($sequence){

        switch ($sequence) {
            case 'First':
                $number = 1;
                break;
            case 'Second':
                $number = 2;
                break;
            case 'Third':
                $number = 3;
                break;
            case 'Forth':
                $number = 4;
                break;
            case 'Fifth':
                $number = 5;
                break;
            default:
                $number = 6;
                break;
        }
        return $number;
    }
    public function download()
    {
        $session = SectionClass::find(1)->currentSession();

        return Excel::download(new PaymentRecordExport($session), 
        'smmiqgs_'.strtolower(str_replace(' ','_',$session->currentSessionTerm()->term->name)).'_'.str_replace('/','_', $session->name).
        '_payment.xlsx');
    }

}
 