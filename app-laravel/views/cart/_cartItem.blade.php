<li class="list-group-item d-flex justify-content-between align-items-start">
	<div class="ms-2 me-auto row col-19">
		<div class="fw-bold">{{ $ci->product->name }}</div>
		<div>{{ $ci->product->comments }}</div>
		<div class="d-flex mt-3 justify-content-end">
			<div class="badge bg-secondary rounded-pill d-flex justify-content-center align-items-center">
				{{ $ci->product->section->name }}
            </div>&nbsp;
			<div class="badge bg-primary rounded-pill d-flex justify-content-center align-items-center">{{ $ci->qty }}
				x
				 @amount($ci->product->price)
				=
				@amount($ci->qty * $ci->product->price)
            </div>
			<form class="mx-2" method="POST" action="{{ route('cart_remove_product', ['id' => $ci->product->id]) }}">
				@csrf
				<button class="btn btn-outline-danger me-2" type="submit">
					<i class="bi bi-x-lg"></i>
				</button>
			</form>
		</div>
	</div>
	<div class="col-3 d-flex justify-content-center align-items-center">
		<div>
			<form action="{{ route('cart_update_qty', ['id' => $ci->product->id]) }}" method="POST">
                @csrf
				<div class="mb-3">
					<label class="form-label" for="{{ 'qty-' . $ci->product->id }}">{{ "Qty" }}</label>
					<input class="form-control" value="{{ $ci->qty }}" type="number" id="{{ 'qty-' . $ci->product->id }}" name="{{ 'qty-' . $ci->product->id }}"/>
				</div>
				<button class="btn btn-outline-primary" type="submit">
					Update
				</button>
			</form>
		</div>
	</div>
</li>
