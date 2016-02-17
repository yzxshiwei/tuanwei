
user_info = new Array();
team_info = new Array();
race_info = new Array();
field_apply = new Array();
temp_field_apply = new Array();
news = new Array();

function append_team( tbody, team_data, d ) {
							
	$.each( team_data, function(i,v) {
		var tr = $('<tr class="team_info" id="'+v.t_id+'"></tr>');
		if( d==0 )
			tbody.append( tr );
		else
			tbody.children('tr:first').after( tr );
		
		tr.append( $('<td>'+v.t_id+'</td>') );
		tr.append( $('<td><a class="team_name">'+v.t_name+'</a></td>') );
		tr.append( $('<td><a class="team_chief">'+v.name+'</a></td>') );
		tr.append( $('<td><a class="team_found">'+formatDate(parseFloat(v.reg_t))+'</a></td>') );
		
		if( v.company )
			tr.append( $('<td>'+v.company+'</td>') );
		else
			tr.append( $('<td>未成立公司</td>') );
			
		tr.append( $('<td class="team_set"><input type="checkbox" class="team_valid"/>禁止</td>') );
		if( v.state=='ban' )
			tr.find( 'input[type=checkbox]' )[0].checked = true; 
	} );
	
	$('input[type=checkbox].team_valid').click( function() {
		var t = $(this).parents('tr');
		var tid = t.attr('id');
		
		var s = this.checked;
		if( s )
			s = 'ban';
		else
			s = 'disband';

		$.post( 'my-php/admin_ui/change_team_state.php', {t_id:tid,state:s} );
	} );
}

function append_race( tbody, race_data, d ) {
								
	$.each( race_data, function(i,v) {
		var tr = $('<tr class="race_info" id="'+v.race_id+'"></tr>');
		if( d==0 )
			tbody.append( tr );
		else
			tbody.children('tr:first').after( tr );
		
		tr.append( $('<td>'+v.race_id+'</td>') );
		tr.append( $('<td><a class="race_name" target="_blank" href="race_enroll.html?race_id='+v.race_id+'&race_name='+v.name+'">'+v.name+'</a></td>') );
		
		var race_time_str = formatDate( parseFloat(v.r_st) ) + '-' + formatDate( parseFloat(v.r_et) );
		var s_time_str = formatDate( parseFloat(v.s_st) ) + '-' + formatDate( parseFloat(v.s_et) );
		
		tr.append( $('<td><a class="race_time">' + race_time_str + '</a></td>') );
		tr.append( $('<td><a class="race_stime">' + s_time_str + '</a></td>') );		
		tr.append( $('<td class="race_set"><a target="_blank" href="edit_race.html?race_id='+v.race_id+'" class="race_edit">编辑</a></td>') );

	} );

}

/*
	field_info	.t_name 	- 团队名称
				.a_id 		- 事务id
				.leader		- 负责人名称
				.leader_info - 学院/专业/学号/email
				.link		- 附件链接
				.step		- 1 状态显示为 待处理
							- 2	状态显示为	待评审，此时应有 reviewer 阈
				.reviewer	- {"d":[{"p":"name_1","r":"kkkkk"},{}]}
*/

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
			$.post( 'my-php/admin_ui/set_field_apply.php', in_p, function(data) {
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
			$.post( 'my-php/admin_ui/set_field_apply.php', in_p, function(data) {
				if( data=='OK' ) {
					field_apply.splice( ind, 1 );
					tr.hide(400, function() {tr.remove();} );
				}
			} );
		} );
		
	} );
}

/*
	临时场地申请( step默认为1 )
	
	temp_field_info	.field		- 申请场地，字符串，校/楼/房间
					.f_id
					.apply		- 申请，数组，每个申请格式为
		
		apply	.id			- 申请id
				.p 			- 申请人,name
				.u_id		- 申请人， u_id
				.p_info		- 申请人信息，学院/专业/学号/email
				.state
				.st			- 申请占用开始时间，时间戳
				.et			- 申请占用结束时间，时间戳
				.reason		- 申请事由

	apply数组中如有多个元素，则这些元素为相互冲突的申请
*/

temp_field_apply[0] = new Object();
temp_field_apply[0].field = 'campus/build/room_1';

