<?php
//---------------------------------------------------
// Module: phpgeneralfunctions.php
// Desc: These are general universal functions
//---------------------------------------------------

//---------------------------------------------------
// Function: parseNamedParameters
// Desc: Parse named parameters
//---------------------------------------------------
function parseNamedParameters($argv) {
    $params = [];
    foreach ($argv as $arg) {
        if (strpos($arg, '--') === 0) {
            $parts = explode('=', substr($arg, 2), 2);
            $key = $parts[0];
            $value = $parts[1] ?? true; // Default to true if no value is provided
            $params[$key] = $value;
        }
    }
    return $params;
}

//---------------------------------------------------
// Function: printline
// Desc: Print string value with newline - \n
//---------------------------------------------------
function printline($val) {
   print("{$val}\n");
}

//---------------------------------------------------
// Function: FormatNow
// Desc: Format now date/time stamp into readable txt
// Ex Unix Date Format: /Date(1719792000000+0000)/
//---------------------------------------------------
function FormatNow($fmtstr='Y-m-d-His') {

  try {

    // Format current time
    $dt = date($fmtstr,time());

    return $dt;

    } catch ( Throwable $e){
        // Uncomment for message to console
        //print("Error: FormatNow throwable error:$e\n");
        return "";
    } catch (Exception $e) {
        // Uncomment for message to console
        //print("Error: FormatNow exception error:$e\n");
        return "";
    }

}

//---------------------------------------------------
// Function: FormatDate
// Desc: Format date/time stamp into readable txt
//
// Parms:
// $dateteime1= Datetime field
// $fmtstr=PHP data format string. Default: "M d, Y" Ex:Jun 04, 2024
// "M  d, Y" - Ex: Jun 04, 2024
// "M  j, Y - Ex: Jun 4, 2024
// Link:
// https://www.php.net/manual/en/datetime.formats.php
//---------------------------------------------------
function FormatDate($datetime1,$fmtstr='M j, Y') {

  try {

    //print("Datetime1:".$datetime1."\n");

    // Convert date to string
    $datestr1=strval($datetime1);
    // Create new DateTime object from date/time string
    $date1=new DateTime($datestr1);
    // Format and return the date value as a string
    $dt=$date1->format($fmtstr);
    //print("dt:".$dt."\n");
    return $dt;

    } catch ( Throwable $e){
        // Uncomment for message to console
        print("Error: FormatDate throwable error:$e");
        return "";
    } catch (Exception $e) {
        // Uncomment for message to console
        print("Error: FormatDate exception error:$e");
        return "";
    }

}

//---------------------------------------------------
// Function: CalcDaysFromNow
// Desc: Calculate how many days is selected date from
//       current time. We return as an absolute value. 
// Parms:
// $pate: Date to check against. YYYY-MM-DD format. At least 10 characters
// $absolutedays: True/False. True returns absolute value.
// Returns: 
// Number of days difference or -999999 if errors.
// Negative number means today is xx days past the input date (overdue). 
// Positive number means the input date is xx days in the future from today.
//---------------------------------------------------
function CalcDaysFromNow($pdate,$absolutedays=false) {

  try {

    // Make sure date is at least 10 characters
    if (strlen($pdate) < 10) {
      return -999999;
    }

    //$workdate1=strval($pdate);
    // Get parameter date as time in seconds
    $workdateinput = strtotime(substr(strval($pdate), 0, 10));
    // Get current time in seconds
    $workdatenow = time(); // Current time

    // Calculate difference in absolute seconds
    if ($absolutedays) {
      $datediff = abs($workdateinput-$workdatenow);
    } else{
      $datediff = $workdateinput - $workdatenow;
    }

    // Convert to days
    $datediffrounded=round($datediff / (60 * 60 * 24));

    return $datediffrounded;

    } catch ( Throwable $e){
        return -999999;
    } catch (Exception $e) {
        // Uncomment for message to console
        //print("Error: CalcDaysFromNow exception error:$e");
        return -999999;
    }

}

//---------------------------------------------------
// Function: CalcDaysOverdue
// Desc: Calculate how many days overdue is selected date from
//       current time. We optionally return as an absolute value. 
// Parms:
// $pate: Date to check against. YYYY-MM-DD format. At least 10 characters
// $absolutedays: True/False. True returns absolute value.
// Returns: 
// Number of days difference or -999999 if errors.
// Positive number means today is xx days past the input date (overdue). 
// Negative number means the input date is xx days in the future from today.
//---------------------------------------------------
function CalcDaysOverdue($pdate,$absolutedays=false) {

  try {

    // Make sure date is at least 10 characters
    if (strlen($pdate) < 10) {
      return -999999;
    }

    //$workdate1=strval($pdate);
    // Get parameter date as time in seconds
    $workdateinput = strtotime(substr(strval($pdate), 0, 10));
    // Get current time in seconds
    $workdatenow = time(); // Current time

    // Calculate difference in absolute seconds
    if ($absolutedays) {
      $datediff = abs($workdatenow-$workdateinput);
    } else{
      $datediff = $workdatenow - $workdateinput;
    }

    // Convert to days
    $datediffrounded=round($datediff / (60 * 60 * 24));

    return $datediffrounded;

    } catch ( Throwable $e){
        return -999999;
    } catch (Exception $e) {
        // Uncomment for message to console
        //print("Error: CalcDaysFromNow exception error:$e");
        return -999999;
    }

}

