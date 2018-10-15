<div id="confirm-delete-account-modal" class="sm-modal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close hide-sm-modal" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <h3 class="text-center">Are you sure you want to delete your account?</h3>
            <div id="">
                <hr>
                <form action="https://www.otruvez.com/account/deleteAccount" method="post" class="validate-delete-account">
                    <input class="form-control" name="email" type="text" placeholder="enter user account email">
                    <hr>
                    {{csrf_field()}}
                    <button class="btn btn-danger" type="submit">Yes, Delete my account</button>
                </form>
            </div>
        </div>
    </div>
</div>