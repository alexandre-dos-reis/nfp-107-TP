@extends('layout.base')

@section('body')
    <h2 class="text-center mb-4">Checkout</h2>
    <ul class="list-group">
        @foreach ($cartItems as $ci)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="ms-2 me-auto">
                    <span class="fw-bold">{{ $ci->product->name }}</span>
                    <span>{{ $ci->product->comments }}</span>
                </div>
                <span class="badge bg-secondary rounded-pill">
                    {{ $ci->product->section->name }}
                </span>&nbsp;
                <span class="badge bg-primary rounded-pill">
                    @amount($ci->product->price) x {{ $ci->qty }} = @amount($ci->product->price * $ci->qty)
                </span>
            </li>
        @endforeach
    </ul>
    <div class="container w-50 mt-4">
        <h4 class="mb-3 text-center">Your information</h4>
        <form class="needs-validation" novalidate="">
            <div class="row g-3">
                <div class="col-sm-12">
                    <label for="firstName" class="form-label">Fullname</label>
                    <input type="text" class="form-control" id="firstName" value="Alexandre" disabled>
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="Dos Reis" disabled>
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Credit Card</label>
                    <input type="email" class="form-control" id="email" value="3782 8224 6310 0052" disabled>
                </div>
            </div>
        </form>
    </div>
    <div class="mx-auto text-center mt-4" style="width: 300px;">
        <div class="card mb-4 rounded-3 shadow-sm">
            <div class="card-body">
                <h1 class="card-title pricing-card-title">@amount($total)</h1>
                <form method="POST" class="mb-2" action="{{ route('pay') }}">
                    @csrf
                    <button class="w-100 btn btn-lg btn-outline-primary"><i class="bi bi-credit-card"></i> Confirm & Pay</button>
                </form>
            </div>
        </div>
    </div>
@endsection
