$(function(){

    $("ul.dropdown_menu li").hover(function(){
    
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
    
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');
    
    });
    
    $("ul.dropdown_menu li ul li:has(ul)").find("a:first").append(" &raquo; ");

});