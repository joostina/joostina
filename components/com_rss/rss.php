<?php
/**
 */

defined('_VALID_MOS') or die();

mosMainFrame::addLib('feedcreator');
mosMainFrame::addLib('text');

$content = mosGetParam($_REQUEST, 'content', '');

rssStok::feed($content);

/*
switch ($task) {
	case 'live_bookmark':
		rssStok::feed($id, false );
		break;

	default:
		rssStok::feed($id, true );
		break;
}
*/

class rssStok {
	function getParameters () {
		$params->id = 1;
		$params->catids = $feed->catids;
		$params->published = $feed->published;
		if (method_exists($database,"getNullDate")) {
			$params->nullDate 	= $database->getNullDate();
		} else {
			$params->nullDate = '0000-00-00 00:00:00';
		}		
		$params->now 		= date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60 );
		$iso 		= split( '=', _ISO );
		
		// parameter intilization
		$params->date			= date( 'r' );
		$params->year			= date( 'Y' );
		$params->encoding		= 'UTF-8';
		$params->link			= htmlspecialchars( JPATH_SITE );
		$params->cache			= $params->def( 'cache', 1 );
		$params->cache_time		= $params->def( 'cache_time', 3600 );
		$params->count			= $params->def( 'count', 5 );
		$params->orderby		= $params->def( 'orderby', '' );
		$params->title			= $params->def( 'title', '' );
		$params->description	= $params->def( 'description', '' );
		$params->image_file		= $params->def( 'image_file', '' );
		if ( $params->image_file  == -1 ) {
			$params->image	= NULL;
		} else{
			$params->image	= JPATH_SITE .'/images/stories/'. $params->image_file;
		}
		$params->image_alt		= $params->def( 'image_alt', '' );
		$params->limit_text		= $params->def( 'limit_text', 1 );
		$params->text_length	= $params->def( 'text_length', 20 );
		// get feed type from url
		$params->feed			= mosGetParam($_GET, 'feed', 'RSS2.0');
		// live bookmarks
		$params->live_bookmark	= $params->def( 'live_bookmark', '' );

		return $params;
	}	

	function getData ($content) {

            return $rows;
	}
	
	
	function feed($content) {
		$mainframe = &mosMainFrame::getInstance();

//		$params = rssStok::getParameters($id);
/*
		// set filename for live bookmarks feed
		if ( !$showFeed & $params->live_bookmark ) {
			// standard bookmark filename
			$params->file = $mainframe->config->config_cachepath.DS. $params->live_bookmark . '_' . $id;
		} else {
		// set filename for rss feeds
			$params->file = strtolower( str_replace( '.', '', $params->feed ) );
			$params->file = $mainframe->config->config_cachepath.DS. $params->file. '_' .$id .'.xml';
		}
*/
/*
 			<title></title>
			<guid isPermaLink="true"></guid>
			<link></link>
			<description></description>
			<pubDate></pubDate>
			<author></author>
			<category></category><category></category>
 */

/*
<title></title>
<link></link>
<description></description>
<category></category>
<enclosure url="http://joo/images/stories/asterisk.jpg" type="image/jpeg"/>
<pubDate></pubDate>
<yandex:full-text></yandex:full-text>
*/
                $params->feed = mosGetParam($_GET, 'feed', 'RSS2.0');
                $params->file = strtolower(str_replace('.', '', $params->feed));
                $params->file = $mainframe->config->config_cachepath . DS . $params->file . '_' . $content . '.xml';

		// load feed creator class
		$rss = new UniversalFeedCreator();
		// load image creator class
		$image = new FeedImage();
/*
		// loads cache file
		if ($showFeed && $params->cache) {
			$rss->useCached( $params->feed, $params->file, $params->cache_time );
		}
*/
		$rss->title = 'Лучшие в мире новости!';
		$rss->description = 'Какое-то пояснение для тех, кто не понял заголовок';
		$rss->link = 'http://site';
		$rss->syndicationURL = 'http://site';
		$rss->cssStyleSheet = NULL;
		$rss->encoding = 'UTF-8';
	
		if (0/*нужна картинка?*/) {
			$image->url = 'http://куда будет посылать';
			$image->link = 'http://мегакартинки';
			$image->title = 'подпись к ней';
			$image->description  = 'описание';
			// loads image info into rss array
			$rss->image 		= $image; //под вопросом
		}

                //добываем данные для RSS, component.rss.php заботливо сложит их в $rows
                $fname = 'components/com_' . $content . '/' . $content . '.rss.php';
                if (!file_exists($fname)) {
                    return 0;
                }
                require_once $fname;

                if (count($rows)) {
			foreach ($rows as $row) {
				// title for particular item
				$item_title = htmlspecialchars($row->title);
				$item_title = html_entity_decode($item_title);
		
				// url link to article
				// & used instead of &amp; as this is converted by feed creator
				$item_link = 'index.php?option=com_topic&task=view&id='. $row->id;
				$item_link = sefRelToAbs($item_link);
		
				// removes all formating from the intro text for the description text
				$item_description = $row->anons;
				$item_description = Text::cleanText($item_description);
				$item_description = html_entity_decode($item_description);

				// load individual item creator class
				$item = new FeedItem();
				// item info
				$item->title 		= $item_title;
				$item->link 		= $item_link;
				$item->description 	= $item_description;
				$item->source 		= JPATH_SITE;
				$item->date		= date('r', $row->created_ts);
				$item->category		= $content; //заголовок

				// yandex export
				$item->fulltext = $row->text;
				$item->fulltext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $item->fulltext);
				$item->fulltext = htmlspecialchars(strip_tags($item->fulltext));
				$item->fulltext = str_replace("'", "&apos;", $item->fulltext);

        			$item->images = array();
/*
				if ($row->images) {
					$item->images = array();
					$row->images = explode( "\n", $row->images );
					foreach ($row->images as $img) {
						$img = trim( $img );
						if ($img) {
							$temp = explode('|', trim($img));
							$item->images[] = '/images/stories/'. $temp[0];
						}
					}
				}
*/
				/*
				if ($row->images) {
					$item->images = array();
					$row->images = explode( "\n", $row->images );
					foreach ($row->images as $img) {
						$img = trim( $img );
						if ($img) {
							$temp = explode( '|', trim( $img ) );
							$item->images[] = '/images/stories/'. $temp[0];
						}
					}
				}
				*/
				// meta export

				// loads item info into rss array
				$rss->addItem($item);
			}
		}
		//save feed file
		$rss->saveFeed($params->feed, $params->file);
	}
}
function video() {
    $database = &database::getInstance();

    $query = "SELECT n.*, n.desc AS anons, n.desc AS text, UNIX_TIMESTAMP(n.created_at) AS created_ts" .
        "\n FROM #__video AS n" .
        "\n LEFT JOIN #__users AS u ON u.id = n.user_id" .
        "\n WHERE n.state = 1" .
        "\n ORDER BY n.id" .
        "\n LIMIT 10";

    $database->setQuery($query);

    return $database->loadObjectList();
}