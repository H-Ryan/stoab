
// Nav active
$(function () {
	setNavigation();
});

function setNavigation() {
	var path = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
	$("#mainNav a").each(function () {
		var href = $(this).attr('href');
			href = href.substring(0,href.indexOf('.'));	
		if (path.substring(0, href.length) === href) {
			
			$(this).closest('li').addClass('active');					
		}
		else{
			$(this).closest('li').removeClass('active');
		}
	});
}
// upload file

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $("#uploadInput"),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });
	// hide order translation form result
	setTimeout(function() { $("#testdiv").fadeOut(1500); }, 5000);

});
// scroll to section

/*$(function() {
		  $('a[href*=#]:not([href=#])').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			  var target = $(this.hash);
			  var headerHeight = $('header').outerHeight();
			  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			  if (target.length) {
				$('html,body').animate({
				  scrollTop: target.offset().top -headerHeight/2
				}, 1000);
				return false;
			  }
			}
		  });
		});*/

// Form wizared
/*$('.modal').on('hidden.bs.modal', function(){
	/*$("form").validate().resetForm();
    $(this).find('form')[0].reset();
	$(".modal-body a").each(function () {
		
			$(this).closest('li').removeClass('active');
		
	});
	$(".tab-content div").each(function () {
		
			$(this).removeClass('active');
		
	});
});*/
