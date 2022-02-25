<?php
include_once("include/webdesign.php");
PrintStartHtml('Flat Icon Maker',3,'The Flat Icon Maker is a simple tool to create your own free custom icons for your applications or websites predefined icons and colors of your choice.');
?>

<script src="iconmaker2-static/IconMaker.js" type="module"></script>

<div class="page-section">
  <h2>Flat Icon Maker</h2>
  <p>
    Use the Flat Icon Maker to create your own simple icons for your applications or websites - using any modern browser!
    Feel free to <a href="contact">contact me</a> if you have any questions or feedback.
  </p>
</div>

<div class="page-section">
    <icon-maker></icon-maker>
</div>

<div class="page-section">
    <h3>Icon Sets License Information</h3>
    <p>I have not created these icons myself and they use different licenses. By using any icons, you confirm that you have reviewed the license terms for the corresponding icon set. Hint: When downloading an icon, the set will be a part of the file name for your convenience.</p>
    <ul>
        <li><a target="_blank" rel="noopener noreferrer" href="http://www.toicon.com/series/afiado">Afiado</a> - <a target="_blank" rel="noopener noreferrer" href="http://www.toicon.com/license">CC BY 4.0 License</a></li>
        <li><a target="_blank" rel="noopener noreferrer" href="http://www.entypo.com">Entypo+</a> - <a target="_blank" rel="noopener noreferrer" href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0 License</a></li>
        <li><a target="_blank" rel="noopener noreferrer" href="https://github.com/colebemis/feather">Feather</a> - <a target="_blank" rel="noopener noreferrer" href="https://opensource.org/licenses/MIT">MIT License</a></li>
        <li><a target="_blank" rel="noopener noreferrer" href="https://icomoon.io/#icons-icomoon">IcoMoon - Free</a> - <a target="_blank" rel="noopener noreferrer" href="http://www.gnu.org/licenses/gpl.html">GPL</a> or <a target="_blank" rel="noopener noreferrer" href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0 License</a></li>
        <li><a target="_blank" rel="noopener noreferrer" href="https://simpleicons.org/">Simple Icons</a> - <a target="_blank" rel="noopener noreferrer" href="https://github.com/simple-icons/simple-icons/blob/develop/LICENSE.md">CC0 1.0 Universal License</a></li>
    </ul>
</div>

<div class="page-section">
  <h2>About the Icon Maker</h2>
  <p>
      Back in 2011, web front end development with JavaScript had really taken off. So when a friend asked me to make him a logo, I jokingly set up a tool for creating a simple logo instead. While it may not have not been any good for actual logos, it did the job fine for icons that could be used on a web page. I used popular technologies at the time: jQuery, jQuery UI, and Knockout JS. That version is available <a href="shiny-iconmaker">here</a>.
  </p>
  <p>
      In 2018, jQuery and Knockout JS were considered legacy libraries and I rewrote the Icon Maker using React JS, the hottest as well as coolest thing at the time. Starting a React JS project was easy using <c>create-react-app</c> and I was happy with the result - a component-oriented, well-factored project that was easy to work with and to deploy. That version is available <a href="react-iconmaker">here</a>.
  </p>
  <p>
      The last rewrite was made in early 2022 and is using web components. While React JS was easy to work with, the project uses more than 1000 NPM packages and I kept getting warnings about vulnerabilities in those dependencies. The motivation for the switch to web components was to reduce the tools and libraries involved and leverage native web standards instead. By collapsing the stack I hope that the icon maker will "just work" for years to come, and I don't have to worry about updating dependencies or the version of React JS I'm using becoming obsolete.
  </p>
</div>

<?php
echo '</div>';
PrintEndHtml();
?>
