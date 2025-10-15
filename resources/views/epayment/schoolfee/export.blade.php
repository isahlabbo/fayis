<table class="table" id="myTable">
        <thead>
            <tr>
                
                <th>INVOICE ID</th>
                <th>TRANSACTION ID</th>
                <th>ADMISSION NO</th>
                <th>CLASS</th>
                <th>STUDENT NAME</th>
                <th>AMOUNT</th>
                <th>TERM</th>
                <th>USER</th>
                <th>CREATED AT</th>
            </tr>
        </thead>
        <tbody>
            
                @foreach(App\Models\Section::find(1)->currentSession()->invoices->where('status','paid') as $invoice)
                    @if($invoice->sectionClassStudentTerm && $invoice->sectionClassStudentTerm->academicSessionTerm->id ==$invoice->currentSessionTerm()->id)
                    <tr>
                        <td>{{$invoice->number}}</td>
                        <td>{{$invoice->transaction->transaction_id ?? ''}}</td>
                        <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->admission_no ?? ''}}</td>
                        <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name}}</td>
                        <td>{{$invoice->sectionClassStudentTerm->sectionClassStudent->student->name ?? ''}}</td>
                        <td>{{$invoice->amount}}</td>
                        <td>{{$invoice->sectionClassStudentTerm->academicSessionTerm->term->name}}</td>
                        <td>{{$invoice->created_at}}</td>
                    </tr>
                    @endif
                @endforeach
           
        </tbody>
        </table>
    