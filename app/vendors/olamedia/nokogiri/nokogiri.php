<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

require __DIR__ . '/lib_nokogiri.php';

class joosParser extends nokogiri {

	protected function getXpathSubquery( $expression ) {
		$query = '';
		if ( preg_match( "/(?P<tag>[a-z0-9]+)?(\[(?P<attr>\S+)=(?P<value>\S+)\])?(#(?P<id>\S+))?(\.(?P<class>\S+))?/ims" , $expression , $subs ) ) {
			$tag = $subs['tag'];
			//$id = $subs['id'];
			//$attr = $subs['attr'];
			//$attrValue = $subs['value'];
			//$class = $subs['class'];

			if ( !strlen( $tag ) ) {
				$tag = '*';
			}
			$query = '//' . $tag;
			if ( isset( $subs['id'] ) && ( $id = $subs['id'] ) && strlen( $id ) ) {
				$query .= "[@id='" . $id . "']";
			}
			if ( isset( $subs['attr'] ) && ( $attr = $subs['attr'] ) && strlen( $attr ) && isset( $subs['value'] ) ) {
				$attrValue = $subs['value'];
				$query .= "[@" . $attr . "='" . $attrValue . "']";
			}
			if ( isset( $subs['class'] ) && ( $class = $subs['class'] ) && strlen( $class ) ) {
				//$query .= "[@class='".$class."']";
				$query .= '[contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")]';
			}
		}
		return $query;
	}

}