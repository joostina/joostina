$(document).ready(function() {

	var $click_td = $('.acl_state input');

	$click_td.bind('click',function(){
		//$('input',$(this)).prop('checked',true);

		console.log( $(this) );

		var $chbox = $(this);

		$.ajax({
			url:"ajax.index.php?option=acls&task=change",
			type:"POST",
			dataType:'json',
			data: {
				state: ($chbox.prop('checked') ? 1 : 0 ),
				group_id: $chbox.data('group-id'),
				task_id:$chbox.val()
			},
			success:function(data) {
				$("#change_result").html(data.body);
			}
		});

		return true;
	} );


});