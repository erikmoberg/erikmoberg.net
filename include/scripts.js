var emnet = {}

emnet.search = {

    init: function () {
        for (let button of document.querySelectorAll('.close-search-results')) {
            button.addEventListener('click', function () {
                emnet.search.closeResults();
            });
        }

        document.querySelector('#open-search').addEventListener('click', function () {
            if (!document.querySelectorAll('#cse-search-form.display-search').length) {
                emnet.search.open();
            } else {
                emnet.search.close();
                emnet.search.closeResults();
            }
        });

        document.querySelector('#cse-search-form-inner').addEventListener('submit', function (e) {

            e.preventDefault();
            var query = document.querySelector('#gsc-search-input').value;
            if (!query) {
                return;
            }

            document.querySelector('#menucontainer input.gsc-search-button').classList.add('searching');
            fetch('/searchservice.php?q=' + encodeURIComponent(query))
                .then(function (response) {
                    return response.text();
                })
                .then(function (markup) {
                    document.querySelector('#cse').innerHTML = markup;
                    document.querySelector('#menucontainer input.gsc-search-button').classList.remove('searching');
                    emnet.search.showResults();
                });
        })
    },

    open: function () {

        document.querySelector('#cse-search-form').classList.add('display-search');
        document.querySelector('#menucontainer').classList.add('display-search');
        document.querySelector('#gsc-search-input').focus();
    },

    close: function () {
        document.querySelector('#cse-search-form').classList.remove('display-search');
        document.querySelector('#menucontainer').classList.remove('display-search');
        document.querySelector('#search-results').style.display = 'none';
        document.querySelector('#header').style.display = 'block';
    },

    showResults: function () {
        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
        document.querySelector('.gsc-input input').blur();
        document.querySelector('#search-results').style.display = 'block';
        document.querySelector('#header').style.display = 'none';

        setTimeout(() => {
            // trigger the scroll event so that the top image fades back in
            document.dispatchEvent(new Event('scroll'));
        }, 500);
    },

    closeResults: function () {
        emnet.search.close();
    }
}

// ---- jscolor settings
if (typeof jscolor !== "undefined") {
    jscolor.dir = '/include/jscolor/';
}
// ---- end jscolor settings

document.querySelector('#menu-toggle').addEventListener('click', function (event) {
    const shouldOpen = !document.querySelector('#menu-toggle').classList.contains('open');

    if (shouldOpen) {
        document.querySelector('#menu-toggle').classList.add('open');
        document.querySelector('#menucontainer').classList.add('display-menu');
        document.querySelector('#search-results').classList.add('display-menu');
    } else {
        document.querySelector('#menu-toggle').classList.remove('open');
        document.querySelector('#menucontainer').classList.remove('display-menu');
        document.querySelector('#search-results').classList.remove('display-menu');
    }
});

fetch('/flickr.php?type=recent')
    .then(function (response) {
        return response.json();
    })
    .then(function (photos) {
        var html = '';
        for (let photo of photos) {
            html += '<a rel="noreferrer" href="' + photo.url + '" target="_blank"><img src="' + photo.image + '" alt="recent" /></a>';
        }

        html += '<div class="clearfix"></div>';
        document.querySelector('#flickr-recent').innerHTML = html;
    });

var lastPos = document.body.scrollTop;
var minScroll = 250;
var tolerance = 50;
var headerElement = document.querySelector("#header .header-image");
document.addEventListener('scroll', function () {
    var newPos = window.scrollY;
    headerElement.style.opacity = Math.max(0, headerElement.clientHeight - newPos) / headerElement.clientHeight;
    let menuContainer = document.querySelector('#menucontainer');
    if (Math.abs(newPos - lastPos) > (!menuContainer.classList.contains('scrolled') ? 0 : tolerance)) {
        if (newPos > lastPos && newPos > minScroll) {
            menuContainer.classList.add('scrolled');
        } else {
            menuContainer.classList.remove('scrolled');
        }

        lastPos = newPos;
    }
});

