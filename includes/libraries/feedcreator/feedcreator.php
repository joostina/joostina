<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 * FeedCreator class v1.7.2
 * originally (c) Kai Blankenhorn
 * www.bitfolge.de
 * kaib@bitfolge.de
 * v1.3 work by Scott Reynen (scott@randomchaos.com) and Kai Blankenhorn
 * v1.5 OPML support by Dirk Clemens
 */

defined('_JOOS_CORE') or die();
global $mosConfig_feed_timeoffset;
define("TIME_ZONE",$mosConfig_feed_timeoffset);

define("FEEDCREATOR_VERSION","Joostina CMS");

class FeedItem extends HtmlDescribable {
	var $title,$description,$link;
	var $author,$authorEmail,$image,$category,$comments,$guid,$source,$creator;
	var $date;
	var $additionalElements = array();
	var $enclosures = array();
	function addEnclosure($url,$length = 0,$type='') {
		$this->enclosures[] = array("url" => $url,"length" => $length,"type" => $type);
	}
}
class FeedImage extends HtmlDescribable {
	var $title,$url,$link;
	var $width,$height,$description;
}
class HtmlDescribable {
	var $descriptionHtmlSyndicated;
	var $descriptionTruncSize;
	function getDescription() {
		$descriptionField = new FeedHtmlField($this->description);
		$descriptionField->syndicateHtml = $this->descriptionHtmlSyndicated;
		$descriptionField->truncSize = $this->descriptionTruncSize;
		return $descriptionField->output();
	}
}
class FeedHtmlField {
	var $rawFieldContent;
	var $truncSize,$syndicateHtml;
	function FeedHtmlField($parFieldContent) {
		if($parFieldContent) {
			$this->rawFieldContent = $parFieldContent;
		}
	}
	function output() {
		if(!$this->rawFieldContent) {
			$result = "";
		} elseif($this->syndicateHtml) {
			$result = "<![CDATA[".$this->rawFieldContent."]]>";
		} else {
			if($this->truncSize and is_int($this->truncSize)) {
				$result = FeedCreator::iTrunc(htmlspecialchars($this->rawFieldContent),$this->truncSize);
			} else {
				$result = htmlspecialchars($this->rawFieldContent);
			}
		}
		return $result;
	}
}
class UniversalFeedCreator extends FeedCreator {
	var $_feed;
	function _setFormat($format) {
		switch(strtoupper($format)) {
			case "2.0":
			case "RSS2.0":
				$this->_feed = new RSSCreator20();
				break;
			case "1.0":
			case "RSS1.0":
				$this->_feed = new RSSCreator10();
				break;
			case "0.91":
			case "RSS0.91":
				$this->_feed = new RSSCreator091();
				break;
			case "PIE0.1":
				$this->_feed = new PIECreator01();
				break;
			case "MBOX":
				$this->_feed = new MBOXCreator();
				break;
			case "OPML":
				$this->_feed = new OPMLCreator();
				break;
			case "ATOM":
			case "ATOM0.3":
				$this->_feed = new AtomCreator03();
				break;
			case "HTML":
				$this->_feed = new HTMLCreator();
				break;
			case "JS":
			case "JAVASCRIPT":
				$this->_feed = new JSCreator();
				break;
			case "YANDEX":
				$this->_feed = new Yandex();
				break;
			default:
				$this->_feed = new RSSCreator091();
				break;
		}
		$vars = get_object_vars($this);
		foreach($vars as $key => $value) {
			if(!in_array($key,array("_feed","contentType"))) {
				$this->_feed->{$key} = $this->{$key};
			}
		}
	}
	function createFeed($format = "RSS0.91") {
		$this->_setFormat($format);
		return $this->_feed->createFeed();
	}
	function saveFeed($format = "RSS0.91",$filename = "",$displayContents = true) {
		$this->_setFormat($format);
		$this->_feed->saveFeed($filename,$displayContents);
	}
	function useCached($format = "RSS0.91",$filename = "",$timeout = 3600) {
		$this->_setFormat($format);
		$this->_feed->useCached($filename,$timeout);
	}
}
class FeedCreator extends HtmlDescribable {
	var $title,$description,$link;
	var $syndicationURL,$image,$language,$copyright,$pubDate,$lastBuildDate,$editor,$editorEmail,$webmaster,$category,$docs,$ttl,$rating,$skipHours,$skipDays;
	var $xslStyleSheet = "";
	var $items = array();
	var $contentType = "application/xml";
	var $encoding = "UTF-8";
	var $additionalElements = array();
	function addItem($item) {
		$this->items[] = $item;
	}
	function iTrunc($string,$length) {
		if(strlen($string) <= $length) {
			return $string;
		}
		$pos = strrpos($string,".");
		if($pos >= $length - 4) {
			$string = substr($string,0,$length - 4);
			$pos = strrpos($string,".");
		}
		if($pos >= $length * 0.4) {
			return substr($string,0,$pos + 1)." ...";
		}
		$pos = strrpos($string," ");
		if($pos >= $length - 4) {
			$string = substr($string,0,$length - 4);
			$pos = strrpos($string," ");
		}
		if($pos >= $length * 0.4) {
			return substr($string,0,$pos)." ...";
		}
		return substr($string,0,$length - 4)." ...";
	}
	function _createGeneratorComment() {
		return "<!-- generator=\"".FEEDCREATOR_VERSION."\" -->\n";
	}
	function _createAdditionalElements($elements,$indentString = "") {
		$ae = "";
		if(is_array($elements)) {
			foreach($elements as $key => $value) {
				$ae .= $indentString."<$key>$value</$key>\n";
			}
		}
		return $ae;
	}
	function _createStylesheetReferences() {
		$xml = "";
		if($this->cssStyleSheet)
			$xml .= "<?xml-stylesheet href=\"".$this->cssStyleSheet."\" type=\"text/css\"?>\n";
		if($this->xslStyleSheet)
			$xml .= "<?xml-stylesheet href=\"".$this->xslStyleSheet."\" type=\"text/xsl\"?>\n";
		return $xml;
	}
	function createFeed() {
	}
	function _generateFilename() {
		$fileInfo = pathinfo($_SERVER["PHP_SELF"]);
		return substr($fileInfo["basename"],0,-(strlen($fileInfo["extension"]) + 1)).".xml";
	}
	function _redirect($filename) {
		Header("Content-Type: ".$this->contentType."; charset=".$this->encoding);
		Header("Content-Disposition: inline; filename=".basename($filename));
		readfile($filename,"r");
		die();
	}
	function useCached($filename = "",$timeout = 3600) {
		$this->_timeout = $timeout;
		if($filename == "") {
			$filename = $this->_generateFilename();
		}
		if(file_exists($filename) and (time() - filemtime($filename) < $timeout)) {
			$this->_redirect($filename);
		}
	}
	function saveFeed($filename = "",$displayContents = true) {
		if($filename == "") {
			$filename = $this->_generateFilename();
		}
		$feedFile = fopen($filename,"w+");
		if($feedFile) {
			fputs($feedFile,$this->createFeed());
			fclose($feedFile);
			if($displayContents) {
				$this->_redirect($filename);
			}
		} else {
			echo "<br /><b>"._ERROR_CREATING_NEWSFEED."</b><br />";
		}
	}
}
class FeedDate {
	var $unix;
	function FeedDate($dateString = "") {
		if($dateString == "")
			$dateString = date("r");
		if(is_integer($dateString)) {
			$this->unix = $dateString;
			return;
		}
		if(preg_match("~(?:(?:Mon|Tue|Wed|Thu|Fri|Sat|Sun),\\s+)?(\\d{1,2})\\s+([a-zA-Z]{3})\\s+(\\d{4})\\s+(\\d{2}):(\\d{2}):(\\d{2})\\s+(.*)~",
		$dateString,$matches)) {
			$months = array("Jan" => 1,"Feb" => 2,"Mar" => 3,"Apr" => 4,"May" => 5,"Jun" =>6,"Jul" => 7,"Aug" => 8,"Sep" => 9,"Oct" => 10,"Nov" => 11,"Dec" => 12);
			$this->unix = mktime($matches[4],$matches[5],$matches[6],$months[$matches[2]],$matches[1],
					$matches[3]);
			if(substr($matches[7],0,1) == '+' or substr($matches[7],0,1) == '-') {
				$tzOffset = (substr($matches[7],0,3) * 60 + substr($matches[7],-2)) * 60;
			} else {
				if(strlen($matches[7]) == 1) {
					$oneHour = 3600;
					$ord = ord($matches[7]);
					if($ord < ord("M")) {
						$tzOffset = (ord("A") - $ord - 1) * $oneHour;
					} elseif($ord >= ord("M") and $matches[7] != "Z") {
						$tzOffset = ($ord - ord("M")) * $oneHour;
					} elseif($matches[7] == "Z") {
						$tzOffset = 0;
					}
				}
				switch($matches[7]) {
					case "UT":
					case "GMT":
						$tzOffset = 0;
				}
			}
			$this->unix += $tzOffset;
			return;
		}
		if(preg_match("~(\\d{4})-(\\d{2})-(\\d{2})T(\\d{2}):(\\d{2}):(\\d{2})(.*)~",$dateString,
		$matches)) {
			$this->unix = mktime($matches[4],$matches[5],$matches[6],$matches[2],$matches[3],
					$matches[1]);
			if(substr($matches[7],0,1) == '+' or substr($matches[7],0,1) == '-') {
				$tzOffset = (substr($matches[7],0,3) * 60 + substr($matches[7],-2)) * 60;
			} else {
				if($matches[7] == "Z") {
					$tzOffset = 0;
				}
			}
			$this->unix += $tzOffset;
			return;
		}
		$this->unix = 0;
	}
	function rfc822() {
		$date = gmdate("D, d M Y H:i:s",$this->unix);
		if(TIME_ZONE != "")
			$date .= " ".str_replace(":","",TIME_ZONE);
		return $date;
	}
	function iso8601() {
		$date = gmdate("Y-m-d\TH:i:sO",$this->unix);
		$date = substr($date,0,22).':'.substr($date,-2);
		if(TIME_ZONE != "")
			$date = str_replace("+00:00",TIME_ZONE,$date);
		return $date;
	}
	function unix() {
		return $this->unix;
	}
}
class RSSCreator10 extends FeedCreator {
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= $this->_createGeneratorComment();
		$feed .= $this->_createStylesheetReferences();
		$feed .= "<rdf:RDF\n";
		$feed .= "	xmlns=\"http://purl.org/rss/1.0/\"\n";
		$feed .= "	xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"\n";
		$feed .= "	xmlns:slash=\"http://purl.org/rss/1.0/modules/slash/\"\n";
		$feed .= "	xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
		$feed .= "	<channel rdf:about=\"".$this->syndicationURL."\">\n";
		$feed .= "		<title>".htmlspecialchars($this->title)."</title>\n";
		$feed .= "		<description>".htmlspecialchars($this->description)."</description>\n";
		$feed .= "		<link>".$this->link."</link>\n";
		if($this->image != null) {
			$feed .= "		<image rdf:resource=\"".$this->image->url."\" />\n";
		}
		$now = new FeedDate();
		$feed .= "	   <dc:date>".htmlspecialchars($now->iso8601())."</dc:date>\n";
		$feed .= "		<items>\n";
		$feed .= "			<rdf:Seq>\n";
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "				<rdf:li rdf:resource=\"".htmlspecialchars($this->items[$i]->link)."\"/>\n";
		}
		$feed .= "			</rdf:Seq>\n";
		$feed .= "		</items>\n";
		$feed .= "	</channel>\n";
		if($this->image != null) {
			$feed .= "	<image rdf:about=\"".$this->image->url."\">\n";
			$feed .= "		<title>".$this->image->title."</title>\n";
			$feed .= "		<link>".$this->image->link."</link>\n";
			$feed .= "		<url>".$this->image->url."</url>\n";
			$feed .= "	</image>\n";
		}
		$feed .= $this->_createAdditionalElements($this->additionalElements,"	");
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "	<item rdf:about=\"".htmlspecialchars($this->items[$i]->link)."\">\n";
			$feed .= "		<dc:format>text/html</dc:format>\n";
			if($this->items[$i]->date != null) {
				$itemDate = new FeedDate($this->items[$i]->date);
				$feed .= "		<dc:date>".htmlspecialchars($itemDate->iso8601())."</dc:date>\n";
			}
			if($this->items[$i]->source != "") {
				$feed .= "		<dc:source>".htmlspecialchars($this->items[$i]->source)."</dc:source>\n";
			}
			if($this->items[$i]->author != "") {
				$feed .= "		<dc:creator>".htmlspecialchars($this->items[$i]->author)."</dc:creator>\n";
			}
			$feed .= "		<title>".htmlspecialchars(strip_tags(strtr($this->items[$i]->title,"\n\r","  ")))."</title>\n";
			$feed .= "		<link>".htmlspecialchars($this->items[$i]->link)."</link>\n";
			$feed .= "		<description>".htmlspecialchars($this->items[$i]->description).
					"</description>\n";
			$feed .= $this->_createAdditionalElements($this->items[$i]->additionalElements,
					"		");
			$feed .= "	</item>\n";
		}
		$feed .= "</rdf:RDF>\n";
		return $feed;
	}
}
class RSSCreator091 extends FeedCreator {
	var $RSSVersion;
	function RSSCreator091() {
		$this->_setRSSVersion("0.91");
		$this->contentType = "application/rss+xml";
	}
	function _setRSSVersion($version) {
		$this->RSSVersion = $version;
	}
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= $this->_createGeneratorComment();
		$feed .= $this->_createStylesheetReferences();
		$feed .= "<rss version=\"".$this->RSSVersion."\">\n";
		$feed .= "	<channel>\n";
		$feed .= "		<title>".FeedCreator::iTrunc(htmlspecialchars($this->title),100)."</title>\n";
		$this->descriptionTruncSize = 500;
		$feed .= "		<description>".$this->getDescription()."</description>\n";
		$feed .= "		<link>".$this->link."</link>\n";
		$now = new FeedDate();
		$feed .= "		<lastBuildDate>".htmlspecialchars($now->rfc822())."</lastBuildDate>\n";
		$feed .= "		<generator>".FEEDCREATOR_VERSION."</generator>\n";
		if($this->image != null) {
			$feed .= "		<image>\n";
			$feed .= "			<url>".$this->image->url."</url>\n";
			$feed .= "			<title>".FeedCreator::iTrunc(htmlspecialchars($this->image->title),100)."</title>\n";
			$feed .= "			<link>".$this->image->link."</link>\n";
			if($this->image->width != "") {
				$feed .= "			<width>".$this->image->width."</width>\n";
			}
			if($this->image->height != "") {
				$feed .= "			<height>".$this->image->height."</height>\n";
			}
			if($this->image->description != "") {
				$feed .= "			<description>".$this->image->getDescription()."</description>\n";
			}
			$feed .= "		</image>\n";
		}
		if($this->language != "") {
			$feed .= "		<language>".$this->language."</language>\n";
		}
		if($this->copyright != "") {
			$feed .= "		<copyright>".FeedCreator::iTrunc(htmlspecialchars($this->copyright),100)."</copyright>\n";
		}
		if($this->editor != "") {
			$feed .= "		<managingEditor>".FeedCreator::iTrunc(htmlspecialchars($this->editor),100)."</managingEditor>\n";
		}
		if($this->webmaster != "") {
			$feed .= "		<webMaster>".FeedCreator::iTrunc(htmlspecialchars($this->webmaster),100)."</webMaster>\n";
		}
		if($this->pubDate != "") {
			$pubDate = new FeedDate($this->pubDate);
			$feed .= "		<pubDate>".htmlspecialchars($pubDate->rfc822())."</pubDate>\n";
		}
		if($this->category != "") {
			$feed .= "		<category>".htmlspecialchars($this->category)."</category>\n";
		}
		if($this->docs != "") {
			$feed .= "		<docs>".FeedCreator::iTrunc(htmlspecialchars($this->docs),500)."</docs>\n";
		}
		if($this->ttl != "") {
			$feed .= "		<ttl>".htmlspecialchars($this->ttl)."</ttl>\n";
		}
		if(isset($this->rating_count) && $this->rating_count > 0) {
			$rating = round($this->rating_sum / $this->rating_count);
			$feed .= "		<rating>".FeedCreator::iTrunc(htmlspecialchars($rating),500)."</rating>\n";
		}
		if($this->skipHours != "") {
			$feed .= "		<skipHours>".htmlspecialchars($this->skipHours)."</skipHours>\n";
		}
		if($this->skipDays != "") {
			$feed .= "		<skipDays>".htmlspecialchars($this->skipDays)."</skipDays>\n";
		}
		$feed .= $this->_createAdditionalElements($this->additionalElements,"	");
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "		<item>\n";
			$feed .= "			<title>".FeedCreator::iTrunc(htmlspecialchars(strip_tags($this->items[$i]->title)),100)."</title>\n";
			$feed .= "			<link>".htmlspecialchars($this->items[$i]->link)."</link>\n";
			$feed .= "			<description>".$this->items[$i]->getDescription()."</description>\n";
			if($this->items[$i]->author != "") {
				$feed .= "			<author>".htmlspecialchars($this->items[$i]->author)."</author>\n";
			}
			if($this->items[$i]->category != "") {
				$feed .= "			<category>".htmlspecialchars($this->items[$i]->category)."</category>\n";
			}
			if($this->items[$i]->comments != "") {
				$feed .= "			<comments>".htmlspecialchars($this->items[$i]->comments)."</comments>\n";
			}
			if($this->items[$i]->date != "") {
				$itemDate = new FeedDate($this->items[$i]->date);
				$feed .= "			<pubDate>".htmlspecialchars($itemDate->rfc822())."</pubDate>\n";
			}
			if($this->items[$i]->guid != "") {
				$feed .= "			<guid>".htmlspecialchars($this->items[$i]->guid)."</guid>\n";
			}
			$feed .= $this->_createAdditionalElements($this->items[$i]->additionalElements,
					"		");
			$feed .= "		</item>\n";
		}
		$feed .= "	</channel>\n";
		$feed .= "</rss>\n";
		return $feed;
	}
}
class RSSCreator20 extends RSSCreator091 {
	function RSSCreator20() {
		parent::_setRSSVersion("2.0");
	}
}
class PIECreator01 extends FeedCreator {
	function PIECreator01() {
		$this->encoding = "UTF-8";
	}
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= $this->_createStylesheetReferences();
		$feed .= "<feed version=\"0.1\" xmlns=\"http://example.com/newformat#\">\n";
		$feed .= "	<title>".FeedCreator::iTrunc(htmlspecialchars($this->title),100)."</title>\n";
		$this->truncSize = 500;
		$feed .= "	<subtitle>".$this->getDescription()."</subtitle>\n";
		$feed .= "	<link>".$this->link."</link>\n";
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "	<entry>\n";
			$feed .= "		<title>".FeedCreator::iTrunc(htmlspecialchars(strip_tags($this->items[$i]->title)),100)."</title>\n";
			$feed .= "		<link>".htmlspecialchars($this->items[$i]->link)."</link>\n";
			$itemDate = new FeedDate($this->items[$i]->date);
			$feed .= "		<created>".htmlspecialchars($itemDate->iso8601())."</created>\n";
			$feed .= "		<issued>".htmlspecialchars($itemDate->iso8601())."</issued>\n";
			$feed .= "		<modified>".htmlspecialchars($itemDate->iso8601())."</modified>\n";
			$feed .= "		<id>".htmlspecialchars($this->items[$i]->guid)."</id>\n";
			if($this->items[$i]->author != "") {
				$feed .= "		<author>\n";
				$feed .= "			<name>".htmlspecialchars($this->items[$i]->author)."</name>\n";
				if($this->items[$i]->authorEmail != "") {
					$feed .= "			<email>".$this->items[$i]->authorEmail."</email>\n";
				}
				$feed .= "		</author>\n";
			}
			$feed .= "        <content type=\"text/html\" xml:lang=\"ru-Ru\">\n";
			$feed .= "			<div xmlns=\"http://www.w3.org/1999/xhtml\">".$this->items[$i]->getDescription().
					"</div>\n";
			$feed .= "		</content>\n";
			$feed .= "	</entry>\n";
		}
		$feed .= "</feed>\n";
		return $feed;
	}
}
class AtomCreator03 extends FeedCreator {
	function AtomCreator03() {
		$this->contentType = "application/atom+xml";
		$this->encoding = "UTF-8";
	}
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= $this->_createGeneratorComment();
		$feed .= $this->_createStylesheetReferences();
		$feed .= "<feed version=\"0.3\" xmlns=\"http://purl.org/atom/ns#\"";
		if($this->language != "") {
			$feed .= " xml:lang=\"".$this->language."\"";
		}
		$feed .= ">\n";
		$feed .= "	<title>".htmlspecialchars($this->title)."</title>\n";
		$feed .= "	<tagline>".htmlspecialchars($this->description)."</tagline>\n";
		$feed .= "	<link rel=\"alternate\" type=\"text/html\" href=\"".htmlspecialchars($this->link).
				"\"/>\n";
		$feed .= "	<id>".htmlspecialchars($this->link)."</id>\n";
		$now = new FeedDate();
		$feed .= "	<modified>".htmlspecialchars($now->iso8601())."</modified>\n";
		if($this->editor != "") {
			$feed .= "	<author>\n";
			$feed .= "		<name>".$this->editor."</name>\n";
			if($this->editorEmail != "") {
				$feed .= "		<email>".$this->editorEmail."</email>\n";
			}
			$feed .= "	</author>\n";
		}
		$feed .= "	<generator>".FEEDCREATOR_VERSION."</generator>\n";
		$feed .= $this->_createAdditionalElements($this->additionalElements,"	");
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "	<entry>\n";
			$feed .= "		<title>".htmlspecialchars(strip_tags($this->items[$i]->title)).
					"</title>\n";
			$feed .= "		<link rel=\"alternate\" type=\"text/html\" href=\"".
					htmlspecialchars($this->items[$i]->link)."\"/>\n";
			if($this->items[$i]->date == "") {
				$this->items[$i]->date = time();
			}
			$itemDate = new FeedDate($this->items[$i]->date);
			$feed .= "		<created>".htmlspecialchars($itemDate->iso8601())."</created>\n";
			$feed .= "		<issued>".htmlspecialchars($itemDate->iso8601())."</issued>\n";
			$feed .= "		<modified>".htmlspecialchars($itemDate->iso8601())."</modified>\n";
			$feed .= "		<id>".htmlspecialchars($this->items[$i]->link)."</id>\n";
			$feed .= $this->_createAdditionalElements($this->items[$i]->additionalElements,
					"		");
			if($this->items[$i]->author != "") {
				$feed .= "		<author>\n";
				$feed .= "			<name>".htmlspecialchars($this->items[$i]->author)."</name>\n";
				$feed .= "		</author>\n";
			}
			if($this->items[$i]->description != "") {
				$feed .= "		<summary>".htmlspecialchars($this->items[$i]->description).
						"</summary>\n";
			}
			$feed .= "	</entry>\n";
		}
		$feed .= "</feed>\n";
		return $feed;
	}
}
class MBOXCreator extends FeedCreator {
	function MBOXCreator() {
		$this->contentType = "text/plain";
		$this->encoding = "UTF-8";
	}
	function qp_enc($input = "",$line_max = 76) {
		$hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
		$lines = preg_split("/(?:\r\n|\r|\n)/",$input);
		$eol = "\r\n";
		$escape = "=";
		$output = "";
		while(list(,$line) = each($lines)) {
			$linlen = strlen($line);
			$newline = "";
			for($i = 0; $i < $linlen; $i++) {
				$c = substr($line,$i,1);
				$dec = ord($c);
				if(($dec == 32) && ($i == ($linlen - 1))) {
					$c = "=20";
				} elseif(($dec == 61) || ($dec < 32) || ($dec > 126)) {
					$h2 = floor($dec / 16);
					$h1 = floor($dec % 16);
					$c = $escape.$hex["$h2"].$hex["$h1"];
				}
				if((strlen($newline) + strlen($c)) >= $line_max) {
					$output .= $newline.$escape.$eol;
					$newline = "";
				}
				$newline .= $c;
			}
			$output .= $newline.$eol;
		}
		return trim($output);
	}
	function createFeed() {
		for($i = 0; $i < count($this->items); $i++) {
			if($this->items[$i]->author != "") {
				$from = $this->items[$i]->author;
			} else {
				$from = $this->title;
			}
			$itemDate = new FeedDate($this->items[$i]->date);
			$feed .= "From ".strtr(MBOXCreator::qp_enc($from)," ","_")." ".date("D M d H:i:s Y",
					$itemDate->unix())."\n";
			$feed .= "Content-Type: text/plain;\n";
			$feed .= "	charset=\"".$this->encoding."\"\n";
			$feed .= "Content-Transfer-Encoding: quoted-printable\n";
			$feed .= "Content-Type: text/plain\n";
			$feed .= "From: \"".MBOXCreator::qp_enc($from)."\"\n";
			$feed .= "Date: ".$itemDate->rfc822()."\n";
			$feed .= "Subject: ".MBOXCreator::qp_enc(FeedCreator::iTrunc($this->items[$i]->title,
					100))."\n";
			$feed .= "\n";
			$body = chunk_split(MBOXCreator::qp_enc($this->items[$i]->description));
			$feed .= preg_replace("~\nFrom ([^\n]*)(\n?)~","\n>From $1$2\n",$body);
			$feed .= "\n";
			$feed .= "\n";
		}
		return $feed;
	}
	function _generateFilename() {
		$fileInfo = pathinfo($_SERVER["PHP_SELF"]);
		return substr($fileInfo["basename"],0,-(strlen($fileInfo["extension"]) + 1)).
				".mbox";
	}
}
class OPMLCreator extends FeedCreator {
	function OPMLCreator() {
		$this->encoding = "utf-8";
	}
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= $this->_createGeneratorComment();
		$feed .= $this->_createStylesheetReferences();
		$feed .= "<opml xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n";
		$feed .= "	<head>\n";
		$feed .= "		<title>".htmlspecialchars($this->title)."</title>\n";
		if($this->pubDate != "") {
			$date = new FeedDate($this->pubDate);
			$feed .= "		 <dateCreated>".$date->rfc822()."</dateCreated>\n";
		}
		if($this->lastBuildDate != "") {
			$date = new FeedDate($this->lastBuildDate);
			$feed .= "		 <dateModified>".$date->rfc822()."</dateModified>\n";
		}
		if($this->editor != "") {
			$feed .= "		 <ownerName>".$this->editor."</ownerName>\n";
		}
		if($this->editorEmail != "") {
			$feed .= "		 <ownerEmail>".$this->editorEmail."</ownerEmail>\n";
		}
		$feed .= "	</head>\n";
		$feed .= "	<body>\n";
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "	<outline type=\"rss\" ";
			$title = htmlspecialchars(strip_tags(strtr($this->items[$i]->title,"\n\r","  ")));
			$feed .= " title=\"".$title."\"";
			$feed .= " text=\"".$title."\"";
			$feed .= " url=\"".htmlspecialchars($this->items[$i]->link)."\"";
			$feed .= "/>\n";
		}
		$feed .= "	</body>\n";
		$feed .= "</opml>\n";
		return $feed;
	}
}
class HTMLCreator extends FeedCreator {
	var $contentType = "text/html";
	var $header;
	var $footer;
	var $separator;
	var $stylePrefix;
	var $openInNewWindow = true;
	var $imageAlign = "right";
	var $stylelessOutput = "";
	function createFeed() {
		if($this->stylelessOutput != "") {
			return $this->stylelessOutput;
		}
		if($this->stylePrefix == "") {
			$this->stylePrefix = str_replace(".","_",$this->_generateFilename())."_";
		}
		if($this->openInNewWindow) {
			$targetInsert = " target='_blank'";
		}
		$feedArray = array();
		if($this->image != null) {
			$imageStr = "<a href='".$this->image->link."'".$targetInsert.">"."<img src='".$this->image->url."' border='0' alt='".FeedCreator::iTrunc(htmlspecialchars($this->image->title),100)."' align='".$this->imageAlign."' ";
			if($this->image->width) {
				$imageStr .= " width='".$this->image->width."' ";
			}
			if($this->image->height) {
				$imageStr .= " height='".$this->image->height."' ";
			}
			$imageStr .= "/></a>";
			$feedArray[] = $imageStr;
		}
		if($this->title) {
			$feedArray[] = "<div class='".$this->stylePrefix."title'><a href='".$this->link."' ".$targetInsert." class='".$this->stylePrefix."title'>".FeedCreator::iTrunc(htmlspecialchars($this->title),100)."</a></div>";
		}
		if($this->getDescription()) {
			$feedArray[] = "<div class='".$this->stylePrefix."description'>".str_replace("]]>","",str_replace("<![CDATA[","",$this->getDescription()))."</div>";
		}
		if($this->header) {
			$feedArray[] = "<div class='".$this->stylePrefix."header'>".$this->header."</div>";
		}
		for($i = 0; $i < count($this->items); $i++) {
			if($this->separator and $i > 0) {
				$feedArray[] = "<div class='".$this->stylePrefix."separator'>".$this->separator."</div>";
			}
			if($this->items[$i]->title) {
				if($this->items[$i]->link) {
					$feedArray[] = "<div class='".$this->stylePrefix."item_title'><a href='".$this->items[$i]->link."' class='".$this->stylePrefix."item_title'".$targetInsert.">".FeedCreator::iTrunc(htmlspecialchars(strip_tags($this->items[$i]->title)),100)."</a></div>";
				} else {
					$feedArray[] = "<div class='".$this->stylePrefix."item_title'>".FeedCreator::iTrunc(htmlspecialchars(strip_tags($this->items[$i]->title)),100)."</div>";
				}
			}
			if($this->items[$i]->getDescription()) {
				$feedArray[] = "<div class='".$this->stylePrefix."item_description'>".str_replace("]]>","",str_replace("<![CDATA[","",$this->items[$i]->getDescription()))."</div>";
			}
		}
		if($this->footer) {
			$feedArray[] = "<div class='".$this->stylePrefix."footer'>".$this->footer."</div>";
		}
		$feed = "".join($feedArray,"\r\n");
		return $feed;
	}
	function _generateFilename() {
		$fileInfo = pathinfo($_SERVER["PHP_SELF"]);
		return substr($fileInfo["basename"],0,-(strlen($fileInfo["extension"]) + 1)).".html";
	}
}
class JSCreator extends HTMLCreator {
	var $contentType = "text/javascript";
	function createFeed() {
		$feed = parent::createFeed();
		$feedArray = explode("\n",$feed);
		$jsFeed = "";
		foreach($feedArray as $value) {
			$jsFeed .= "document.write('".trim(addslashes($value))."');\n";
		}
		return $jsFeed;
	}
	function _generateFilename() {
		$fileInfo = pathinfo($_SERVER["PHP_SELF"]);
		return substr($fileInfo["basename"],0,-(strlen($fileInfo["extension"]) + 1)).'.js';
	}
}
class GoogleSiteMapIndex extends FeedCreator {
	function createFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$feed .= "<sitemapindex xmlns=\"http://www.google.com/schemas/sitemap/0.84\"\n";
		$feed .= "  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
		$feed .= "  xsi:schemaLocation=\"http://www.google.com/schemas/sitemap/0.84\n";
		$feed .= "  http://www.google.com/schemas/sitemap/0.84/siteindex.xsd\">\n";
		$total = count($this->items);
		for($i = 0; $i < $total; $i++) {
			$feed .= "  <sitemap>\n";
			$feed .= "<loc>".htmlspecialchars($this->items[$i]->link)."</loc>\n";
			if($this->items[$i]->date != "") {
				$itemDate = new FeedDate($this->items[$i]->date);
				$feed .= "	<lastmod>".htmlspecialchars($itemDate->iso8601())."</lastmod>\n";
			}
			$feed .= "  </sitemap>\n";
		}
		$feed .= "</sitemapindex>\n";
		return $feed;
	}
}
class Yandex extends FeedCreator {
	function createFeed() {
		global $mosConfig_sef;
		$feed = "<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n";
		$feed .= "<rss version=\"2.0\"\n";
		$feed .= "xmlns=\"http://backend.userland.com/rss2\"\n";
		$feed .= "xmlns:yandex=\"http://news.yandex.ru\">\n";
		$feed .= "<channel>\n";
		$feed .= "<title>".FeedCreator::iTrunc(htmlspecialchars($this->title),100)."</title>\n";
		$this->descriptionTruncSize = 500;
		$feed .= "<description>".$this->getDescription()."</description>\n";
		$feed .= "<link>".$this->link."</link>\n";
		//$now = new FeedDate();
		if($this->image != null) {
			$feed .= "<image>\n";
			$feed .= "<url>".$this->image->url."</url>\n";
			$feed .= "<title>".FeedCreator::iTrunc(htmlspecialchars($this->image->title),100)."</title>\n";
			$feed .= "<link>".$this->image->link."</link>\n";
			$feed .= "</image>\n";
		}
		if($this->pubDate != "") {
			$pubDate = new FeedDate($this->pubDate);
			$feed .= "<pubDate>".htmlspecialchars($pubDate->rfc822_j())."</pubDate>\n";
		}
		if($this->category != "") {
			$feed .= "<category>".htmlspecialchars($this->category)."</category>\n";
		}
		for($i = 0; $i < count($this->items); $i++) {
			$feed .= "<item>\n";
			$feed .= "<title>".FeedCreator::iTrunc(htmlspecialchars(strip_tags($this->items[$i]->title)),100)."</title>\n";
			// при отключенном SEF допишем к адресу ленты полный путь от корня
			//if(!$mosConfig_sef) $this->items[$i]->link = JPATH_SITE.'/'.$this->items[$i]->link;
			$feed .= "<link>".htmlspecialchars($this->items[$i]->link)."</link>\n";
			$feed .= "<description>".$this->items[$i]->getDescription()."</description>\n";
			if($this->items[$i]->author != "") {
				$feed .= "<author>".htmlspecialchars($this->items[$i]->author)."</author>\n";
			}
			if($this->items[$i]->category != "") {
				$feed .= "<category>".htmlspecialchars($this->items[$i]->category)."</category>\n";
			}
			if(is_array(@$this->items[$i]->images)) {
				foreach($this->items[$i]->images as $image) {
					if(function_exists('getimagesize')) {
						$type = @getimagesize(JPATH_BASE.$image);
					} else {
						$type = array();
					}
					$feed .= "<enclosure url=\"".JPATH_SITE.$image."\" type=\"".$type['mime']."\"/>\n";
				}
			}
			if($this->items[$i]->date != "") {
				$itemDate = new FeedDate($this->items[$i]->date);
				$feed .= "<pubDate>".htmlspecialchars($itemDate->rfc822())."</pubDate>\n";
			}
			if($this->items[$i]->fulltext != "") {
				$feed .= "<yandex:full-text>".$this->items[$i]->fulltext."</yandex:full-text>\n";
			}
			$feed .= "</item>\n";
		}
		$feed .= "</channel>\n";
		$feed .= "</rss>\n";
		return $feed;
	}
}