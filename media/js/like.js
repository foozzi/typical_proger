
$(document).ready(function()
{
  $("span.on_img").mouseover(function ()
  {
    $(this).addClass("over_img");
  });

  $("span.on_img").mouseout(function ()
  {
    $(this).removeClass("over_img");
  });
});

$(function() {
$(".love").click(function() 
{
var id = $(this).attr("id");
var dataString = 'id='+ id ;
var parent = $(this);


$(this).fadeOut(300);
$.ajax({
type: "POST",
url: "class/like.class.php",
data: dataString,
cache: false,

success: function(html)
{
parent.html(html);
parent.fadeIn(300);
} 
});


return false;

 });
});
