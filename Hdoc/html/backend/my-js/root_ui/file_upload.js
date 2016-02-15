
function show_image( file_list, show_area_selecter ) {

	if( file_list.length>0 ) {
				
		main_banner = file_list[0];
		
		var reader = new FileReader();
		reader.onload = function() { 
			var show_area = $(show_area_selecter);
			var image = $(show_area_selecter).children('img');
			image.bind( 'load', function() {
				var r1 = show_area.height() / show_area.width();
				var r2 = image.height() / image.width();
				
				if( r2<r1 ) {
					image.css('width','100%');
					image.css('height','auto');
					
					var m_top = ( show_area.height()-image.height() ) / 2;
					image.css('margin-top',m_top);
				}
				else {
					image.css('height','100%');
					image.css('width','auto');
					image.css('margin','auto');
				}
				
			} );
			image.attr('src', this.result);
		};	
		reader.readAsDataURL( main_banner );		
	}
}