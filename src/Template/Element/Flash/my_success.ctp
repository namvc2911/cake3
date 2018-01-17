<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success alert-dismissable fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <?= $message ?>
</div>
