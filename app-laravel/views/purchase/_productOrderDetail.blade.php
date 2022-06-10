<li class="list-group-item d-flex justify-content-between lh-sm">
	<div>
		<h6 class="my-0">#{{ $d->product->id }} | {{ $d->product->name }}</h6>
		<small class="text-muted">Section : {{ $d->product->section->name }} <i class="bi bi-arrow-up-right-square"></i> <a href="{{ route('product_detail', ['id' => $d->product->id]) }}">Go to product's page</a></small>
	</div>
	<span class="text-muted">@amount($d->product->price) x {{$d->quantity}} = @amount($d->product->price * $d->quantity)</span>
</li>
