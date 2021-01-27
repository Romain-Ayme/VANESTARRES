document.addEventListener('DOMContentLoaded', function()
{
    window.onscroll = function(ev)
    {
        document.getElementById("scrollUp").className = (window.pageYOffset > 100) ? "visible" : "invisible";
    };
});