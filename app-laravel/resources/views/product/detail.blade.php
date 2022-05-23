@extends('layout.base')

@section('body')
	<figure class="text-center blockquote">
		<h2 class="text-center m-4">{{ $p->name }}</h2>
		<figcaption class="blockquote-footer">
			{{ $p->section->name }}
		</figcaption>
	</figure>
	<img src="https://picsum.photos/800/300" class="img-fluid rounded mx-auto d-block" alt="image">
	<p class="lead mt-4">{{ $p->comments }}</p>
	<div class="mx-auto text-center" style="width: 250px;">
		<div class="card mb-4 rounded-3 shadow-sm">
			<div class="card-body">
				<h1 class="card-title pricing-card-title">@amount($p->price)</h1>
				<ul class="list-unstyled mt-3 mb-4">
					<li>Stock available : {{ $p->stock }}</li>
				</ul>
				<a class="w-100 btn btn-lg btn-outline-primary" href="#" role="button">
                	<i class="bi bi-cart"></i>Add to cart</button>
				</a>
			</div>
		</div>
	</div>
@endsection