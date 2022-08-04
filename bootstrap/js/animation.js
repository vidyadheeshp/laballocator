$(document).ready(function(){
    $.cookie('animations','bounce');
    $('#change-transitions1').click(function() {
        $(this).removeClass($.cookie('animations'));
        var ani = $(this).attr('data-value');
        $(this).addClass("animated " + ani);
        $.cookie('animations', ani);
    });
	
	$('#change-transitions2').click(function() {
        $(this).removeClass($.cookie('animations'));
        var ani = $(this).attr('data-value');
        $(this).addClass("animated " + ani);
        $.cookie('animations', ani);
    });
});