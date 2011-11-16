<?php
/**
 * @class PL_FTP
 * @brief Transparent FTP access
 * @author Reza Esmaili (me@dfox.info)
 * 
 * This class wraps the most used php ftp-functins and provides a simple interface for ftp-transactions
 */
class PL_FTP
{
 	var $link;
 	var $host;
 	var $user;
 	var $password;
 	var $message = array();
	/**
	 * Connect to a FTP-Server.
	 *
	 * @param $host FTP host (e.g. localhost)
	 * @param $user FTP user (e.g. root)
	 * @param $password FTP password (e.g. mypass)
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
 	function directConnect()
 	{
		$this->link = ftp_connect($this->host); 
		ftp_pasv($this->link, true);
		if ($this->checkConnection() === false)
			return;
			
		// login
		$loginResult = ftp_login($this->link, $this->user, $this->password); 
		
		// check connection
		if ((!$this->link) || (!$loginResult)) { 
			$this->message[] = "Ftp-Verbindung nicht hergestellt";
			$this->message[] = "Verbindung mit ".$this->host." als Benutzer ".$this->user." nicht möglich"; 
		} else
			$this->message[] = "Verbunden mit ".$this->host." als Benutzer ".$this->user;
 	}

	/**
	 * Connect using the connection parameters in the system configuration.
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
 	function connect()
 	{
 		$this->host = plGetConfig("ftp", "host");
 		$this->user = plGetConfig("ftp", "user_name");
 		$this->password = plGetConfig("ftp", "password");
  		$this->directConnect();
 	}

	/**
	 * Creates a FTP directory
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function mkdir($remoteDir)
	{
		if ($this->checkConnection() === false)
			return;
		if (@ftp_chdir($this->link, $remoteDir)) {
			$this->message[] = "Verzeichnis ".$remoteDir." existiert bereits";
			@ftp_chdir($this->link, "../");
			return;
		} else {
			if (@ftp_mkdir($this->link, $remoteDir))
				$this->message[] = "Verzeichnis ".$remoteDir." wurde erfolgreich erstellt";
			else
				$this->message[] = "Verzeichnis ".$remoteDir." konnte nicht erstellt werden";
		}
	}

	/**
	 * Change directory
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function chdir($dir)
	{
		if ($this->checkConnection() === false)
			return;
		if (@ftp_chdir($this->link, $dir)) {
			$this->message[] = "Wechsel ins Verzeichnis ".$dir;
			return;
		} else {
			$this->message[] = "Verzeichnis ".$dir." existiert nicht";
		}
	}

	/**
	 * List directory content
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function nlist($dir)
	{
		echo $dir;
		if ($this->checkConnection() === false)
			return;
		$array = ftp_nlist($this->link, $dir);
	}

	/**
	 * Uploads a file to FTP
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function put($localFile, $remoteFile)
	{
		if ($this->checkConnection() === false)
			return;
		if (is_file($localFile)) {
			$fp = fopen($localFile, "r");   
			if (ftp_fput($this->link, $remoteFile, $fp, FTP_BINARY))
				$this->message[] = "Datei ".$localFile." wurde erfolgreich übertragen";
			else
				$this->message[] = "Datei ".$localFile." konnte nicht übertragen werden";
		} else
			$this->message[] = "Datei ".$localFile." existiert nicht";
	}

	/**
	 * Compares filetime of remote file with filetime of the local file
	 * Sucks, because timestamp is updated when a file is transferred via ftp
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function mdtm($localFile, $remoteFile)
	{
		if ($this->checkConnection() === false)
			return;
			
		$remoteFileTime = ftp_mdtm($this->link, $remoteFile);
		$localFileTime = filectime($localFile);
		
		if ($remoteFileTime == -1) {
			$this->message[] = "Datei existiert noch nicht";
			return true;
		} elseif ($remoteFileTime <> $localFileTime) {
			$this->message[] = "Datei existiert, ist aber nicht aktuell";
			return true;
		} else {
			$this->message[] = "Datei ".$remoteFile." existiert bereits und ist aktuell";
			return false;
		}
	}

	/**
	 * Close the FTP connection.
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function close()
	{
		if ($this->checkConnection() === false)
			return;
		ftp_quit($this->link);
		$this->message[] = "Verbindung mit ".$this->host." getrennt";
	}

	/**
	 * Checks if a ftp connection exists
	 *
	 * @return nothing
     * @author Reza Esmaili (me@dfox.info)
	 */
	function checkConnection()
	{
		if (!is_resource($this->link)) {
			$this->message[] = "Keine Verbindung mit dem FTP";
			return false;
		} else
			return true;
	}
}
?>