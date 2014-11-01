<?php
/**
 * File operations for AttendanceList
 *
 * The generated attendees.tex is a simple collection of variables in the
 * Format "\newcommand{\M<literal number>}{<name of attendee>}".
 * Literal number is a running number, starting with one and is open ended, so
 * you can modify the meeting.tex to include as many people as you like.
 *
 * PHP version 5
 *
 * @package AttendanceList
 * @author  Peter Hense <peter.hense@gmail.com>
 * @version 0.1
 */

require_once 'ErrorLogging.php';

class AttendeesFile {
  /**
   * Filehandle on local file
   * @var filestream
   */
  private $filehandle;

  /**
   * counter of the attendees
   * @var integer
   */
  private $attendeecounter = 1;

  function __construct() {
      $this->filehandle = @fopen('attendees.tex', "w");
      if (!$this->filehandle) {
        report("fopen of attendees.tex failed");
        exit ("Fatal Error: Cannot create the Attendees file.");
      }

  }

  function __destruct() {
      fclose($this->filehandle);
  }

  /**
   * creates the latex variables and writes them to attendees.tex
   * @param  string $name Name of the Attendee
   * @return null
   */
  public function append_attendee_to_file($name) {
      $content = "\\newcommand{\\M"
      . $this->numberToLiteral($this->attendeecounter)
      . "}{" . $name ."}\n";

      if (!fwrite($this->filehandle, $content)) {
          report("fwrite to attendees.tex failed");
          exit ("Fatal Error: Cannot write attendees to file.");
      }
      $this->attendeecounter += 1;
  }

  /**
   * Converts an integer to its textual representation.
   * @param  integer $num   the number to convert to a textual representation
   * @param  integer $depth the number of times this has been recursed
   * @return string         literal representation of $num
   * @author pib ( http://probablyprogramming.com/ )
   * @license http://www.opensource.org/licenses/mit-license.html MIT License
   */
  private function numberToLiteral($num, $depth=0)
  {
      $num = (int)$num;
      $retval ="";
      if ($num < 0) // if it's any other negative, just flip it and call again
          return "negative " + $this->numberToLiteral(-$num, 0);
      if ($num > 99) // 100 and above
      {
          if ($num > 999) // 1000 and higher
              $retval .= $this->numberToLiteral($num/1000, $depth+3);

          $num %= 1000; // now we just need the last three digits
          if ($num > 99) // as long as the first digit is not zero
              $retval .= $this->numberToLiteral($num/100, 2)."hundred";
          $retval .= $this->numberToLiteral($num%100, 1); // our last two digits
      }
      else // from 0 to 99
      {
          $mod = floor($num / 10);
          if ($mod == 0) // ones place
          {
              if ($num == 1) $retval.="one";
              else if ($num == 2) $retval.="two";
              else if ($num == 3) $retval.="three";
              else if ($num == 4) $retval.="four";
              else if ($num == 5) $retval.="five";
              else if ($num == 6) $retval.="six";
              else if ($num == 7) $retval.="seven";
              else if ($num == 8) $retval.="eight";
              else if ($num == 9) $retval.="nine";
          }
          else if ($mod == 1) // if there's a one in the ten's place
          {
              if ($num == 10) $retval.="ten";
              else if ($num == 11) $retval.="eleven";
              else if ($num == 12) $retval.="twelve";
              else if ($num == 13) $retval.="thirteen";
              else if ($num == 14) $retval.="fourteen";
              else if ($num == 15) $retval.="fifteen";
              else if ($num == 16) $retval.="sixteen";
              else if ($num == 17) $retval.="seventeen";
              else if ($num == 18) $retval.="eighteen";
              else if ($num == 19) $retval.="nineteen";
          }
          else // if there's a different number in the ten's place
          {
              if ($mod == 2) $retval.="twenty ";
              else if ($mod == 3) $retval.="thirty";
              else if ($mod == 4) $retval.="forty";
              else if ($mod == 5) $retval.="fifty";
              else if ($mod == 6) $retval.="sixty";
              else if ($mod == 7) $retval.="seventy";
              else if ($mod == 8) $retval.="eighty";
              else if ($mod == 9) $retval.="ninety";
              $retval = rtrim($retval); //get rid of space at end
              $retval.= $this->numberToLiteral($num % 10, 0);
          }
      } // from 0 to 99

      if ($num != 0)
      {
          if ($depth == 3)
              $retval.=" thousand";
          else if ($depth == 6)
              $retval.=" million";
          if ($depth == 9)
              $retval.=" billion";
      }
      return $retval;
  } // function numberToLiteral($num, $depth=0)
} // class AttendeesFile
?>