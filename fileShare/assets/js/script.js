$(function() {
    $('#addMultiFiles').click(function(e) {
        e.preventDefault();
        $('ol.filelist').append('<li class="d-flex align-items-center justify-content-between my-1"><div><input type="file" name="addFiles[]" /></div><button type="button" class="btn btn-danger btn-sm deleteMultiFile">Delete</button></li>');
    });

    //  Delete files 
    $(document).on('click', 'button.deleteMultiFile', function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });
});