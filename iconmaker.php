<?php
include_once("include/webdesign.php");
PrintStartHtml('Flat Icon Maker',3,'The Flat Icon Maker is a simple tool to create your own free custom icons for your applications or websites predefined icons and colors of your choice.');
?>

<script src="iconmaker2-static/IconMaker.js" type="module"></script>

<div class="page-section">
  <h2>Flat Icon Maker</h2>
  <p>
    Use the Flat Icon Maker to create your own simple icons for your applications or websites - using any modern browser except Internet Explorer!
    Feel free to <a href="contact">contact me</a> if you have any questions or feedback.
  </p>
  <p>
    Hint: The old versions of the Icon Maker are available <a href="react-iconmaker">here</a> (reactjs-based) and <a href="shiny-iconmaker">here</a> (knockoutjs-based).
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

<?php
echo '</div>';
PrintEndHtml();
?>
