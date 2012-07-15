<?php

/**
 * This function is used to transform the given text into quoted printable format
 * In fact the decode function exists in Php core, but not the encode ...
 *
 * @param String $sStr
 *
 * @return String
 */
if (!function_exists ('quoted_printable_encode')) {
    public function quoted_printable_encode ($sStr)
    {
        return str_replace ("%", "=", rawurlencode ($sStr));
    }
}
/**
 * Used to send basic or complex emails
 *
 * @package SimpleMail
 * @filesource SimpleMail.php
 *
 * @author Cyril Nicodème
 * @version 0.1
 *
 * @since 09/07/2008
 *
 * @license GNU/GPL
 */
class SimpleMail
{
    /**
     * @var Array $_aProperties
     * Contain the property for the email
     */
    private $_aProperties = array (	'XPriority' 				=> 3,	// To 1 (High) at 5 (Low), 3 is the common
                                'Sender' 					=> '', 	// Default is FROM value
                                'ReplyTo' 				=> '', 	// Default is FROM value, Reply to this email
                                'ReturnPath' 				=> '', 	// Default is FROM value, Mail for delivery failed response
                                'From' 					=> '',
                                'To' 					=> array (),
                                'Cc' 					=> array (),
                                'Bcc' 					=> array (),
                                'DispositionNotificationTo'	=> '',
                                'XMailer' 				=> 'MyPrettySociety Mailer',
                                'Organisation' 			=> 'MyPrettySociety',
                                'Date' 					=> '',
                                'MimeVersion' 				=> '1.0',
                                'Subject' 				=> '',
                                'AbuseContact'				=> '',
                                'Charset'					=> 'Utf-8',
                                'Bodies' 					=> array (),
                                'Attachment'				=> array ()
                                );

    /**
     * @var String $_sBreakLine
     * Breakline style
     */
    private $_sBreakLine = "\n";

    /**
     * Constructor. Set the Date and optionnaly some properties
     *
     * @param Array $aProperties (Optionnal) : The properties to set more quickly
     */
    public function __construct ($aProperties = null)
    {
        if (!function_exists ('mail'))
            throw new Exception ('Function "mail" must exists to use this class');

        $this->_aProperties['Date'] = date("D, j M Y H:i:s -0600");

        if (isset ($aProperties)) {
            foreach ($aProperties as $sKey=>$mProperty) {
                $this->__set ($sKey, $mProperty);
            }
        }
    }

    /**
     * Magic Method setter.
     * Set the properties easily
     *
     * @param String $sKey   : The name of the property
     * @param Mixed  $mValue : The value to set
     *
     * @return Void
     */
    public function __set ($sKey, $mValue)
    {
        if (is_int ($this->_aProperties[$sKey]) && !is_int ($mValue))
            throw new Exception ('Invalid type, must be an Int');
        else if (is_string ($this->_aProperties[$sKey]) && !is_string ($mValue))
            throw new Exception ('Invalid type, must be an String');
        else if (is_array ($this->_aProperties[$sKey]) && !is_array ($mValue))
            throw new Exception ('Invalid type, must be an String');
        else {
            $this->_aProperties[$sKey] = $mValue;
        }
    }

    /**
     * Magic Method getter.
     * Retrieve the property nicely
     *
     * @param String $sKey : The name of the property to retrieve
     *
     * @return Mixed
     */
    public function __get ($sKey)
    {
        if (!isset ($this->_aProperties[$sKey]))
            throw new Exception ('Invalid key "'.$sKey.'"');

        return $this->_aProperties[$sKey];
    }

