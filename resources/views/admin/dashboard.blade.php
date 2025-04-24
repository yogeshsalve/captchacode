@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-3 text-center">🛠️ Admin Dashboard</h1>
    <p class="text-center text-muted">Welcome, Admin! You can manage users and system settings.</p>
</div>

<div class="container mt-5">
    <h2 class="mb-4 text-primary">📋 Registered Users</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered shadow-sm rounded">
            <thead class="table-dark">
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
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($user->amount_received <= 0)
                                    <input type="text"
                                           class="form-control form-control-sm editable-amount"
                                           data-user-id="{{ $user->id }}"
                                           value="{{ $user->amount_received }}"
                                           onkeydown="if(event.key === 'Enter') updateAmount(event, this)">
                                @else
                                    <span class="badge bg-success px-3 py-2">{{ $user->amount_received }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
</div>



    <script>
  function updateAmount(event, inputElement) {
    const userId = inputElement.getAttribute('data-user-id');  // Get user ID from the data attribute
    const newAmount = inputElement.value;

    fetch(`/update-amount/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ amount_received: newAmount })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Success: Show success message
            alert(data.message);

            // Update the input field value with the new amount
            inputElement.value = newAmount;

            // Optionally, you can replace the input box with the updated value
            inputElement.style.display = 'none';  // Hide the text box

            // Create a span to display the updated value
            const span = document.createElement('span');
            span.textContent = `${newAmount}`;
            span.classList.add('updated-amount');  // You can style this span as needed

            // Append the span next to the input element
            inputElement.parentNode.appendChild(span);

        } else {
            // If the response doesn't contain a message, log it
            alert('Failed to update the amount: Unexpected response.');
        }
    })
    .catch(error => {
        // If there was an error with the request, log it
        console.error('Error:', error);
        alert("Something went wrong! Please try again.");
    });
}


</script>    
@endsection
