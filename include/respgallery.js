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
	
        function createDelegatedEventListener(selector, handler) {
            return function (event) {
                if (event.target.matches(selector) || event.target.closest(selector)) {
                    handler(event);
                }
            }
        }

		self.generateMarkup();
	
		self.transformProperty = self.getTransformProperty();
		self.maxIndex = document.querySelectorAll('.' + self.imageClass).length - 1;
		self.imagesContainer = document.querySelector('#' + self.imagesContainerId);
		
        for (let eventName of ['touchstart', 'mousedown']) {
		    document.querySelector('#' + self.touchId).addEventListener(eventName, function(e) {
			    self.isMoving = true;
		    });
        }

		document.querySelector('#' + self.scrollLeftId).addEventListener('click', function() {
		  self.scrollImages(-1);
		});

		document.querySelector('#' + self.scrollRightId).addEventListener('click', function() {
		  self.scrollImages(1);
		});

		document.querySelector('body').addEventListener('click', createDelegatedEventListener('#' + self.imageContainerBackId, function() {
			self.hideGallery();
		}));

		document.querySelector('body').addEventListener('click', createDelegatedEventListener('#' + self.closeImagesId, function() {
			self.hideGallery();
		}));
		
        for (let eventName of ['touchend', 'mouseup', 'touchcancel']) {
		    document.querySelector('#' + self.touchId).addEventListener(eventName, function(e) {
			    self.handleTouchEnd();
		    });
        }

        for (let eventName of ['touchmove', 'mousemove']) {
            document.querySelector('#' + self.touchId).addEventListener(eventName, function(e) {
                self.handleTouchMove(e);
            });
        }

        for (let eventName of ['touchmove', 'touchstart', 'touchend']) {
            document.querySelector('#' + self.touchId).addEventListener(eventName, function(e) {
                e.preventDefault()
            });
        }
		
        let imageIndex = 0;
		for (let element of document.querySelectorAll('.' + self.galleryThumbnailClass)) {
            let currentIndex = imageIndex++;
            element.addEventListener('click', function(e, element) {
                e.preventDefault();
                self.showGallery(currentIndex);
		    });
        }
		
		document.querySelector('#' + self.imagesId + ' img').addEventListener('load', function() {
			// run when each image is loaded
			self.setImageHeight();
		});

		window.addEventListener('load', function() {
		  // run when all images are loaded
		  self.setImageHeight();
		});
		
		window.addEventListener('resize', function() {
			self.setImageHeight();
			self.scrollImages(0, true);
		});
		
		document.addEventListener('keydown', function(event) {
			switch (event.key) {
				case "Escape":
					self.hideGallery();
				break;
				case "ArrowLeft":
					self.scrollImages(-1);
				break;
				case "ArrowRight":
					self.scrollImages(1);
				break;
			}
		});
		
		window.addEventListener('hashchange', function() {
			if (location.hash == '') {
				self.hideGallery();
			}
		});
	};

    self.fadeOut = function(element, callback) {
        element.animate({
            opacity: 0
          }, {
            duration: 200,
            easing: "linear",
            iterations: 1,
            fill: "both"
          })
          .onfinish = function() {
            element.style.display = "none";
            if (callback) {
                callback();
            }
        }
    }

    self.fadeIn = function(element, callback) {
        element.style.opacity = 0;
        element.style.display = "block";
        element.animate({
            opacity: 1
          }, {
            duration: 200,
            easing: "linear",
            iterations: 1,
            fill: "both"
          })
          .onfinish = function() {
            if (callback) {
                callback();
            }
        }
    }

	self.generateMarkup = function () {
	
		var html = '';
		html += '<div id="' + self.imagesContainerId + '">\r\n';
		html += '  <div id="' + self.imagesId + '">\r\n';
		
		for (let link of document.querySelectorAll('.' + self.galleryThumbnailClass)) {
			var image = link.querySelector('img');
			var url = link.getAttribute('href');
			var alt = image.getAttribute('alt');
			html += '    <div class="' + self.imageClass + '">\r\n';
			html += '      <img src="' + url + '" alt="' + alt + '" />\r\n';
			html += '    </div>\r\n';
		};
		
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
        let div = document.createElement("div");
        div.innerHTML = html;
		document.body.append(div);
	};
	
	self.showGallery = function(index) {

		location.hash = 'gallery';
	
		document.body.classList.add('gallery');
		
        self.fadeIn(self.imagesContainer);
		
		self.imageIndex = index || 0;
		self.scrollImages(0, true);
		self.setImageHeight();
	}
	
	self.getX = function(event) {
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
			
		var pos = Math.max(self.imageIndex * window.innerWidth, 0) + (self.orgX - self.currX);
		
		requestAnimationFrame(function() {
			self.isWaitingForAnimationFrame = false;
			if (!self.isMoving) {
				return;
			}

			if (!self.transformProperty) {
                self.imagesContainer.style.left = '-' + (pos) + "px";
			} else {
				// css transform
				document.querySelector('#' + self.imagesId).style.transform = "translate3d(-" + pos + "px, 0px, 0px)";
			}
		});
	};
	
	self.handleTouchEnd = function() {
		self.isMoving = false;
		var diff = self.orgX - self.currX;
		if (Math.abs(diff) < 20) {
			self.fadeOut(document.querySelector('.' + self.overlayClass));
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
		var pos = Math.max(self.imageIndex * window.innerWidth, 0);
		var transformProperty = self.transformProperty;
		var delay = 0;
		if (!instant) {
			delay = 300;
			document.querySelector('#' + self.imagesId).classList.add('ease');
		}
		
		if (!transformProperty) {
			self.imagesContainer.animate({"left": '-' + (pos) + "px"}, delay);
		} else {
			document.querySelector('#' + self.imagesId).style[transformProperty] = "translate3d(-" + pos + "px, 0px, 0px)";
		}

		setTimeout(function() {
            document.querySelector('#' + self.imagesId).classList.remove('ease');
			}, 300);

        document.querySelector('#' + self.imageCaptionNumberId).innerHTML = (self.imageIndex+1) + ' / ' + (self.maxIndex+1);

        var a = document.querySelectorAll('#' + self.imagesId + ' img')[self.imageIndex];
        var html = !!a ? (a.getAttribute('alt') || '') : '';
        document.querySelector('#' + self.imageCaptionTextId).innerHTML = html;
		self.adjustTouchArea();
	};

	self.setImageHeight = function() {
        let images = document.querySelectorAll('.' + self.imageClass);
        let index = 0;
        for (let image of images) {
            image.style.height = window.innerHeight + 'px';
            image.style.width = window.innerWidth + 'px';
            let currentIndex = index++;
            image.style.left = window.innerWidth * currentIndex + 'px';
            image.style.top = window.innerHeight / 2 - image.querySelector('img').clientHeight/2 + 'px';
        }

		self.maxPos = (document.querySelectorAll('.' + self.imageClass).length - 1) * window.innerWidth;
		self.imagesContainer.style.width = document.querySelectorAll('.' + self.imageClass).length * window.innerWidth;
		self.adjustTouchArea();
	}

	self.adjustTouchArea = function() {
      let currentImage = document.querySelectorAll('.' + self.imageClass + ' img')[self.imageIndex];
      let target = document.querySelector('#' + self.touchId);
      target.style.height = currentImage.clientHeight + 'px';
      target.style.width = currentImage.clientWidth + 'px';
      target.style.left = window.innerWidth/2 - target.clientWidth/2 + 'px';
      target.style.top = window.innerHeight/2 - target.clientHeight/2 + 'px'
    }

	self.hideGallery = function() {
        if (location.hash == '#gallery') {
            history.back();
        }
        
		if (!document.body.classList.contains('gallery')) {
			return;
		}
		
		document.body.classList.remove('gallery');

        self.fadeOut(self.imagesContainer, function() {
            document.querySelector('#' + self.imagesId).style[self.transformProperty] = '';
        });
	}
	
	self.getTransformProperty = function() {
	return "transform";
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