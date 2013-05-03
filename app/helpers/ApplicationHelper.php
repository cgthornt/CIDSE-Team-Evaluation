<?php
/**
 * The main applicaiton helper
 */

 /**
  * Shortcut function to escape possible malicious HTML.
  * @param string $text the text to escape
  * @param boolean $newlines if TRUE, converts newline characters into <br> tags.
  *   If FALSE, leaves newlines the same
  * @return string The encoded string
  */
function h($text, $newlines = false) {
  $text = CHtml::encode($text);
  if($newlines) $text = nl2br($text);
  return $text;
}

?>