<?php
/**
 *
 * */
defined( '_JOOS_CORE' ) or exit();
?>
<?php if ( isset( $object_data['pager'] ) ): ?>
<div class="page"><?php echo $object_data['pager']->output ?></div>
<?php endif; 