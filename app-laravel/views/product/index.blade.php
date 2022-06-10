@extends('layout.base')

@section('body')
<form class="row g-3">
    <div class="col-sm-11">
        <input type="text" placeholder="Fulltext search, try the following words : dodge, harley, ..." value="" name="search" class="form-control" id="search" aria-describedby="search">
    </div>
    <div class="col-sm">
        <button type="submit" class="btn btn-primary mb-2">Search</button>
    </div>
</form>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
    @foreach ($products as $k => $p)
        <a href="{{ route('product_detail', [ 'id' => $p->id ]) }}" class="text-decoration-none link-secondary">
            <div class="col">
                <div class="card shadow-sm">
                    <img src="https://picsum.photos/200/100?random={{ $k }}" alt="">
                    <div class="card-body">
                        <h4>{{ $p->name }}</h4>
                        <p class="card-text">{{ Str::limit($p->comments, 50, '...') }}</p>
                        <p class="card-text">@amount($p->price)</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Details</button>
                                <form action="{{ route('cart_increment_qty', ['id' => $p->id]) }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Add to cart <i class="bi bi-cart-fill"></i></button>
                                </form>
                            </div>
                            <small class="text-muted">{{ $p->section->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
</div>
@endsection