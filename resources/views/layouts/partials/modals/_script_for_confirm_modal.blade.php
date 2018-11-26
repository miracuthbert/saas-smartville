<script>
    $('#confirmModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var title = button.data('title') // Extract info from data-* attributes
        var message = button.data('message') // Extract info from data-* attributes
        var warning = button.data('warning') // Extract info from data-* attributes
        var type = button.data('type') // Extract info from data-* attributes
        var form = button.data('action') // Extract info from data-* attributes
        var modal = $(this)

        modal.find('.modal-title').text(title)
        modal.find('.modal-body .message').html(message)
        modal.find('.modal-body .warning').html(warning)
        modal.find('.modal-dialog').removeClass().addClass('modal-dialog modal-' + type)
        modal.find('.modal-footer .confirm-button').removeClass()
            .addClass('confirm-button btn btn-' + type)
            .click(function () {
                // submit form
                $('form#' + form).submit();

                // close modal
                modal.modal('hide')
            })
    })
</script>
