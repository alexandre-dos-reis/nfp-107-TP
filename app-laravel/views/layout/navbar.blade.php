@inject('cartService', 'App\Service\Cart\CartService')

<nav class="navbar navbar-expand-lg navbar-light">
	<div class="container-fluid">
		<a class="navbar-brand" href="{{ route('product_index') }}">Laravel Mini Cars</a>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
				@include('layout/_linkItem', [
					'route' => 'product_index',
					'label' => 'Products',
					'icon' => 'bi bi-bicycle'
				])
				@include('layout/_linkItem', [
					'route' => 'purchase_index',
					'label' => 'My purchases',
					'icon' => 'bi bi-receipt-cutoff'
				])
				{{-- {% if app.user %}
					{% include "navbar/_linkItem.html.twig" with {
						'path': 'purchase_index',
						'label': 'My purchases',
						'icon' : 'bi bi-receipt-cutoff'
					} %}
				{% else %}
					<li class="nav-item">
						<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">My purchases</a>
					</li>
				{% endif %} --}}
			</ul>
			<ul class="navbar-nav mb-2 mb-lg-0">
				{{-- {% if app.user %}
					<li class="nav-item">
						<a class="nav-link" style="cursor: default;" href="#" tabindex="-1" aria-disabled="true">Logged as
							{{ app.user.username ?? '' }}</a>
					</li>
					{% include "navbar/_linkItem.html.twig" with {
                    'path': 'logout',
                    'label': 'Logout',
					'icon' : 'bi bi-door-closed'
                } %}
				{% else %}
					{% include "navbar/_linkItem.html.twig" with {
                    'path': 'login',
                    'label': 'Login',
					'icon' : 'bi bi-box-arrow-in-right'
                } %}
				{% endif %} --}}
				<li class="nav-item">
					<a class="d-flex justify-content-center align-items-center nav-link {{ Route::currentRouteName() == 'cart_index' ? 'active' : '' }} position-relative" {{ Route::currentRouteName() == 'cart_index' ? 'aria-current="Cart"' : '' }} href="{{ route('cart_index') }}">
						<i class="bi bi-cart"></i>&nbsp;Cart{!! $cartService->countProducts() > 0 ? ('&nbsp;<span class="badge rounded-pill bg-danger">' . $cartService->countProducts() . '</span>') : '' !!}
					</a>
				</li>

			</ul>
		</div>
	</div>
</nav>
