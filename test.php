<?php
include_once("include/webdesign.php");
PrintStartHtml('About',5);
?>

<h2>About me</h2>
<p>
<img src="images/me.jpg" class="image" style="float: left;" alt="Picture of me" />
Originally from Sweden, but living in Germany. 
I am working as a software developer since 2005, doing mostly ASP.NET and Silverlight development.
</p>
<p>
I also like photography and travelling. You can find some of my pictures in the <a href="images.php">image gallery</a>.
</p>
<h2>Lorem Ipsum</h2>
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut eros augue, molestie a aliquam consectetur, viverra a metus. Etiam sit amet arcu purus, vitae lacinia quam. Donec viverra gravida nibh, et eleifend massa egestas eget. In dictum risus viverra justo pulvinar quis faucibus erat placerat. Sed lobortis risus in tellus tempus accumsan. Sed posuere tempus turpis vitae accumsan. Nullam in mauris lorem, id congue neque. Etiam tincidunt justo eget massa feugiat sed interdum lorem vehicula. Etiam nec diam fringilla mauris vestibulum consectetur. Donec tincidunt lacinia tristique. Aenean scelerisque hendrerit nunc id laoreet. Duis odio turpis, posuere vitae viverra eu, fringilla nec arcu. In dictum, urna vitae mattis auctor, dolor lorem aliquam libero, eu gravida lorem nunc at nunc. Pellentesque nisl sapien, molestie sed interdum ac, placerat quis erat. Quisque accumsan tempus eros at cursus. Vivamus accumsan malesuada ligula ut tincidunt. Nullam euismod tristique sem, non venenatis mauris rutrum non. Vivamus ultrices libero vitae nulla porttitor pharetra. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Etiam cursus purus lacinia nulla varius quis placerat est suscipit. 
</p>

<script type="text/javascript">
$(function() {
	$("#sideBarTab").click(toggleSidebar);
	$("#closeSidebarLink").click(toggleSidebar);
});

function toggleSidebar() {
	if($("#sideBarContents").width() == 0) {
		$("#sideBarTab").css("background-image", "url(content/slide-closed.jpg)");
		$("#sideBarContents").show().animate({width: 400}, function(){$("#sideBarContents").show();});
	}
	else {
		$("#sideBarTab").css("background-image", "url(content/slide-open.jpg)");
		$("#sideBarContents").animate({width: 0}, function(){$("#sideBarContents").hide();});
	}
}
</script>

<style type="text/css">
#sideBar{
        position: fixed;
        width: auto;
        height: auto;
        top: 100px;
        left:0px;
    }
   
    #sideBarTab{
        float:right;
        height:137px;
        width:28px;
		background-image: url(content/slide-open.jpg);
    }
   
    #sideBarContents{
		float: left;
		border: 1px solid #ccc;
        background-color: #ddd;
        overflow:hidden !important;
    }
   
    #sideBarContentsInner{
		padding: 0 10px 0 10px;
        width:380px;
    }
	
	#closeSidebarLink {
		float: right;
		font-weight: bold;
		margin: 5px;
	}
</style>

<div id="sideBar">
   
    <a href="#" id="sideBarTab">
    </a>
   
    <div id="sideBarContents" style="width:0px;display: none;">
        <div id="sideBarContentsInner">
            <h2>About this site</h2>

<p>
Being a web developer but not having an own web site felt just plain wrong. 
I decided to put together something simple and completely self developed.
</p>
<p>
The site uses PHP, and is programmed with Notepad++. For image manipulation, GIMP is used. 
The guestbook, image gallery and the first page ("blog") use XML for storing data.
</p>
<p>
<a id="closeSidebarLink" href="#">Close this</a>
</p>
           
        </div>
    </div>
   
</div> 

<?php
PrintEndHtml();
?>