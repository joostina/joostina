<?php

class BitTorrent {

	protected $name = ''; //Name of the torrent
	protected $filename = ''; //Filename of the torrent
	protected $comment = '';
	protected $date = 0; //Creation date as unix timestamp
	protected $files = array(); //Files in the torrent
	protected $size = 0; //Size of of the full torrent (after download)
	protected $created_by = '';
	protected $announce = ''; //tracker (the tracker the torrent has been received from)
	protected $pieces = '';
	protected $announce_list = array(); //List of known trackers for the torrent
	protected $source = ''; //Source string
	protected $source_length = 0; //Source length
	protected $position = 0; //Current position of the string
	protected $decoded = array();
	protected $is_private = false; //Torrent is marked as 'private'.

	function decode($str) {
		$this->source = $str;
		$this->position = 0;
		$this->source_length = strlen($this->source);
		$result = $this->bdecode();
		return $result;
	}

	function decodeSource($source) {

		// Reset public attributes
		$this->name = '';
		$this->filename = '';
		$this->comment = '';
		$this->date = 0;
		$this->files = array();
		$this->size = 0;
		$this->created_by = '';
		$this->announce = '';
		$this->announce_list = array();
		$this->pieces = '';
		$this->position = 0;

		// Decode .torrent
		$this->source = $source;
		$this->source_length = strlen($this->source);
		$this->decoded = $this->bdecode();
		if (!is_array($this->decoded)
			)return false;

		// Pull information form decoded data
		$this->filename = '';
		// Name of the torrent - statet by the torrent's author
		$this->name = $this->decoded['info']['name'];
		// Authors may add comments to a torrent
		if (isset($this->decoded['comment']))
			$this->comment = $this->decoded['comment'];
		// Creation date of the torrent as unix timestamp
		if (isset($this->decoded['creation date']))
			$this->date = $this->decoded['creation date'];
		// This contains the signature of the application used to create the torrent
		if (isset($this->decoded['created by']))
			$this->created_by = $this->decoded['created by'];
		// Get the directory separator
		$sep = (PHP_OS == 'Linux') ? '/' : '\\';
		// There is sometimes an array listing all files
		// in the torrent with their individual filesize
		if (isset($this->decoded['info']['files']) and is_array($this->decoded['info']['files'])) {
			foreach ($this->decoded['info']['files'] as $file) {
				$path = join($sep, $file['path']);
				// We are computing the total size of the download heres
				$this->size += $file['length'];
				$this->files[] = array(
					'filename' => $path,
					'size' => $file['length'],
				);
			}
			// In case the torrent contains only on file
		} elseif (isset($this->decoded['info']['name'])) {
			$this->files[] = array(
				'filename' => $this->decoded['info']['name'],
				'size' => $this->decoded['info']['length'],
			);
		}
		// If the the info->length field is present we are dealing with
		// a single file torrent.
		if (isset($this->decoded['info']['length']) and $this->size == 0)
			$this->size = $this->decoded['info']['length'];

		// This contains the tracker the torrent has been received from
		if (isset($this->decoded['announce']))
			$this->announce = $this->decoded['announce'];

		// This contains a list of all known trackers for this torrent
		if (isset($this->decoded['announce-list']) and is_array($this->decoded['announce-list']))
			$this->announce_list = $this->decoded['announce-list'];

		if (isset($this->decoded['pieces']))
			$this->pieces = $this->decoded['pieces'];
		// Private flag
		if (isset($this->decoded['info']['private']) and $this->decoded['info']['private'])
			$this->is_private = true;
		return array(
			'name' => $this->name,
			//'filename'      => $this->filename,
			'comment' => $this->comment,
			'date' => $this->date,
			'created_by' => $this->created_by,
			'files' => $this->files,
			'size' => $this->size,
			'announce' => $this->announce,
			'info' => $this->decoded['info'],
			'announce_list' => $this->announce_list,
			'info_hash' => $this->getInfoHash(),
		);
	}

	protected function bdecode() {
		switch ($this->getChar()) {
			case 'i':
				$this->position++;
				return $this->decode_int();
				break;
			case 'l':
				$this->position++;
				return $this->decode_list();
				break;
			case 'd':
				$this->position++;
				return $this->decode_dict();
				break;
			default:
				return $this->decode_string();
		}
	}

	protected function decode_dict() {
		$return = array();
		$ended = false;
		$lastkey = NULL;
		while ($char = $this->getChar()) {
			if ($char == 'e') {
				$ended = true;
				break;
			}
			if (!ctype_digit($char)) {

			}
			$key = $this->decode_string();
			if (isset($return[$key])) {
				
			}
			if ($key < $lastkey) {
				
			}
			$val = $this->bdecode();
			if ($val === false) {

			}
			$return[$key] = $val;
			$lastkey = $key;
		}
		if (!$ended) {
			
		}
		$this->position++;
		return $return;
	}

