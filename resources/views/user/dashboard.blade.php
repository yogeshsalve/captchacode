@extends('layouts.app')

@section('content')
    <style>
        .modal.right .modal-dialog {
            position: fixed;
            margin: auto;
            width: 50%;
            height: 100%;
            right: 0;
            top: 0;
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
        }

        .modal.right.show .modal-dialog {
            transform: translateX(0);
        }

        .modal.right .modal-content {
            height: 100%;
            overflow-y: auto;
            border-radius: 0;
        }

        .modal.right .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal.right .modal-body {
            padding: 2rem;
        }

        .wallet-warning {
            font-size: 1.1rem;
            font-weight: 500;
            color: #dc3545;
        }

        .earnings-amount {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .qr-image {
            max-width: 200px;
            margin: 1rem auto;
        }

        .upi-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>

    <style>
        /* Make modal take half the screen width from the left */
        .left-half-modal .modal-dialog-left {
            position: fixed;
            top: 0;
            left: 0;
            margin: 0;
            width: 50vw;
            height: 100vh;
            transform: translateX(-100%);
            transition: transform 0.4s ease;
            pointer-events: none;
        }

        .left-half-modal.show .modal-dialog-left {
            transform: translateX(0);
            pointer-events: auto;
        }

        /* Make sure modal backdrop covers full screen */
        .left-half-modal .modal-backdrop {
            opacity: 0.5 !important;
        }
    </style>


    <div class="container my-4">
        <!-- Header -->
        {{-- <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
            <h2 class="mb-2 mb-md-0">🙋 User Dashboard</h2>
            <p>Try Here</p>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#earnedModal"
                onclick="syncEarnedToModal()" id="earnedButton">
                <i class="fas fa-wallet me-2"></i>₹ <span id="totalEarned">0</span>
            </a>
        </div> --}}

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
            <!-- Left side: Title -->
            <h2 class="mb-2 mb-md-0">🙋 User Dashboard</h2>

            <!-- Right side: Try text and Button -->
            <div class="d-flex align-items-center gap-2">
                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#captchaModal"
                    onclick="syncEarnedToModal1()" id="tryButton">
                    🎁 Try For Free
                </a>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#earnedModal"
                    onclick="syncEarnedToModal()" id="earnedButton">
                    <i class="fas fa-wallet me-2"></i>₹ <span id="totalEarned">0</span>
                </a>
            </div>
        </div>



        {{-- free try captcha modal --}}
        <div class="modal fade" id="captchaModal" tabindex="-1" aria-labelledby="captchaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg rounded style="box-shadow: 0 0 40px rgba(0,0,0,0.4);"">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="captchaModalLabel">🧩 Try this Captcha Challenge</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body d-flex flex-column flex-md-row gap-3">
                        <!-- Left Side: User Info -->
                        <div class="flex-fill border-end pe-md-3 mb-3 mb-md-0">
                            <h5 class="mb-2">👤 User Info</h5>
                            <p><strong>Name:</strong> {{ $user['name'] }}</p>
                            <p><strong>Plan:</strong> Free Trial</p>
                            <p><strong>Trial Captchas:</strong> 10 remaining</p>
                            <p><strong>Amount Earned:</strong> Rs. 5.00</p>

                            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                                <a href="#" class="btn btn-success" style="width: 150px;">
                                    <span id="trycorrectCount">0</span>
                                </a>
                                <a href="#" class="btn btn-danger" style="width: 150px;">
                                    <span id="tryincorrectCount">0</span>
                                </a>
                            </div>
                            

                           
                            <!-- Add more info as needed -->
                        </div>

                        <!-- Right Side: Captcha Form -->
                        <div class="flex-fill text-center">

                            <center>
                                <div class="small">
                                    <strong class="text-success">+ 50 Paisa</strong> for Right Captcha &nbsp; | &nbsp;
                                    <strong class="text-danger">- 25 Paisa</strong> for Wrong Captcha
                                </div>
                            </center>

                            <!-- Captcha Image and Reload -->
                            <div class="mb-3">
                                <img id="captchaImage1" src="{{ $captcha_image }}" alt="Captcha" class="my-3"
                                    style="height: 60px;">

                            </div>

                            <!-- Input -->
                            <input type="text" name="captcha" id="trycaptcha-input" class="form-control mx-auto"
                                placeholder="Enter captcha" style="max-width: 300px;" required>
                            <br>
                            <!-- Submit -->
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="reloadCaptcha1()"
                                    title="Reload Captcha">
                                    <i class="fas fa-sync-alt"></i> Reload
                                </button>
                                <button class="btn btn-primary" id="trycaptcha-submit">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade right" id="earnedModal" tabindex="-1" aria-labelledby="earnedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="earnedModalLabel">Earnings Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-4 align-items-center">

                            <!-- Left Column -->
                            <div class="col-md-6 text-center border-end">
                                <div class="wallet-warning mb-2">Your wallet balance is low!</div>
                                <div class="earnings-amount">₹ <span id="totalEarnedModal"></span></div>

                                <!-- QR Code Section -->
                                <div id="addMoneySection" class="d-none">
                                    <img src="{{ asset('images/taskitqr.jpeg') }}" alt="QR Code"
                                        class="img-fluid rounded shadow qr-image">
                                    <div class="upi-text">Scan using any UPI app (PhonePe, GPay, Paytm)</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Request Withdrawal</h5>
                                <p id="withdrawal-message" class="text-muted mb-4">Withdraw funds to your bank account.</p>

                                <button id="withdrawButton" class="btn btn-warning w-100" disabled>
                                    Request Withdrawal
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Welcome Message -->
        <p class="lead text-muted mb-4">Welcome {{ $user['name'] }} !!, You can manage your account and activity here.</p>

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
                        <p><strong>Plan:</strong> <strong style="color: green;">STANDARD</strong> &nbsp; <strong
                                style="color: blue;">VALID TILL
                                :{{ \Carbon\Carbon::parse(Auth::user()->created_at)->addDays(30)->format('d F Y') }}
                            </strong></p>
                        <p><strong>Total Captcha:</strong> <span id="totalCaptchas">0</span> / 15000</p>

                        <!-- Buttons -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                            <a href="#" class="btn btn-success" style="width: 150px;"><span
                                    id="correctCount">0</span></a>
                            <a href="#" class="btn btn-danger" style="width: 150px;"><span
                                    id="incorrectCount">0</span></a>
                        </div>
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

                            <!-- Top-left Button Row -->
                            <div class="position-absolute top-0 start-0 mt-2 ms-3 d-flex">
                                <marquee>
                                    <div class="small">
                                        <strong class="text-success">+ 50 Paisa</strong> for Right Captcha &nbsp; | &nbsp;
                                        <strong class="text-danger">- 25 Paisa</strong> for Wrong Captcha
                                    </div>
                                </marquee>
                                {{-- <button type="button" class="btn btn-sm btn-warning me-1" id="tryCaptchaBtn"
                                onclick="tryCaptcha()" title="Try Captcha">
                                <i class="fas fa-vial">&nbsp; Try Free 10 Captcha</i>
                            </button> --}}
                                {{-- <form id="stopWorkFormLeft" action="{{ route('stop.work') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" title="Stop">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                </form> --}}
                            </div>

                            <!-- Top-right Button Row -->
                            <div class="position-absolute top-0 end-0 mt-2 me-3 d-flex">
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                    onclick="reloadCaptcha()" title="Reload Captcha">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1"
                                    onclick="downloadData()" title="Download">
                                    <i class="fas fa-download"></i>
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

                            <!-- Red warning message -->
                            <p id="wallet-warning" class="text-danger mt-2" style="display: none;">
                                <strong>To Start Earning, Add Money in Your Wallet.</strong>
                            </p>
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


    <script>
        function earnedSum() {
            $.ajax({
                url: "{{ route('user.earnedSum') }}",
                type: "GET",
                success: function(data) {
                    let earned = parseFloat(data.total_earned) || 0;

                    // Update both span and modal
                    $('#totalEarned').text(earned.toFixed(2));
                    $('#totalEarnedModal').text(earned.toFixed(2));

                    // Conditionally change button text
                    if (earned <= 0) {
                        $('#earnedButton').html('<i class="fas fa-wallet me-2"></i> Add Rs. 1000');
                        $('#captcha-input').prop('disabled', true);
                        $('#captcha-submit').attr('disabled', 'disabled').addClass('disabled');
                        $('#wallet-warning').show();

                        // Show QR in modal
                        $('#addMoneySection').removeClass('d-none');
                    } else {
                        $('#earnedButton').html('<i class="fas fa-wallet me-2"></i> ₹ <span id="totalEarned">' +
                            earned.toFixed(2) + '</span>');
                        $('#captcha-input').prop('disabled', false);
                        $('#captcha-submit').removeAttr('disabled').removeClass('disabled');
                        $('#wallet-warning').hide();

                        // Show QR in modal
                        $('#addMoneySection').addClass('d-none');
                    }
                },
                error: function(xhr) {
                    console.error("Error fetching earned sum:", xhr.responseText);
                }
            });
        }

        function syncEarnedToModal() {
            const earned = $('#totalEarned').text();
            $('#totalEarnedModal').text(earned);
        }

        setInterval(earnedSum, 5000);
        earnedSum(); // Initial call
    </script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Set default values in case there's no data
    if (!document.getElementById('trycorrectCount').innerText) {
        document.getElementById('trycorrectCount').innerText = '0';
    }

    if (!document.getElementById('tryincorrectCount').innerText) {
        document.getElementById('tryincorrectCount').innerText = '0';
    }
});
    </script>






