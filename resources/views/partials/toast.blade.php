<div id="ToastsBox" class="position-fixed top-0 p-3" style="z-index: 11; left:40vw">
    <div id="success-message" class="toast bg-primary text-center system-message" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
            @if(Session::get('success') == true)
                {{Session::get('msg')}}
            @endif
        </div>
    </div>
    @foreach ($errors->all() as $error)
        <div class="toast bg-danger system-message text-center error-message" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body text-white">
                {{ $error }}
            </div>
        </div>
    @endforeach
</div>
