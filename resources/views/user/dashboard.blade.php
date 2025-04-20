@extends('layouts.app')

@section('content')
    <div class="container my-3">
        <h1>🙋 User Dashboard</h1>

        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0">Welcome to your dashboard. You can manage your account and activity.</p>

            <a href="#" class="btn btn-primary">
                <i class="fas fa-wallet me-1"></i> ₹ 131.50
            </a>
        </div>
    </div>


    <div class="container mt-2">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        My Profile
                    </div>
                    <div class="card-body">

                        {{-- @php
                            $workStarted = Auth::user()->work_started === 'yes';
                        @endphp
                        @if (!$workStarted)
                            <form id="startWorkForm" action="{{ route('start.work') }}" method="POST">
                                @csrf
                                <div class="position-absolute top-2 end-0 mt-2 me-3 d-flex align-items-center">
                                    <button type="submit" id="startBtn" class="btn btn-success">Start Work</button>
                                </div>
                            </form>
                        @endif --}}

                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Registered On :</strong>
                            {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d F Y') }}</p>
                        <p><strong>Plan:</strong> Plan A &nbsp; <a href="">Upgrade Plan</a></p>
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
                            <div class="container py-1">
                                <h3 class="mb-4 text-center">📈 Captcha Activity Overview</h3>
                                <canvas id="activityChart" height="70"></canvas>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                <form id="stopWorkForm" action="{{ route('stop.work') }}" method="POST" class="d-none">
                                    @csrf
                                    <button class="btn btn-sm btn-danger ms-2" title="Stop">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form>

                                {{-- <div id="captcha-timer" class="ms-3 fw-bold text-primary" style="width: 30px;">10</div> --}}
                            </div>


                            <img id="captchaImage" src="{{ captcha_src('default') }}" alt="Captcha" style="height: 60px;">
                            <input type="text" id="captcha-input" class="form-control mt-2" placeholder="Enter captcha"
                                style="width: 250px; margin: 0 auto;">
                            <button class="btn btn-primary mt-2" id="captcha-submit">Submit</button>
                        </div>


                        <div id="captcha-section">


                        </div>
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



    {{-- chart --}}
    <script>
        const chartData = @json($activity_data);

        const labels = chartData.map(item => item.date);
        const correct = chartData.map(item => item.correct_count);
        const incorrect = chartData.map(item => item.incorrect_count);

        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
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
@endsection
