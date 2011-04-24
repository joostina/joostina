<?php
class PluginBar extends Generic_PluginBar
{
  public function Hello() {
    parent::Hello();
    echo '<br />В десятке и ниипёт!';
  }
}
?>