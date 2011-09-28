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

   import('core::configuration::provider','BaseConfigurationProvider');
   import('core::configuration::provider::ini','IniConfiguration');

   /**
    * @package core::configuration::provider::ini
    * @class IniConfigurationProvider
    *
    * Implements the configuration provider for the default APF ini format. The
    * following features can be activated:
    * <ul>
    * <li>
    *    Disable context: in case $omitContext is set to true, the context will not be
    *    added to the configuration file path.
    * </li>
    * <li>
    *    Activate environment fallback: in case $activateEnvironmentFallback is set to true,
    *    the configuration provider first looks up the desired configuration file with the
    *    current environment prefix and falls back to DEFAULT environment. Having this feature
    *    activated you may only specify the configuration files, that are really depending on
    *    the environment.
    * </li>
    * <li>
    *    Disable environment: in case $omitEnvironment is set to true, the environment is not
    *    used as sub part of the file name.
    * </li>
    * </ul>
    * In case configuration keys contain a dot (".") they are interpreted as sub-sections of the
    * current section. Providing a key named "conf.abc" and "conf.def" will generate a section
    * "conf" with the two keys "abc" and "def".
    *
    * @author Christian Achatz
    * @version
    * Version 0.1, 27.09.2010<br />
    */
   class IniConfigurationProvider extends BaseConfigurationProvider implements ConfigurationProvider {
      
      /**
       * @private
       * @var string The sub key delimiter.
       */
      private static $NAMESPACE_DELIMITER = '.';

      public function loadConfiguration($namespace, $context, $language, $environment, $name) {

         $fileName = $this->getFilePath($namespace, $context, $language, $environment, $name);

         if(file_exists($fileName)){
            $rawConfig = parse_ini_file($fileName, true);
            return $this->parseConfig($rawConfig);
         }

         if($this->activateEnvironmentFallback && $environment !== 'DEFAULT'){
            return $this->loadConfiguration($namespace, $context, $language, 'DEFAULT', $name);
         }

         throw new ConfigurationException('[IniConfigurationProvider::loadConfiguration()] '
                 .'Configuration with namepace "'.$namespace.'", context "'.$context.'", '
                 .' language "'.$language.'", environment "'.$environment.'", and name '
                 .'"'.$name.'" cannot be loaded!', E_USER_ERROR);

      }

      /**
       * @private
       *
       * Creates the configuration representation for all sections.
       *
       * @param string[] $entries The sections of the current configuration.
       * @return IniConfiguration The appropriate configuration.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 04.10.2010<br />
       */
      private function parseConfig($entries) {

         $config = new IniConfiguration();
         foreach ($entries as $section => $entries) {
            $config->setSection($section, $this->parseSection($entries));
         }
         return $config;

      }

      /**
       * @private
       *
       * Creates the configuration representation of one single section.
       *
       * @param string[] $entries The entries of the current main section.
       * @return IniConfiguration The configuration, that represents the applied entries.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 04.10.2010<br />
       */
      private function parseSection(array $entries){

         $config = new IniConfiguration();
         foreach($entries as $name => $value){
            $config->setValue($name, $value);

            // do always parse sub sections to have a clear API for the configuration provider
            $dot = strpos($name, self::$NAMESPACE_DELIMITER);
            if($dot !== false){
               $this->parseSubSection($config, $name, $value);
            }
         }
         return $config;
      }

      /**
       * @private
       *
       * Creates the sub-section configuration representation in case the
       * parse sub section feature is activated.
       *
       * @param Configuration $config The current configuration.
       * @param string $name The name of the current section.
       * @param string $value The value of the current section.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 04.10.2010<br />
       */
      private function parseSubSection(Configuration &$config, $name, $value){
         
         $dot = strpos($name, self::$NAMESPACE_DELIMITER);
         if ($dot === false) {
            $config->setValue($name, $value);
         } else {
            $subSectionName = substr($name, 0, $dot);
            $remainingName = substr($name, $dot + strlen(self::$NAMESPACE_DELIMITER));

            $nextSection = $config->getSection($subSectionName);
            if($nextSection === null){
               $nextSection = new IniConfiguration();
            }

            $this->parseSubSection($nextSection, $remainingName, $value);
            $config->setSection($subSectionName, $nextSection);
         }
      }

      public function saveConfiguration($namespace, $context, $language, $environment, $name, Configuration $config) {

         $fileName = $this->getFilePath($namespace, $context, $language, $environment, $name);

         $buffer = '';
         foreach ($config->getSectionNames() as $name) {
            $buffer .= '[' . $name . ']' . PHP_EOL;
            $buffer .= $this->processSection($config->getSection($name));
            $buffer .= PHP_EOL;
         }

         // create file path if necessary to avoid "No such file or directory" errors
         $this->createFilePath($fileName);

         if (file_put_contents($fileName, $buffer) === false) {
            throw new ConfigurationException('[IniConfigurationProvider::saveConfiguration()] '
                    . 'Configuration with name "' . $fileName . '" cannot be saved! Please check your '
                    . 'file system configuration, the file name, or your environment configuration.');
         }
      }

      private function processSection(IniConfiguration $section) {

         $buffer = '';
         
         // append simple values except the dot notation values
         foreach ($section->getValueNames() as $name) {
            $dot = strpos($name, self::$NAMESPACE_DELIMITER);
            if($dot === false){
               $buffer .= $name . ' = "' . $section->getValue($name) . '"' . PHP_EOL;
            }
         }

         // append regular sections (including the dot notation keys)
         foreach($section->getSectionNames() as $name) {
            $buffer .= $this->generateComplexConfigValue($section->getSection($name), $name);
         }
         return $buffer;
      }

      private function generateComplexConfigValue(IniConfiguration $config, $currentName) {

         $buffer = '';
         
         // append simple values
         foreach ($config->getValueNames() as $name) {
            $buffer .= $currentName . '.' . $name . ' = "' . $config->getValue($name) . '"' . PHP_EOL;
         }

         // append sections
         foreach ($config->getSectionNames() as $name) {
            $buffer .= $this->generateComplexConfigValue($config->getSection($name), $currentName . '.' . $name);
         }

         return $buffer;
      }

   }
?>