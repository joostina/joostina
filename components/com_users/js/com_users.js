$(document).ready(function() {
	$("#save").click(function () {
		$("input#task").val('saveUserEdit');
		$("#mosUserForm").submit();
	});
	$("#cancel").click(function () {
		$("input#task").val('cancel');
		$("#mosUserForm").submit();
	});
	jQuery.validator.messages.required = "";
	$("#mosUserForm").validate();
});