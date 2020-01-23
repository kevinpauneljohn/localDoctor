$(document).ready(function () {
    let x = 1;
    setInterval(function(){
        this.sendToServer('/sync-clinic',false);
        setInterval(function(){
            this.sendToServer('/sync-medical-staff',false);
        },10000);
    },15000);
});

function sendToServer(url,displayResult = true)
{
    $.ajax({
        'url' : url,
        'type' : 'GET',
        'cache' : true,
        success: function (result) {
            if(displayResult === true)
            {
                console.log(result);
            }
        }
    });
}