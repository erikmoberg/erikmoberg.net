<?php
include_once("include/webdesign.php");
PrintStartHtml('Flat Icon Maker',3,'The Flat Icon Maker is a simple tool to create your own free custom icons for your applications or websites predefined icons and colors of your choice.');
?>

<div class="page-section">
  <h2>Flat Icon Maker</h2>
  <p>
    Use the Flat Icon Maker to create your own simple icons for your applications or websites - using any modern browser except Internet Explorer!
    Feel free to <a href="contact">contact me</a> if you have any questions or feedback.
  </p>
  <p>
    Hint: The old Shiny Icon Maker <a href="shiny-iconmaker">is still available</a>.
  </p>
</div>

<div id="root">
  <div class="page-section">
    <h3>Loading...</h3>
  </div>
</div>

<script src="iconmaker-static/js/runtime.js"></script>
<script src="iconmaker-static/js/2.js"></script>
<script src="iconmaker-static/js/main.js"></script>


<?php
echo '</div>';
PrintEndHtml();
?>
