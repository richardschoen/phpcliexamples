# PHP Command Line (CLI) Example Scripts/Templates
This repository houses sample PHP command line example scripts and templates. I have not found a lot of good sample CLI templates for use with PHP.    

There may be a mix of Multi Platform, Windows, Linux and IBM i specific scripts added here over time. 

## PHP Sample Script List - All Platforms
These are PHP scripts that should work on all platforms.  
- ```phpparms1.php``` - Parse command line parameters and validate them. This example contains techniques to write a clean PHP CLI script that uses try/catch to catch and handle errors and return the appropriate messages via standardard output. This may not fit everyone's style bugt I like it, especially when I want to capture stdout for error logging.
- ```generalfunctions.pnp``` - General functions include module PHP script.

## PHP Sample Script List - IBM i 
These are PHP scripts that should work natively on IBM i from QShell or PASE. Any scripts using ODBC will require the native IBM i Access ODBC drivers to be loaded via the OPen Source Package Management or yum commands.      
To integrate IBM i php command line (CLI) scripts to a traditional IBM i job stream or job scheduler, check out the QSHEXEC or QSHBASH commands available as part of the QShell on i utilities. https://www.github.com/richardschoen/qshoni 

```ibmi/phpodbcqueryibmi.php``` - Run SQL query using the IBM i native ODBC driver and export results to a delimited file. The example uses the *LOCAL data source so it will automatically run as the current logged in IBM i user so no user/password is needed.   

## Tips

### Debugging your PHP CLI script from command line   
Run your script with the ```-d ```debug flag and ```error_log=``` to debug from the command line and see the results echoed.   
Ex: ```php -d error_log= phpparms1.php  --name="value1" --name2="value2"```


 





