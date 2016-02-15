
function append_race( tbody, race_data, d ) {
								
	$.each( race_data, function(i,v) {
		var tr = $('<tr class="race_info" id="'+v.id+'"></tr>');
		if( d==0 )
			tbody.append( tr );
		else
			tbody.children('tr:first').after( tr );
		
		tr.append( $('<td>'+i+'</td>') );
		tr.append( $('<td><a class="race_name" target="_blank" href="work_review.html?race_id="'+v.id+'">'+v.pn+'</a></td>') );
		tr.append( $('<td>'+v.r_name+'</td>') );
		tr.append( $('<td>'+v.col+'</td>') );
	} );

}

/*
		field_apply[] .id 		- 事务的 id 字段值
					  .link		- 附件链接	
					  .t		- 收到的时间	
*/

function list_field_apply() {
	var tbody = $('section.tab-item-6 table tbody');

	$.each( field_apply, function(i,v) {
		var tr = $('<tr class="team_info normal_tr" id="'+v.id+'"></tr>');
		tbody.append( tr );

		tr.append( $('<td><a class="race_name" target="_blank" href="work_review.html?race_id="'+v.id+'">'+v.pn+'</a></td>') );
		
		var time_str = formatDate( parseFloat(v.t) );
		tr.append( $('<td><a class="race_time">' + time_str + '</a></td>') );	
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
						
					$.post( 'my-php/admin_ui/set_temp_field_apply.php', in_p, function(data) {
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