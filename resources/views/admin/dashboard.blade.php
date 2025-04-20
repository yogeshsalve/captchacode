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
                <th>Email</th>
                <th>Registered At</th>
                <th>Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                    <td>Rs. 500 </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
