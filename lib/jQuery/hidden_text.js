$(document).ready(
function() 
{ 
    $('.hidden').hide();
    $('.title_of_hidden').toggle(
    function() 
    {
        $(this).next().slideDown(500);
    },
    
    function()
    {
        $(this).next().slideUp(500);
    });

});