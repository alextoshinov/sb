<?php
// Instantiate new DOMDocument object
$svg = new DOMDocument();
// Load SVG file from public folder
$svg->load(public_path('images/add.svg'));
// Add CSS class (you can omit this line)
$svg->documentElement->setAttribute("data-ui-if", "Modernizr.svg");
// Echo XML without version element
echo $svg->saveXML($svg->documentElement);
