		var expert_info;

		$( function() {
		
			$('#expert_choose').draggable();
			$('#expert_choose').css( 'width', '60%' );
					
			$('#expert_add').click( function(e) {
				var div = $('#expert_choose');
				div.css( 'top', e.pageY-100 );
				div.toggleClass( 'hide' );
				
				div.attr( 'num', $(this).attr('num') );
			} );
		
			$.post( 'my-php/root_ui/get_expert.php', function(data) {
				if( data ) {
					expert_info = $.parseJSON( data );
					list_expert( expert_info );
				}
			} );
			
		} );
		
		function list_expert( expert_info ) {
			var t = $('#expert_choose');
			$.each( expert_info, function(i,v) {
				t.append( '<p class="expert_label"><input type="checkbox" index="'+i+'"/>'+v.name+'-'+v.com+'</p>' );
			} );
			
			t.append( "<br/><button id='expert_choose_b' class='expert_choose_b'>确定</button>" );
			
			$('#expert_choose_b').click( function() {
				var t = $(this);
				var num = t.parent().attr( 'num' );
				
				var res = t.parent().find('input[type=checkbox]:checked');
				var ps = new Array();
				var p_uid = new Array();
				
				$.each( res, function(i,v) {
					var index  = $(v).attr('index');
					ps.push( expert_info[index].name );
					p_uid.push( expert_info[index].u_id );
				} );
	
				$('#'+num+'_expert_class').val( ps.join(',') );
				$('#'+num+'_expert_class').prop( 'uid', p_uid.join(',') );
				
				$(this).parent('div.comments').toggleClass( 'hide' );
			} );
		};


//==========lee的增补=============

$(document).ready(function(){
	$.get("my-php/php-lib/get_user_info.php?u_id=0",function(data,success){
		if(success=="success"&&data!=null)
		{
			var user_email = data.email
			$(".rAndl").html('<a href="backend/">'+user_email+'</a>')
			$(".user_name").html(data.name)
		}

	},"json")


})

function logout(){
	$.get("my-php/lee/logout.php",function(){
		
		window.location.href='../index.html'

	})

	

}
		
	
		




		