<?php
require 'Parsedown.php';
$Parsedown = new Parsedown();
echo $Parsedown->text( $paste );
