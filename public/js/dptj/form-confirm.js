$(function(){
    $('.confirm').on('click', function (e){
        e.preventDefault();
        var form = $('#confirm-form');
        if(confirm('Are you sure?')) {
            form.trigger("submit");
        }
    });
});