	protected function decode_string() {
		// Check for bad leading zero
		if (substr($this->source, $this->position, 1) == '0' and
				substr($this->source, $this->position + 1, 1) != ':') {

		}
		// Find position of colon
		// Supress error message if colon is not found which may be caused by a corrupted or wrong encoded string
		if (!$pos_colon = @strpos($this->source, ':', $this->position)) {

		}
		// Get length of string
		$str_length = intval(substr($this->source, $this->position, $pos_colon));
		if ($str_length + $pos_colon + 1 > $this->source_length) {

		}
		// Get string
		if ($str_length === 0) {
			$return = '';
		} else {
			$return = substr($this->source, $pos_colon + 1, $str_length);
		}
		// Move Pointer after string
		$this->position = $pos_colon + $str_length + 1;
		return $return;
	}

	protected function decode_int() {
		$pos_e = strpos($this->source, 'e', $this->position);
		$p = $this->position;
		if ($p === $pos_e) {
			
		}
		if (substr($this->source, $this->position, 1) == '-')
			$p++;
		if (substr($this->source, $p, 1) == '0' and
				($p != $this->position or $pos_e > $p + 1)) {

		}
		for ($i = $p; $i < $pos_e - 1; $i++) {
			if (!ctype_digit(substr($this->source, $i, 1))) {
				
			}
		}

		$return = substr($this->source, $this->position, $pos_e - $this->position) + 0;
		$this->position = $pos_e + 1;
		return $return;
	}

	protected function decode_list() {
		$return = array();
		$char = $this->getChar();
		$p1 = $p2 = 0;

		while ($char !== false && substr($this->source, $this->position, 1) != 'e') {
			$p1 = $this->position;
			$val = $this->bdecode();
			$p2 = $this->position;
			// Empty does not work here

			$return[] = $val;
		}
		$this->position++;
		return $return;
	}

	protected function getChar() {
		if (empty($this->source))
			return false;
		if ($this->position >= $this->source_length)
			return false;
		return substr($this->source, $this->position, 1);
	}

	function getStats($announce=false) {
		if (!$announce)
			$announce = $this->announce;
		// Check if we can access remote data
		if (!ini_get('allow_url_fopen')) {
			return false;
		}
		// Query the scrape page
		$packed_hash = pack('H*', $this->getInfoHash());
		$scrape_url = preg_replace('/\/announce$/', '/scrape', $announce) . '?info_hash=' . urlencode($packed_hash);
		if (strpos($scrape_url, 'http://') === false)
			return false;
		$scrape_data = file_get_contents($scrape_url);
		if (!$scrape_data)
			return false;
		$stats = $this->decode($scrape_data);

		if (isset($stats['files'][$packed_hash]))
			return $stats['files'][$packed_hash];


		$alt_hash = str_replace(' ', '+', $packed_hash);
		if (isset($stats['files'][$alt_hash]))
			return $stats['files'][$alt_hash];
	}

	function getName() {
		return $this->name;
	}

	function getFilename() {
		return $this->filename;
	}

	function getComment() {
		return $this->comment;
	}

	function getDate() {
		return $this->date;
	}

	function getCreator() {
		return $this->created_by;
	}

	function getFiles() {
		return $this->files;
	}

	function getAnnounce() {
		return $this->announce;
	}

	function getAnnounceList() {
		return $this->announce_list;
	}

	function getInfoHash($raw = false) {
		$return = sha1($this->encode($this->decoded['info']), $raw);
		return $return;
	}

	public function isPrivate() {
		return $this->is_private;
	}

	function encode($mixed) {
		switch (gettype($mixed)) {
			case is_null($mixed):
				return $this->encode_string('');
			case 'string':
				return $this->encode_string($mixed);
			case 'integer':
			case 'double':
				return $this->encode_int(sprintf('%.0f', round($mixed)));
			case 'array':
				return $this->encode_array($mixed);
			default:
		}
	}

	function encode_string($str) {
		return strlen($str) . ':' . $str;
	}

	function encode_int($int) {
		return 'i' . $int . 'e';
	}

	function encode_array(array $array) {
		// Check for strings in the keys
		$isList = true;
		foreach (array_keys($array) as $key) {
			if (!is_int($key)) {
				$isList = false;
				break;
			}
		}
		if ($isList) {
			// Wie build a list
			ksort($array, SORT_NUMERIC);
			$return = 'l';
			foreach ($array as $val) {
				$return .= $this->encode($val);
			}
			$return .= 'e';
		} else {
			// We build a Dictionary
			ksort($array, SORT_STRING);
			$return = 'd';
			foreach ($array as $key => $val) {
				$return .= $this->encode(strval($key));
				$return .= $this->encode($val);
			}
			$return .= 'e';
		}
		return $return;
	}

}

?>
