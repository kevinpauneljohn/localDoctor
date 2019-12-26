$(document).ready(function(){
    $(".main-header .nav-link").click(function(){
        if($("body").hasClass('sidebar-collapse'))
        {
            $("body").removeClass("sidebar-collapse");
        }else{
            $("body").addClass("sidebar-collapse");
        }
    });
});