@extends('layout.base')

@section('body')
<div class="list-group">
    @foreach ($purchases as $p)
    <a href="{{ route('purchase_detail', ['id' => $p->id]) }}" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Purchase #{{ $p->id }}</h5>
            @php ($bgColor = ['bg-warning text-dark', 'bg-success', 'bg-secondary'])
					<small class="badge rounded-pill {{ $bgColor[$p->status] }} d-flex align-items-center">
						Status :
						{{ $p->getStatusLabel() }}
					</small>
				</div>
				@php ( $toPay = $p->toPay == 0 ? '<span class="badge rounded-pill bg-success">Paid</span>' : ('<span class="badge rounded-pill bg-danger">' . $p->toPay . ' left to paid</span>'))
				<p class="mb-1 d-flex align-items-center">Amount :
					@amount($p->amount)&nbsp;{!! $toPay !!}</p>
				<small>
					Made by
					{{ $p->user->name }}
					on
					{{ $p->dateCreation }}
					at
					{{ $p->dateCreation }}
				</small>
			</a>
            @endforeach
	</div>
@endsection
