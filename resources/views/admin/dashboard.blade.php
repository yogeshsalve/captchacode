@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Welcome, Admin! You can manage users and system settings.</p>
    </div>

    <div class="container">
        <h2>Registered Users</h2>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Registered At</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->email }}</td>
                        <td> {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d F Y') }}</td>
                        <td>{{ $user->amount_received }}
                            <!-- Edit Amount Button with Pencil Icon -->
                            <!-- Edit Button -->
                            <a href="javascript:void(0)" class="btn btn-outline-info"
                                onclick="openEditAmountModal({{ $user->id }}, {{ $user->amount_paid ?? 0 }})">
                                <i class="fas fa-pencil-alt"></i> Edit Amount
                            </a>
                        </td>
                @endforeach
            </tbody>
        </table>
    </div>

  <!-- Modal for editing amount -->
<div class="modal fade" id="editAmountModal" tabindex="-1" aria-labelledby="editAmountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAmountModalLabel">Edit Amount Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Amount Form -->
                <form id="editAmountForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Amount Paid (₹)</label>
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Amount</button>
                </form>
                <div id="successMessage" class="mt-2 text-success" style="display:none;">
                    <strong>Amount received successfully!</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    function openEditAmountModal(userId, currentAmount) {
        $('#amount_paid').val(currentAmount);
        $('#editAmountForm').attr('action', `/admin/users/${userId}/update-amount`);
        $('#editAmountModal').modal('show');
    }

    $('#editAmountForm').submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const url = form.attr('action');
        const data = form.serialize();

        $.post(url, data, function (response) {
            $('#editAmountModal').modal('hide');
            $('#successMessage').show().delay(2000).fadeOut();
            location.reload(); // Optional: you can remove this if you dynamically update the UI
        }).fail(function (xhr) {
            alert("Error updating amount.");
        });
    });
</script>
@endsection
