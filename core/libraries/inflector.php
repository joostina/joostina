<?php

/**
 * @todo адаптировать, продокументировать
 */
final class Inflector 
{
    /**
     *  Return an CamelizeSyntaxed (LikeThisDearReader) from something like_this_dear_reader.
     *
     * @param string $string Word to camelize
     * @return string Camelized word. LikeThis.
     */
    public static function camelize($string)
    {
        return str_replace(' ','',ucwords(str_replace('_',' ', $string)));
    }

    /**
     * Return an underscore_syntaxed (like_this_dear_reader) from something LikeThisDearReader.
     *
     * @param  string $string CamelCased word to be "underscorized"
     * @return string Underscored version of the $string
     */
    public static function underscore($string)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
    }
    
    /**
     * Return a Humanized syntaxed (Like this dear reader) from something like_this_dear_reader.
     *
     * @param  string $string CamelCased word to be "underscorized"
     * @return string Underscored version of the $string
     */
    public static function humanize($string)
    {
        return ucfirst(str_replace('_', ' ', $string));
    }
}