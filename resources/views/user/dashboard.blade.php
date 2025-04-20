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
                        My Profile
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Registered On :</strong>
                            {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d F Y') }}</p>
                        <p><strong>Plan:</strong> Plan A</p>
                        <p><strong>Total Captcha:</strong> 1002 / 10000</p>
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
                       <!-- Start Work Button -->
                       @php
    $workStarted = Auth::user()->work_started === 'yes';
@endphp

<!-- Start Work Button - Only show if work has NOT started -->
@if (!$workStarted)
    <form id="startWorkForm" action="{{ route('start.work') }}" method="POST">
        @csrf
        <button type="submit" id="startBtn" class="btn btn-success">Start Work</button>
    </form>
@endif

<!-- Captcha Form - Only show if work has started -->
<div id="captchaForm" class="{{ $workStarted ? '' : 'd-none' }} mt-3">

    <!-- Top-right Buttons with Icons -->
    <div class="position-absolute top-2 end-0 mt-2 me-3 d-flex align-items-center">
        <!-- Reload -->
        <button type="button" class="btn btn-sm btn-outline-secondary ms-2"
            onclick="reloadCaptcha()" title="Reload Captcha">
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

        {{-- <div id="captcha-timer" class="ms-3 fw-bold text-primary" style="width: 30px;">10</div> --}}
    </div>


    <img id="captchaImage" src="{{ captcha_src('default') }}" alt="Captcha" style="height: 60px;">
    <input type="text" id="captcha-input" class="form-control mt-2" placeholder="Enter captcha" style="width: 150px; margin: 0 auto;">
    <button class="btn btn-primary mt-2" id="captcha-submit">Submit</button>
</div>

                            
                            <div id="captcha-section">
                               

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <a href="" class="btn btn-success">
            <i class="bi bi-download"></i> Download Report (Excel)
        </a>
    </div>


    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // document.getElementById('startBtn').addEventListener('click', function() {
        //     this.style.display = 'none'; // Hide Start Button
        //     document.getElementById('captchaForm').classList.remove('d-none'); // Show Captcha Form
        // });

        document.getElementById('startWorkForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide Start Button
                document.getElementById('startBtn').style.display = 'none';

                // Show Captcha Form
                document.getElementById('captchaForm').classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>

    <script>
        $(document).ready(function() {
            $('#captcha-submit').on('click', function(e) {
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
                    success: function(response) {
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
                    error: function() {
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



            window.reloadCaptcha = function() {
                $('#captchaImage').attr('src', '{{ captcha_src('default') }}?' + Math.random());

                // Auto reload every 10 seconds
                setInterval(reloadCaptcha, 15000);
            }


        });
    </script>
    {{-- 
<script>
    let captchaInterval;
    let countdownInterval;
    let timeLeft = 10;
    let isPaused = false;

    function reloadCaptcha() {

      
        const captchaImage = document.getElementById('captchaImage');
        captchaImage.src = '{{ captcha_src('default') }}?' + Math.random();
    }

    function updateTimerDisplay() {
        document.getElementById('captcha-timer').textContent = timeLeft;
    }

    function startCaptchaAutoReload() {
        updateTimerDisplay();

        countdownInterval = setInterval(() => {
            if (!isPaused) {
                timeLeft--;
                if (timeLeft <= 0) {
                    reloadCaptcha();
                    timeLeft = 10;
                }
                updateTimerDisplay();
            }
        }, 1000);
    }

    function toggleCaptchaAutoReload() {
        isPaused = !isPaused;

        const pauseBtn = document.getElementById('pauseCaptchaBtn');
        if (isPaused) {
            pauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            pauseBtn.title = "Resume";
        } else {
            pauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            pauseBtn.title = "Pause";
        }
    }

    document.addEventListener('DOMContentLoaded', startCaptchaAutoReload);
</script> --}}
@endsection
