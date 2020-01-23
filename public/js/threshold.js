$(document).ready(function () {
    let x = 1;
    setInterval(function(){
        //this.sendToServer('/sync-clinic',false);
        console.log(1);
        setInterval(function(){
            //this.sendToServer('/sync-medical-staff');
            console.log(2);
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