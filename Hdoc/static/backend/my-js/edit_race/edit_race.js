var main_banner;

function init( config ) {
	$('#input-race-name').val( config['name'] );
	
	$('#input-race-time1').val( config['r_st'] );
	$('#input-race-time2').val( config['r_et'] );
	
	$('#input-reg-time1').val( config['s_st'] );
	$('#input-reg-time2').val( config['s_et'] );
	
	$('#file_upload_area img').bind( 'load', function() {
		var r1 = $('#file_upload_area').height() / $('#file_upload_area').width();
		var img = $(this);
		var r2 = img.height() / img.width();
		
		if( r2<r1 ) {
			img.css('width','100%');
			img.css('height','auto');
			
			var m_top = ( $('#file_upload_area').height()-img.height() ) / 2;
			img.css('margin-top',m_top);
		}
		else {
			img.css('height','100%');
			img.css('width','auto');
			img.css('margin','auto');
		}
		
	} );			
	$('#file_upload_area img').attr( 'src', config['banner_path'] );
	
	var mid = $.parseJSON( config['class'] )['class'];
	$.each( mid, function(i,v) {
		if( i>0 )
			$('#race_add_group').trigger( 'click' );
		$('.race_group_div input').eq(i).val( v );
	} );
	
	$.post( 'my-php/root_ui/get_expert.php', function(data) {
		if( data ) {
			expert_info = $.parseJSON( data );
			mid = $.parseJSON( config['review'] )['review'];
			
			$.each( mid, function(i,v) {
				if( i>0 )
					$('#race_expert_num_add').trigger( 'click' );
					
				var ns = get_expert_name( v );
				var t = $('#race_expert_set_div input').eq(i);		
				t.val( ns.join(',') );
				t.prop( 'uid', v.join(',') );
			} );
			
			list_expert( expert_info );
		}
	} );
	
	$('#race_url').val( window.location.host+'/race/'+r_id+'/config/race_page.html' );
	
	UE.getEditor('editor').ready( function() {
		UE.getEditor('editor').setContent( config['rule'] );
	} );
	
};

// uids - [uid1,uid2....]
function get_expert_name( uids ) {

	var ns = new Array();
	
	$.each( uids, function(i,v) {
		$.each( expert_info, function(i,v2) {
			if( v2.u_id==v ) {
				ns.push( v2.name );
				return true;
			}
		} );
	} );
	
	return ns;
};

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
		
