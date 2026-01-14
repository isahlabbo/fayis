<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff ID Card</title>
<!-- bootstrap -->
    <link 
        rel="stylesheet" 
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    >   

  <link rel="stylesheet" href="{{ asset('css/card.css') }}">
</head>
<body>

  

  <!-- ID Card Display -->
  <div class="id-container container text text-center" id="id-card">
    <br>
    <br>
    <br>
    <br>
    <br>
    <!-- FRONT -->
     <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3" style="padding-left: 70px;">
            <div class="id-card front">
                <div class="header">
                    <div class="row">
                        <div class="col-md-2"><img src="{{ asset('images/logo.jpg') }}" alt="Watermark" class="logo" width="50"></div>
                        <div class="col-md-10 pl-4"> <span style="color: red;">STAFF IDENTITY</span><br> CARD</div>
                    </div>
                </div>
                <img src="{{ asset('images/logo.jpg') }}" alt="Watermark" class="watermark">

                <div class="photo">
                    @if($user->profile_photo_path)
                    <img id="staff-photo"
                        src="{{ Storage::url($user->profile_photo_path) }}"
                        alt="Staff Photo" width="100" height="100">
                    @else
                    <img id="staff-photo"
                        src="{{ asset('images/user.jpg') }}"
                        alt="Staff Photo" width="100" height="100">
                    @endif
                </div>

                <div class="details">
                    <table>
                        <tr>
                            <td width="30%"><b>Name: </b></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><b>ID No: </b></td>
                            <td>{{ $user->pendingCardRequest()->staffID}}</td>
                        </tr>
                        <tr>
                            <td><b>Section: </b></td>
                            <td>{{ $user->pendingCardRequest()->section->name ?? 'General' }}</td>
                        </tr>
                        <tr>
                            <td><b>Role: </b></td>
                            <td>{{ $user->pendingCardRequest()->position }}</td>
                        </tr>
                    </table>
                </div>

                <div class="footer" style="height: 30px; margin: opx !important;">
                    Valid Until: {{ $user->pendingCardRequest()->validity ?? date('Y') + 2 }}
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-right: 70px;">
            <div class="id-card back text-center">
                <div class="header" style="color: red;">Staff Identity Card</div>
                <img src="{{ asset('images/logo.jpg') }}" alt="Watermark" class="watermark">
                <p>
                    The person whose details appear on the front is a staff 
                    of FAYIS. If found, please return it to the 
                    school.
                </p>

                <div class="qr">
                    {!! $user->pendingCardRequest()->generateQRCode('This card belongs to ' . $user->name. ' who is a staff member of FAYIS', 70) !!}
                </div>

                <div class="signature">
                    <img src="{{Storage::url($user->pendingCardRequest()->signature)}}" alt="" width="100"><br>
                    ______________________<br>
                    Authorized Signature
                </div>
                </div>
            </div>
        </div>
     </div>
    
</div>
    <!-- BACK -->
@if(Auth::user()->role == 'admin')    

  <button id="print-btn" onclick="printCard()">Print ID Card</button>

  <script>
    function printCard() {
      window.print();
    }
  </script>
@endif
</body>
</html>
