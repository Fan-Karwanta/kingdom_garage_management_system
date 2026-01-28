$(document).ready(function () {
  function getSharedText() {
    const urlPatterns = [
      { pattern: 'supplier/', text: 'Shared with supplier' },   //check script in layouts/app.blade.php-translation text
      { pattern: 'product/', text: 'Shared with supplier' },
    ];

    for (const item of urlPatterns) {
      if (window.location.href.includes(item.pattern)) {
        return item.text;
      }
    }
    // Default text if no patterns match
    return 'Shared with customer';
  }
  // Add a new note row when the addNotes button is clicked
  $('.addNotes').click(function () {
    let newNoteId = Date.now(); // Generate a unique ID
    let sharedText = getSharedText(); // Get the appropriate shared text
    let newNoteRow = `
          <div class="row notes-row" id="notes-${newNoteId}">
            <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for=""> ${translations.note} <label class="color-danger"></label></label>
            <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
              <textarea class="form-control" id="" name="notes[${newNoteId}][note_text]" maxlength="100"></textarea>   
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 form-group my-form-group">
              <input type="file" name="notes[${newNoteId}][note_file][]" class="form-control imageclass mt-2" data-max-file-size="5M" accept="image/*,application/pdf,video/*" multiple/>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 pt-0">
              <div class="d-flex">
                <input type="checkbox" name="notes[${newNoteId}][internal]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                <label class="control-label pt-1" for="">${translations.internal_notes} <label class="text-danger"></label></label>
              </div>
              <div class="d-flex">
                <input type="checkbox" name="notes[${newNoteId}][shared]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                <label class="control-label pt-1" for="">${sharedText} <label class="text-danger"></label></label>
              </div>
            </div> 
            <div class="col-md-1 col-lg-1 col-xl-1 col-xxl-1 col-sm-1 col-xs-1 text-center pt-3">
              <i class="fa fa-trash fa-2x deleteNotes"></i>
            </div>
          </div>
        `;
    $('.note-row').append(newNoteRow);
  });

  // Delete a specific note row when the delete button is clicked
  $(document).on('click', '.deleteNotes', function () {
    $(this).closest('.notes-row').remove();
  });

  $('body').on('click', '.deletedatas', function () {

    var url = $(this).attr('url');
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
        window.location.href = url;
      }
    });
  });
});
