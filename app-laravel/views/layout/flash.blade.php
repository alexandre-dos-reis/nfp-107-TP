@if(Session::has('success'))
    <div class="alert alert-success d-flex justify-content-center align-items-center">
        {{Session::get('success')}}
    </div>
@endif
@if(Session::has('danger'))
    <div class="alert alert-danger d-flex justify-content-center align-items-center">
        {{Session::get('danger')}}
    </div>
@endif