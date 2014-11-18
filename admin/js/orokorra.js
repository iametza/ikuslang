$(document).ready(function() {

    $('.btn').tooltip( { placement: 'top' } );
    
    $(".datepicker").datepicker ({ dateFormat: 'yy-mm-dd', firstDay: 1 });
    $(".datetimepicker").datetimepicker ({
					dateFormat: 'yy-mm-dd',
					firstDay: 1,
					timeFormat: "hh:mm:ss"
				     });
    
    $('.td_klik').hover(
		function () {
			$(this).css('cursor', 'pointer');
			//$(this).parent().addClass("gainetik");
		},
		function () {
			//$(this).parent().removeClass("gainetik");
		}
	);
	
	$('.td_klik').click(function (){
		var aukerak = $(this).parent().find('.td_aukerak');
		if (typeof aukerak == 'object'){
			var aldatu = aukerak.find('a[data-original-title="aldatu"]');
			
			if (typeof aldatu == 'object' && aldatu.attr('href') != undefined)
				document.location = aldatu.attr('href');
		}
	});
	
	
		// pestainak urlko anchor hartu eta dagokiona ireki
		var gotoHashTab = function (customHash) {
		var hash = customHash || location.hash;
		console.log(hash);
		var hashPieces = hash.split('?'),
			activeTab = $('[href=' + hashPieces[0] + ']');
		activeTab && activeTab.tab('show');
		}
		
		// onready go to the tab requested in the page hash
		gotoHashTab();
	
    
});