    /**
     * Add an attachment to the email (will be found on Multipart/mixed)
     *
     * @param String       $sFilePath : The path of the file to send
     * @param String       $sType     (Optionnal, default set to 'Application/Octet-Stream') : The content Type
     * @param String       $sFileName (Optionnal) : The filename to display for the recipient
     * @param iCompression $oCompress (Optionnal) : An instance of iCompress Class for compress the file
     *
     * @return Void
     */
    public function addAttachment ($sFilePath, $sType = 'Application/Octet-Stream', $sFileName = null, iCompression $oCompress = null, $sCid = null)
    {
        if (!file_exists ($sFilePath) || !is_readable ($sFilePath))
            throw new Exception ('The file "'.$sFilePath.'" is unreadable.');

        if (!is_string ($sType))
            throw new Exception ('Type must be a String');

        if (!isset ($sFileName))
            $sFileName = substr ($sFilePath, strrpos ($sFilePath, '/'));
        else if (!is_string ($sFileName))
            throw new Exception ('Filename must be a String');

        $sContent = file_get_contents ($sFilePath);

        if (isset ($oCompress))
            $sContent = $oCompress->compress ($sContent);

        $iCountAttachments = count ($this->_aProperties['Attachment']);

        $this->_aProperties['Attachment'][$iCountAttachments]['ContentType'] = $sType;
        $this->_aProperties['Attachment'][$iCountAttachments]['ContentTransfertEncoding'] = 'base64';
        $this->_aProperties['Attachment'][$iCountAttachments]['ContentDisposition'] = (isset ($sCid)) ? 'inline' : 'attachment';
        $this->_aProperties['Attachment'][$iCountAttachments]['Filename'] = $sFileName;

        if (isset ($sCid))
            $this->_aProperties['Attachment'][$iCountAttachments]['Content-ID'] = $sCid;

        $this->_aProperties['Attachment'][$iCountAttachments]['Content'] = chunk_split (base64_encode ($sContent));
    }

    /**
     * Add a text to the email
     *
     * @param String $sBody    : The text to add
     * @param String $sType    (Optionnal, default to 'text/plain') : The content type
     * @param String $sCharset (Optionnal, default to 'Utf-8') : The Charset
     *
     * @return Void
     */
    public function addBody ($sBody, $sType = 'text/plain', $sCharset = null)
    {
        if (!is_string ($sBody))
            throw new Exception ('Body must be a String');

        if (!is_string ($sType))
            throw new Exception ('Type must be a String');

        if (!isset ($sCharset))
            $sCharset = $this->_aProperties['Charset'];

        if (!is_string ($sCharset))
            throw new Exception ('Charset must be a String');

        $iCountBodies = count ($this->_aProperties['Bodies']);

        $this->_aProperties['Bodies'][$iCountBodies]['ContentType'] = $sType;
        $this->_aProperties['Bodies'][$iCountBodies]['Charset'] = $sCharset;
        $this->_aProperties['Bodies'][$iCountBodies]['ContentTransfertEncoding'] = 'quoted-printable';
        $this->_aProperties['Bodies'][$iCountBodies]['ContentDisposition'] = 'inline';

        if ($sType == 'text/html')
            $sBody = preg_replace_callback ('#src[\ ]*=[\ ]*["|\']([^"|\']*)["|\']#i', array ($this, 'inlineAttachment'), $sBody);

        $this->_aProperties['Bodies'][$iCountBodies]['Content'] = quoted_printable_encode (chunk_split ($sBody, 76, $this->_sBreakLine));
    }

