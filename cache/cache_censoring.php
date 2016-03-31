<?php

define('PUN_CENSOR_LOADED', 1);

$search_for = array (
  0 => '%(?<=[^\\p{L}\\p{N}])(fuck)(?=[^\\p{L}\\p{N}])%iu',
  1 => '%(?<=[^\\p{L}\\p{N}])(shit)(?=[^\\p{L}\\p{N}])%iu',
);

$replace_with = array (
  0 => 'love',
  1 => 'love',
);

?>