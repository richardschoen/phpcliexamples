<?php
//-------------------------------------------------------------
// Script name: phpodbcqueryibmi1.php
// Desc: Query IBM i Database via ODBC and export to delimited file.
//       By default it uses the *LOCAL DSN for IBMi.
//
// Ex PHP command line call: 
// php -d error_log= phpodbcquery1.php --sqlquery="select * from qiws.qcustcdt" 
//         --outputfile="/tmp/output.csv"  --replace=true --delimiter=","
// 
// Example IBMi call using QSHEXEC CL command - Part of QSHONI:
// https://www.github.com/richardschoen/qshoni
// # values are replaced with single quotes to circumvent mixed quote issues
// if QSHBASH is used instead of QSHEXEC. 
// QSHEXEC CMDLINE('cd /home/richard/phpapps;php -d error_log=       
//    phpodbcqueryibmi1.php 
//    --sqlquery="select * from qiws.qcustcdt where lstnam=#Henning#" 
//    --outputfile="/tmp/output.csv"  --replace=true      
//    --delimiter=","')                               
//    DSPSTDOUT(*YES)                                                         
//-------------------------------------------------------------

$delim="|";
$quotechar='"';
$scriptname=$argv[0];
$scriptdesc="SQL Query CLI Export to Delimited File App";
$exitcode=0;
$exitmessage="";
$exitexception="";
$dashes="-------------------------------------------------------------";
$parmname="";
$reccount=0;
// use *LOCAL ODBC connection and trim char fields
$connstring="DSN=*LOCAL;TrimCharFields=1";

//-------------------------------------------------------------
// Main script 
//-------------------------------------------------------------

try {

      // Get the named parameters
      $params = parseNamedParameters($argv);

      // Output script overview info to stdout
      printline($dashes);    
      printline("{$scriptdesc}-{$scriptname}"); 
      $now1=FormatNow();
      printline("Processing Start-{$now1}"); 

      printline("Connecting to DB2 database via ODBC.");

      // Connect now using soft coded IBMi access connection string.
      $pdo=new PDO("odbc:{$connstring}");

      if( $pdo ) {
           printline("Connection established.");
      }else{
            throw new Exception("ODBC connection could not be established");
      }

     // SQL query parameter
     $parmname="sqlquery";
     if (isset($params[$parmname])) {
        //printline("{$parmname} value: {$params[$parmname]}");
     } else {
        throw new Exception("--{$parmname} parameter not provided");
     }

     // Output file parameter
     $parmname="outputfile";
     if (isset($params[$parmname])) {
        //printline("{$parmname} value: {$params[$parmname]}");
     } else {
        throw new Exception("--{$parmname} parameter not provided");
     }

     // Replace output file parameter
     $parmname="replace";
     if (isset($params[$parmname])) {
        //printline("{$parmname} value: {$params[$parmname]}");
     } else {
        throw new Exception("--{$parmname} parameter not provided");
     }

     // Replace output file parameter
     $parmname="delimiter";
     if (isset($params[$parmname])) {
        //printline("{$parmname} value: {$params[$parmname]}");
        $delim=$params[$parmname];
     } else {
        // It's OK not to pass delimiteer. We will use default
        //throw new Exception("--{$parmname} parameter not provided");
     }

     // Output parameters
     printline("Parameter values:");
     // in sqlquery, replace # with single quotes. Resolves 
     // the issue of passing mixed quotes in an SQL statement.
     // We bypass the issue by passing # where we want a single quote
     // in our SQL statement.
     $parmsqlquery = $params['sqlquery'];
     $parmsqlquery=str_replace("#","'",$parmsqlquery);
     $parmoutputfile = $params['outputfile'];
     $parmreplace = strToBool($params['replace']);
     $parmdelimiter = $delim;
     printline("sqlquery: {$parmsqlquery}");
     printline("outputfile: {$parmoutputfile}");
     printline("replace: {$parmreplace}");
     printline("delimiter: {$parmdelimiter}");

     // Query to fetch data
     printline("Executing SQL query now.");
     $stmt = $pdo->prepare($parmsqlquery);
     $stmt->execute();

     // See if output file exists
     if (file_exists($parmoutputfile)) {
        if ($parmreplace==true) {
           unlink($parmoutputfile);
        } else {
            throw new Exception("Output file {$parmoutputfile} exists and replace not selected.");
        }
     }

     // Open a file in write mode
     $filename = $parmoutputfile;
     $file = fopen($filename, 'w');

     // Fetch column names and write as the first row in the CSV
     printline("Exporting query results to file {$filename}");
     $columns = array_keys($stmt->fetch(PDO::FETCH_ASSOC));
     fputcsv($file, $columns,$parmdelimiter,$quotechar);

     // Reset the cursor and fetch data row by row
     $stmt->execute(); // Re-execute to fetch data again
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         fputcsv($file, $row,$delim,$quotechar);
         $reccount += 1;
     }
     printline("Total records exported: {$reccount}");

     // Close the file
     fclose($file);

     printline("Disconnecting from DB2 database.");

     // Close the connection
     $pdo = null;
     
     $now1=FormatNow();
     printline("Processing End/Complete-{$now1}"); 
    
     $exitcode=0;
     $exitmessage="Data successfully exported to {$filename}";    

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
// Function: strToBool
// Desc: Convert string value to boolean value
//
// Parms:
// $str = String input
// Return:
// true or false
//---------------------------------------------------
function strToBool($str){
    if($str === 'true' || $str === 'TRUE' || $str === 'True' || $str === 'on' || $str === 'On' || $str === 'ON'){
        $str = true;
    }else{
        $str = false;
    }
    return $str;
}