    /**
     * Send an Email after checking the kind of structure (Normal, Multipart/Alternative, Multipart/Mixed, etc)
     *
     * @return Void
     */
    public function send ()
    {
        if (count ($this->_aProperties['To']) == 0 && count ($this->_aProperties['Cc']) == 0 && count ($this->_aProperties['Bcc']) == 0)
            throw new Exception ('You need to specify at least one recipient (To, Cc or Bcc)');

        $iCountBodies = count ($this->_aProperties['Bodies']);
        $iCountAttachments = count ($this->_aProperties['Attachment']);

        if (($iCountBodies + $iCountAttachments) == 1) { // Qu'un seul truc !
            $sHeaders = $this->_createHeaders ();

            if ($iCountBodies == 1)
                $sHeaders .= $this->_createSection ($this->_aProperties['Bodies'][0]);
            else
                $sHeaders .= $this->_createSection ($this->_aProperties['Attachment'][0]);

            $sHeaders .= $this->_sBreakLine.$this->_sBreakLine;

        } elseif ($iCountBodies == 1 && $iCountAttachments > 0) { // Mixed
            $sBoundary = $this->_boundaryGenerate ();

            $sHeaders = $this->_createHeaders ();

            $sHeaders .= 'Content-Type: multipart/mixed;'.$this->_sBreakLine."\t".'boundary="'.$sBoundary.'"'.$this->_sBreakLine.$this->_sBreakLine;

            $sHeaders .= $this->_createSection ($this->_aProperties['Bodies'][0], $sBoundary);

            foreach ($this->_aProperties['Attachment'] as $aAttachment) {
                $sHeaders .= $this->_createSection ($aAttachment, $sBoundary);
            }

            $sHeaders .= '--'.$sBoundary.'--'.$this->_sBreakLine.$this->_sBreakLine;
        } elseif ($iCountBodies > 1 && $iCountAttachments == 0) { // Alternative
            $sBoundary = $this->_boundaryGenerate ();

            $sHeaders = $this->_createHeaders ();

            $sHeaders .= 'Content-Type: multipart/alternative;'.$this->_sBreakLine."\t".'boundary="'.$sBoundary.'"'.$this->_sBreakLine.$this->_sBreakLine;

            foreach ($this->_aProperties['Bodies'] as $aBody) {
                $sHeaders .= $this->_createSection ($aBody, $sBoundary);
            }

            $sHeaders .= '--'.$sBoundary.'--'.$this->_sBreakLine.$this->_sBreakLine;
        } elseif ($iCountBodies > 1 && $iCountAttachments >= 1) { // Mixed + Alternative
            $sMixedBoundary = $this->_boundaryGenerate ();
            $sAlternativeBoundary = $this->_boundaryGenerate ();

            $sHeaders = $this->_createHeaders ();

            $sAlternativeBody = 'Content-Type: multipart/alternative;'.$this->_sBreakLine."\t".'boundary="'.$sAlternativeBoundary.'"'.$this->_sBreakLine.$this->_sBreakLine;

            foreach ($this->_aProperties['Bodies'] as $aBody) {
                $sAlternativeBody .= $this->_createSection ($aBody, $sAlternativeBoundary);
            }

            $sAlternativeBody .= '--'.$sAlternativeBoundary.'--'.$this->_sBreakLine.$this->_sBreakLine;

            $sHeaders .= 'Content-Type: multipart/mixed;'.$this->_sBreakLine."\t".'boundary="'.$sMixedBoundary.'"'.$this->_sBreakLine.$this->_sBreakLine;

            $sHeaders .= '--'.$sMixedBoundary.$this->_sBreakLine;
            $sHeaders .= $sAlternativeBody;

            foreach ($this->_aProperties['Attachment'] as $aAttachment) {
                $sHeaders .= $this->_createSection ($aAttachment, $sMixedBoundary);
            }

            $sHeaders .= '--'.$sMixedBoundary.'--'.$this->_sBreakLine.$this->_sBreakLine;
        } else
            throw new Exception ('Invalid Email structure');

        $sSubject = '=?'.$this->_aProperties['Charset'].'?q?'.quoted_printable_encode ($this->_aProperties['Subject']).'?=';

        if (@mail (implode (', ', $this->_aProperties['To']), $sSubject, '', $sHeaders) === false)
            throw new Exception ('An error occured while sending the email');
    }

    /**
     * Modify the src="" element to be joined with the mail as an inline statement
     *
     * @param Array $aMatches
     *
     * @return String
     */
    public function inlineAttachment ($aMatches)
    {
        $sCID = $this->_cidGenerate ();
        try {
            $this->addAttachment ($aMatches[1], MimeType::get ($aMatches[1]), null, null, $sCID);

            return 'src="cid:'.$sCID.'"';
        } catch (Exception $oE) {
            return $aMatches[0];
        }
    }

