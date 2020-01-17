$(document).ready(function () {
    let x = 1;
    setInterval(function(){
        //this.sendToServer();
    },60000);
});

function sendToServer()
{
    $.ajax({
        'url' : '/sync-clinic',
        'type' : 'GET',
        'cache' : true,
        success: function (result) {
            console.log(result);
        }
    });
}