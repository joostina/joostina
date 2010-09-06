<?php

/* From MVC perspective this is our "Model"
 * News array, by year, month and date
 * Just imagine we fetch these from database...
 */

function news_item($year, $month, $day) {
    static $data = array(
'2008' => array(
    '01' => array('01' => array('title' => 'New Year', 'body' => 'Happy New Year!')),
    '02' => array('26' => array('title' => 'Title26', 'body' => 'body26')),
),
    );

    if (!empty($data[$year][$month][$day])) {
        return $data[$year][$month][$day];
    } else {
        return NULL;
    }
}

/* Controller class */

class News {

    public static function show($args) {
        $item = news_item($args['year'], $args['month'], $args['day']);

        if ($item) {
            print("<h2>" . $item['title'] . "</h2>");
            print("<div>" . $item['body'] . "</div>");
        } else {
            print("<b>Error:</b> News item not found");
        }
    }

    public static function show_year($args) {
        print("Hello from show_year()");
    }

    public static function show_title($args) {
        print("Hello from show_title()");
    }

}
