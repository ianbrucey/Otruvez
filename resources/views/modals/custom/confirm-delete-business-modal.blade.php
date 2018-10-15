<div id="confirm-delete-business-modal" class="sm-modal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close hide-sm-modal" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <h3 class="text-center">Are you sure you want to delete your Business?</h3>
            <div id="">
                <hr>
                <form action="https://www.otruvez.com/business/deleteBusiness/{{$businessId}}" method="post" class="validate-delete-account">
                    <input class="form-control" name="email" type="text" placeholder="enter email you sign in with">
                    <hr>
                    <button class="btn btn-danger" type="submit">Yes, Delete my business account</button>
                    {{csrf_field()}}
                </form>
            </div>
        </div>
    </div>
</div>