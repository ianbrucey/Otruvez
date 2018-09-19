@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            {{--<li>No special characters may be entered</li>--}}
        </ul>
    </div>
@endif