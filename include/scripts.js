$(function() {
	// ---- jscolor settings
	if (jscolor) {
		jscolor.dir = '/include/jscolor/';
	}
	// ---- end jscolor settings
});

$('.close-search-results').on('click', function() {
	$('#search-results').fadeOut({queue:false}).hide("slide", { direction: "up" });
});

// animate menu marker
var setMarkerToActiveItem = function (){
	var activeItem = $('#horizontal li.active a');
	if(activeItem.length == 0) {
		$('#activeItemMarker').hide();
	}
	else {
		$('#activeItemMarker').stop().animate({'margin-left': getOffsetForMenuItem(activeItem.get(0)) + 'px'}, 200);
	}
}

$('#horizontal li a').hover(function() {
	$('#activeItemMarker').stop().show().animate({'margin-left': getOffsetForMenuItem(this) + 'px'}, 200);
	$(this).addClass('hover',10);
}, function(){
	setMarkerToActiveItem();
	$(this).removeClass('hover',500);
})

setMarkerToActiveItem();

// load quote
var rssurl = 'http://quotes4all.net/rss/560210110/quotes.xml';
$.getJSON('http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=20&callback=?&q=' + encodeURIComponent(rssurl), function(data) {
	var entryIndex = Math.floor(Math.random()*100%data.responseData.feed.entries.length);
	var entry = data.responseData.feed.entries[entryIndex];
	$('#bottom').html('<div id="quotecontainer"><p>' + entry.content + '</p><p>' + entry.title + '</p></div><div class="clearfix"></div>');
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
