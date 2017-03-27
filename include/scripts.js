var emnet = {}

emnet.search = {

	init: function() {
		$('.close-search-results').on('click', function() {
			emnet.search.closeResults();
		});

		$('#open-search').on('click', function() {
			if (!$('#cse-search-form.display-search').length) {
				emnet.search.open();
			} else {
				emnet.search.close();
				emnet.search.closeResults();
			}
		});

		$('#cse-search-form-inner').on('submit', function (e) {
			e.preventDefault();
			var query = $('#gsc-search-input').val();
			$.get('/searchservice.php?q=' + encodeURIComponent(query), function(markup) {
				$('#cse').html(markup);
				emnet.search.showResults();
			});
		})
	},

	open: function() {

		$('#cse-search-form').addClass('display-search');
		$('#menucontainer').addClass('display-search');
		$('#gsc-search-input')[0].focus();
	},

	close: function() {
		$('#cse-search-form').removeClass('display-search');
		$('#menucontainer').removeClass('display-search');

		if ($('#search-results:visible').length > 0) {
			$('#search-results').fadeOut({queue:false}).hide("slide", { direction: "up" }, function() {
				$('#header').fadeIn('fast');
			});
		}
	},

	showResults: function() {
		if ($('#search-results:visible').length == 0) {
			$("html, body").animate({ scrollTop: 0 }, "fast");
			$('.gsc-input input').blur();
			$('#header').fadeOut('fast', function() {
				$('#search-results').fadeIn({queue:false}).show("slide", { direction: "up" });
			});
		}
	},

	closeResults: function() {
		emnet.search.close();
	}
}

$(function() {
	// ---- jscolor settings
	if (jscolor) {
		jscolor.dir = '/include/jscolor/';
	}
	// ---- end jscolor settings

	$('#menu-toggle').on('click', function() {
		$(this).toggleClass('open');
		$('#menucontainer').toggleClass('display-menu');
		$('#search-results').toggleClass('display-menu');
	});

	$.get('/flickr.php?type=recent', function(data) {
		var photos = JSON.parse(data);
		var html = '';
		$.each(photos, function(i, photo) {
			html += '<a href="' + photo.url + '" target="_blank"><img onload="javascript:$(this).fadeIn(\'slow\');" src="' + photo.image + '" alt="recent" /></a>';
		});
		html += '<div class="clearfix"></div>';
		$('#flickr-recent').html(html);
	});

	var lastPos = $(document).scrollTop();
	var minScroll = 250;
	var tolerance = 50;
	$(document).on('scroll', function() {
		var newPos = $(document).scrollTop();
		if (Math.abs(newPos - lastPos) > (!$('#menucontainer').hasClass('scrolled') ? 0 : tolerance)) {
			$('#menucontainer').toggleClass('scrolled', newPos > lastPos && newPos > minScroll);
			lastPos = newPos;
		}
	});
});

emnet.search.init();

// load quote
$.get('/quoteservice.php', function(markup) {
	$('#bottom').html(markup);
});

$(document).on('mouseover', '#flickr-recent img, .maingalleryitem, .gallery-thumbnail img', function() {
	$(this).stop().fadeTo('fast', 0.6);
});
$(document).on('mouseout', '#flickr-recent img, .maingalleryitem, .gallery-thumbnail img', function() {
	$(this).stop().fadeTo('fast', 1);
});

if ($('.gallery-thumbnail').length) {
	new RespGallery();
}

function getOffsetForMenuItem(item) {
	return $(item).offset().left - $('#activeItemMarkerContainer').offset().left + $(item).width()/2 - $('#activeItemMarker').width()/2;
}

function showPic (imagefile, thumbimagefile, description)
{
 if (document.getElementById)
 {
  document.getElementById('placeholder').src = thumbimagefile;
  document.getElementById('desc').childNodes[0].nodeValue = description;
  document.getElementById('lnkplaceholder').href = imagefile;
  return false;
 }
 else
 {
  return true;
 }
}

function postContact(txtName, txtEmail, txtTesttext, txtMessage)
{
	var name = document.getElementById(txtName).value;
	var email = document.getElementById(txtEmail).value;
	var message = document.getElementById(txtMessage).value;
	var testtext = document.getElementById(txtTesttext).value;

	if(name == '' || testtext != 'emalj' || message == '' || email == '')
	{
		//Show error message
		alert('Please enter name, email and a message.')
		return false;
	}
	else
	{
		if(!isEmailValid(email))
		{
			alert('Enter a valid email address.');
			return false;
		}
		return true;
	}
}

function validateCommentForm(formData) {

	var errors = [];

	if (!formData.name) {
		errors.push({field: 'txtName', message: 'Please enter a name.' });
	}

	if (!formData.message) {
		errors.push({field: 'txtMessage', message: 'Please enter a message.'});
	}

	if (formData.website && !isValidUrl(formData.website)) {
		errors.push({field: 'txtWebsite', message: 'The posted web site url is invalid.'});
	}

	if (errors.length) {
		errors.push({field: 'btnSend', message: 'The form contains errors; see above.'});
	}

	return errors;
}

function isEmailValid(email)
{
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return filter.test(email)
}

function isValidUrl(website)
{
	if (website.indexOf('.') < 0 || website.indexOf('@') >= 0) {
		return false;
	}

	return true;
}

function padLeft(str, max, padChar) {
	return str.length < max ? padLeft(padChar + str, max) : str;
}

function checkMaxLength(textBox, e, maxLength) {
	if (textBox.value.length > maxLength - 1 && e.keyCode !=8 && e.keyCode!=46 && e.keyCode!=37 && e.keyCode!=38 && e.keyCode!=39 && e.keyCode!=40) {
		return false;
    }
}
