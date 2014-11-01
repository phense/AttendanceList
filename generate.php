<?php
/**
 * Main file of AttendanceList Script
 *
 * This file includes some simple functions to generate a LaTeX-File containing
 * LaTeX-Variables used in meetings.tex, then starts a compilation-run to
 * render and display the pdf-file
 *
 * requires a working LaTeX-System with latex, dvips and ps2pdf inside the
 * environment
 *
 * PHP version 5
 *
 * @package    AttendanceList
 * @author     Peter Hense <peter.hense@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    0.1
 * @since      File available since Release 0.1
 */

//////////////////////////////////////
// DEFINE THE INPUT SOURCE YOU WANT //
// SET ALL FALSE TO USE THE EXAMPLE //
//////////////////////////////////////

define('USE_SQL_DB', true);

////////////////////////
// SCRIPT STARTS HERE //
////////////////////////
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); // debug
error_reporting(E_ERROR); // error only


require_once 'ErrorLogging.php';
require 'AttendeesFile.php';
require 'MySqlDB.php';

/**
 * Generates attendees.tex from selected input source.
 *
 * Note: Inputsource is selected via global define.
 * @return null
 */
function generateTEX() {
    $file = new AttendeesFile();

    if(USE_SQL_DB == true) {
        $sqldb = new MySqlDB();

        // statement 1
        $query_result = $sqldb->query_statement_1();
        foreach($query_result as $attendeename) {
            $file->append_attendee_to_file($attendeename);
        }

        // statement 2
        $query_result = $sqldb->query_statement_2();
        foreach($query_result as $attendeename) {
            $file->append_attendee_to_file($attendeename);
        }

        // statement 3
        $query_result = $sqldb->query_statement_3();
        foreach($query_result as $attendeename) {
            $file->append_attendee_to_file($attendeename);
        }

        // statement 4
        $query_result = $sqldb->query_statement_4();
        foreach($query_result as $attendeename) {
            $file->append_attendee_to_file($attendeename);
        }

        $sqldb = null;
    }

    $file = null;
 }


/**
 * Generating PDF Output via LateX
 *
 * Generates the meeting.pdf from the LaTeX source and displays the file in
 * the browser (application/pdf).
 * If, for some reason, no attendees.tex was generated, the latex compilation
 * will fallback to the given example.
 * Warning: Uses shell_exec
 * Warning: latex, dvips and ps2pdf need to be installed and inside the path-
 *          environment
 *
 * @return  null
 **/
function generatePDF() {
    // TODO: you want a cache and not generate the pdf every time someone
    //       is pressing a button
    $meeting = 'meeting.pdf';

    /*
     * because we are using pstricks to generate the seating order,
     * we need to generate DVI->PS->PDF. Using pdflatex would just
     * scramble the output
     */
    shell_exec("latex -interaction=batchmode meeting.tex");
    shell_exec("dvips -P pdf meeting.dvi");
    shell_exec("ps2pdf meeting.ps");
    // cleanup
    shell_exec("rm meeting.aux meeting.dvi meeting.log meeting.ps attendees.tex");
    // show the generated PDF File and exit
    if (file_exists($meeting)) {
        header("Content-Type: application/pdf");
        header('Content-Length: ' . filesize($meeting));
        header("Content-Disposition:inline;filename=$meeting");
        readfile($meeting);
        exit();
    }
    else {
        report("PDF does not exist after LaTeX run");
        exit ("Fatal Error: PDF could not be created. Check your LaTeX System.");
    }
}

generateTEX();
generatePDF();
?>