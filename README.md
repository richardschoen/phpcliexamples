# PHP Command Line (CLI) Example Scripts/Templates
This repository houses sample PHP command line example scripts and templates. I have not found a lot of good sample CLI templates for use with PHP.    

There may be a mix of Multi Platform, Windows, Linux and IBM i specific scripts added here over time. 

## PHP Sample Script List - All Platforms
These are PHP scripts that should work on all platforms.  
- ```phpparms1.php``` - Parse command line parameters and validate them. This example contains techniques to write a clean PHP CLI script that uses try/catch to catch and handle errors and return the appropriate messages via standardard output. This may not fit everyone's style bugt I like it, especially when I want to capture stdout for error logging.
- ```generalfunctions.pnp``` - General functions include module PHP script.

## PHP Sample Script List - IBM i 
These are PHP scripts that should work natively on IBM i from QShell or PASE. Any scripts using ODBC will require the native IBM i Access ODBC drivers to be loaded via the Open Source Package Management or yum commands. You will also need familiarity with calling php from QShell or PASE.  

To integrate IBM i php command line (CLI) scripts to a traditional IBM i job stream or job scheduler, check out the QSHEXEC or QSHBASH commands available as part of the QShell on i utilities. https://www.github.com/richardschoen/qshoni 

```ibmi/phpodbcqueryibmi.php``` - Run SQL query using the IBM i native ODBC driver and export results to a delimited file. The example uses the *LOCAL data source so it will automatically run as the current logged in IBM i user so no user/password is needed.  

Sample call to php command to run the query script:    
```
php -d error_log= phpodbcquery1.php --sqlquery="select * from qiws.qcustcdt"
  --outputfile="/tmp/output.csv"  --replace=true --delimiter=","
```

Sample call to QSHEXEC command to run the query script and display the STDOUT results. If submitting to batch, use PRTSTDOUT(*YES) instead of DSPSTDOUT(*YES) so the STDOUT results log creates a spool file instead of displaying the log. It's good to print the STDOUT log for troubleshooting script failures.   
❗ Notice that if # is passed for single quote SQL query placeholders they will get automatically converted to single quotes in the script so we don't have to do any quote escaping or single quote matching in the CL command line.
```
QSHONI/QSHEXEC CMDLINE('cd /home/richard/phpapps;php -d error_log= phpodbcqueryibmi1.php        
    --sqlquery="select * from qiws.qcustcdt where lstnam=#Henning#" 
    --outputfile="/tmp/output.csv"  --replace=true      
    --delimiter=","')                               
    DSPSTDOUT(*YES)                                                         
```

Sample call using the QSHPHPRUN PHP wrapper command. With ```PHPCMD``` option set to *DEBUG, the PHP cli command is: ```php -d error_log=```. Also the ```DEBUGCMD``` option will display the QSHEXEC command line for debugging. We also set ```ARGDLM``` to *NONE so that we can custom format each argument and its double quotes placement. And of course ```DSPSTDOUT``` is set to *YES so we can display and debug STDOUT interactively when testing.  
```
QSHPHPRUN SCRIPTDIR('/home/richard/phpapps') CHGSCRDIR(*YES)                                     
PHPCMD(*DEBUG) SCRIPTFILE(phpodbcqueryibmi1.php)                   
ARGS('--sqlquery="select * from qiws.qcustcdt where lstname=#Henning#"' 
'--outputfile="/tmp/output.csv"' '--replace=true' '--delimiter=","')                          
ARGDLM(*NONE)                                       
DSPSTDOUT(*YES)                                     
DEBUGCMD(*YES)                                      
```

## Tips

### Debugging your PHP CLI script from command line   
Run your script with the ```-d ```debug flag and ```error_log=``` to debug from the command line and see the results echoed.   
Ex: ```php -d error_log= phpparms1.php  --name="value1" --name2="value2"```


 





