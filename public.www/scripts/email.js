function send_mail()
{
	// check that all fields are filled in
	var error_display = false;
	
	if($("name").value == "")
	{
		$("name_error").style.display = "";
		error_display = true;
	}
	else
	{
		$("name_error").style.display = "none";
	}
	
	if($("email_from").value == "" || $("email_from").value.search("@") == -1)
	{
		$("email_error").style.display = "";
		error_display = true;
	}
	else
	{
		$("email_error").style.display = "none";
	}
	
	if($("msg").value == "")
	{
		$("msg_error").style.display = "";
		error_display = true;
	}
	else
	{
		$("msg_error").style.display = "none";
	}
	
	if(error_display)
	{
		$("error_text").style.display = "";
		alert($("error_text").innerHTML);
	}
	else
	{
		$("error_text").style.display = "none";
		$("email_form").style.display = "none";
		$("email_status").style.display = "";
		send();
	}
}

function send()
{
	var url = "http://people.clemson.edu/~siam/common/SIAM_AJAX.php";
	bcc_on = ($("bcc").checked) ? "1" : "0";

	var myAjax = new Ajax.Request(url, {
		parameters: {
			name: $("name").value,
			email_from: $("email_from").value,
			email_to: $("email_to").value,
			msg: $("msg").value,
			bcc: bcc_on,
			func: 'send_email'
		},
		onSuccess: displayMsgSent,
		onFailure: showError
	});
}

function displayMsgSent(transport)
{
	if(transport.responseText == "1")
	{
		$("email_status_msg").innerHTML = "The message was succesfully sent.";
	}
	else
	{
		$("email_status_msg").innerHTML = "An error has occured, please try again later.";
	}
}

function showError()
{
	alert("An error has occured");
}