    /**
     * Create the headers with the values specified in the $_aProperties array
     *
     * @return String
     */
    private function _createHeaders ()
    {
        if (empty ($this->_aProperties['From']))
            throw new Exception ('From must be set !');

        if (empty ($this->_aProperties['ReplyTo']))
            $this->_aProperties['ReplyTo'] = $this->_aProperties['From'];

        if (empty ($this->_aProperties['ReturnPath']))
            $this->_aProperties['ReturnPath'] = $this->_aProperties['From'];

        if (empty ($this->_aProperties['Sender']))
            $this->_aProperties['Sender'] = $this->_aProperties['From'];

        $sHeaders = 'X-Priority: '.$this->_aProperties['XPriority'].$this->_sBreakLine;
        $sHeaders .= 'X-Mailer: '.$this->_aProperties['XMailer'].$this->_sBreakLine;
        $sHeaders .= 'Organisation: '.$this->_aProperties['Organisation'].$this->_sBreakLine;
        $sHeaders .= 'Date: '.$this->_aProperties['Date'].$this->_sBreakLine;
        $sHeaders .= 'MIME-version: '.$this->_aProperties['MimeVersion'].$this->_sBreakLine;
        $sHeaders .= 'From: '.$this->_aProperties['From'].$this->_sBreakLine;
        $sHeaders .= 'Reply-To: '.$this->_aProperties['ReplyTo'].$this->_sBreakLine;
        $sHeaders .= 'Return-Path: '.$this->_aProperties['ReturnPath'].$this->_sBreakLine;
        $sHeaders .= 'Sender: '.$this->_aProperties['Sender'].$this->_sBreakLine;
        $sHeaders .= 'X-Sender: '.$this->_aProperties['Sender'].$this->_sBreakLine;
        //$sHeaders .= 'Subject: '.$this->_aProperties['Subject'].$this->_sBreakLine;

        if (!empty ($this->_aProperties['DispositionNotificationTo'])) {
            $sHeaders .= 'Disposition-Notification-To: '.$this->_aProperties['DispositionNotificationTo'].$this->_sBreakLine;
            $sHeaders .= 'X-Confirm-Reading-To: '.$this->_aProperties['DispositionNotificationTo'].$this->_sBreakLine;
            $sHeaders .= 'Return-receipt-to: '.$this->_aProperties['DispositionNotificationTo'].$this->_sBreakLine;
        }

        if (!empty ($this->_aProperties['AbuseContact'])) {
            $sHeaders .= 'X-abuse-contact: '.$this->_aProperties['AbuseContact'].$this->_sBreakLine;
        }
/*
        if (count ($this->_aProperties['To']) > 0)
            $sHeaders .= 'To:'.implode (',', $this->_aProperties['To']).$this->_sBreakLine;
*/
        if (count ($this->_aProperties['Cc']) > 0)
            $sHeaders .= 'Cc:'.implode (',', $this->_aProperties['Cc']).$this->_sBreakLine;

        if (count ($this->_aProperties['Bcc']) > 0)
            $sHeaders .= 'Bcc:'.implode (',', $this->_aProperties['Bcc']).$this->_sBreakLine;

        return $sHeaders;
    }

    /**
     * Create a section for the email
     *
     * @param Array  $aElement  : The element informations and content
     * @param String $sBoundary (Optionnal) : The boundary to delimite the parts of the email
     *
     * @return String
     */
    private function _createSection ($aElement, $sBoundary = null)
    {
        $sMessage = '';
        if (isset ($sBoundary))
            $sMessage = '--'.$sBoundary.$this->_sBreakLine;

        $sMessage .= 'Content-Type: '.$aElement['ContentType'];

        if (!empty ($aElement['Charset']))
            $sMessage .= '; charset="'.$aElement['Charset'].'"';
        else if ($aElement['ContentTransfertEncoding'] == 'base64')
            $sMessage .= '; name="'.$aElement['Filename'].'"';

        $sMessage .= $this->_sBreakLine;
        $sMessage .= 'Content-Transfer-Encoding: '.$aElement['ContentTransfertEncoding'].$this->_sBreakLine;
        $sMessage .= 'Content-Disposition: '.$aElement['ContentDisposition'];

        if (!empty ($aElement['Filename']))
            $sMessage .= '; filename="'.$aElement['Filename'].'"';

        $sMessage .= $this->_sBreakLine;

        if (!empty ($aElement['Content-ID']))
            $sMessage .= 'Content-ID: <'.$aElement['Content-ID'].'>'.$this->_sBreakLine;


        $sMessage .= $this->_sBreakLine;
        $sMessage .= $aElement['Content'].$this->_sBreakLine.$this->_sBreakLine;

        return $sMessage;
    }

    /**
     * Generate a Boundary
     *
     * @return String
     */
    private function _boundaryGenerate ()
    {
        return '---=Part_'.md5 (uniqid (mt_rand ()));
    }

    /**
     * Generate a CID
     *
     * @return String
     */
    private function _cidGenerate ()
    {
        return 'CID_'.md5 (uniqid (mt_rand ()));
    }
}
