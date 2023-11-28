Set objShell = CreateObject("WScript.Shell")

' Change directory to the specified path
objShell.CurrentDirectory = "C:\escpos-php-development\example\interface"

' Run the PHP server command silently
objShell.Run "php -S localhost:8080", 0, False
