<?php
class PluginFoo extends Generic_PluginFoo
{
  public function Hello() {
    echo 'Второй нах?<br />';
    parent::Hello();
  }
}
?>