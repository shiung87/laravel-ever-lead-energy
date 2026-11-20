@extends('layouts.admin')

@section('title', 'Testimonials')
@section('page_title', 'Customer Testimonials')

@section('content')
<div class="card kpi-card p-4 mb-4">
    <h6 class="fw-bold mb-3">Add Testimonial</h6>
    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="row g-2">
        @csrf
        <div class="col-md-3"><input type="text" name="customer_name" class="form-control form-control-sm" placeholder="Customer Name *" required></div>
        <div class="col-md-3"><input type="text" name="customer_role" class="form-control form-control-sm" placeholder="Role (e.g. Honda Civic Owner)"></div>
        <div class="col-md-2"><input type="text" name="vehicle_info" class="form-control form-control-sm" placeholder="Vehicle"></div>
        <div class="col-md-2"><input type="text" name="city" class="form-control form-control-sm" placeholder="City"></div>
        <div class="col-md-2"><input type="number" name="rating" value="5" min="1" max="5" class="form-control form-control-sm" required></div>
        <div class="col-10"><input type="text" name="quote" class="form-control form-control-sm" placeholder="Testimonial quote *" required></div>
        <div class="col-2"><button type="submit" class="btn btn-primary btn-sm w-100">Add</button></div>
    </form>
</div>

<div class="card kpi-card p-4">
    <table class="table align-middle mb-0">
        <thead><tr><th>Customer</th><th>Quote</th><th>Rating</th><th></th></tr></thead>
        <tbody>
        @foreach($testimonials as $t)
            <tr>
                <td><div class="fw-semibold">{{ $t->customer_name }}</div><div class="small text-muted">{{ $t->vehicle_info }} • {{ $t->city }}</div></td>
                <td class="small">{{ $t->quote }}</td>
                <td class="text-warning">{{ $t->rating }} ★</td>
                <td class="text-end">
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
