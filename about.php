<?php
header('Location: /contact');

include_once("include/webdesign.php");
PrintStartHtml('About',4,'About me. I have been working as a software developer since 2005, doing ASP.NET (MVC 3), Silverlight and WPF using C#, and recently, some client side magic with jQuery.');
?>

<div class="page-section">
<h2>About me</h2>
<p>
<img src="/content/me_about.jpg" class="image" style="float: left;" alt="Portrait of Erik Moberg" />
I have been working as a software developer since 2005, doing mainly web development using ASP.NET (MVC) with C# and some client side magic with JavaScript, jQuery and Knockout JS.
</p>
<p>
I also like photography and travelling. You can find some of my pictures in the <a href="/images">image gallery</a>.
</p>

<h2>About this site</h2>

<p>
Being a web developer but not having an own web site felt just plain wrong. 
I decided to put together something simple and self developed, as a hobby project - to try some new things and improve my programming skills a little.
</p>
<p>
The site uses PHP, and is programmed with Notepad++. For image manipulation, GIMP and Inkscape are used. 
The image gallery and the first page use XML for storing data, handled by a simple CMS-like application.
</p>

<p>
<a href="https://plus.google.com/110691647499482145684/posts" rel="me" target="_blank">Me on Google+</a>
</p>

</div>

<?php
PrintEndHtml();
?>