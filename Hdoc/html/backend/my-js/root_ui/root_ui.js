
user_info = new Array();
team_info = new Array();
race_info = new Array();
field_apply = new Array();
temp_field_apply = new Array();
news = new Array();


function list_field_apply() {
	var tbody = $('section.tab-item-6 table tbody');

	$.each( field_apply, function(i,v) {
		var tr = $('<tr class="team_info normal_tr" id="'+v.a_id+'"></tr>');
		tbody.append( tr );

		tr.append( $('<td>'+v.t_name+'</td>') );
		tr.append( $('<td><p class="text_left">'+v.leader+'</p><p class="text_left">'+v.leader_info+'</p></td>') );
		tr.append( $('<td><a class="team_chief" target="_blank" href="'+v.link+'">查看计划书</a></td>') );
		if( v.step==1 ) {
			tr.append( $('<td>待处理</td>') );
			tr.append( $('<td class="team_set"><a class="pass race_edit">通过</a><a class="refuse race_edit">不通过</a></td>') );
		}
		else {
			tr.append( $('<td>专家评审</td>') );
			tr.append( $('<td class="team_set"><a class="show_comments team_found">查看评审结果</a></td>') );	
		}
		
		// "查看评审结果"链接动作
		$('section.tab-item-6 a.show_comments').click( function(e) {
			var a_id = $(this).parents('tr').attr('id');
			var ind = find_field_apply( a_id );
			if( ind>=0 ) {
				var div = $('div.comments');
				div.html( '' );
				var comments = $.parseJSON( field_apply[ind].reviewer ).d;
				
				$.each( comments, function(i,v) {
					div.append('<p><b>'+v.p+'</b></p><p style="padding-left:24px">'+v.r+'</p></br>');
				} );
				
				div.css( 'top', e.pageY-100 );
				div.toggleClass( 'hide' );
			}
		} );
		
		// "通过"链接动作
		tr.find('a.pass').click( function() {
			var a_id = tr.attr('id');
			var ind = find_field_apply( a_id );
			if( ind<0 )
				return;

			var in_p = new Object();
			in_p['id'] = a_id;
			in_p['op'] = 'pass';
			$.post( 'my-php/root_ui/set_field_apply.php', in_p, function(data) {
				if( data=='OK' ) {
					field_apply.splice( ind, 1 );
					tr.hide(400, function() {tr.remove();} );
				}
			} );
			
		} );
		
		// "不通过"链接动作
		tr.find('a.refuse').click( function() {
			var a_id = tr.attr('id');
			var ind = find_field_apply( a_id );
			if( ind<0 )
				return;

			var in_p = new Object();
			in_p['id'] = a_id;
			in_p['op'] = 'refuse';
			$.post( 'my-php/root_ui/set_field_apply.php', in_p, function(data) {
				if( data=='OK' ) {
					field_apply.splice( ind, 1 );
					tr.hide(400, function() {tr.remove();} );
				}
			} );
		} );
		
	} );
}

//-------------------------------------------------------------------------
//								public functions
//-------------------------------------------------------------------------
function temp_field_apply_op( apply_id, op ) {
	
	var se = confirm('是否执行此操作?');
	if( se==false )
		return;
	
	var sig = 0;
	
	$.each( temp_field_apply, function(i,v) {
		$.each( v.apply, function(i2,v2) {
			
			if( v2.id==apply_id ) {
				
				sig = 1;
				
				var mid = temp_field_apply[i].apply;
				$.each( mid, function(i3,v3) {
	
					if( v3.id==apply_id ) {
						temp_field_apply[i].apply[i3].state = op;
						sig = 2;
					}
					else {
						if( op=='pass' )
							temp_field_apply[i].apply[i3].state = 'refuse';
					}
					
					// 提交审判信息
					var in_p = new Object();
					in_p['id'] = apply_id;
					in_p['res'] = temp_field_apply[i].apply[i3].state;
					if( in_p['res']=='pass' ) {
						in_p['st'] = temp_field_apply[i].apply[i3].st;
						in_p['et'] = temp_field_apply[i].apply[i3].et;
						in_p['reason'] = temp_field_apply[i].apply[i3].reason;
						in_p['u_id'] = temp_field_apply[i].apply[i3].u_id;
					}
						
					$.post( 'my-php/root_ui/set_temp_field_apply.php', in_p, function(data) {
						// update ui
						var id = temp_field_apply[i].apply[i3].id;		
						if( temp_field_apply[i].apply[i3].state=='pass' )
							$('#'+id+'_st').text('申请通过');			
						if( temp_field_apply[i].apply[i3].state=='refuse' )
							$('#'+id+'_st').text('申请未通过');
					} );

					if( sig==2 && op!='pass' )
						return false;
				} );
			}

		} );
					
		if( sig>0 )
			return false;
	} );
	
}

function parse_content( affair ) {
	$.each( affair, function(i, v) {
		if( typeof(v.content)=="undefined" || v.content==='' )
			return true;
		affair[i].c_obj = $.parseJSON( v.content );
	} );
}

function find_field_apply( a_id ) {
	
	var ind = -1;
	
	$.each( field_apply, function(i,v) {
		if( v.a_id==a_id ) {
			ind = i;
			return false;
		}
	} );
	
	return ind;
}

function find_news( id ) {
	var ind = -1;
	$.each( news, function(i,v) {
		if( v.id==id ) {
			ind = i;
			return false;
		}
	} );
	return ind;
}

// UTC - 单位为: 秒
function formatDate( UTC ) {
	var d = new Date( (UTC+8*3600)*1000 );
	var year = d.getUTCFullYear();     
	var month = d.getUTCMonth() + 1;     
	var date = d.getUTCDate();         
	return year+"."+month+"."+date;     
}

function formatDate_2( UTC ) {
	var d = new Date( (UTC+8*3600)*1000 );
	var year = d.getUTCFullYear();     
	var month = d.getUTCMonth() + 1;     
	var date = d.getUTCDate();     
	var hour = d.getUTCHours();     
	var minute = d.getUTCMinutes();     
	var second = d.getUTCSeconds();     
	return year+"."+month+"."+date+" "+hour+":"+minute+":"+second;     
}

function formatDate_3( UTC ) {
	var d = new Date( (UTC+8*3600)*1000 );   
	var hour = d.getUTCHours();         
	return hour;     
}