<?php
   /**
    * <!--
    * This file is part of the adventure php framework (APF) published under
    * http://adventure-php-framework.org.
    *
    * The APF is free software: you can redistribute it and/or modify
    * it under the terms of the GNU Lesser General Public License as published
    * by the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
    *
    * The APF is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    * GNU Lesser General Public License for more details.
    *
    * You should have received a copy of the GNU Lesser General Public License
    * along with the APF. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
    * -->
    */

   /**
    * @package core::configuration::provider
    * @class BaseConfiguration
    *
    * Provides base functionality for the concrete configuration object implementations.
    *
    * @author Christian Achatz
    * @version
    * Version 0.1, 30.10.2010<br />
    */
   abstract class BaseConfiguration {

      /**
       * @var array Stores the values of the current configuration/section.
       */
      private $values = array();

      /**
       * @var XmlConfiguration[] Stores the sections of the current config.
       */
      private $sections = array();

      public function getSection($name) {
         return isset($this->sections[$name]) ? $this->sections[$name] : null;
      }

      public function getSectionNames() {
         return array_keys($this->sections);
      }

      public function getValue($name) {
         return isset($this->values[$name]) ? $this->values[$name] : null;
      }

      public function getValueNames() {
         return array_keys($this->values);
      }

      public function setSection($name, Configuration $section) {
         $this->sections[$name] = $section;
      }

      public function setValue($name, $value) {
         $this->values[$name] = $value;
      }

      public function removeSection($name) {
         unset($this->sections[$name]);
      }

      public function removeValue($name) {
         unset($this->values[$name]);
      }

   }
?>