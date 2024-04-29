@echo off
%1 mshta vbscript:CreateObject("Shell.Application").ShellExecute("cmd.exe","/c %~s0 ::","","runas",1)(window.close)&&exit
cd /d "%~dp0"

C:\xampp\mysql\bin\mysqldump -hlocalhost -uroot -p1234 base_captis > C:\xampp\htdocs\captis\public\base_captis.sql

exit

