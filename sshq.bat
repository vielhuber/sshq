@echo off
REM save current dir
set OLDDIR=%CD%
REM change to script dir
@cd /d "%~dp0"
REM run php file
php sshq.php %*
REM change back
chdir /d %OLDDIR%
REM run.bat
if exist %~dp0run.bat %~dp0run.bat
