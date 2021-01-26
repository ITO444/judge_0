$(function(){
    Echo.private('update.publish.' + id)
    .listen('UpdatePublish', (e) => {
        if(e.result == "Published"){
            window.location.replace("/task/" + task_id);
        }
        $("#result").html(e.result);
        $("#message").html(e.message);
    });
});