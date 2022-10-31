<?php

/*{{{ v.150906.001 (0.0.1)

    Logging module.

    ---
    Igor Lilliputten
    mailto: igor at lilliputten dot ru
    http://lilliputtem.ru/

    Ivan Pushkin
    mailto: iv dot pushk at gmail dot com

}}}*/

/*{{{ Global variables */

$_LOG_FILE    = 'log.txt'; // default log file name
$_LOG_ENABLED = false;     // set to 'true' for enabling logging

/*}}}*/

function _LOG_CLEAR ()/*{{{*/
{
    global $_LOG_FILE;

    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        // file_put_contents($GLOBALS['_LOG_FILE'], "", LOCK_EX);
        // flush();
        if ( is_file($_LOG_FILE) ) {
            unlink($_LOG_FILE);
        }
    }
}/*}}}*/
function _LOG ($s)/*{{{*/
{
    if ( !empty($GLOBALS['_LOG_ENABLED']) ) {
        $datetime = date('Y.m.d H:i:s');
        file_put_contents($GLOBALS['_LOG_FILE'], $datetime."\t".$s."\n", FILE_APPEND | LOCK_EX);
        flush();
    }
}/*}}}*/
function _LOG_VAR ($s,$p)/*{{{*/
{
    _LOG($s.': '.print_r($p,true));
}/*}}}*/
function _ERROR ($s)/*{{{*/
{
    _LOG('ERROR: '.$s);
}/*}}}*/
