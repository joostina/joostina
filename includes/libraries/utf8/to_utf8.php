<?php
/**
* @package Joostina
* @copyright ��������� ����� (C) 2007-2010 Joostina team. ��� ����� ��������.
* @license �������� http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, ��� help/license.php
* Joostina! - ��������� ����������� ����������� ���������������� �� �������� �������� GNU/GPL
* ��� ��������� ���������� � ������������ ����������� � ��������� �� ��������� �����, �������� ���� help/copyright.php.
*/

// ������ ������� �������
defined('_JOOS_CORE') or die();

/**
 * ������������ ����� �� ��������� cp1259 (windows-1259) � ��������� UTF-8
 * cp1259 - ��� ���������� ������������ ��������� ���������� �����, ������� �������� � ���� ��� ������� ����� �� cp1251.
 *
 * @param    string  $str
 * @return   string
 * @link     http://search.cpan.org/CPAN/authors/id/A/AM/AMICHAUER/Lingua-TT-Yanalif-0.08.tar.gz
 * @link     http://www.unicode.org/charts/PDF/U0400.pdf
 *
 * @license  http://creativecommons.org/licenses/by-sa/3.0/
 * @author   Nasibullin Rinat <nasibullin at starlink ru>
 * @charset  ANSI
 * @version  1.0.2
 */