$( function() {
	
	$('#file_upload_area').bind( {
		drop: function(e) {
		
			e.preventDefault();
			var oe = e.originalEvent;
			var file_list = oe.dataTransfer.files;
			main_banner = file_list[0];
			show_image( file_list, '#file_upload_area' );
		},
		dragover: function(e) {
			e.preventDefault();
			return true;
		}
	} );

	$('.file_upload_plugin a.upload').click( function() {
		
		if( typeof(main_banner)!='undefined' ) {
		
			var banner = new Object();
			var reader = new FileReader();
			
			reader.onload = function() {
				banner['banner'] = $.base64( 'encode', this.result );
				banner['banner_name'] = main_banner.name;
				banner['race_id'] = race_id;
				$.post( 'my-php/root_ui/add_race.php', banner, function() { alert('banner 上传成功'); } );	
			};	
			
			reader.readAsBinaryString( main_banner );
		}
	} );
	
	$('.file_upload_plugin a.browse').click( function(e) {
		$('#f_u').trigger('click');
	} );
	
	$('#f_u').change( function(e) {	
		main_banner = this.files[0];
		show_image( this.files, '#file_upload_area');
	} );
		
	$('#banner_close').click( function() {
		var target = $(this);
		target.prev('img').attr('src','');
		target.prev('img').css( {'width':0,'height':0} );
		main_banner = '';
	} );

	$('#race_add_group').click( function() {
		var target = $('.race_group_div');
		var num = target.children('input[type=text]').size();
		target.append( $("<input type='text' class='race_group' placeholder='分组"+(num+1)+"' required='required' />") );
		var del = $( "<a class='fa fa-minus race-group-del'></a>" );
		target.append( del );
		
		del.click( function() {
			var target = $( this );
			target.prev('input[type=text]').remove();
			target.remove();			
		} );
	} );

	$('#race_expert_num_add').click( function() {

		var target = $('#race_expert_set_div');
	
		var index = $('input.race_expert').length;
		
		target.append( $("<input id='"+index+"_expert_class' type='text' class='race_expert' placeholder='指定评审专家' />") );
		var user_add = $( "<a num='"+index+"' style='margin-left:18px' class='fa fa-user-plus'></a>" );
		
		target.append( user_add );
		var minus = $( "<a style='margin-left:18px' class='fa fa-minus'></a>" );
		target.append( minus );
		
		var inputs = target.children('input[type=text]');
		$('#race_expert_num').html( inputs.size() );
		
		user_add.click( function(e) {
			var div = $('#expert_choose');
			div.css( 'top', e.pageY-100 );
			div.toggleClass( 'hide' );
			
			div.attr( 'num', $(this).attr('num') );
		} );
		
		minus.click( function() {
			var target = $( this );
			target.prev('a.fa-user-plus' ).remove();
			target.prev('input[type=text]').remove();
			target.remove();
			
			var inputs = $('#race_expert_set_div').children('input[type=text]');
			$('#race_expert_num').html( inputs.size() );
		} );
	} );
	
	$('#expert_choose').draggable();			
	$('#expert_add').click( function(e) {
		var div = $('#expert_choose');
		div.css( 'top', e.pageY-100 );
		div.toggleClass( 'hide' );
		
		div.attr( 'num', $(this).attr('num') );
	} );
			
	// 上传新建的比赛信息
	$('#b_ok').click( function() {
		var data = new Object();
	
		data['race_id'] = r_id;
		data['name'] = $('#input-race-name').val();
		data['r_st'] = $('#input-race-time1').val();
		data['r_et'] = $('#input-race-time2').val();
		
		data['s_st'] = $('#input-reg-time1').val();
		data['s_et'] = $('#input-reg-time2').val();

		var rg = $('input.race_group');
		data['class'] = '[';
		$.each( rg, function(i, v) {
			data['class'] += '"' + $(v).val() + '",';
		} );
		data['class'] = data['class'].substring( 0, data['class'].length-1) + ']';
		
		var re = $('input.race_expert');
		data['review'] = '[';
		$.each( re, function(i, v) {
			var mstr = $(v).prop('uid').split( ',' );
			data['review'] += '[';
			$.each( mstr, function(i2,v2) {
				data['review'] += '"' + v2 + '",';
			} );
			data['review'] = data['review'].substring( 0, data['review'].length-1) + '],';
		} );
		data['review'] = data['review'].substring( 0, data['review'].length-1)  + ']';
			
		// 先上传比赛基本信息
		$.post( 'my-php/edit_race/edit_race.php', data, function(res) {		
			// 再单独上传 banner
			var banner = new Object();
			if( typeof(main_banner)!='undefined' ) {
				var reader = new FileReader();
				reader.onload = function() {
					banner['banner'] = $.base64( 'encode', this.result );
					banner['banner_name'] = main_banner.name;
					banner['race_id'] = data['race_id'];
					$.post( 'my-php/edit_race/edit_race.php', banner );	
				};	
				reader.readAsBinaryString( main_banner );
			}
			
			// 单独上传rule
			var rule = new Object();
			rule['rule'] = UE.getEditor('editor').getContent();
			rule['race_id'] = data['race_id'];
			$.post( 'my-php/edit_race/edit_race.php', rule );
			
			// 单独上传比赛用表格数据
			var chart_f = $('input[name=upload_template]')[0].files[0];
			if( chart_f ) {
				var reader = new FileReader();
				reader.onload = function() {
					var chart = new Object();
					chart['race_id'] = data['race_id'];
					chart['chart'] = $.base64( 'encode', this.result );
					chart['chart_name'] = chart_f.name;
					$.post( 'my-php/edit_race/edit_race.php', chart );	
				};
				reader.readAsBinaryString( chart_f );
			}
			
			// 单独上传比赛展示界面
			var temp_f = $('input[name=upload_template]')[0].files[0];
			if( temp_f ) {
			var reader = new FileReader();
				reader.onload = function() {
					var temp = new Object();
					temp['race_id'] = data['race_id'];
					temp['template'] = $.base64( 'encode', this.result );
					$.post( 'my-php/edit_race/edit_race.php', temp );	
				};
				reader.readAsBinaryString( temp_f );
			}
			
			$('#edit_race_res').html( '<span style="color:blue"><b>修改成功！</b></span>' );
			setTimeout( function(){ $('#edit_race_res').html('');}, 3000 );
		} );	
	} );
	
} );