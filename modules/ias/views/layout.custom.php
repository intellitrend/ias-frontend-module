<?php
/**
 * @var List $this
 */

header('Content-Type: ' . $data['mime_type'] . '; charset=UTF-8');

echo $data['main_block'];
?>