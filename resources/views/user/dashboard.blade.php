@extends('layouts.app')

@section('content')
<div class="container">
   
    <h1>🙋 User Dashboard</h1>
    <p>Welcome to your dashboard. You can manage your account and activity.</p>
</div>


<div class="container mt-4">

    
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Sidebar / Profile
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-8">
            <div class="card shadow-sm text-center">
                <div class="card-header bg-primary text-white">
                    Captcha Work
                </div>
                <div class="card-body" id="workArea">
                    <!-- Start Button -->
                    <button id="startBtn" class="btn btn-lg btn-success">Start Work</button>

                    <!-- Captcha Form (Hidden Initially) -->
<div id="captchaForm" class="mt-4 d-none position-relative">

    <!-- Pause & Stop Buttons at Top-Right -->
    {{-- <div class="position-absolute top-0 end-0 mt-2 me-3">
        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="reloadCaptcha()">Reload Captcha</button>
        <button class="btn btn-sm btn-warning me-2">Pause</button>
        <button class="btn btn-sm btn-danger">Stop</button>

    </div> --}}

    <!-- Top-right Buttons with Icons -->
<div class="position-absolute top-0 end-0 mt-2 me-3 d-flex align-items-center">
    <!-- Reload -->
    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="reloadCaptcha()" title="Reload Captcha">
        <i class="fas fa-sync-alt"></i>
    </button>

    <!-- Pause -->
    <button class="btn btn-sm btn-warning ms-2" title="Pause">
        <i class="fas fa-pause"></i>
    </button>

    <!-- Stop -->
    <button class="btn btn-sm btn-danger ms-2" title="Stop">
        <i class="fas fa-stop"></i>
    </button>
</div>

    <div id="captcha-section">
        <div class="form-group">
            <div id="captcha-result" class="mt-3"></div>
            <label for="captcha">Enter Captcha:</label><br>          
            <div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
                <img src="{{ $captcha_image }}" id="captchaImage" alt="Captcha" style="height: 60px;">
                <input type="text" id="captcha-input" class="form-control m-2" placeholder="Enter captcha" style="width: 300px;">
            </div>
           
            <button id="captcha-submit" class="btn btn-success">Submit</button>
           
        </div>
        
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.getElementById('startBtn').addEventListener('click', function () {
        this.style.display = 'none'; // Hide Start Button
        document.getElementById('captchaForm').classList.remove('d-none'); // Show Captcha Form
    });



</script>

    <script>
    $(document).ready(function () {
        $('#captcha-submit').on('click', function (e) {
            e.preventDefault();

            var captcha = $('#captcha-input').val();
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('captcha.verify') }}",
                type: 'POST',
                data: {
                    _token: token,
                    captcha: captcha
                },
                success: function (response) {
                    $('#captcha-input').val('');
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Correct!',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Incorrect!',
                        text: response.message
                    });

                    reloadCaptcha(); // Refresh captcha image if incorrect
                }
            },
            error: function () {
                $('#captcha-input').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred.'
                });

                reloadCaptcha(); // Just in case
            }
        });
    });
        


    window.reloadCaptcha = function () {
    $('#captchaImage').attr('src', '{{ captcha_src('default') }}?' + Math.random());

    // Auto reload every 10 seconds
    setInterval(reloadCaptcha, 10000);
}

  
    });

 

</script>


    
@endsection
