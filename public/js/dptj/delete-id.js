$(function(){
    $('.del').on('click', function (){
        var id = $(this).data('id');
        del(id);
    });
    function del(id){
        var delForm = $('#delete');
        if(confirm('Are you sure you want to delete this?')) {
            delForm.attr("action", path + id);
            delForm.trigger("submit");
        }
    }
});