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

   import('core::configuration::provider::mem', 'MemcachedConfiguration');

   /**
    * @package core::configuration::provider::memcached
    * @class MemcachedConfigurationProvider
    *
    * Implements a configuration provider to store a configuration within a memcached store.
    * This is done by using another configuration provider to read the persistent configuration
    * from.
    *
    * @author Christian Achatz
    * @version
    * Version 0.1, 30.10.2010<br />
    */
   class MemcachedConfigurationProvider implements ConfigurationProvider {

      /**
       * @var string The file extension, the provider is registered with.
       */
      private $extension;

      /**
       * @var ConfigurationProvider The configuration provider to read the persistent configuration from.
       */
      private $persistenceProviderExtension; // perhaps we do not need it!

      /**
       * @var Memcached The memcached service.
       */
      private $memcachedService;

      /**
       * @var int Expires time in seconds. This is the time, the config is refreshed from the persistent file.
       */
      private $expireTime = 3600;

      /**
       * @public
       *
       * Initializes the memcached configuration provider.
       *
       * @param string $persistenceProviderExtension The name of the extension of the provider to use to load the persistent config with.
       * @param Memcached $memcachedService The memcached connection.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 30.10.2010<br />
       */
      public function __construct($persistenceProviderExtension, Memcache $memcachedService) {
         $this->persistenceProviderExtension = $persistenceProviderExtension;
         $this->memcachedService = $memcachedService;
      }

      /**
       * @return int The entry expiring time in seconds.
       */
      public function getExpireTime() {
         return $this->expireTime;
      }

      /**
       * @param int $expireTime The expiring time in seconds.
       */
      public function setExpireTime($expireTime) {
         $this->expireTime = $expireTime;
      }

      public function loadConfiguration($namespace, $context, $language, $environment, $name) {

         $name = $this->remapConfigurationName($name);

         // try to get the configuration from the memcached store first if not available, read
         // persistent configuration and store it
         $key = $this->getStoreIdentifier($namespace, $context, $language, $environment, $name);
         $config = $this->memcachedService->get($key);
         
         if($config === false) {
            $config = ConfigurationManager::loadConfiguration($namespace, $context, $language, $environment, $name);
            $this->memcachedService->set($key, $config, 0, $this->expireTime);
         }

         return $config;
         
      }

      /**
       * @private
       *
       * Remapps the configuration file name to the extension of the persistent configuration
       * file to be able to load and store the physical file.
       *
       * @param string $name The given in-memory configuration file name.
       * @return string The remapped configuration file name.
       *
       * @author Christian Achatz
       * @version
       * Version 0.1, 30.10.2010<br />
       */
      private function remapConfigurationName($name) {
         return str_replace('.' . $this->extension, '.' . $this->persistenceProviderExtension, $name);
      }

      private function getStoreIdentifier($namespace, $context, $language, $environment, $name) {
         return md5($namespace . $context . $language . $environment . $name);
      }

      public function saveConfiguration($namespace, $context, $language, $environment, $name, Configuration $config) {

         $name = $this->remapConfigurationName($name);

         // saving the configuration always includes saving in both the
         // persistent file and the memcached store!
         $key = $this->getStoreIdentifier($namespace, $context, $language, $environment, $name);
         $this->memcachedService->replace($key, $config, 0, $this->expireTime);
         ConfigurationManager::saveConfiguration($namespace, $context, $language, $environment, $name, $config);

      }

      public function setExtension($extension) {
         $this->extension = $extension;
      }

   }
?>