$(document).ready(function() {

	var $click_td = $('.acl_state input');

	$click_td.bind('click',function(){
		//$('input',$(this)).prop('checked',true);

		//var $h = $('input',$(this));

		console.log( $(this) );

		//$h.click(  );

		return false;
	} );


});