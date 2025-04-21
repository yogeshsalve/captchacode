@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
            <h2 class="mb-2 mb-md-0">🙋 User Dashboard</h2>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-wallet me-2"></i> ₹ {{ $earned_sum }}
            </a>
        </div>

        <!-- Welcome Message -->
        <p class="lead text-muted mb-4">Welcome to your dashboard. You can manage your account and activity.</p>

        <!-- Cards Section -->
        <div class="row g-4">
            <!-- Profile Card -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">My Profile</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Registered On:</strong>
                            {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d F Y') }}</p>
                        <p><strong>Plan:</strong> <strong style="color: green;">STANDARD</strong> &nbsp; <strong style="color: blue;">VALID TILL :{{ \Carbon\Carbon::parse(Auth::user()->created_at)->addDays(30)->format('d F Y') }}
                        </strong></p>
                        <p><strong>Total Captcha:</strong> <span id="totalCaptchas">0</span> / 15000</p>

                        <!-- Buttons -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                            <a href="#" class="btn btn-success" style="width: 150px;"><span id="correctCount">0</span></a>
                            <a href="#" class="btn btn-danger" style="width: 150px;"><span id="incorrectCount">0</span></a>
                        </div>


                        {{-- <div id="captcha-stats">
                            <p><strong>Total Captchas:</strong> <span id="totalCaptchas">0</span></p>
                            <p><strong>Correct:</strong> <span id="correctCount">0</span></p>
                            <p><strong>Incorrect:</strong> <span id="incorrectCount">0</span></p>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Captcha Work Card -->
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Captcha Work</h5>
                    </div>
                    <div class="card-body position-relative">
                        @php
                            $workStarted = Auth::user()->work_started === 'yes';
                        @endphp

                        @if (!$workStarted)
                            <!-- Chart Section -->
                            <div class="container py-1">
                                <h5 class="text-center mb-3">📈 Captcha Activity Overview</h5>
                                <canvas id="activityChart" height="80"></canvas>
                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            </div>
                        @endif

                        <!-- Captcha Form -->
                        <div id="captchaForm" class="{{ $workStarted ? '' : 'd-none' }} mt-4 text-center">

                            <!-- Top-right Button Row -->
                            <div class="position-absolute top-0 end-0 mt-2 me-3 d-flex">
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                    onclick="reloadCaptcha()" title="Reload Captcha">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-sm btn-warning me-1" title="Pause">
                                    <i class="fas fa-pause"></i>
                                </button>
                                <form id="stopWorkForm" action="{{ route('stop.work') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" title="Stop">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Captcha Display -->
                            <img id="captchaImage" src="{{ $captcha_image }}" alt="Captcha" class="my-3"
                                style="height: 60px;">
                            <input type="text" name="captcha" id="captcha-input" class="form-control mx-auto"
                                placeholder="Enter captcha" style="max-width: 300px;" required>
                            <button class="btn btn-primary mt-3" id="captcha-submit">Submit</button>
                        </div>

                        <div id="captcha-section"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>





    {{-- stop button  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startBtn = document.getElementById('startBtn');
            const stopForm = document.getElementById('stopWorkForm');
            const captchaForm = document.getElementById('captchaForm');

            // If work started (set from backend), show stop button and captcha form
            @if (Auth::user()->work_started == 'yes')
                stopForm.classList.remove('d-none');
            @endif
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

                        console.log("Response:", response);

                        $('#captcha-input').val('');
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Congratulations 50 Paise Credited !!',
                                text: response.message
                            });

                            reloadCaptcha();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops,  25 Paise Deducted !!',
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
                $.get('/reload-captcha', function(data) {
                    $('#captchaImage').attr('src', data.captcha);
                });
            };


        });
    </script>



    {{-- chart --}}
    <script>
        const chartData = @json($activity_data);

        const labels = chartData.map(item => item.date);
        const correct = chartData.map(item => item.correct_count);
        const incorrect = chartData.map(item => item.incorrect_count);

        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Correct Captchas',
                        data: correct,
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Incorrect Captchas',
                        data: incorrect,
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Captchas'
                        }
                    }
                }
            }
        });
    </script>


<script>
    function fetchCaptchaStats() {
        $.ajax({
            url: "{{ route('user.captcha.stats') }}",
            type: "GET",
            success: function(data) {
                $('#totalCaptchas').text(data.total_captchas);
                $('#correctCount').text(data.correct_count);
                $('#incorrectCount').text(data.incorrect_count);
            }
        });
    }

    // Fetch every 5 seconds
    setInterval(fetchCaptchaStats, 5000);
    fetchCaptchaStats(); // Initial load
</script>
@endsection
