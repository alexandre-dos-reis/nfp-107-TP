@extends('layout.base')

@section('body')
<h2 class="text-center mb-4">Cart</h2>
    @if(count($cartItems) > 0)
		<ul class="list-group">
			@foreach ($cartItems as $ci)
				@include('cart/_cartItem', [
					'ci' => $ci
				])
			@endforeach
			<li class="list-group-item d-flex justify-content-between align-items-center">
				<h4>Total (EUR)</h4>
				<div class="d-flex justify-content-between align-items-center">
					<h4>@amount($total)</h4>
					<span>&nbsp;&nbsp;</span>
					<form class="mb-2" action="{{ route('product_index') }}">
						<button class="btn btn-primary">Go to Checkout</button>
					</form>
				</div>
			</li>
		</ul>
	    @else
		<h4 class="mt-4 text-center">Your cart is empty ! Go to
			<a href="{{ route('product_index') }}">products page</a>.</h4>
    @endif
@endsection