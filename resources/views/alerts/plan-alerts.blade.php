@if (session('successMessage'))
    <div class="alert alert-success">
        {{ session('successMessage') }}
    </div>
@endif

@if (session('infoMessage'))
    <div class="alert alert-info">
        {{ session('infoMessage') }}
    </div>
@endif

@if (session('warningMessage'))
    <div class="alert alert-warning">
        {{ session('warningMessage') }}
    </div>
@endif

@if (session('errorMessage'))
    <div class="alert alert-danger">
        {{ session('errorMessage') }}
    </div>
@endif

@if (isset($_GET['uploadSuccess']))
    <div class="alert alert-success">
        Your upload was successful
    </div>
@endif

@if (isset($_GET['uploadFailed']))
    <div class="alert alert-warning">
        Your upload failed
    </div>
@endif

@if (isset($_GET['messageSent']))
    <div class="alert alert-success">
        Your message was sent
    </div>
@endif
