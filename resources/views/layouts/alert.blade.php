@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible show fade w-100">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">
                <span>×</span>
            </button>
            <p>{{ $message }}</p>
        </div>
    </div>
@endif
