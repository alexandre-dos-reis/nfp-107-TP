@extends('layout.base')

@section('body')
@php ( $bgColor = ['bg-warning text-dark', 'bg-success', 'bg-secondary'])
	<div class="py-5 text-center">
		<h2>Purchase #{{ $purchase->id }}</h2>
		<p class="lead">
        @if($purchase->employee)
			Person in charge : <b>{{ $purchase->employee->name }}</b><br/>
		@endif
		Made {{ $purchase->dateCreation }}
			at
			{{ $purchase->dateCreation }}<br/>
			@php ( $toPay = $purchase->toPay === 0 ? '<span class="badge rounded-pill bg-success">Paid</span>' : ('<span class="badge rounded-pill bg-danger">' . $purchase->toPay . ' left to paid</span>'))
			{!! $toPay !!}
			<span class="badge rounded-pill {{ $bgColor[$purchase->status] }}">{{ $purchase->getStatusLabel() }}</span>
			</p>
	</div>
	<div class="row g-5">
		<div class="col-md-6 col-lg-6 order-md-last">
			<h4 class="d-flex justify-content-between align-items-center mb-3">
				<span class="text-primary">Purchase's details</span>
				<span class="badge bg-primary rounded-pill">{{ $purchase->orderDetails }} product{{ count($purchase->purchasedetails) > 1 ? 's' : '' }}</span>
			</h4>
			<ul class="list-group mb-3">
                @foreach($purchase->purchasedetails as $d)
                    @include('purchase/_productOrderDetail', ['d' => $d])
                @endforeach
				<li class="list-group-item d-flex justify-content-between">
					<span>Total (EUR)</span>
					<strong>@amount($purchase->amount)</strong>
				</li>
			</ul>
			{{-- @can('EMPLOYEE') --}}
				<form method="POST" action="{{ route('purchase_update_status', ['id' => $purchase->id] ) }}">
					@csrf
					<select name="status" class="form-select form-select mb-3" aria-label=".form-select-lg example">
						<option>-- Choose a status --</option>
						@foreach ($purchase::STATUSES as $key => $s)
							<option {{ $key === $purchase->status ? 'selected' : '' }} value="{{ $key }}">{{ $s }}</option>
						@endforeach
					</select>
					<button type="submit" class="btn btn-primary">Update status</button>
				</form>
			{{-- @endcan --}}
		</div>
		<div class="col-md-6 col-lg-6">
			<h4 class="mb-3">Client's information</h4>
			<form class="needs-validation" novalidate="">
				<div class="row g-3">
					<div class="col-sm-12">
						<label for="firstName" class="form-label">Fullname</label>
						<input type="text" class="form-control" id="firstName" value="{{ $purchase->user->name }}" disabled>
					</div>
					<div class="col-12">
						<label for="email" class="form-label">Email</label>
						<input type="email" class="form-control" id="email" value="{{ $purchase->user->email }}" disabled>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
