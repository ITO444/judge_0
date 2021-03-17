$(function(){
    Echo.channel('update.submit.' + id)
    .listen('UpdateSubmit', (e) => {
        location.reload();
    });
});