{{-- trycaptcha script --}}
<script>
$(document).ready(function() {
    $('#trycaptcha-submit').on('click', function(e) {
        e.preventDefault();

        var captcha = $('#trycaptcha-input').val();
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('trycaptcha.verify') }}",
            type: 'POST',
            data: {
                _token: token,
                captcha: captcha
            },
            success: function(response) {

                console.log("Response:", response);

                $('#trycaptcha-input').val('');
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Congratulations 50 Paise Credited !!',
                        text: response.message
                    });

                    reloadCaptcha1();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops,  25 Paise Deducted !!',
                        text: response.message
                    });

                    reloadCaptcha1(); // Refresh captcha image if incorrect
                }
            },
            error: function() {
                $('#trycaptcha-input').val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred.'
                });

                reloadCaptcha1(); // Just in case
            }
        });
    });


});
window.reloadCaptcha1 = function() {
            $.get(`{{ url('/reload-captcha') }}?t=${Date.now()}`, function(data) {
                $('#captchaImage1').attr('src', data.captcha);
            });
            };


// trycaptchastatus
            function tryfetchCaptchaStats() {
            $.ajax({
                url: "{{ route('user.trycaptcha.stats') }}",
                type: "GET",
                success: function(data) {
                    $('#trytotalCaptchas').text(data.total_captchas || 0);
                    $('#trycorrectCount').text(data.correct_count || 0);
                    $('#tryincorrectCount').text(data.incorrect_count || 0);
                }
            });
        }

        // Fetch every 5 seconds
        setInterval(tryfetchCaptchaStats, 5000);
        tryfetchCaptchaStats(); // Initial load
</script>

















@endsection
