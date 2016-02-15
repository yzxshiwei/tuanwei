$(document).ready(function(){
	$.get("backend/my-php/php-lib/get_user_info.php?u_id=0",function(data,success){
		if(success=="success"&&data!=null)
		{
			console.log(data)
			var user_email = data.email
			$(".rAndl").html('<a href="backend/">'+user_email+'</a>')
		}

	},"json")


})