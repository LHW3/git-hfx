jQuery(function($){
	//QR Code Link // Depends on Pretty Photo
	$('#link-qrcode').click(function(e){
		e.preventDefault();
		$.fn.prettyPhoto({show_title: false, social_tools: false});
		$.prettyPhoto.open('http://chart.apis.google.com/chart?chs=250x250&cht=qr&chld=L|4&choe=UTF-8&chl=' + encodeURI(document.URL));
	});
});