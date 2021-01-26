$(function(){
    $('.del').on('click', function (){
        var id = $(this).data('id');
        del(id);
    });
    function del(id){
        var delForm = $('#delete');
        if(confirm('Are you sure you want to delete this test case?')) {
            delForm.attr("action", "/task/" + task_id + "/tests/" + id);
            delForm.trigger("submit");
        }
    }
});