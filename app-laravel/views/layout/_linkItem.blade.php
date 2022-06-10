<li class="nav-item">
	<a class="nav-link {{ Route::currentRouteName() == $route ? 'active' : '' }}" {{ Route::currentRouteName() == $route ? 'aria-current="' . $label . '"' : '' }} href="{{ route($route) }}">
		<i class="{{ $icon }}"></i> {{ $label }}
	</a>
</li>
