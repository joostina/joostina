----------------------------------------------------------------------------------------------------
---                                                                                              ---
--- This statement can be used to create a configuration database table for the                  ---
--- DbConfigurationProvider. Since MySQL supports only 1000byte key length we have               ---
--- restrictions to the length of the configuration identifiers!                                 ---
---                                                                                              ---
----------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `config_test` (
  `context` varchar(50) NOT NULL,
  `language` varchar(5) NOT NULL,
  `environment` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `section` varchar(20) NOT NULL,
  `key` varchar(30) NOT NULL,
  `value` varchar(500) NOT NULL,
  `creationtimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationtimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`context`,`language`,`environment`,`name`,`section`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;