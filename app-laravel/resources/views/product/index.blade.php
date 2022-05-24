@extends('layout.base')

@section('body')
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