function cp1259_to_utf8(&$str)
{
    #This table contains the data on how cp1259 characters map into Unicode (UTF-8).
    #The cp1259 map describes standart tatarish cyrillic charset and based on the cp1251 table.
    static $trans = array(
        #�� 0x00 �� 0x7F (ASCII) ����� ����������� ��� ����
        "\x80" => "\xd3\x98",      #0x04d8 CYRILLIC CAPITAL LETTER SCHWA
        "\x81" => "\xd0\x83",      #0x0403 CYRILLIC CAPITAL LETTER GJE
        "\x82" => "\xe2\x80\x9a",  #0x201a SINGLE LOW-9 QUOTATION MARK
        "\x83" => "\xd1\x93",      #0x0453 CYRILLIC SMALL LETTER GJE
        "\x84" => "\xe2\x80\x9e",  #0x201e DOUBLE LOW-9 QUOTATION MARK
        "\x85" => "\xe2\x80\xa6",  #0x2026 HORIZONTAL ELLIPSIS
        "\x86" => "\xe2\x80\xa0",  #0x2020 DAGGER
        "\x87" => "\xe2\x80\xa1",  #0x2021 DOUBLE DAGGER
        "\x88" => "\xe2\x82\xac",  #0x20ac EURO SIGN
        "\x89" => "\xe2\x80\xb0",  #0x2030 PER MILLE SIGN
        "\x8a" => "\xd3\xa8",      #0x04e8 CYRILLIC CAPITAL LETTER BARRED O
        "\x8b" => "\xe2\x80\xb9",  #0x2039 SINGLE LEFT-POINTING ANGLE QUOTATION MARK
        "\x8c" => "\xd2\xae",      #0x04ae CYRILLIC CAPITAL LETTER STRAIGHT U
        "\x8d" => "\xd2\x96",      #0x0496 CYRILLIC CAPITAL LETTER ZHE WITH DESCENDER
        "\x8e" => "\xd2\xa2",      #0x04a2 CYRILLIC CAPITAL LETTER EN WITH HOOK
        "\x8f" => "\xd2\xba",      #0x04ba CYRILLIC CAPITAL LETTER SHHA
        "\x90" => "\xd3\x99",      #0x04d9 CYRILLIC SMALL LETTER SCHWA
        "\x91" => "\xe2\x80\x98",  #0x2018 LEFT SINGLE QUOTATION MARK
        "\x92" => "\xe2\x80\x99",  #0x2019 RIGHT SINGLE QUOTATION MARK
        "\x93" => "\xe2\x80\x9c",  #0x201c LEFT DOUBLE QUOTATION MARK
        "\x94" => "\xe2\x80\x9d",  #0x201d RIGHT DOUBLE QUOTATION MARK
        "\x95" => "\xe2\x80\xa2",  #0x2022 BULLET
        "\x96" => "\xe2\x80\x93",  #0x2013 EN DASH
        "\x97" => "\xe2\x80\x94",  #0x2014 EM DASH
        #"\x98"                    #UNDEFINED
        "\x99" => "\xe2\x84\xa2",  #0x2122 TRADE MARK SIGN
        "\x9a" => "\xd3\xa9",      #0x04e9 CYRILLIC SMALL LETTER BARRED O
        "\x9b" => "\xe2\x80\xba",  #0x203a SINGLE RIGHT-POINTING ANGLE QUOTATION MARK
        "\x9c" => "\xd2\xaf",      #0x04af CYRILLIC SMALL LETTER STRAIGHT U
        "\x9d" => "\xd2\x97",      #0x0497 CYRILLIC SMALL LETTER ZHE WITH DESCENDER
        "\x9e" => "\xd2\xa3",      #0x04a3 CYRILLIC SMALL LETTER EN WITH HOOK
        "\x9f" => "\xd2\xbb",      #0x04bb CYRILLIC SMALL LETTER SHHA
        "\xa0" => "\xc2\xa0",      #0x00a0 NO-BREAK SPACE
        "\xa1" => "\xd0\x8e",      #0x040e CYRILLIC CAPITAL LETTER SHORT U
        "\xa2" => "\xd1\x9e",      #0x045e CYRILLIC SMALL LETTER SHORT U
        "\xa3" => "\xd0\x88",      #0x0408 CYRILLIC CAPITAL LETTER JE
        "\xa4" => "\xc2\xa4",      #0x00a4 CURRENCY SIGN
        "\xa5" => "\xd2\x90",      #0x0490 CYRILLIC CAPITAL LETTER GHE WITH UPTURN
        "\xa6" => "\xc2\xa6",      #0x00a6 BROKEN BAR
        "\xa7" => "\xc2\xa7",      #0x00a7 SECTION SIGN
        "\xa8" => "\xd0\x81",      #0x0401 CYRILLIC CAPITAL LETTER IO
        "\xa9" => "\xc2\xa9",      #0x00a9 COPYRIGHT SIGN
        "\xaa" => "\xd0\x84",      #0x0404 CYRILLIC CAPITAL LETTER UKRAINIAN IE
        "\xab" => "\xc2\xab",      #0x00ab LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
        "\xac" => "\xc2\xac",      #0x00ac NOT SIGN
        "\xad" => "\xc2\xad",      #0x00ad SOFT HYPHEN
        "\xae" => "\xc2\xae",      #0x00ae REGISTERED SIGN
        "\xaf" => "\xd0\x87",      #0x0407 CYRILLIC CAPITAL LETTER YI
        "\xb0" => "\xc2\xb0",      #0x00b0 DEGREE SIGN
        "\xb1" => "\xc2\xb1",      #0x00b1 PLUS-MINUS SIGN
        "\xb2" => "\xd0\x86",      #0x0406 CYRILLIC CAPITAL LETTER BYELORUSSIAN-UKRAINIAN I
        "\xb3" => "\xd1\x96",      #0x0456 CYRILLIC SMALL LETTER BYELORUSSIAN-UKRAINIAN I
        "\xb4" => "\xd2\x91",      #0x0491 CYRILLIC SMALL LETTER GHE WITH UPTURN
        "\xb5" => "\xc2\xb5",      #0x00b5 MICRO SIGN
        "\xb6" => "\xc2\xb6",      #0x00b6 PILCROW SIGN
        "\xb7" => "\xc2\xb7",      #0x00b7 MIDDLE DOT
        "\xb8" => "\xd1\x91",      #0x0451 CYRILLIC SMALL LETTER IO
        "\xb9" => "\xe2\x84\x96",  #0x2116 NUMERO SIGN
        "\xba" => "\xd1\x94",      #0x0454 CYRILLIC SMALL LETTER UKRAINIAN IE
        "\xbb" => "\xc2\xbb",      #0x00bb RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
        "\xbc" => "\xd1\x98",      #0x0458 CYRILLIC SMALL LETTER JE
        "\xbd" => "\xd0\x85",      #0x0405 CYRILLIC CAPITAL LETTER DZE
        "\xbe" => "\xd1\x95",      #0x0455 CYRILLIC SMALL LETTER DZE
        "\xbf" => "\xd1\x97",      #0x0457 CYRILLIC SMALL LETTER YI
        "\xc0" => "\xd0\x90",      #0x0410 CYRILLIC CAPITAL LETTER A
        "\xc1" => "\xd0\x91",      #0x0411 CYRILLIC CAPITAL LETTER BE
        "\xc2" => "\xd0\x92",      #0x0412 CYRILLIC CAPITAL LETTER VE
        "\xc3" => "\xd0\x93",      #0x0413 CYRILLIC CAPITAL LETTER GHE
        "\xc4" => "\xd0\x94",      #0x0414 CYRILLIC CAPITAL LETTER DE
        "\xc5" => "\xd0\x95",      #0x0415 CYRILLIC CAPITAL LETTER IE
        "\xc6" => "\xd0\x96",      #0x0416 CYRILLIC CAPITAL LETTER ZHE
        "\xc7" => "\xd0\x97",      #0x0417 CYRILLIC CAPITAL LETTER ZE
        "\xc8" => "\xd0\x98",      #0x0418 CYRILLIC CAPITAL LETTER I
        "\xc9" => "\xd0\x99",      #0x0419 CYRILLIC CAPITAL LETTER SHORT I
        "\xca" => "\xd0\x9a",      #0x041a CYRILLIC CAPITAL LETTER KA
        "\xcb" => "\xd0\x9b",      #0x041b CYRILLIC CAPITAL LETTER EL
        "\xcc" => "\xd0\x9c",      #0x041c CYRILLIC CAPITAL LETTER EM
        "\xcd" => "\xd0\x9d",      #0x041d CYRILLIC CAPITAL LETTER EN
        "\xce" => "\xd0\x9e",      #0x041e CYRILLIC CAPITAL LETTER O
        "\xcf" => "\xd0\x9f",      #0x041f CYRILLIC CAPITAL LETTER PE
        "\xd0" => "\xd0\xa0",      #0x0420 CYRILLIC CAPITAL LETTER ER
        "\xd1" => "\xd0\xa1",      #0x0421 CYRILLIC CAPITAL LETTER ES
        "\xd2" => "\xd0\xa2",      #0x0422 CYRILLIC CAPITAL LETTER TE
        "\xd3" => "\xd0\xa3",      #0x0423 CYRILLIC CAPITAL LETTER U
        "\xd4" => "\xd0\xa4",      #0x0424 CYRILLIC CAPITAL LETTER EF
        "\xd5" => "\xd0\xa5",      #0x0425 CYRILLIC CAPITAL LETTER HA
        "\xd6" => "\xd0\xa6",      #0x0426 CYRILLIC CAPITAL LETTER TSE
        "\xd7" => "\xd0\xa7",      #0x0427 CYRILLIC CAPITAL LETTER CHE
        "\xd8" => "\xd0\xa8",      #0x0428 CYRILLIC CAPITAL LETTER SHA
        "\xd9" => "\xd0\xa9",      #0x0429 CYRILLIC CAPITAL LETTER SHCHA
        "\xda" => "\xd0\xaa",      #0x042a CYRILLIC CAPITAL LETTER HARD SIGN
        "\xdb" => "\xd0\xab",      #0x042b CYRILLIC CAPITAL LETTER YERU
        "\xdc" => "\xd0\xac",      #0x042c CYRILLIC CAPITAL LETTER SOFT SIGN
        "\xdd" => "\xd0\xad",      #0x042d CYRILLIC CAPITAL LETTER E
        "\xde" => "\xd0\xae",      #0x042e CYRILLIC CAPITAL LETTER YU
        "\xdf" => "\xd0\xaf",      #0x042f CYRILLIC CAPITAL LETTER YA
        "\xe0" => "\xd0\xb0",      #0x0430 CYRILLIC SMALL LETTER A
        "\xe1" => "\xd0\xb1",      #0x0431 CYRILLIC SMALL LETTER BE
        "\xe2" => "\xd0\xb2",      #0x0432 CYRILLIC SMALL LETTER VE
        "\xe3" => "\xd0\xb3",      #0x0433 CYRILLIC SMALL LETTER GHE
        "\xe4" => "\xd0\xb4",      #0x0434 CYRILLIC SMALL LETTER DE
        "\xe5" => "\xd0\xb5",      #0x0435 CYRILLIC SMALL LETTER IE
        "\xe6" => "\xd0\xb6",      #0x0436 CYRILLIC SMALL LETTER ZHE
        "\xe7" => "\xd0\xb7",      #0x0437 CYRILLIC SMALL LETTER ZE
        "\xe8" => "\xd0\xb8",      #0x0438 CYRILLIC SMALL LETTER I
        "\xe9" => "\xd0\xb9",      #0x0439 CYRILLIC SMALL LETTER SHORT I
        "\xea" => "\xd0\xba",      #0x043a CYRILLIC SMALL LETTER KA
        "\xeb" => "\xd0\xbb",      #0x043b CYRILLIC SMALL LETTER EL
        "\xec" => "\xd0\xbc",      #0x043c CYRILLIC SMALL LETTER EM
        "\xed" => "\xd0\xbd",      #0x043d CYRILLIC SMALL LETTER EN
        "\xee" => "\xd0\xbe",      #0x043e CYRILLIC SMALL LETTER O
        "\xef" => "\xd0\xbf",      #0x043f CYRILLIC SMALL LETTER PE
        "\xf0" => "\xd1\x80",      #0x0440 CYRILLIC SMALL LETTER ER
        "\xf1" => "\xd1\x81",      #0x0441 CYRILLIC SMALL LETTER ES
        "\xf2" => "\xd1\x82",      #0x0442 CYRILLIC SMALL LETTER TE
        "\xf3" => "\xd1\x83",      #0x0443 CYRILLIC SMALL LETTER U
        "\xf4" => "\xd1\x84",      #0x0444 CYRILLIC SMALL LETTER EF
        "\xf5" => "\xd1\x85",      #0x0445 CYRILLIC SMALL LETTER HA
        "\xf6" => "\xd1\x86",      #0x0446 CYRILLIC SMALL LETTER TSE
        "\xf7" => "\xd1\x87",      #0x0447 CYRILLIC SMALL LETTER CHE
        "\xf8" => "\xd1\x88",      #0x0448 CYRILLIC SMALL LETTER SHA
        "\xf9" => "\xd1\x89",      #0x0449 CYRILLIC SMALL LETTER SHCHA
        "\xfa" => "\xd1\x8a",      #0x044a CYRILLIC SMALL LETTER HARD SIGN
        "\xfb" => "\xd1\x8b",      #0x044b CYRILLIC SMALL LETTER YERU
        "\xfc" => "\xd1\x8c",      #0x044c CYRILLIC SMALL LETTER SOFT SIGN
        "\xfd" => "\xd1\x8d",      #0x044d CYRILLIC SMALL LETTER E
        "\xfe" => "\xd1\x8e",      #0x044e CYRILLIC SMALL LETTER YU
        "\xff" => "\xd1\x8f",      #0x044f CYRILLIC SMALL LETTER YA
    );
    #str_replace() ������������ ������ �.�. ����� � �������� ������� ������������� ����� ������ �����!
    return strtr($str, $trans);
}

?>
