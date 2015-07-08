function RespGallery(options) {

	var self = this;
	
	self.orgX;
	self.currX;
	self.isMoving = false;
	self.imageIndex = 0;
	self.isWaitingForAnimationFrame = false;
	self.maxIndex = 0;
	self.transformProperty;
	
	// CSS Ids/classes
	self.imagesContainerId = 'imagesContainer';
	self.imagesId = 'images'
	self.imageContainerBackId = 'imageContainer-back';
	self.imageCaptionId = 'image-caption';
	self.imageCaptionNumberId = 'image-caption-number';
	self.imageCaptionTextId = 'image-caption-text';
	self.touchId = 'touch';
	self.closeImagesId = 'close-images';
	self.scrollLeftContainerId = 'scroll-left-container';
	self.scrollLeftId = 'scroll-left';
	self.scrollRightContainerId = 'scroll-right-container';
	self.scrollRightId = 'scroll-right';
	self.imageClass = 'gallery-image';
	self.overlayClass = 'overlay';
	self.galleryThumbnailClass = 'gallery-thumbnail';
	
	// elements
	self.imagesContainer;
	
	self.init = function () {
	
		self.generateMarkup();
	
		self.transformProperty = self.getTransformProperty();
		self.maxIndex = $('.' + self.imageClass).length-1;
		self.imagesContainer = $('#' + self.imagesContainerId);
		
		$('#' + self.touchId).on('touchstart mousedown',function(e) {
			self.isMoving = true;
		});

		$('#' + self.scrollLeftId).on('click', function() {
		  self.scrollImages(-1);
		});

		$('#' + self.scrollRightId).on('click', function() {
		  self.scrollImages(1);
		});

		$('#' + self.imageContainerBackId).on('click', function() {
			self.hideGallery();
		});

		$('#' + self.closeImagesId).on('click', function() {
			self.hideGallery();
		});
		
		$('#' + self.touchId).on('touchend mouseup touchcancel',function(e) {
			self.handleTouchEnd();
		});

		$('#' + self.touchId).on('touchmove mousemove',function(e) {
			self.handleTouchMove(e);
		});

		$('#' + self.touchId).on('touchmove touchstart touchend',function(e){e.preventDefault()});
		
		$('.' + self.galleryThumbnailClass).on('click', function(e, element) {
			e.preventDefault();
			var index = $('.' + self.galleryThumbnailClass).index($(e.delegateTarget));
			self.showGallery(index);
		});
		
		$('#' + self.imagesId + ' img').load(function() {
			// run when each image is loaded
			self.setImageHeight();
		});

		$(window).load(function() {
		  // run when all images are loaded
		  self.setImageHeight();
		});
		
		$(window).resize(function() {
			self.setImageHeight();
			self.scrollImages(0, true);
		});
		
		$(document).keydown(function(event) {
			switch(event.which) {
				case 27: // esc
					self.hideGallery();
				break;
				case 37: // left
					self.scrollImages(-1);
				break;
				case 39: // right
					self.scrollImages(1);
				break;
			}
		});
		
		$(window).on('hashchange', function() {
			if (location.hash == '') {
				self.hideGallery();
			}
		});
	};

	self.generateMarkup = function () {
	
		var html = '';
		
		html += '<div id="' + self.imagesContainerId + '">\r\n';
		//html += '  <a href="javascript:void(0);" id="' + self.imageContainerBackId + '"></a>\r\n';
		html += '  <div id="' + self.imagesId + '">\r\n';
		
		$('.' + self.galleryThumbnailClass).each(function(i, el) {
			var link = $(el);
			var image = link.find('img');
			var url = link.attr('href');
			var alt = image.attr('alt');
			html += '    <div class="' + self.imageClass + '">\r\n';
			html += '      <img src="' + url + '" alt="' + alt + '" />\r\n';
			html += '    </div>\r\n';
		})
		
		html += '  </div>\r\n';
		html += '  <div id="' + self.imageCaptionId + '" class="' + self.overlayClass + '">\r\n';
		html += '    <span id="' + self.imageCaptionNumberId + '"></span>\r\n';
		html += '    <span id="' + self.imageCaptionTextId + '"></span>\r\n';
		html += '  </div>\r\n';
		html += '  <div id="' + self.touchId + '"></div>\r\n';
		html += '  <div id="' + self.closeImagesId + '" class="' + self.overlayClass + '"></div>\r\n';
		html += '  <div id="' + self.scrollLeftContainerId + '" class="' + self.overlayClass + '"><div id="' + self.scrollLeftId + '"></div></div>\r\n';
		html += '  <div id="' + self.scrollRightContainerId + '" class="' + self.overlayClass + '"><div id="' + self.scrollRightId + '"></div></div>\r\n';
		html += '</div>\r\n';
		$(document.body).append(html);
	};
	
	self.showGallery = function(index) {

		location.hash = 'gallery';
	
		$('body').addClass('gallery');
		
		self.imagesContainer.fadeTo(0.1);
		self.imagesContainer.show();
		
		self.imageIndex = index || 0;
		self.scrollImages(0, true);
		self.setImageHeight();
		
		var delay = 300;
		$($('.gallery-thumbnail').get(index)).effect( "transfer", { to: $($('#' + self.imagesId + ' img')[index]) }, delay );
		
		setTimeout(function() {
			self.imagesContainer.fadeTo(delay/2, 1);
		}, delay/2);
	}
	
	self.getX = function(e) {
		var event = e.originalEvent;
		var pointer = event.targetTouches ? event.targetTouches[0] : event;
		return pointer.pageX;
	}

	self.handleTouchMove = function (e) {
	
		if (self.isWaitingForAnimationFrame) {
			return;
		}
		
		if(!self.isMoving) {
			return;
		}

		self.isWaitingForAnimationFrame = true;

		if (!self.orgX) {
			self.orgX = self.getX(e);
		}
		
		self.currX = self.getX(e);
			
		var pos = Math.max(self.imageIndex * $(window).width(), 0) + (self.orgX - self.currX);
		
		requestAnimationFrame(function() {
			self.isWaitingForAnimationFrame = false;
			if (!self.isMoving) {
				return;
			}

			if (!self.transformProperty) {
				self.imagesContainer.css({"left": '-' + (pos) + "px"});
			} else {
				// css transform
				
				//$("#images").css("-webkit-transform", "translate3d(-" + pos + "px, 0px, 0px)");
				//$('#' + self.imagesId).css(self.transformProperty, "translate(-" + pos + "px, 0px)");
				
				$('#' + self.imagesId).css("-webkit-transform", "translate3d(-" + pos + "px, 0px, 0px)");
				$('#' + self.imagesId).css("-moz-transform", "translate3d(-" + pos + "px, 0px, 0px)");
			}
		});
	};
	
	self.handleTouchEnd = function() {
		self.isMoving = false;
		var diff = self.orgX - self.currX;
		if (Math.abs(diff) < 20) {
			$('.' + self.overlayClass).fadeToggle('fast');
			self.scrollImages(0, false);
		} else {
			self.scrollImages(diff >= 0 ? 1 : -1, false);
		}
	};
	
	self.scrollImages = function(direction, instant) {
		self.orgX = null;
		self.currX = null;
		self.isMoving = false;
		if ((self.imageIndex === 0 && direction < 0) || (self.imageIndex >= self.maxIndex && direction > 0)) {
			return;
		}
  
		self.imageIndex += direction;
		var pos = Math.max(self.imageIndex * $(window).width(), 0);
		var transformProperty = self.transformProperty;
		var delay = 0;
		if (!instant) {
			delay = 300;
			$('#' + self.imagesId).addClass('ease');
		}
		
		if (!transformProperty) {
			self.imagesContainer.animate({"left": '-' + (pos) + "px"}, delay);
		} else {
			$('#' + self.imagesId).css(transformProperty, "translate3d(-" + pos + "px, 0px, 0px)");
		}

		setTimeout(function() {
				$('#' + self.imagesId).removeClass('ease');
			}, 300);

		$('#' + self.imageCaptionNumberId).text((self.imageIndex+1) + ' / ' + (self.maxIndex+1));
		$('#' + self.imageCaptionTextId).text($($('#' + self.imagesId + ' img')[self.imageIndex]).attr('alt') || '');
		self.adjustTouchArea();
	};

	self.setImageHeight = function() {
		$('.' + self.imageClass).height($(window).height());
		$('.' + self.imageClass).width($(window).width());
		$('.' + self.imageClass).each(function(i) {
			$(this).css({left: $(window).width()*i + 'px'});
			$(this).css({top: ($(window).height()/2 - $(this).find('img').height()/2) + 'px'});
		});
		self.maxPos = ($('.' + self.imageClass).length-1) * $(window).width();
		self.imagesContainer.width(($('.' + self.imageClass).length) * $(window).width());
		self.adjustTouchArea();
	}

	self.adjustTouchArea = function() {
	  $('#' + self.touchId).height($($('.' + self.imageClass + ' img')[self.imageIndex]).height());
	  $('#' + self.touchId).width($($('.' + self.imageClass + ' img')[self.imageIndex]).width());
	  $('#' + self.touchId).css({ 
		left: $(window).width()/2 - $('#' + self.touchId).width()/2,
		top: $(window).height()/2 - $('#' + self.touchId).height()/2
	  });
	}

	self.hideGallery = function() {
		if (!$('body').hasClass('gallery')) {
			return;
		}
		
		$('body').removeClass('gallery');
		self.imagesContainer.fadeOut(200, function() {
			$('#' + self.imagesId).css(self.transformProperty, '');
		});
	}
	
	self.getTransformProperty = function() {
	
		var el = document.createElement('p'), 
				 t, 
				 has3d,
				 supportProperty,
				 transforms = {
					'WebkitTransform':'-webkit-transform',
					'OTransform':'-o-transform',
					'MSTransform':'-ms-transform',
					'MozTransform':'transform',
					'Transform':'transform'
				};

		/* Add it to the body to get the computed style.*/
		document.body.insertBefore(el, document.body.lastChild);

		for(t in transforms){
			if( el.style[t] !== undefined ){
				el.style[ transforms[t] ] = 'matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)';
				has3d = window.getComputedStyle(el).getPropertyValue( transforms[t] );
				supportProperty = t;
			}
		}

		if( has3d !== undefined && has3d !== 'none'){
			return supportProperty;
		}
		
		return null;
	}
	
	self.init();
}