document.querySelector("#changetheme-dark").addEventListener("click", function () {
    document.body.classList.add("dark-theme");
    sessionStorage.setItem('theme', 'dark');
});

document.querySelector("#changetheme-light").addEventListener("click", function () {
    document.body.classList.remove("dark-theme");
    sessionStorage.setItem('theme', 'light');
});

emnet.isDarkTheme = function () {
    return document.body.classList.contains("dark-theme");
}

emnet.search.init();

if (document.querySelectorAll('.gallery-thumbnail').length) {
    new RespGallery();
}

function showPic(imagefile, thumbimagefile, description) {
    if (document.getElementById) {
        document.getElementById('placeholder').src = thumbimagefile;
        document.getElementById('desc').childNodes[0].nodeValue = description;
        document.getElementById('lnkplaceholder').href = imagefile;
        return false;
    }
    else {
        return true;
    }
}

function postContact(txtName, txtEmail, txtTesttext, txtMessage) {
    for (const errorLabel of document.querySelectorAll('.form-error')) {
        errorLabel.innerHTML = '';
        errorLabel.classList.remove('form-error-visible');
    }

    var name = document.getElementById(txtName).value;
    var email = document.getElementById(txtEmail).value;
    var message = document.getElementById(txtMessage).value;
    var testtext = document.getElementById(txtTesttext).value;

    var errors = [];

    if (!name) {
        errors.push({ field: 'txtName', message: 'Please enter a name.' });
    }

    if (!email) {
        errors.push({ field: 'txtEmail', message: 'Please enter an email address.' });
    }

    if (email && !isEmailValid(email)) {
        errors.push({ field: 'txtEmail', message: 'Enter a valid email address.' });
    }

    if (!message) {
        errors.push({ field: 'txtMessage', message: 'Please enter a message.' });
    }

    if (testtext != 'emalj') {
        errors.push({ field: 'txtEmail', message: 'Please enter an email address.' });
    }

    if (errors.length) {
        errors.push({ field: 'btnSend', message: 'The form contains errors; see above.' });
    }

    if (errors.length > 0) {
        for (const formError of errors) {
            const errorLabel = document.querySelector('#' + formError.field + 'Error');
            errorLabel.innerHTML = formError.message;
            errorLabel.classList.add('form-error-visible');
        }
    } else {
        document.querySelector('#contact-shroud').style.display = 'block';
    }

    return errors.length === 0;
}

function validateCommentForm(formData) {

    var errors = [];

    if (!formData.name) {
        errors.push({ field: 'txtName', message: 'Please enter a name.' });
    }

    if (!formData.message) {
        errors.push({ field: 'txtMessage', message: 'Please enter a message.' });
    }

    if (formData.website && !isValidUrl(formData.website)) {
        errors.push({ field: 'txtWebsite', message: 'The posted web site url is invalid.' });
    }

    if (errors.length) {
        errors.push({ field: 'btnSend', message: 'The form contains errors; see above.' });
    }

    return errors;
}

function isEmailValid(email) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return filter.test(email)
}

function isValidUrl(website) {
    if (website.indexOf('.') < 0 || website.indexOf('@') >= 0) {
        return false;
    }

    return true;
}

function padLeft(str, max, padChar) {
    return str.length < max ? padLeft(padChar + str, max) : str;
}

function checkMaxLength(textBox, e, maxLength) {
    if (textBox.value.length > maxLength - 1 && e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40) {
        return false;
    }
}

emnet.utils = {
    scrollIntoView: function (elementId) {
        document.querySelector('#' + elementId).scrollIntoView({behavior: "smooth", block: "center"});
        document.querySelector("#" + elementId).classList.add("pulse");
            setTimeout(function () {
                document.querySelector("#" + elementId).classList.remove("pulse")
            }, 3000);
    }
}
