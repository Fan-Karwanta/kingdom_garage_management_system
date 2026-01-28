$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var checkboxes = $('input[name="chk"]');
    var totalRows = $('table tbody tr').length;
    var selectAllButton = $('#select-all-btn input[name="selectAll"]');

    $('#select-all-btn').click(function () {
        // Toggle the checked state of all checkboxes
        var isChecked = !selectAllButton.prop('checked');
        checkboxes.prop('checked', isChecked);
    });

    checkboxes.change(function () {
        // Update the state of the button based on the checkboxes' states
        selectAllButton.prop('checked', checkboxes.length === checkboxes.filter(':checked').length);
    });

    $('#delete-selected-btn').click(function () {
        var selectedIds = [];
        var deleteUrl = $(this).data('url');

        $('input[name="chk"]:checked').each(function () {
            selectedIds.push($(this).closest('tr').data('user-id'));
        });

        if (selectedIds.length === 0) {
            swal({
                title: "Please select at least one record",
                icon: 'warning',
                button: "OK",
                dangerMode: true,
            });
            return;
        } else {
            var msg1 = "Are You Sure?";
            var msg2 = "You will not be able to recover this data afterwards!";
            var msg3 = "Cancel";
            var msg4 = "Yes, delete!";

            swal({
                title: msg1,
                text: msg2,
                icon: 'warning',
                cancelButtonColor: '#C1C1C1',
                buttons: [msg3, msg4],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            ids: selectedIds
                        },
                        success: function (response) {
                            selectedIds.forEach(function (id) {
                                $('tr[data-user-id="' + id + '"]').remove();
                            });
                            location.reload();
                        },
                        error: function () {
                            alert('An error occurred while deleting selected rows.');
                        }
                    });
                }
            });
        }
    });
});
