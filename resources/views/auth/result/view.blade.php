<x-guest-layout>
    @section('title')
       
    @endsection
    @section('styles')
        <style>
        #printWrapper {
            width: 794px;          /* A4 width at 96 DPI */
            min-height: 1123px;    /* A4 height */
            padding: 20px;
            background: #fff;
        }

        @media screen {
            #printWrapper {
                margin: auto;
            }
        }
        </style>
    @endsection
    @section('content')
        <div class="row">
            <div class="col-md-12 mb-3 text-right">
                <button class="btn btn-secondary" id="print" onclick="printDiv('report');" ><i class="fas fa-print"></i> Print</button>
                <!-- download button   -->
                 <button class="btn btn-outline-success" onclick="downloadDiv('report');"><i class="fas fa-download"></i> Download</button>
                
                <button class="btn btn-outline-primary " onclick="window.location.href='{{ route('result.check') }}'">Check Another Result</button>
            </div>
        </div>
        
        <div id="report">
            @php
                $student = $studentTerm->sectionClassStudent->student;
                $sectionClassStudent = $studentTerm->sectionClassStudent;
                $sectionClassStudentTerm = $studentTerm;
            @endphp
            @include('section.class.student.result.reportcard.view')
        </div>
        <!-- include html2pdf (for download) with CDN -> local fallback, and use vanilla JS for print -->
        <script>
            function printDiv(divId) {
                const divContent = document.getElementById(divId).innerHTML;
                const originalContent = document.body.innerHTML;

                document.body.innerHTML = divContent;
                window.print();
                document.body.innerHTML = originalContent;
                location.reload();
            }
        </script>

        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <script>
function downloadDiv(divId) {
    const element = document.getElementById(divId);

    const options = {
        margin: 0,
        filename: 'fayis_termly_result.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 3,
            useCORS: true,
            scrollY: 0
        },
        jsPDF: {
            unit: 'px',
            format: [794, 1123], // A4 exact size
            orientation: 'portrait'
        },
        pagebreak: { mode: 'avoid-all' }
    };

    html2pdf()
        .set(options)
        .from(element)
        .save();
}

        </script>
    @endsection
    
</x-guest-layout>
