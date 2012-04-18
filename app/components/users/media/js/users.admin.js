$(document).ready(function() {

	var $click_td = $('.acl_state input');

	$click_td.bind('click',function(){

		var $chbox = $(this);

		$.ajax({
			url:"ajax.index.php?option=users&task=change_rules",
			type:"POST",
			dataType:'json',
			data: {
				state: ($chbox.prop('checked') ? 1 : 0 ),
				group_id: $chbox.data('group-id'),
				task_id:$chbox.val()
			},
			success:function(data) {
                joosNotify(data.message,'success');
			}
		});

		return true;
	} );
    
});