temp_field_apply[0].apply = new Array();
temp_field_apply[0].apply[0] = new Object();
temp_field_apply[0].apply[0].id = 'tf_1';
temp_field_apply[0].apply[0].p = 'p_1';
temp_field_apply[0].apply[0].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[0].apply[0].state = 'proing';
temp_field_apply[0].apply[0].st = 0;
temp_field_apply[0].apply[0].et = 3600;
temp_field_apply[0].apply[0].reason = 'wrut jkf wooir ruty ffiy qoo';

temp_field_apply[0].apply[1] = new Object();
temp_field_apply[0].apply[1].id = 'tf_2';
temp_field_apply[0].apply[1].p = 'p_2';
temp_field_apply[0].apply[1].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[0].apply[1].state = 'proing';
temp_field_apply[0].apply[1].st = 0;
temp_field_apply[0].apply[1].et = 3600;
temp_field_apply[0].apply[1].reason = 'wrut jkf wooir ruty ffiy qoo';

temp_field_apply[0].apply[2] = new Object();
temp_field_apply[0].apply[2].id = 'tf_3';
temp_field_apply[0].apply[2].p = 'p_3';
temp_field_apply[0].apply[2].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[0].apply[2].state = 'proing';
temp_field_apply[0].apply[2].st = 0;
temp_field_apply[0].apply[2].et = 3600;
temp_field_apply[0].apply[2].reason = 'wrut jkf wooir ruty ffiy qoo';

temp_field_apply[1] = new Object();
temp_field_apply[1].field = 'campus/build/room_2';

temp_field_apply[1].apply = new Array();
temp_field_apply[1].apply[0] = new Object();
temp_field_apply[1].apply[0].id = 'tf_4';
temp_field_apply[1].apply[0].p = 'p_1';
temp_field_apply[1].apply[0].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[1].apply[0].state = 'proing';
temp_field_apply[1].apply[0].st = 0;
temp_field_apply[1].apply[0].et = 3600;

temp_field_apply[2] = new Object();
temp_field_apply[2].field = 'campus/build/room_3';

temp_field_apply[2].apply = new Array();
temp_field_apply[2].apply[0] = new Object();
temp_field_apply[2].apply[0].id = 'tf_5';
temp_field_apply[2].apply[0].p = 'p_9';
temp_field_apply[2].apply[0].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[2].apply[0].state = 'proing';
temp_field_apply[2].apply[0].st = 0;
temp_field_apply[2].apply[0].et = 3600;
temp_field_apply[2].apply[0].reason = 'wrut jkf wooir ruty ffiy qoo';

temp_field_apply[2].apply[1] = new Object();
temp_field_apply[2].apply[1].id = 'tf_6';
temp_field_apply[2].apply[1].p = 'p_20';
temp_field_apply[2].apply[1].p_info = 'collage/speciality/s0001/free-bug@163.com';
temp_field_apply[2].apply[1].state = 'proing';
temp_field_apply[2].apply[1].st = 0;
temp_field_apply[2].apply[1].et = 3600;
temp_field_apply[2].apply[1].reason = 'wrut jkf wooir ruty ffiy qoo';



/*
	新闻管理( step默认为1 )
	
		news	.id		
				.title
				.col
				.t			- 时间戳
*/

function list_news() {
	var tbody = $('section.tab-item-8 table tbody');

	$.each( news, function(i,v) {
		var tr = $('<tr class="team_info normal_tr" id="'+v.id+'"></tr>');	
		tbody.append( tr );
		
		tr.append( $('<td><p class="text_left">'+v.id+'</p></td>') );
		tr.append( $('<td><p class="text_left"><a class="a_news" target="_blank" href="news_review.html?id='+v.id+'">'+v.title+'</a></p></td>') );
		tr.append( $('<td><p class="text_left">'+v.col+'</p></td>') );
		
		tr.append( $('<td><p class="time text_left">修改日期:</br>'+formatDate_2(parseFloat(v.t))+'</p></td>') );
		tr.append( $('<td><p class="state text_left">未提交</p></td>') );
		tr.append( $('<td class="op team_set"><a class="pass race_edit" target="_blank" href="news_review.html?id='+v.id+'">编辑</a></td>') );

	} );
	
};

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