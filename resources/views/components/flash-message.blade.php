@if(session()->has('flash_notification'))
<div class="row">
    <div class="col-12 font-weight-bold text-center m-3">
        @include('flash::message')
    </div>
</div>
@endif
