
<?php
//-------------------------------------------------------------
// Script name: phpparms1.php
// Desc: Parse command line parameters.
// The command line below runs the script in debug mode and displays any errors.
// Ex: php -d error_log= phpparms1.php  --name="value1" --name2="value2"
//-------------------------------------------------------------

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


//-------------------------------------------------------------
// Main script 
//-------------------------------------------------------------
$scriptname=$argv[0];
$scriptdesc="Sample Parameter passing CLI app";
$exitcode=0;
$exitmessage="";
$exitexception="";
$dashes="-------------------------------------------------------------";

try {

    // Get the named parameters
    $params = parseNamedParameters($argv);

    // Output script overview info to stdout
    printline($dashes);    
    printline("{$scriptdesc} - {$scriptname}"); 
    $now1=FormatNow();
    printline("Processing Start -  {$now1}"); 

    printline("We are simply parsing parameters and determining if all there."); 

    
    // Check for --name parm
    // If found just echo it
    if (isset($params['name'])) {
        printline("--name value: {$params['name']}");
    } else {
        throw new Exception('No --name parameter not provided');
    }

    // Check for --name2 parm
    // If found just echo it
    if (isset($params['name2'])) {
        printline("--name2 value: {$params['name2']}");
    } else {
        throw new Exception('--name2 parameter not provided');
    }

    $now1=FormatNow();
    printline("Processing End/Complete - {$now1}"); 
    
    $exitcode=0;
    $exitmessage="Processing completed successfully";    
    
  // Handle errors  
} catch (Throwable $e){
  $exitexception=$e;
  $exitmessage="{$e->getMessage()}";
  $exitcode=-2;
  
} catch (Exception $e) {
  $exitexception=$e;
  $exitmessage="{$e->getMessage()}";
  $exitcode=-2;
  
  // Final exit output return code and message
} finally {
  // Output script completion info to stdout
  printline("ExitCode:{$exitcode}"); 
  printline("ExitMessage:{$exitmessage}"); 
  printline("ExitException:{$exitexception}"); 
  printline($dashes);    
  exit($exitcode);
}  

