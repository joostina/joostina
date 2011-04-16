<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 * License GNU/GPL - Vincent Blavet - Janvier 2001
 * http://www.phpconcept.net & http://phpconcept.free.fr
 **/
defined('_JOOS_CORE') or die();
if (!defined("PCL_TAR")) {
    define("PCL_TAR", 1);
    if (!isset($g_pcltar_lib_dir))
        $g_pcltar_lib_dir = "lib";
    $g_pcltar_version = "1.3";
    $g_pcltar_extension = substr(strrchr(basename(@$_SERVER["PATH_TRANSLATED"]), '.'),
                                 1);
    if (!defined("PCLERROR_LIB")) {
        include ($g_pcltar_lib_dir . "/pclerror.lib." . $g_pcltar_extension);
    }
    if (!defined("PCLTRACE_LIB")) {
        include ($g_pcltar_lib_dir . "/pcltrace.lib." . $g_pcltar_extension);
    }
    function PclTarCreate($p_tarname, $p_filelist = "", $p_mode = "", $p_add_dir = "",
        $p_remove_dir = "")
    {
        TrFctStart(__file__, __line__, "PclTarCreate", "tar=$p_tarname, file='$p_filelist', mode=$p_mode, add_dir='$p_add_dir', remove_dir='$p_remove_dir'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            TrFctMessage(__file__, __line__, 1, "Auto mode selected : found $p_mode");
        }
        if (is_array($p_filelist)) {
            $v_result = PclTarHandleCreate($p_tarname, $p_filelist, $p_mode, $p_add_dir, $p_remove_dir);
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                $v_result = PclTarHandleCreate($p_tarname, $v_list, $p_mode, $p_add_dir, $p_remove_dir);
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                $v_result = -3;
            }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarAdd($p_tarname, $p_filelist)
    {
        TrFctStart(__file__, __line__, "PclTarAdd", "tar=$p_tarname, file=$p_filelist");
        $v_result = 1;
        $v_list_detail = array();
        if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if (is_array($p_filelist)) {
            $v_result = PclTarHandleAppend($p_tarname, $p_filelist, $p_mode, $v_list_detail, "",
                                           "");
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                $v_result = PclTarHandleAppend($p_tarname, $v_list, $p_mode, $v_list_detail, "", "");
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                $v_result = -3;
            }
        unset($v_list_detail);
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarAddList($p_tarname, $p_filelist, $p_add_dir = "", $p_remove_dir = "",
        $p_mode = "")
    {
        TrFctStart(__file__, __line__, "PclTarAddList", "tar=$p_tarname, file=$p_filelist, p_add_dir='$p_add_dir', p_remove_dir='$p_remove_dir', mode=$p_mode");
        $v_result = 1;
        $p_list_detail = array();
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        }
        if (is_array($p_filelist)) {
            $v_result = PclTarHandleAppend($p_tarname, $p_filelist, $p_mode, $p_list_detail, $p_add_dir,
                                           $p_remove_dir);
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                $v_result = PclTarHandleAppend($p_tarname, $v_list, $p_mode, $p_list_detail, $p_add_dir,
                                               $p_remove_dir);
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                $v_result = -3;
            }
        if ($v_result != 1) {
            TrFctEnd(__file__, __line__, 0);
            return 0;
        }
        TrFctEnd(__file__, __line__, $p_list_detail);
        return $p_list_detail;
    }

    function PclTarList($p_tarname, $p_mode = "")
    {
        TrFctStart(__file__, __line__, "PclTarList", "tar=$p_tarname, mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        $p_list = array();
        if (($v_result = PclTarHandleExtract($p_tarname, 0, $p_list, "list", "", $p_mode, "")) !=
            1) {
            unset($p_list);
            TrFctEnd(__file__, __line__, 0, PclErrorString());
            return (0);
        }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarExtract($p_tarname, $p_path = "./", $p_remove_path = "", $p_mode =
    "")
    {
        TrFctStart(__file__, __line__, "PclTarExtract", "tar='$p_tarname', path='$p_path', remove_path='$p_remove_path', mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (($v_result = PclTarHandleExtract($p_tarname, 0, $p_list, "complete", $p_path, $p_mode,
                                             $p_remove_path)) != 1) {
            TrFctEnd(__file__, __line__, 0, PclErrorString());
            return (0);
        }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarExtractList($p_tarname, $p_filelist, $p_path = "./", $p_remove_path =
    "", $p_mode = "")
    {
        TrFctStart(__file__, __line__, "PclTarExtractList", "tar=$p_tarname, list, path=$p_path, remove_path='$p_remove_path', mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (is_array($p_filelist)) {
            if (($v_result = PclTarHandleExtract($p_tarname, $p_filelist, $p_list, "partial", $p_path,
                                                 $v_tar_mode, $p_remove_path)) != 1) {
                TrFctEnd(__file__, __line__, 0, PclErrorString());
                return (0);
            }
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                if (($v_result = PclTarHandleExtract($p_tarname, $v_list, $p_list, "partial", $p_path,
                                                     $v_tar_mode, $p_remove_path)) != 1) {
                    TrFctEnd(__file__, __line__, 0, PclErrorString());
                    return (0);
                }
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarExtractIndex($p_tarname, $p_index, $p_path = "./", $p_remove_path =
    "", $p_mode = "")
    {
        TrFctStart(__file__, __line__, "PclTarExtractIndex", "tar=$p_tarname, index='$p_index', path=$p_path, remove_path='$p_remove_path', mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (is_integer($p_index)) {
            if (($v_result = PclTarHandleExtractByIndexList($p_tarname, "$p_index", $p_list, $p_path,
                                                            $p_remove_path, $v_tar_mode)) != 1) {
                TrFctEnd(__file__, __line__, 0, PclErrorString());
                return (0);
            }
        } else
            if (is_string($p_index)) {
                if (($v_result = PclTarHandleExtractByIndexList($p_tarname, $p_index, $p_list, $p_path,
                                                                $p_remove_path, $v_tar_mode)) != 1) {
                    TrFctEnd(__file__, __line__, 0, PclErrorString());
                    return (0);
                }
            } else {
                PclErrorLog(-3, "Invalid variable type $p_index");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarDelete($p_tarname, $p_filelist, $p_mode = "")
    {
        TrFctStart(__file__, __line__, "PclTarDelete", "tar='$p_tarname', list='$p_filelist', mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (is_array($p_filelist)) {
            if (($v_result = PclTarHandleDelete($p_tarname, $p_filelist, $p_list, $p_mode)) != 1) {
                TrFctEnd(__file__, __line__, 0, PclErrorString());
                return (0);
            }
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                if (($v_result = PclTarHandleDelete($p_tarname, $v_list, $p_list, $p_mode)) != 1) {
                    TrFctEnd(__file__, __line__, 0, PclErrorString());
                    return (0);
                }
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarUpdate($p_tarname, $p_filelist, $p_mode = "", $p_add_dir = "", $p_remove_dir =
    "")
    {
        TrFctStart(__file__, __line__, "PclTarUpdate", "tar='$p_tarname', list='$p_filelist', mode='$p_mode'");
        $v_result = 1;
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (is_array($p_filelist)) {
            if (($v_result = PclTarHandleUpdate($p_tarname, $p_filelist, $p_list, $p_mode, $p_add_dir,
                                                $p_remove_dir)) != 1) {
                TrFctEnd(__file__, __line__, 0, PclErrorString());
                return (0);
            }
        } else
            if (is_string($p_filelist)) {
                $v_list = explode(" ", $p_filelist);
                if (($v_result = PclTarHandleUpdate($p_tarname, $v_list, $p_list, $p_mode, $p_add_dir,
                                                    $p_remove_dir)) != 1) {
                    TrFctEnd(__file__, __line__, 0, PclErrorString());
                    return (0);
                }
            } else {
                PclErrorLog(-3, "Invalid variable type p_filelist");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        TrFctEnd(__file__, __line__, $p_list);
        return $p_list;
    }

    function PclTarMerge($p_tarname, $p_tarname_add, $p_mode = "", $p_mode_add = "")
    {
        TrFctStart(__file__, __line__, "PclTarMerge", "tar='$p_tarname', tar_add='$p_tarname_add', mode='$p_mode', mode_add='$p_mode_add'");
        $v_result = 1;
        if (($p_tarname == "") || ($p_tarname_add == "")) {
            PclErrorLog(-3, "Invalid empty archive name");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if (($p_mode == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if (($p_mode = PclTarHandleExtension($p_tarname)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        if (($p_mode_add == "") || (($p_mode_add != "tar") && ($p_mode_add != "tgz"))) {
            if (($p_mode_add = PclTarHandleExtension($p_tarname_add)) == "") {
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return 0;
            }
        }
        clearstatcache();
        if ((!is_file($p_tarname)) || (((($v_size = filesize($p_tarname)) % 512) != 0) &&
                                       ($p_mode == "tar"))) {
            if (!is_file($p_tarname))
                PclErrorLog(-4, "Archive '$p_tarname' does not exist");
            else
                PclErrorLog(-6, "Archive '$p_tarname' has invalid size " . filesize($p_tarname) .
                                "(not a 512 block multiple)");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ((!is_file($p_tarname_add)) || (((($v_size_add = filesize($p_tarname_add)) %
                                             512) != 0) && ($p_mode_add == "tar"))) {
            if (!is_file($p_tarname_add))
                PclErrorLog(-4, "Archive '$p_tarname_add' does not exist");
            else
                PclErrorLog(-6, "Archive '$p_tarname_add' has invalid size " . filesize($p_tarname_add) .
                                "(not a 512 block multiple)");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ($p_mode == "tgz") {
            if (($p_tar = @gzopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @gzopen($v_temp_tarname, "wb")) == 0) {
                gzclose($p_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_buffer = gzread($p_tar, 512);
            if (!gzeof($p_tar)) {
                TrFctMessage(__file__, __line__, 3, "More than one 512 block file");
                $i = 1;
                do {
                    $v_binary_data = pack("a512", "$v_buffer");
                    gzputs($v_temp_tar, $v_binary_data);
                    $i++;
                    TrFctMessage(__file__, __line__, 3, "Reading block $i");
                    $v_buffer = gzread($p_tar, 512);
                } while (!gzeof($p_tar));
                TrFctMessage(__file__, __line__, 3, "$i 512 bytes blocks");
            }
        } else
            if ($p_mode == "tar") {
                if (($p_tar = fopen($p_tarname, "r+b")) == 0) {
                    PclErrorLog(-1, "Unable to open file '$p_tarname' in binary write mode");
                    TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                    return PclErrorCode();
                }
                TrFctMessage(__file__, __line__, 4, "Position before :" . ($p_mode == "tar" ? ftell($p_tar) :
                        gztell($p_tar)));
                fseek($p_tar, $v_size - 512);
                TrFctMessage(__file__, __line__, 4, "Position after :" . ($p_mode == "tar" ? ftell($p_tar) :
                        gztell($p_tar)));
            } else {
                PclErrorLog(-3, "Invalid tar mode $p_mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        if ($p_mode_add == "tgz") {
            TrFctMessage(__file__, __line__, 4, "Opening file $p_tarname_add");
            if (($p_tar_add = @gzopen($p_tarname_add, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname_add' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_buffer = gzread($p_tar_add, 512);
            if (!gzeof($p_tar_add)) {
                TrFctMessage(__file__, __line__, 3, "More than one 512 block file");
                $i = 1;
                do {
                    $v_binary_data = pack("a512", "$v_buffer");
                    if ($p_mode == "tar")
                        fputs($p_tar, $v_binary_data);
                    else
                        gzputs($v_temp_tar, $v_binary_data);
                    $i++;
                    TrFctMessage(__file__, __line__, 3, "Reading block $i");
                    $v_buffer = gzread($p_tar_add, 512);
                } while (!gzeof($p_tar_add));
                TrFctMessage(__file__, __line__, 3, "$i 512 bytes blocks");
            }
            gzclose($p_tar_add);
        } else
            if ($p_mode == "tar") {
                if (($p_tar_add = @fopen($p_tarname_add, "rb")) == 0) {
                    PclErrorLog(-2, "Unable to open file '$p_tarname_add' in binary read mode");
                    TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                    return PclErrorCode();
                }
                $v_buffer = fread($p_tar_add, 512);
                if (!feof($p_tar_add)) {
                    TrFctMessage(__file__, __line__, 3, "More than one 512 block file");
                    $i = 1;
                    do {
                        $v_binary_data = pack("a512", "$v_buffer");
                        if ($p_mode == "tar")
                            fputs($p_tar, $v_binary_data);
                        else
                            gzputs($v_temp_tar, $v_binary_data);
                        $i++;
                        TrFctMessage(__file__, __line__, 3, "Reading block $i");
                        $v_buffer = fread($p_tar_add, 512);
                    } while (!feof($p_tar_add));
                    TrFctMessage(__file__, __line__, 3, "$i 512 bytes blocks");
                }
                fclose($p_tar_add);
            }
        $v_result = PclTarHandleFooter($p_tar, $p_mode);
        if ($p_mode == "tgz") {
            gzclose($p_tar);
            gzclose($v_temp_tar);
            if (!@unlink($p_tarname)) {
                PclErrorLog(-11, "Error while deleting archive name $p_tarname");
            }
            if (!@rename($v_temp_tarname, $p_tarname)) {
                PclErrorLog(-12, "Error while renaming temporary file $v_temp_tarname to archive name $p_tarname");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            TrFctEnd(__file__, __line__, $v_result);
            return $v_result;
        } else
            if ($p_mode == "tar") {
                fclose($p_tar);
            }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleCreate($p_tarname, $p_list, $p_mode, $p_add_dir = "", $p_remove_dir =
    "")
    {
        TrFctStart(__file__, __line__, "PclTarHandleCreate", "tar=$p_tarname, list, mode=$p_mode, add_dir='$p_add_dir', remove_dir='$p_remove_dir'");
        $v_result = 1;
        $v_list_detail = array();
        if (($p_tarname == "") || (($p_mode != "tar") && ($p_mode != "tgz"))) {
            if ($p_tarname == "")
                PclErrorLog(-3, "Invalid empty archive name");
            else
                PclErrorLog(-3, "Unknown mode '$p_mode'");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ($p_mode == "tar") {
            if (($p_tar = fopen($p_tarname, "wb")) == 0) {
                PclErrorLog(-1, "Unable to open file [$p_tarname] in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            if (($v_result = PclTarHandleAddList($p_tar, $p_list, $p_mode, $v_list_detail, $p_add_dir,
                                                 $p_remove_dir)) == 1) {
                $v_result = PclTarHandleFooter($p_tar, $p_mode);
            }
            fclose($p_tar);
        } else {
            if (($p_tar = @gzopen($p_tarname, "wb")) == 0) {
                PclErrorLog(-1, "Unable to open file [$p_tarname] in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            if (($v_result = PclTarHandleAddList($p_tar, $p_list, $p_mode, $v_list_detail, $p_add_dir,
                                                 $p_remove_dir)) == 1) {
                $v_result = PclTarHandleFooter($p_tar, $p_mode);
            }
            gzclose($p_tar);
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleAppend($p_tarname, $p_list, $p_mode, &$p_list_detail, $p_add_dir,
        $p_remove_dir)
    {
        TrFctStart(__file__, __line__, "PclTarHandleAppend", "tar=$p_tarname, list, mode=$p_mode");
        $v_result = 1;
        if ($p_tarname == "") {
            PclErrorLog(-3, "Invalid empty archive name");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        clearstatcache();
        if ((!is_file($p_tarname)) || (((($v_size = filesize($p_tarname)) % 512) != 0) &&
                                       ($p_mode == "tar"))) {
            if (!is_file($p_tarname))
                PclErrorLog(-4, "Archive '$p_tarname' does not exist");
            else
                PclErrorLog(-6, "Archive '$p_tarname' has invalid size " . filesize($p_tarname) .
                                "(not a 512 block multiple)");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ($p_mode == "tgz") {
            if (($p_tar = @gzopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @gzopen($v_temp_tarname, "wb")) == 0) {
                gzclose($p_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_buffer = gzread($p_tar, 512);
            if (!gzeof($p_tar)) {
                TrFctMessage(__file__, __line__, 3, "More than one 512 block file");
                $i = 1;
                do {
                    $v_binary_data = pack("a512", "$v_buffer");
                    gzputs($v_temp_tar, $v_binary_data);
                    $i++;
                    TrFctMessage(__file__, __line__, 3, "Reading block $i");
                    $v_buffer = gzread($p_tar, 512);
                } while (!gzeof($p_tar));
                TrFctMessage(__file__, __line__, 3, "$i 512 bytes blocks");
            }
            if (($v_result = PclTarHandleAddList($v_temp_tar, $p_list, $p_mode, $p_list_detail,
                                                 $p_add_dir, $p_remove_dir)) == 1) {
                $v_result = PclTarHandleFooter($v_temp_tar, $p_mode);
            }
            gzclose($p_tar);
            gzclose($v_temp_tar);
            if (!@unlink($p_tarname)) {
                PclErrorLog(-11, "Error while deleting archive name $p_tarname");
            }
            if (!@rename($v_temp_tarname, $p_tarname)) {
                PclErrorLog(-12, "Error while renaming temporary file $v_temp_tarname to archive name $p_tarname");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            TrFctEnd(__file__, __line__, $v_result);
            return $v_result;
        } else
            if ($p_mode == "tar") {
                if (($p_tar = fopen($p_tarname, "r+b")) == 0) {
                    PclErrorLog(-1, "Unable to open file '$p_tarname' in binary write mode");
                    TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                    return PclErrorCode();
                }
                TrFctMessage(__file__, __line__, 4, "Position before :" . ($p_mode == "tar" ? ftell($p_tar) :
                        gztell($p_tar)));
                fseek($p_tar, $v_size - 512);
                TrFctMessage(__file__, __line__, 4, "Position after :" . ($p_mode == "tar" ? ftell($p_tar) :
                        gztell($p_tar)));
                if (($v_result = PclTarHandleAddList($p_tar, $p_list, $p_mode, $p_list_detail, $p_add_dir,
                                                     $p_remove_dir)) == 1) {
                    $v_result = PclTarHandleFooter($p_tar, $p_mode);
                }
                fclose($p_tar);
            } else {
                PclErrorLog(-3, "Invalid tar mode $p_mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleAddList($p_tar, $p_list, $p_mode, &$p_list_detail, $p_add_dir,
        $p_remove_dir)
    {
        TrFctStart(__file__, __line__, "PclTarHandleAddList", "tar='$p_tar', list, mode='$p_mode', add_dir='$p_add_dir', remove_dir='$p_remove_dir'");
        $v_result = 1;
        $v_header = array();
        $v_nb = sizeof($p_list_detail);
        if ($p_tar == 0) {
            PclErrorLog(-3, "Invalid file descriptor in file " . __file__ . ", line " . __line__);
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if (sizeof($p_list) == 0) {
            PclErrorLog(-3, "Invalid file list parameter (invalid or empty list)");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        for ($j = 0; ($j < count($p_list)) && ($v_result == 1); $j++) {
            $p_filename = $p_list[$j];
            TrFctMessage(__file__, __line__, 2, "Looking for file [$p_filename]");
            if ($p_filename == "") {
                TrFctMessage(__file__, __line__, 2, "Skip empty filename");
                continue;
            }
            if (!file_exists($p_filename)) {
                TrFctMessage(__file__, __line__, 2, "File '$p_filename' does not exists");
                PclErrorLog(-4, "File '$p_filename' does not exists");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            if (strlen($p_filename) > 99) {
                PclErrorLog(-5, "File name is too long (max. 99) : '$p_filename'");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            TrFctMessage(__file__, __line__, 4, "File position before header =" . ($p_mode ==
                                                                                   "tar" ? ftell($p_tar) : gztell($p_tar)));
            if (($v_result = PclTarHandleAddFile($p_tar, $p_filename, $p_mode, $v_header, $p_add_dir,
                                                 $p_remove_dir)) != 1) {
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            $p_list_detail[$v_nb++] = $v_header;
            if (is_dir($p_filename)) {
                TrFctMessage(__file__, __line__, 2, "$p_filename is a directory");
                if ($p_filename != ".")
                    $v_path = $p_filename . "/";
                else
                    $v_path = "";
                $p_hdir = opendir($p_filename);
                $p_hitem = readdir($p_hdir);
                $p_hitem = readdir($p_hdir);
                while ($p_hitem = readdir($p_hdir)) {
                    if (is_file($v_path . $p_hitem)) {
                        TrFctMessage(__file__, __line__, 4, "Add the file '" . $v_path . $p_hitem . "'");
                        if (($v_result = PclTarHandleAddFile($p_tar, $v_path . $p_hitem, $p_mode, $v_header, $p_add_dir,
                                                             $p_remove_dir)) != 1) {
                            TrFctEnd(__file__, __line__, $v_result);
                            return $v_result;
                        }
                        $p_list_detail[$v_nb++] = $v_header;
                    } else {
                        TrFctMessage(__file__, __line__, 4, "'" . $v_path . $p_hitem . "' is a directory");
                        $p_temp_list[0] = $v_path . $p_hitem;
                        $v_result = PclTarHandleAddList($p_tar, $p_temp_list, $p_mode, $p_list_detail, $p_add_dir,
                                                        $p_remove_dir);
                    }
                }
                unset($p_temp_list);
                unset($p_hdir);
                unset($p_hitem);
            } else {
                TrFctMessage(__file__, __line__, 4, "File position after blocks =" . ($p_mode ==
                                                                                      "tar" ? ftell($p_tar) : gztell($p_tar)));
            }
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleAddFile($p_tar, $p_filename, $p_mode, &$p_header, $p_add_dir, $p_remove_dir)
    {
        TrFctStart(__file__, __line__, "PclTarHandleAddFile", "tar='$p_tar', filename='$p_filename', p_mode='$p_mode', add_dir='$p_add_dir', remove_dir='$p_remove_dir'");
        $v_result = 1;
        if ($p_tar == 0) {
            PclErrorLog(-3, "Invalid file descriptor in file " . __file__ . ", line " . __line__);
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ($p_filename == "") {
            PclErrorLog(-3, "Invalid file list parameter (invalid or empty list)");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        $v_stored_filename = $p_filename;
        if ($p_remove_dir != "") {
            if (substr($p_remove_dir, -1) != '/')
                $p_remove_dir .= "/";
            if ((substr($p_filename, 0, 2) == "./") || (substr($p_remove_dir, 0, 2) == "./")) {
                if ((substr($p_filename, 0, 2) == "./") && (substr($p_remove_dir, 0, 2) != "./"))
                    $p_remove_dir = "./" . $p_remove_dir;
                if ((substr($p_filename, 0, 2) != "./") && (substr($p_remove_dir, 0, 2) == "./"))
                    $p_remove_dir = substr($p_remove_dir, 2);
            }
            if (substr($p_filename, 0, strlen($p_remove_dir)) == $p_remove_dir) {
                $v_stored_filename = substr($p_filename, strlen($p_remove_dir));
                TrFctMessage(__file__, __line__, 3, "Remove path '$p_remove_dir' in file '$p_filename' = '$v_stored_filename'");
            }
        }
        if ($p_add_dir != "") {
            if (substr($p_add_dir, -1) == "/")
                $v_stored_filename = $p_add_dir . $v_stored_filename;
            else
                $v_stored_filename = $p_add_dir . "/" . $v_stored_filename;
            TrFctMessage(__file__, __line__, 3, "Add path '$p_add_dir' in file '$p_filename' = '$v_stored_filename'");
        }
        if (strlen($v_stored_filename) > 99) {
            PclErrorLog(-5, "Stored file name is too long (max. 99) : '$v_stored_filename'");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if (is_file($p_filename)) {
            if (($v_file = fopen($p_filename, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_filename' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            if (($v_result = PclTarHandleHeader($p_tar, $p_filename, $p_mode, $p_header, $v_stored_filename)) !=
                1) {
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            TrFctMessage(__file__, __line__, 4, "File position after header =" . ($p_mode ==
                                                                                  "tar" ? ftell($p_tar) : gztell($p_tar)));
            $i = 0;
            while (($v_buffer = fread($v_file, 512)) != "") {
                $v_binary_data = pack("a512", "$v_buffer");
                if ($p_mode == "tar")
                    fputs($p_tar, $v_binary_data);
                else
                    gzputs($p_tar, $v_binary_data);
                $i++;
            }
            TrFctMessage(__file__, __line__, 2, "$i 512 bytes blocks");
            fclose($v_file);
            TrFctMessage(__file__, __line__, 4, "File position after blocks =" . ($p_mode ==
                                                                                  "tar" ? ftell($p_tar) : gztell($p_tar)));
        } else {
            if (($v_result = PclTarHandleHeader($p_tar, $p_filename, $p_mode, $p_header, $v_stored_filename)) !=
                1) {
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            TrFctMessage(__file__, __line__, 4, "File position after header =" . ($p_mode ==
                                                                                  "tar" ? ftell($p_tar) : gztell($p_tar)));
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleHeader($p_tar, $p_filename, $p_mode, &$p_header, $p_stored_filename)
    {
        TrFctStart(__file__, __line__, "PclTarHandleHeader", "tar=$p_tar, file='$p_filename', mode='$p_mode', stored_filename='$p_stored_filename'");
        $v_result = 1;
        if (($p_tar == 0) || ($p_filename == "")) {
            PclErrorLog(-3, "Invalid file descriptor in file " . __file__ . ", line " . __line__);
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        if ($p_stored_filename == "")
            $p_stored_filename = $p_filename;
        $v_reduce_filename = PclTarHandlePathReduction($p_stored_filename);
        TrFctMessage(__file__, __line__, 2, "Filename (reduced) '$v_reduce_filename', strlen " .
                                            strlen($v_reduce_filename));
        $v_info = stat($p_filename);
        $v_uid = sprintf("%6s ", DecOct($v_info[4]));
        $v_gid = sprintf("%6s ", DecOct($v_info[5]));
        TrFctMessage(__file__, __line__, 3, "uid=$v_uid, gid=$v_gid");
        $v_perms = sprintf("%6s ", DecOct(fileperms($p_filename)));
        TrFctMessage(__file__, __line__, 3, "file permissions $v_perms");
        $v_mtime_data = filemtime($p_filename);
        TrFctMessage(__file__, __line__, 2, "File mtime : $v_mtime_data");
        $v_mtime = sprintf("%11s", DecOct($v_mtime_data));
        if (is_dir($p_filename)) {
            $v_typeflag = "5";
            $v_size = 0;
        } else {
            $v_typeflag = "";
            clearstatcache();
            $v_size = filesize($p_filename);
        }
        TrFctMessage(__file__, __line__, 2, "File size : $v_size");
        $v_size = sprintf("%11s ", DecOct($v_size));
        TrFctMessage(__file__, __line__, 2, "File typeflag : $v_typeflag");
        $v_linkname = "";
        $v_magic = "";
        $v_version = "";
        $v_uname = "";
        $v_gname = "";
        $v_devmajor = "";
        $v_devminor = "";
        $v_prefix = "";
        $v_binary_data_first = pack("a100a8a8a8a12A12", $v_reduce_filename, $v_perms, $v_uid,
                                    $v_gid, $v_size, $v_mtime);
        $v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12", $v_typeflag, $v_linkname,
                                   $v_magic, $v_version, $v_uname, $v_gname, $v_devmajor, $v_devminor, $v_prefix, "");
        $v_checksum = 0;
        for ($i = 0; $i < 148; $i++) {
            $v_checksum += ord(substr($v_binary_data_first, $i, 1));
        }
        for ($i = 148; $i < 156; $i++) {
            $v_checksum += ord(' ');
        }
        for ($i = 156, $j = 0; $i < 512; $i++, $j++) {
            $v_checksum += ord(substr($v_binary_data_last, $j, 1));
        }
        TrFctMessage(__file__, __line__, 3, "Calculated checksum : $v_checksum");
        if ($p_mode == "tar")
            fputs($p_tar, $v_binary_data_first, 148);
        else
            gzputs($p_tar, $v_binary_data_first, 148);
        $v_checksum = sprintf("%6s ", DecOct($v_checksum));
        $v_binary_data = pack("a8", $v_checksum);
        if ($p_mode == "tar")
            fputs($p_tar, $v_binary_data, 8);
        else
            gzputs($p_tar, $v_binary_data, 8);
        if ($p_mode == "tar")
            fputs($p_tar, $v_binary_data_last, 356);
        else
            gzputs($p_tar, $v_binary_data_last, 356);
        $p_header[filename] = $v_reduce_filename;
        $p_header[mode] = $v_perms;
        $p_header[uid] = $v_uid;
        $p_header[gid] = $v_gid;
        $p_header[size] = $v_size;
        $p_header[mtime] = $v_mtime;
        $p_header[typeflag] = $v_typeflag;
        $p_header[status] = "added";
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleFooter($p_tar, $p_mode)
    {
        TrFctStart(__file__, __line__, "PclTarHandleFooter", "tar='$p_tar', p_mode=$p_mode");
        $v_result = 1;
        $v_binary_data = pack("a512", "");
        if ($p_mode == "tar")
            fputs($p_tar, $v_binary_data);
        else
            gzputs($p_tar, $v_binary_data);
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleExtract($p_tarname, $p_file_list, &$p_list_detail, $p_mode, $p_path,
        $p_tar_mode, $p_remove_path)
    {
        TrFctStart(__file__, __line__, "PclTarHandleExtract", "archive='$p_tarname', list, mode=$p_mode, path=$p_path, tar_mode=$p_tar_mode, remove_path='$p_remove_path'");
        $v_result = 1;
        $v_nb = 0;
        $v_extract_all = true;
        $v_listing = false;
        $isWin = (substr(PHP_OS, 0, 3) == 'WIN');
        if (!$isWin) {
            if (($p_path == "") || ((substr($p_path, 0, 1) != "/") && (substr($p_path, 0, 3) !=
                                                                       "../")))
                $p_path = "./" . $p_path;
        }
        if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/')) {
            $p_remove_path .= '/';
        }
        $p_remove_path_size = strlen($p_remove_path);
        switch ($p_mode) {
            case "complete":
                $v_extract_all = true;
                $v_listing = false;
                break;
            case "partial":
                $v_extract_all = false;
                $v_listing = false;
                break;
            case "list":
                $v_extract_all = false;
                $v_listing = true;
                break;
            default:
                PclErrorLog(-3, "Invalid extract mode ($p_mode)");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
        }
        if ($p_tar_mode == "tar") {
            TrFctMessage(__file__, __line__, 3, "Open file in binary read mode");
            $v_tar = fopen($p_tarname, "rb");
        } else {
            TrFctMessage(__file__, __line__, 3, "Open file in gzip binary read mode");
            $v_tar = @gzopen($p_tarname, "rb");
        }
        if ($v_tar == 0) {
            PclErrorLog(-2, "Unable to open archive '$p_tarname' in binary read mode");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        while (!($v_end_of_file = ($p_tar_mode == "tar" ? feof($v_tar) : gzeof($v_tar)))) {
            TrFctMessage(__file__, __line__, 3, "Looking for next header ...");
            clearstatcache();
            $v_extract_file = false;
            $v_extraction_stopped = 0;
            if ($p_tar_mode == "tar")
                $v_binary_data = fread($v_tar, 512);
            else
                $v_binary_data = gzread($v_tar, 512);
            if (($v_result = PclTarHandleReadHeader($v_binary_data, $v_header)) != 1) {
                if ($p_tar_mode == "tar")
                    fclose($v_tar);
                else
                    gzclose($v_tar);
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            if ($v_header["filename"] == "") {
                TrFctMessage(__file__, __line__, 2, "Empty block found. End of archive ?");
                continue;
            }
            TrFctMessage(__file__, __line__, 2, "Found file '" . $v_header["filename"] .
                                                "', size '$v_header[size]'");
            if ((!$v_extract_all) && (is_array($p_file_list))) {
                TrFctMessage(__file__, __line__, 2, "Look if the file '$v_header[filename]' need to be extracted");
                $v_extract_file = false;
                for ($i = 0; $i < sizeof($p_file_list); $i++) {
                    TrFctMessage(__file__, __line__, 2, "Compare archived file '$v_header[filename]' from asked list file '" .
                                                        $p_file_list[$i] . "'");
                    if (substr($p_file_list[$i], -1) == "/") {
                        TrFctMessage(__file__, __line__, 3, "Compare file '$v_header[filename]' with directory '$p_file_list[$i]'");
                        if ((strlen($v_header["filename"]) > strlen($p_file_list[$i])) && (substr($v_header["filename"],
                                                                                                  0, strlen($p_file_list[$i])) == $p_file_list[$i])) {
                            TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' is in directory '$p_file_list[$i]' : extract it");
                            $v_extract_file = true;
                            break;
                        }
                    } else
                        if ($p_file_list[$i] == $v_header["filename"]) {
                            TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' should be extracted");
                            $v_extract_file = true;
                            break;
                        }
                }
                if (!$v_extract_file) {
                    TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' should not be extracted");
                }
            } else {
                $v_extract_file = true;
            }
            if (($v_extract_file) && (!$v_listing)) {
                if (($p_remove_path != "") && (substr($v_header["filename"], 0, $p_remove_path_size) ==
                                               $p_remove_path)) {
                    TrFctMessage(__file__, __line__, 3, "Found path '$p_remove_path' to remove in file '$v_header[filename]'");
                    $v_header["filename"] = substr($v_header["filename"], $p_remove_path_size);
                    TrFctMessage(__file__, __line__, 3, "Reslting file is '$v_header[filename]'");
                }
                if (($p_path != "./") && ($p_path != "/")) {
                    while (substr($p_path, -1) == "/") {
                        TrFctMessage(__file__, __line__, 3, "Destination path [$p_path] ends by '/'");
                        $p_path = substr($p_path, 0, strlen($p_path) - 1);
                        TrFctMessage(__file__, __line__, 3, "Modified to [$p_path]");
                    }
                    if (substr($v_header["filename"], 0, 1) == "/")
                        $v_header["filename"] = $p_path . $v_header["filename"];
                    else
                        $v_header["filename"] = $p_path . "/" . $v_header["filename"];
                }
                TrFctMessage(__file__, __line__, 2, "Extracting file (with path) '$v_header[filename]', size '$v_header[size]'");
                if (file_exists($v_header["filename"])) {
                    TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' already exists");
                    if (is_dir($v_header["filename"])) {
                        TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is a directory");
                        $v_header["status"] = "already_a_directory";
                        $v_extraction_stopped = 1;
                        $v_extract_file = 0;
                    } else
                        if (!is_writeable($v_header["filename"])) {
                            TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is write protected");
                            $v_header["status"] = "write_protected";
                            $v_extraction_stopped = 1;
                            $v_extract_file = 0;
                        } else
                            if (filemtime($v_header["filename"]) > $v_header["mtime"]) {
                                TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is newer (" .
                                                                    date("l dS of F Y h:i:s A", filemtime($v_header[filename])) .
                                                                    ") than the extracted file (" . date("l dS of F Y h:i:s A", $v_header[mtime]) . ")");
                                $v_header["status"] = "newer_exist";
                                $v_extraction_stopped = 1;
                                $v_extract_file = 0;
                            }
                } else {
                    if ($v_header["typeflag"] == "5")
                        $v_dir_to_check = $v_header["filename"];
                    else
                        if (!strstr($v_header["filename"], "/"))
                            $v_dir_to_check = "";
                        else
                            $v_dir_to_check = dirname($v_header["filename"]);
                    if (($v_result = PclTarHandlerDirCheck($v_dir_to_check)) != 1) {
                        TrFctMessage(__file__, __line__, 2, "Unable to create path for '$v_header[filename]'");
                        $v_header["status"] = "path_creation_fail";
                        $v_extraction_stopped = 1;
                        $v_extract_file = 0;
                    }
                }
                if (($v_extract_file) && ($v_header["typeflag"] != "5")) {
                    if (($v_dest_file = @fopen($v_header["filename"], "wb")) == 0) {
                        TrFctMessage(__file__, __line__, 2, "Error while opening '$v_header[filename]' in write binary mode");
                        $v_header["status"] = "write_error";
                        TrFctMessage(__file__, __line__, 2, "Jump to next file");
                        if ($p_tar_mode == "tar")
                            fseek($v_tar, ftell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                        else
                            gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                    } else {
                        TrFctMessage(__file__, __line__, 2, "Start extraction of '$v_header[filename]'");
                        $n = floor($v_header["size"] / 512);
                        for ($i = 0; $i < $n; $i++) {
                            TrFctMessage(__file__, __line__, 3, "Read complete 512 bytes block number " . ($i + 1));
                            if ($p_tar_mode == "tar")
                                $v_content = fread($v_tar, 512);
                            else
                                $v_content = gzread($v_tar, 512);
                            fwrite($v_dest_file, $v_content, 512);
                        }
                        if (($v_header["size"] % 512) != 0) {
                            TrFctMessage(__file__, __line__, 3, "Read last " . ($v_header["size"] % 512) .
                                                                " bytes in a 512 block");
                            if ($p_tar_mode == "tar")
                                $v_content = fread($v_tar, 512);
                            else
                                $v_content = gzread($v_tar, 512);
                            fwrite($v_dest_file, $v_content, ($v_header["size"] % 512));
                        }
                        fclose($v_dest_file);
                        touch($v_header["filename"], $v_header["mtime"]);
                    }
                    clearstatcache();
                    if (filesize($v_header["filename"]) != $v_header["size"]) {
                        if ($p_tar_mode == "tar")
                            fclose($v_tar);
                        else
                            gzclose($v_tar);
                        PclErrorLog(-7, "Extracted file '$v_header[filename]' does not have the correct file size '" .
                                        filesize($v_filename) . "' ('$v_header[size]' expected). Archive may be corrupted.");
                        TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                        return PclErrorCode();
                    }
                    TrFctMessage(__file__, __line__, 2, "Extraction done");
                } else {
                    TrFctMessage(__file__, __line__, 2, "Extraction of file '$v_header[filename]' skipped.");
                    TrFctMessage(__file__, __line__, 2, "Jump to next file");
                    if ($p_tar_mode == "tar")
                        fseek($v_tar, ftell($v_tar) + (ceil(($v_header["size"] / 512)) * 512));
                    else
                        gzseek($v_tar, gztell($v_tar) + (ceil(($v_header["size"] / 512)) * 512));
                }
            } else {
                TrFctMessage(__file__, __line__, 2, "Jump file '$v_header[filename]'");
                TrFctMessage(__file__, __line__, 4, "Position avant jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
                if ($p_tar_mode == "tar")
                    fseek($v_tar, ($p_tar_mode == "tar" ? ftell($v_tar) : gztell($v_tar)) + (ceil(($v_header[size] /
                                                                                                   512)) * 512));
                else
                    gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                TrFctMessage(__file__, __line__, 4, "Position aprпїЅs jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
            }
            if ($p_tar_mode == "tar")
                $v_end_of_file = feof($v_tar);
            else
                $v_end_of_file = gzeof($v_tar);
            if ($v_listing || $v_extract_file || $v_extraction_stopped) {
                TrFctMessage(__file__, __line__, 2, "Memorize info about file '$v_header[filename]'");
                if (($v_file_dir = dirname($v_header["filename"])) == $v_header["filename"])
                    $v_file_dir = "";
                if ((substr($v_header["filename"], 0, 1) == "/") && ($v_file_dir == ""))
                    $v_file_dir = "/";
                $p_list_detail[$v_nb] = $v_header;
                $v_nb++;
            }
        }
        if ($p_tar_mode == "tar")
            fclose($v_tar);
        else
            gzclose($v_tar);
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleExtractByIndexList($p_tarname, $p_index_string, &$p_list_detail,
        $p_path, $p_remove_path, $p_tar_mode)
    {
        TrFctStart(__file__, __line__, "PclTarHandleExtractByIndexList", "archive='$p_tarname', index_string='$p_index_string', list, path=$p_path, remove_path='$p_remove_path', tar_mode=$p_tar_mode");
        $v_result = 1;
        $v_nb = 0;
        if (($p_path == "") || ((substr($p_path, 0, 1) != "/") && (substr($p_path, 0, 3) !=
                                                                   "../") && (substr($p_path, 0, 2) != "./")))
            $p_path = "./" . $p_path;
        if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/')) {
            $p_remove_path .= '/';
        }
        $p_remove_path_size = strlen($p_remove_path);
        if ($p_tar_mode == "tar") {
            TrFctMessage(__file__, __line__, 3, "Open file in binary read mode");
            $v_tar = @fopen($p_tarname, "rb");
        } else {
            TrFctMessage(__file__, __line__, 3, "Open file in gzip binary read mode");
            $v_tar = @gzopen($p_tarname, "rb");
        }
        if ($v_tar == 0) {
            PclErrorLog(-2, "Unable to open archive '$p_tarname' in binary read mode");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        $v_list = explode(",", $p_index_string);
        sort($v_list);
        $v_index = 0;
        for ($i = 0; ($i < sizeof($v_list)) && ($v_result); $i++) {
            TrFctMessage(__file__, __line__, 3, "Looking for index part '$v_list[$i]'");
            $v_index_list = explode("-", $v_list[$i]);
            $v_size_index_list = sizeof($v_index_list);
            if ($v_size_index_list == 1) {
                TrFctMessage(__file__, __line__, 3, "Only one index '$v_index_list[0]'");
                $v_result = PclTarHandleExtractByIndex($v_tar, $v_index, $v_index_list[0], $v_index_list[0],
                                                       $p_list_detail, $p_path, $p_remove_path, $p_tar_mode);
            } else
                if ($v_size_index_list == 2) {
                    TrFctMessage(__file__, __line__, 3, "Two indexes '$v_index_list[0]' and '$v_index_list[1]'");
                    $v_result = PclTarHandleExtractByIndex($v_tar, $v_index, $v_index_list[0], $v_index_list[1],
                                                           $p_list_detail, $p_path, $p_remove_path, $p_tar_mode);
                }
        }
        if ($p_tar_mode == "tar")
            fclose($v_tar);
        else
            gzclose($v_tar);
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleExtractByIndex($p_tar, &$p_index_current, $p_index_start, $p_index_stop,
        &$p_list_detail, $p_path, $p_remove_path, $p_tar_mode)
    {
        TrFctStart(__file__, __line__, "PclTarHandleExtractByIndex", "archive_descr='$p_tar', index_current=$p_index_current, index_start='$p_index_start', index_stop='$p_index_stop', list, path=$p_path, remove_path='$p_remove_path', tar_mode=$p_tar_mode");
        $v_result = 1;
        $v_nb = 0;
        $v_tar = $p_tar;
        $v_nb = sizeof($p_list_detail);
        while (!($v_end_of_file = ($p_tar_mode == "tar" ? feof($v_tar) : gzeof($v_tar)))) {
            TrFctMessage(__file__, __line__, 3, "Looking for next file ...");
            TrFctMessage(__file__, __line__, 3, "Index current=$p_index_current, range=[$p_index_start, $p_index_stop])");
            if ($p_index_current > $p_index_stop) {
                TrFctMessage(__file__, __line__, 2, "Stop extraction, past stop index");
                break;
            }
            clearstatcache();
            $v_extract_file = false;
            $v_extraction_stopped = 0;
            if ($p_tar_mode == "tar")
                $v_binary_data = fread($v_tar, 512);
            else
                $v_binary_data = gzread($v_tar, 512);
            if (($v_result = PclTarHandleReadHeader($v_binary_data, $v_header)) != 1) {
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            if ($v_header[filename] == "") {
                TrFctMessage(__file__, __line__, 2, "Empty block found. End of archive ?");
                continue;
            }
            TrFctMessage(__file__, __line__, 2, "Found file '$v_header[filename]', size '$v_header[size]'");
            if (($p_index_current >= $p_index_start) && ($p_index_current <= $p_index_stop)) {
                TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' is in the range to be extracted");
                $v_extract_file = true;
            } else {
                TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' is out of the range");
                $v_extract_file = false;
            }
            if ($v_extract_file) {
                if (($v_result = PclTarHandleExtractFile($v_tar, $v_header, $p_path, $p_remove_path,
                                                         $p_tar_mode)) != 1) {
                    TrFctEnd(__file__, __line__, $v_result);
                    return $v_result;
                }
            } else {
                TrFctMessage(__file__, __line__, 2, "Jump file '$v_header[filename]'");
                TrFctMessage(__file__, __line__, 4, "Position avant jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
                if ($p_tar_mode == "tar")
                    fseek($v_tar, ($p_tar_mode == "tar" ? ftell($v_tar) : gztell($v_tar)) + (ceil(($v_header[size] /
                                                                                                   512)) * 512));
                else
                    gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                TrFctMessage(__file__, __line__, 4, "Position aprпїЅs jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
            }
            if ($p_tar_mode == "tar")
                $v_end_of_file = feof($v_tar);
            else
                $v_end_of_file = gzeof($v_tar);
            if ($v_extract_file) {
                TrFctMessage(__file__, __line__, 2, "Memorize info about file '$v_header[filename]'");
                if (($v_file_dir = dirname($v_header[filename])) == $v_header[filename])
                    $v_file_dir = "";
                if ((substr($v_header[filename], 0, 1) == "/") && ($v_file_dir == ""))
                    $v_file_dir = "/";
                $p_list_detail[$v_nb] = $v_header;
                $v_nb++;
            }
            $p_index_current++;
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleExtractFile($p_tar, &$v_header, $p_path, $p_remove_path, $p_tar_mode)
    {
        TrFctStart(__file__, __line__, "PclTarHandleExtractFile", "archive_descr='$p_tar', path=$p_path, remove_path='$p_remove_path', tar_mode=$p_tar_mode");
        $v_result = 1;
        $v_tar = $p_tar;
        $v_extract_file = 1;
        $p_remove_path_size = strlen($p_remove_path);
        if (($p_remove_path != "") && (substr($v_header[filename], 0, $p_remove_path_size) ==
                                       $p_remove_path)) {
            TrFctMessage(__file__, __line__, 3, "Found path '$p_remove_path' to remove in file '$v_header[filename]'");
            $v_header[filename] = substr($v_header[filename], $p_remove_path_size);
            TrFctMessage(__file__, __line__, 3, "Resulting file is '$v_header[filename]'");
        }
        if (($p_path != "./") && ($p_path != "/")) {
            while (substr($p_path, -1) == "/") {
                TrFctMessage(__file__, __line__, 3, "Destination path [$p_path] ends by '/'");
                $p_path = substr($p_path, 0, strlen($p_path) - 1);
                TrFctMessage(__file__, __line__, 3, "Modified to [$p_path]");
            }
            if (substr($v_header[filename], 0, 1) == "/")
                $v_header[filename] = $p_path . $v_header[filename];
            else
                $v_header[filename] = $p_path . "/" . $v_header[filename];
        }
        TrFctMessage(__file__, __line__, 2, "Extracting file (with path) '$v_header[filename]', size '$v_header[size]'");
        if (file_exists($v_header[filename])) {
            TrFctMessage(__file__, __line__, 2, "File '$v_header[filename]' already exists");
            if (is_dir($v_header[filename])) {
                TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is a directory");
                $v_header[status] = "already_a_directory";
                $v_extraction_stopped = 1;
                $v_extract_file = 0;
            } else
                if (!is_writeable($v_header[filename])) {
                    TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is write protected");
                    $v_header[status] = "write_protected";
                    $v_extraction_stopped = 1;
                    $v_extract_file = 0;
                } else
                    if (filemtime($v_header[filename]) > $v_header[mtime]) {
                        TrFctMessage(__file__, __line__, 2, "Existing file '$v_header[filename]' is newer (" .
                                                            date("l dS of F Y h:i:s A", filemtime($v_header[filename])) .
                                                            ") than the extracted file (" . date("l dS of F Y h:i:s A", $v_header[mtime]) . ")");
                        $v_header[status] = "newer_exist";
                        $v_extraction_stopped = 1;
                        $v_extract_file = 0;
                    }
        } else {
            if ($v_header[typeflag] == "5")
                $v_dir_to_check = $v_header[filename];
            else
                if (!strstr($v_header[filename], "/"))
                    $v_dir_to_check = "";
                else
                    $v_dir_to_check = dirname($v_header[filename]);
            if (($v_result = PclTarHandlerDirCheck($v_dir_to_check)) != 1) {
                TrFctMessage(__file__, __line__, 2, "Unable to create path for '$v_header[filename]'");
                $v_header[status] = "path_creation_fail";
                $v_extraction_stopped = 1;
                $v_extract_file = 0;
            }
        }
        if (($v_extract_file) && ($v_header[typeflag] != "5")) {
            if (($v_dest_file = @fopen($v_header[filename], "wb")) == 0) {
                TrFctMessage(__file__, __line__, 2, "Error while opening '$v_header[filename]' in write binary mode");
                $v_header[status] = "write_error";
                TrFctMessage(__file__, __line__, 2, "Jump to next file");
                if ($p_tar_mode == "tar")
                    fseek($v_tar, ftell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                else
                    gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
            } else {
                TrFctMessage(__file__, __line__, 2, "Start extraction of '$v_header[filename]'");
                $n = floor($v_header[size] / 512);
                for ($i = 0; $i < $n; $i++) {
                    TrFctMessage(__file__, __line__, 3, "Read complete 512 bytes block number " . ($i + 1));
                    if ($p_tar_mode == "tar")
                        $v_content = fread($v_tar, 512);
                    else
                        $v_content = gzread($v_tar, 512);
                    fwrite($v_dest_file, $v_content, 512);
                }
                if (($v_header[size] % 512) != 0) {
                    TrFctMessage(__file__, __line__, 3, "Read last " . ($v_header[size] % 512) .
                                                        " bytes in a 512 block");
                    if ($p_tar_mode == "tar")
                        $v_content = fread($v_tar, 512);
                    else
                        $v_content = gzread($v_tar, 512);
                    fwrite($v_dest_file, $v_content, ($v_header[size] % 512));
                }
                fclose($v_dest_file);
                touch($v_header[filename], $v_header[mtime]);
            }
            clearstatcache();
            if (filesize($v_header[filename]) != $v_header[size]) {
                PclErrorLog(-7, "Extracted file '$v_header[filename]' does not have the correct file size '" .
                                filesize($v_filename) . "' ('$v_header[size]' expected). Archive may be corrupted.");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            TrFctMessage(__file__, __line__, 2, "Extraction done");
        } else {
            TrFctMessage(__file__, __line__, 2, "Extraction of file '$v_header[filename]' skipped.");
            TrFctMessage(__file__, __line__, 2, "Jump to next file");
            if ($p_tar_mode == "tar")
                fseek($v_tar, ftell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
            else
                gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleDelete($p_tarname, $p_file_list, &$p_list_detail, $p_tar_mode)
    {
        TrFctStart(__file__, __line__, "PclTarHandleDelete", "archive='$p_tarname', list, tar_mode=$p_tar_mode");
        $v_result = 1;
        $v_nb = 0;
        if ($p_tar_mode == "tar") {
            TrFctMessage(__file__, __line__, 3, "Open file in binary read mode");
            if (($v_tar = @fopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @fopen($v_temp_tarname, "wb")) == 0) {
                fclose($v_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        } else {
            TrFctMessage(__file__, __line__, 3, "Open file in gzip binary read mode");
            if (($v_tar = @gzopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @gzopen($v_temp_tarname, "wb")) == 0) {
                gzclose($v_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        }
        while (!($v_end_of_file = ($p_tar_mode == "tar" ? feof($v_tar) : gzeof($v_tar)))) {
            TrFctMessage(__file__, __line__, 3, "Looking for next header ...");
            clearstatcache();
            $v_delete_file = false;
            if ($p_tar_mode == "tar")
                $v_binary_data = fread($v_tar, 512);
            else
                $v_binary_data = gzread($v_tar, 512);
            if (($v_result = PclTarHandleReadHeader($v_binary_data, $v_header)) != 1) {
                if ($p_tar_mode == "tar") {
                    fclose($v_tar);
                    fclose($v_temp_tar);
                } else {
                    gzclose($v_tar);
                    gzclose($v_temp_tar);
                }
                @unlink($v_temp_tarname);
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            if ($v_header[filename] == "") {
                TrFctMessage(__file__, __line__, 2, "Empty block found. End of archive ?");
                continue;
            }
            TrFctMessage(__file__, __line__, 2, "Found file '$v_header[filename]', size '$v_header[size]'");
            for ($i = 0, $v_delete_file = false; ($i < sizeof($p_file_list)) && (!$v_delete_file);
                $i++) {
                if (($v_len = strcmp($p_file_list[$i], $v_header[filename])) <= 0) {
                    if ($v_len == 0) {
                        TrFctMessage(__file__, __line__, 3, "Found that '$v_header[filename]' need to be deleted");
                        $v_delete_file = true;
                    } else {
                        TrFctMessage(__file__, __line__, 3, "Look if '$v_header[filename]' is a file in $p_file_list[$i]");
                        if (substr($v_header[filename], strlen($p_file_list[$i]), 1) == "/") {
                            TrFctMessage(__file__, __line__, 3, "'$v_header[filename]' is a file in $p_file_list[$i]");
                            $v_delete_file = true;
                        }
                    }
                }
            }
            if (!$v_delete_file) {
                TrFctMessage(__file__, __line__, 2, "Keep file '$v_header[filename]'");
                if ($p_tar_mode == "tar") {
                    fputs($v_temp_tar, $v_binary_data, 512);
                } else {
                    gzputs($v_temp_tar, $v_binary_data, 512);
                }
                $n = ceil($v_header[size] / 512);
                for ($i = 0; $i < $n; $i++) {
                    TrFctMessage(__file__, __line__, 3, "Read complete 512 bytes block number " . ($i + 1));
                    if ($p_tar_mode == "tar") {
                        $v_content = fread($v_tar, 512);
                        fwrite($v_temp_tar, $v_content, 512);
                    } else {
                        $v_content = gzread($v_tar, 512);
                        gzwrite($v_temp_tar, $v_content, 512);
                    }
                }
                TrFctMessage(__file__, __line__, 2, "Memorize info about file '$v_header[filename]'");
                $p_list_detail[$v_nb] = $v_header;
                $p_list_detail[$v_nb][status] = "ok";
                $v_nb++;
            } else {
                TrFctMessage(__file__, __line__, 2, "Start deletion of '$v_header[filename]'");
                TrFctMessage(__file__, __line__, 4, "Position avant jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
                if ($p_tar_mode == "tar")
                    fseek($v_tar, ftell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                else
                    gzseek($v_tar, gztell($v_tar) + (ceil(($v_header[size] / 512)) * 512));
                TrFctMessage(__file__, __line__, 4, "Position aprпїЅs jump [" . ($p_tar_mode == "tar" ?
                        ftell($v_tar) : gztell($v_tar)) . "]");
            }
            if ($p_tar_mode == "tar")
                $v_end_of_file = feof($v_tar);
            else
                $v_end_of_file = gzeof($v_tar);
        }
        PclTarHandleFooter($v_temp_tar, $p_tar_mode);
        if ($p_tar_mode == "tar") {
            fclose($v_tar);
            fclose($v_temp_tar);
        } else {
            gzclose($v_tar);
            gzclose($v_temp_tar);
        }
        if (!@unlink($p_tarname)) {
            PclErrorLog(-11, "Error while deleting archive name $p_tarname");
        }
        if (!@rename($v_temp_tarname, $p_tarname)) {
            PclErrorLog(-12, "Error while renaming temporary file $v_temp_tarname to archive name $p_tarname");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleUpdate($p_tarname, $p_file_list, &$p_list_detail, $p_tar_mode,
        $p_add_dir, $p_remove_dir)
    {
        TrFctStart(__file__, __line__, "PclTarHandleUpdate", "archive='$p_tarname', list, tar_mode=$p_tar_mode");
        $v_result = 1;
        $v_nb = 0;
        $v_found_list = array();
        if ($p_tar_mode == "tar") {
            TrFctMessage(__file__, __line__, 3, "Open file in binary read mode");
            if (($v_tar = @fopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @fopen($v_temp_tarname, "wb")) == 0) {
                fclose($v_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        } else {
            TrFctMessage(__file__, __line__, 3, "Open file in gzip binary read mode");
            if (($v_tar = @gzopen($p_tarname, "rb")) == 0) {
                PclErrorLog(-2, "Unable to open file '$p_tarname' in binary read mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
            $v_temp_tarname = uniqid("pcltar-") . ".tmp";
            TrFctMessage(__file__, __line__, 2, "Creating temporary archive file $v_temp_tarname");
            if (($v_temp_tar = @gzopen($v_temp_tarname, "wb")) == 0) {
                gzclose($v_tar);
                PclErrorLog(-1, "Unable to open file '$v_temp_tarname' in binary write mode");
                TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
                return PclErrorCode();
            }
        }
        for ($i = 0; $i < sizeof($p_file_list); $i++) {
            $v_found_list[$i] = 0;
            $v_stored_list[$i] = $p_file_list[$i];
            if ($p_remove_dir != "") {
                if (substr($p_file_list[$i], -1) != '/')
                    $p_remove_dir .= "/";
                if (substr($p_file_list[$i], 0, strlen($p_remove_dir)) == $p_remove_dir) {
                    $v_stored_list[$i] = substr($p_file_list[$i], strlen($p_remove_dir));
                    TrFctMessage(__file__, __line__, 3, "Remove path '$p_remove_dir' in file '$p_file_list[$i]' = '$v_stored_list[$i]'");
                }
            }
            if ($p_add_dir != "") {
                if (substr($p_add_dir, -1) == "/")
                    $v_stored_list[$i] = $p_add_dir . $v_stored_list[$i];
                else
                    $v_stored_list[$i] = $p_add_dir . "/" . $v_stored_list[$i];
                TrFctMessage(__file__, __line__, 3, "Add path '$p_add_dir' in file '$p_file_list[$i]' = '$v_stored_list[$i]'");
            }
            $v_stored_list[$i] = PclTarHandlePathReduction($v_stored_list[$i]);
            TrFctMessage(__file__, __line__, 3, "After reduction '$v_stored_list[$i]'");
        }
        clearstatcache();
        while (!($v_end_of_file = ($p_tar_mode == "tar" ? feof($v_tar) : gzeof($v_tar)))) {
            TrFctMessage(__file__, __line__, 3, "Looking for next header ...");
            clearstatcache();
            $v_current_filename = "";
            $v_delete_file = false;
            if ($p_tar_mode == "tar")
                $v_binary_data = fread($v_tar, 512);
            else
                $v_binary_data = gzread($v_tar, 512);
            if (($v_result = PclTarHandleReadHeader($v_binary_data, $v_header)) != 1) {
                if ($p_tar_mode == "tar") {
                    fclose($v_tar);
                    fclose($v_temp_tar);
                } else {
                    gzclose($v_tar);
                    gzclose($v_temp_tar);
                }
                @unlink($v_temp_tarname);
                TrFctEnd(__file__, __line__, $v_result);
                return $v_result;
            }
            if ($v_header[filename] == "") {
                TrFctMessage(__file__, __line__, 2, "Empty block found. End of archive ?");
                continue;
            }
            TrFctMessage(__file__, __line__, 2, "Found file '$v_header[filename]', size '$v_header[size]'");
            for ($i = 0, $v_update_file = false, $v_found_file = false; ($i < sizeof($v_stored_list)) &&
                                                                        (!$v_update_file); $i++) {
                TrFctMessage(__file__, __line__, 4, "Compare with file '$v_stored_list[$i]'");
                if ($v_stored_list[$i] == $v_header[filename]) {
                    TrFctMessage(__file__, __line__, 3, "File '$v_stored_list[$i]' is present in archive");
                    TrFctMessage(__file__, __line__, 3, "File '$v_stored_list[$i]' mtime=" . filemtime($p_file_list[$i]) .
                                                        " " . date("l dS of F Y h:i:s A", filemtime($p_file_list[$i])));
                    TrFctMessage(__file__, __line__, 3, "Archived mtime=" . $v_header[mtime] . " " . date("l dS of F Y h:i:s A",
                                                                                                          $v_header[mtime]));
                    $v_found_file = true;
                    $v_current_filename = $p_file_list[$i];
                    if (filemtime($p_file_list[$i]) > $v_header[mtime]) {
                        TrFctMessage(__file__, __line__, 3, "File '$p_file_list[$i]' need to be updated");
                        $v_update_file = true;
                    } else {
                        TrFctMessage(__file__, __line__, 3, "File '$p_file_list[$i]' does not need to be updated");
                        $v_update_file = false;
                    }
                    $v_found_list[$i] = 1;
                } else {
                    TrFctMessage(__file__, __line__, 4, "File '$p_file_list[$i]' is not '$v_header[filename]'");
                }
            }
            if (!$v_update_file) {
                TrFctMessage(__file__, __line__, 2, "Keep file '$v_header[filename]'");
                if ($p_tar_mode == "tar") {
                    fputs($v_temp_tar, $v_binary_data, 512);
                } else {
                    gzputs($v_temp_tar, $v_binary_data, 512);
                }
                $n = ceil($v_header[size] / 512);
                for ($j = 0; $j < $n; $j++) {
                    TrFctMessage(__file__, __line__, 3, "Read complete 512 bytes block number " . ($j + 1));
                    if ($p_tar_mode == "tar") {
                        $v_content = fread($v_tar, 512);
                        fwrite($v_temp_tar, $v_content, 512);
                    } else {
                        $v_content = gzread($v_tar, 512);
                        gzwrite($v_temp_tar, $v_content, 512);
                    }
                }
                TrFctMessage(__file__, __line__, 2, "Memorize info about file '$v_header[filename]'");
                $p_list_detail[$v_nb] = $v_header;
                $p_list_detail[$v_nb][status] = ($v_found_file ? "not_updated" : "ok");
                $v_nb++;
            } else {
                TrFctMessage(__file__, __line__, 2, "Start update of file '$v_current_filename'");
                $v_old_size = $v_header[size];
                if (($v_result = PclTarHandleAddFile($v_temp_tar, $v_current_filename, $p_tar_mode,
                                                     $v_header, $p_add_dir, $p_remove_dir)) != 1) {
                    if ($p_tar_mode == "tar") {
                        fclose($v_tar);
                        fclose($v_temp_tar);
                    } else {
                        gzclose($v_tar);
                        gzclose($v_temp_tar);
                    }
                    @unlink($p_temp_tarname);
                    TrFctEnd(__file__, __line__, $v_result);
                    return $v_result;
                }
                TrFctMessage(__file__, __line__, 2, "Skip old file '$v_header[filename]'");
                if ($p_tar_mode == "tar")
                    fseek($v_tar, ftell($v_tar) + (ceil(($v_old_size / 512)) * 512));
                else
                    gzseek($v_tar, gztell($v_tar) + (ceil(($v_old_size / 512)) * 512));
                $p_list_detail[$v_nb] = $v_header;
                $p_list_detail[$v_nb][status] = "updated";
                $v_nb++;
            }
            if ($p_tar_mode == "tar")
                $v_end_of_file = feof($v_tar);
            else
                $v_end_of_file = gzeof($v_tar);
        }
        for ($i = 0; $i < sizeof($p_file_list); $i++) {
            if (!$v_found_list[$i]) {
                TrFctMessage(__file__, __line__, 3, "File '$p_file_list[$i]' need to be added");
                if (($v_result = PclTarHandleAddFile($v_temp_tar, $p_file_list[$i], $p_tar_mode, $v_header,
                                                     $p_add_dir, $p_remove_dir)) != 1) {
                    if ($p_tar_mode == "tar") {
                        fclose($v_tar);
                        fclose($v_temp_tar);
                    } else {
                        gzclose($v_tar);
                        gzclose($v_temp_tar);
                    }
                    @unlink($p_temp_tarname);
                    TrFctEnd(__file__, __line__, $v_result);
                    return $v_result;
                }
                $p_list_detail[$v_nb] = $v_header;
                $p_list_detail[$v_nb][status] = "added";
                $v_nb++;
            } else {
                TrFctMessage(__file__, __line__, 3, "File '$p_file_list[$i]' was already updated if needed");
            }
        }
        PclTarHandleFooter($v_temp_tar, $p_tar_mode);
        if ($p_tar_mode == "tar") {
            fclose($v_tar);
            fclose($v_temp_tar);
        } else {
            gzclose($v_tar);
            gzclose($v_temp_tar);
        }
        if (!@unlink($p_tarname)) {
            PclErrorLog(-11, "Error while deleting archive name $p_tarname");
        }
        if (!@rename($v_temp_tarname, $p_tarname)) {
            PclErrorLog(-12, "Error while renaming temporary file $v_temp_tarname to archive name $p_tarname");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandleReadHeader($v_binary_data, &$v_header)
    {
        TrFctStart(__file__, __line__, "PclTarHandleReadHeader", "");
        $v_result = 1;
        if (strlen($v_binary_data) == 0) {
            $v_header[filename] = "";
            $v_header[status] = "empty";
            TrFctEnd(__file__, __line__, $v_result, "End of archive found");
            return $v_result;
        }
        if (strlen($v_binary_data) != 512) {
            $v_header[filename] = "";
            $v_header[status] = "invalid_header";
            TrFctMessage(__file__, __line__, 2, "Invalid block size : " . strlen($v_binary_data));
            PclErrorLog(-10, "Invalid block size : " . strlen($v_binary_data));
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        $v_checksum = 0;
        for ($i = 0; $i < 148; $i++) {
            $v_checksum += ord(substr($v_binary_data, $i, 1));
        }
        for ($i = 148; $i < 156; $i++) {
            $v_checksum += ord(' ');
        }
        for ($i = 156; $i < 512; $i++) {
            $v_checksum += ord(substr($v_binary_data, $i, 1));
        }
        TrFctMessage(__file__, __line__, 3, "Calculated checksum : $v_checksum");
        TrFctMessage(__file__, __line__, 2, "Header : '$v_binary_data'");
        $v_data = unpack("a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor",
                         $v_binary_data);
        $v_header["checksum"] = OctDec(trim($v_data["checksum"]));
        TrFctMessage(__file__, __line__, 3, "File checksum : $v_header[checksum]");
        if ($v_header["checksum"] != $v_checksum) {
            TrFctMessage(__file__, __line__, 2, "File checksum is invalid : $v_checksum calculated, $v_header[checksum] expected");
            $v_header["filename"] = "";
            $v_header["status"] = "invalid_header";
            if (($v_checksum == 256) && ($v_header["checksum"] == 0)) {
                $v_header["status"] = "empty";
                TrFctEnd(__file__, __line__, $v_result, "End of archive found");
                return $v_result;
            }
            PclErrorLog(-13, "Invalid checksum : $v_checksum calculated, " . $v_header["checksum"] .
                             " expected");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        TrFctMessage(__file__, __line__, 2, "File checksum is valid ($v_checksum)");
        $v_header["filename"] = trim($v_data["filename"]);
        TrFctMessage(__file__, __line__, 2, "Name : '$v_header[filename]'");
        $v_header["mode"] = OctDec(trim($v_data["mode"]));
        TrFctMessage(__file__, __line__, 2, "Mode : '" . DecOct($v_header["mode"]) . "'");
        $v_header["uid"] = OctDec(trim($v_data["uid"]));
        TrFctMessage(__file__, __line__, 2, "Uid : '$v_header[uid]'");
        $v_header["gid"] = OctDec(trim($v_data["gid"]));
        TrFctMessage(__file__, __line__, 2, "Gid : '$v_header[gid]'");
        $v_header["size"] = OctDec(trim($v_data["size"]));
        TrFctMessage(__file__, __line__, 2, "Size : '$v_header[size]'");
        $v_header["mtime"] = OctDec(trim($v_data["mtime"]));
        TrFctMessage(__file__, __line__, 2, "Date : " . date("l dS of F Y h:i:s A", $v_header["mtime"]));
        if (($v_header["typeflag"] = $v_data["typeflag"]) == "5") {
            $v_header["size"] = 0;
            TrFctMessage(__file__, __line__, 2, "Size (folder) : '$v_header[size]'");
        }
        TrFctMessage(__file__, __line__, 2, "File typeflag : $v_header[typeflag]");
        $v_header["status"] = "ok";
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }

    function PclTarHandlerDirCheck($p_dir)
    {
        $v_result = 1;
        TrFctStart(__file__, __line__, "PclTarHandlerDirCheck", "$p_dir");
        if ((is_dir($p_dir)) || ($p_dir == "")) {
            TrFctEnd(__file__, __line__, "'$p_dir' is a directory");
            return 1;
        }
        $p_parent_dir = dirname($p_dir);
        TrFctMessage(__file__, __line__, 3, "Parent directory is '$p_parent_dir'");
        if ($p_parent_dir != $p_dir) {
            if ($p_parent_dir != "") {
                if (($v_result = PclTarHandlerDirCheck($p_parent_dir)) != 1) {
                    TrFctEnd(__file__, __line__, $v_result);
                    return $v_result;
                }
            }
        }
        TrFctMessage(__file__, __line__, 3, "Create directory '$p_dir'");
        if (!@mkdir($p_dir, 0777)) {
            PclErrorLog(-8, "Unable to create directory '$p_dir'");
            TrFctEnd(__file__, __line__, PclErrorCode(), PclErrorString());
            return PclErrorCode();
        }
        TrFctEnd(__file__, __line__, $v_result, "Directory '$p_dir' created");
        return $v_result;
    }

    function PclTarHandleExtension($p_tarname)
    {
        TrFctStart(__file__, __line__, "PclTarHandleExtension", "tar=$p_tarname");
        if ((substr($p_tarname, -7) == ".tar.gz") || (substr($p_tarname, -4) == ".tgz")) {
            TrFctMessage(__file__, __line__, 2, "Archive is a gzip tar");
            $v_tar_mode = "tgz";
        } else
            if (substr($p_tarname, -4) == ".tar") {
                TrFctMessage(__file__, __line__, 2, "Archive is a tar");
                $v_tar_mode = "tar";
            } else {
                PclErrorLog(-9, "Invalid archive extension");
                TrFctMessage(__file__, __line__, PclErrorCode(), PclErrorString());
                $v_tar_mode = "";
            }
        TrFctEnd(__file__, __line__, $v_tar_mode);
        return $v_tar_mode;
    }

    function PclTarHandlePathReduction($p_dir)
    {
        TrFctStart(__file__, __line__, "PclTarHandlePathReduction", "dir='$p_dir'");
        $v_result = "";
        if ($p_dir != "") {
            $v_list = explode("/", $p_dir);
            for ($i = sizeof($v_list) - 1; $i >= 0; $i--) {
                if ($v_list[$i] == ".") {
                } else
                    if ($v_list[$i] == "..") {
                        $i--;
                    } else
                        if (($v_list[$i] == "") && ($i != (sizeof($v_list) - 1)) && ($i != 0)) {
                        } else {
                            $v_result = $v_list[$i] . ($i != (sizeof($v_list) - 1) ? "/" . $v_result : "");
                        }
            }
        }
        TrFctEnd(__file__, __line__, $v_result);
        return $v_result;
    }
}
?>
