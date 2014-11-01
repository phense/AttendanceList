<?php
/**
 * Simple Error Log
 *
 * Opens errors.log to append Error-Messages via the report-function.
 * There are no failsaves yet, and no limit on how often this file is called.
 * However, since the report-function should only be called before an exit(),
 * i don't see much harm it it.
 *
 * PHP version 5
 *
 * @package AttendanceList
 * @author Peter Hense <peter.hense@gmail.com>
 * @version  0.1
 */


/**
 * Writes Error-Messages to a Logfile.
 *
 * The Format is intended to be:
 * [date and time] <ip adress> Error-Messages
 * @param  string $errorcontent content of the error message
 * @return null
 */
function report($errorcontent) {
    // open errorlog (File)
    $errorhandle = @fopen('errors.log', "a") or
        exit ("Fatal Error: Cannot access Error log.");

    // ensure correct timezone
    date_default_timezone_set('Europe/Berlin');
    // write string to error log
    $content = "[" . date("d.m.y H:i") . "] <" . $_SERVER['REMOTE_ADDR']
            . "> " . $errorcontent . "\n";

    if (!fwrite($errorhandle, $content)) {
        exit("Fatal Error: Cannot write to Error log.");
    }

    // close errorlog (File)
    fclose($errorhandle);
}
?>