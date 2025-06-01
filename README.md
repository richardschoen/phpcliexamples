# PHP Command Line (CLI) Example Scripts/Templates
This repository houses sample PHP command line example scripts and templates. I have not found a lot of good sample CLI templates for use with PHP.    

There may be a mix of Windows, Linux and IBM i specific scripts.   

## PHP Sample Script List - All Platforms
These are PHP scripts that should work on all platforms.  
- ```phpparms1.php``` - Parse command line parameters and validate them. This example contains techniques to write a clean PHP CLI script that uses try/catch to catch and handle errors and return the appropriate messages via standardard output. This may not fit everyone's style bugt I like it, especially when I want to capture stdout for error logging.
- ```hgenralfunctions.pnp``` - General functions include module PHP script.

## PHP Sample Script List - IBM i 
These are PHP scripts that should work with IBM i.

## Tips

### Debugging your PHP CLI script from command line   
Run your script with the ```-d ```debug flag and ```error_log=``` to debug from the command line and see the results echoed.   
Ex: ```php -d error_log= phpparms1.php  --name="value1" --name2="value2"```


 





