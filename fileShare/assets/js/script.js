$(function() {
    $('#addMultiFiles').click(function(e) {
        e.preventDefault();
        // alert('clicked');
        $('ol.filelist').append('<li class="d-flex align-items-center justify-content-between my-1"><div><input type="file" name="addFiles[]" /></div><span class="deleteMultiFile">Delete</span></li>');
    });

    //  Delete files 
    $(document).on('click', 'span.deleteMultiFile', function(e) {
        e.preventDefault();
        // alert('click');
        $(this).parent().remove();
    });
});