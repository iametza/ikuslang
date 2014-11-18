var options = { path: '/', expires: 10 };

$(document).ready(function() {
	
	// Menuak desplegatzeko (cookie bidez egoera mantendu)
	$('.menua').each (function (elem){
		var submenu = $(this).parent().find('div').attr ('id');
		
		//if ($.cookie(submenu) == 'itxita' || $.cookie(submenu) == null){
			//$('#' + submenu).collapse( { hide: true } );
		if ($.cookie(submenu) != null && $.cookie(submenu) == 'irekita'){
			$('#' + submenu).collapse( { show: true } );
			
			$(this).parent().find('i').attr ('class', 'icon-chevron-up');
		}
	});
	
	$('.menua').on('hide', function () {
		$(this).parent().find('i').attr ('class', 'icon-chevron-down');
		
		var submenu = $(this).parent().find('div').attr ('id');
		$.cookie(submenu, 'itxita', options);
	});
	
	$('.menua').on('show', function () {
		$(this).parent().find('i').attr ('class', 'icon-chevron-up');
		
		var submenu = $(this).parent().find('div').attr ('id');
		$.cookie(submenu, 'irekita', options);
	});
	
});
