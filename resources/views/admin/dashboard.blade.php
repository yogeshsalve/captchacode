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
                        <td class="d-flex justify-content-between align-items-center">
                            <span>{{ $user->amount_received }}</span>
                            
                            <!-- Edit Amount Button with Pencil Icon -->
                            @if ($user->amount_received <= 0)
                                <a href="javascript:void(0)" 
                                   class="btn btn-outline-info" 
                                   id="editBtn-{{ $user->id }}"
                                   onclick="openEditAmountModal({{ $user->id }})">
                                   <i class="fas fa-pencil-alt"></i>
                                </a>
                            @endif
                        </td>
                @endforeach
            </tbody>
        </table>
    </div>



    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="editAmountModal" tabindex="-1" aria-labelledby="editAmountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAmountModalLabel">Enter Amount to Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAmountForm">
                        <input type="hidden" id="userIdInput" name="user_id">
                        <div class="mb-3">
                            <label for="amountInput" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amountInput" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (required for modal) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script>
        function openEditAmountModal(userId) {
            document.getElementById('userIdInput').value = userId;
            const modal = new bootstrap.Modal(document.getElementById('editAmountModal'));
            modal.show();
        }

        document.getElementById('editAmountForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const userId = document.getElementById('userIdInput').value;
            const amount = document.getElementById('amountInput').value;

            fetch(`/update-amount/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        amount_received: amount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);



                    // HIDE BUTTON
                    const editBtn = document.getElementById('editBtn-' + userId);
                    if (editBtn) {
                        editBtn.style.display = 'none'; // hide the button
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editAmountModal'));
                    modal.hide();
                    location.reload(); // reload to reflect updated data if needed
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Something went wrong!");
                });
        });
    </script>
@endsection
