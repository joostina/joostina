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
    * @class BaseConfigurationProvider
    *
    * Provides basic configuration provider functionality.
    *
    * @author Christian Achatz
    * @version
    * Version 0.1, 09.10.2010<br />
    */
   abstract class BaseConfigurationProvider {
      
      /**
       * @var boolean Set to true, the context is omitted within the configuration file path.
       */
      protected $omitContext = false;

      /**
       * @var boolean Set to true, the environment fallback will be activated.
       */
      protected $activateEnvironmentFallback = false;

      /**
       * @var boolean Set to true, the environment is omitted within the configuration file path.
       */
      protected $omitEnvironment = false;

      /**
       * @var string The file extension of the provider.
       */
      protected $extension = null;

      /**
       * @var int The file permission to use to create folders.
       */
      protected $folderPermission = 0770;

      public function setOmitContext($omitContext) {
         $this->omitContext = $omitContext;
      }

      public function setActivateEnvironmentFallback($activateEnvironmentFallback) {
         $this->activateEnvironmentFallback = $activateEnvironmentFallback;
      }

      public function setOmitEnvironment($omitEnvironment) {
         $this->omitEnvironment = $omitEnvironment;
      }

      public function setExtension($extension) {
         $this->extension = $extension;
      }

      public function setFolderPermission($folderPermission) {
         $this->folderPermission = $folderPermission;
      }

      /**
       * @param string $namespace The namespace of the desired config.
       * @param string $context The current application's context.
       * @param string $language The current application's language.
       * @param string $environment The current environment.
       * @param string $name The name of the desired config.
       * @return string The appropriate file path.
       */
      protected function getFilePath($namespace, $context, $language, $environment, $name) {

         // fallback for missing file extensions (backward compatibility for pre-1.13 config files)
         if (!preg_match('/\.' . $this->extension . '$/i', $name)) {
            $name = $name . '.' . $this->extension;
         }

         // assemble the context
         $contextPath = ($this->omitContext || $context === null ) ? '' : '/' . str_replace('::', '/', $context);

         // assemble file name
         $fileName = ($this->omitEnvironment || $environment === null) ? '/' . $name : '/' . $environment . '_' . $name;

         // using APPS__PATH is about 50 times faster than the registry!
         return APPS__PATH
         . '/config'
         . '/' . str_replace('::', '/', $namespace)
         . $contextPath
         . $fileName;

      }

      /**
       * @protected
       *
       * Creates the configuration file's path in case if does not exist. This is used for
       * saving configurations as <em>file_put_contents()</em> does not create missing folders.
       *
       * @param string $fileName The fully qualified name of the configuration file.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 21.11.2010<br />
       */
      protected function createFilePath($fileName) {
         $path = dirname($fileName);
         if (!file_exists($path)) {
            mkdir($path, $this->folderPermission, true);
         }
      }

   }

?>