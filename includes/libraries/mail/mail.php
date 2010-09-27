<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

function mosCreateMail($from = '', $fromname = '', $subject='', $body='') {

    mosMainFrame::addLib('phpmailer');
    $mail = new mosPHPMailer();

    $config = Jconfig::getInstance();

    $mail->PluginDir = JPATH_BASE . DS . 'includes/libraries/phpmailer/';
    $mail->SetLanguage(_LANGUAGE, JPATH_BASE . DS . 'includes/libraries/phpmailer/language/');
    $mail->CharSet = substr_replace(_ISO, '', 0, 8);
    $mail->IsMail();
    $mail->From = $from ? $from : $config->config_mailfrom;
    $mail->FromName = $fromname ? $fromname : $config->config_fromname;
    $mail->Mailer = $config->config_mailer;

    // Add smtp values if needed
    if ($config->config_mailer == 'smtp') {
        $mail->SMTPAuth = $config->config_smtpauth;
        $mail->Username = $config->config_smtpuser;
        $mail->Password = $config->config_smtppass;
        $mail->Host = $config->config_smtphost;
    } else // Set sendmail path
    if ($config->config_mailer == 'sendmail') {
        if (isset($config->config_sendmail))
            $mail->Sendmail = $config->config_sendmail;
    } // if

    $mail->Subject = $subject;
    $mail->Body = $body;

    return $mail;
}

/**
 * Mail function (uses phpMailer)
 * @param string From e-mail address
 * @param string From name
 * @param string/array Recipient e-mail address(es)
 * @param string E-mail subject
 * @param string Message body
 * @param boolean false = plain text, true = HTML
 * @param string/array CC e-mail address(es)
 * @param string/array BCC e-mail address(es)
 * @param string/array Attachment file name(s)
 * @param string/array ReplyTo e-mail address(es)
 * @param string/array ReplyTo name(s)
 * @return boolean
 */
function mosMail($from, $fromname, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null, $replyto = null, $replytoname = null) {
    $config = Jconfig::getInstance();

    // Allow empty $from and $fromname settings (backwards compatibility)
    if ($from == '') {
        $from = $config->config_mailfrom;
    }
    if ($fromname == '') {
        $fromname = $config->config_fromname;
    }

    // Filter from, fromname and subject
    if (!JosIsValidEmail($from) || !JosIsValidName($fromname) || !JosIsValidName($subject)) {
        return false;
    }

    $mail = mosCreateMail($from, $fromname, $subject, $body);

    // activate HTML formatted emails
    if ($mode) {
        $mail->IsHTML(true);
    }

    if (is_array($recipient)) {
        foreach ($recipient as $to) {
            if (!JosIsValidEmail($to)) {
                return false;
            }
            $mail->AddAddress($to);
        }
    } else {
        if (!JosIsValidEmail($recipient)) {
            return false;
        }
        $mail->AddAddress($recipient);
    }
    if (isset($cc)) {
        if (is_array($cc)) {
            foreach ($cc as $to) {
                if (!JosIsValidEmail($to)) {
                    return false;
                }
                $mail->AddCC($to);
            }
        } else {
            if (!JosIsValidEmail($cc)) {
                return false;
            }
            $mail->AddCC($cc);
        }
    }
    if (isset($bcc)) {
        if (is_array($bcc)) {
            foreach ($bcc as $to) {
                if (!JosIsValidEmail($to)) {
                    return false;
                }
                $mail->AddBCC($to);
            }
        } else {
            if (!JosIsValidEmail($bcc)) {
                return false;
            }
            $mail->AddBCC($bcc);
        }
    }
    if ($attachment) {
        if (is_array($attachment)) {
            foreach ($attachment as $fname) {
                $mail->AddAttachment($fname);
            }
        } else {
            $mail->AddAttachment($attachment);
        }
    }
    //Important for being able to use mosMail without spoofing...
    if ($replyto) {
        if (is_array($replyto)) {
            reset($replytoname);
            foreach ($replyto as $to) {
                $toname = ((list($key, $value) = each($replytoname)) ? $value : '');
                if (!JosIsValidEmail($to) || !JosIsValidName($toname)) {
                    return false;
                }
                $mail->AddReplyTo($to, $toname);
            }
        } else {
            if (!JosIsValidEmail($replyto) || !JosIsValidName($replytoname)) {
                return false;
            }
            $mail->AddReplyTo($replyto, $replytoname);
        }
    }
    $mailssend = $mail->Send();
    return $mailssend;
}

// mosMail
function JosIsValidEmail($email) {
    return is_string(filter_var($_text,FILTER_VALIDATE_EMAIL));
}

function JosIsValidName($string) {
    $invalid = preg_match('/[\x00-\x1F\x7F]/', $string);
    return ($invalid) ? false : true;
}