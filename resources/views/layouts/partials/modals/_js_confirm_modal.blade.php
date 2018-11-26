<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    Confirm Action
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="message">
                    Do you want to proceed?
                </p>

                <p class="text-danger">
                    <strong class="warning">Once completed, this action cannot be undone.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-button" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger confirm-button">Yes</button>
            </div>
        </div>
    </div>
</div>
{{-- Button Example
<a class="dropdown-item" href="#"
   data-toggle="modal" data-target="#confirmModal"
   data-title="Revoke role from all users confirmation"
   data-message="Do you want to revoke access of users with &bprime;{{ $role->name }}&prime; role?"
   data-warning="Once completed, this action cannot be undone."
   data-type="danger"
   data-action="revoke-role-users-access-form-{{ $role->id }}">
    Revoke from all users
</a>
--}}