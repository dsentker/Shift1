@echo off
if "%PHP_PEAR_BIN_DIR%" == "" set PHP_PEAR_BIN_DIR=C:\xampp\php
GOTO RUN

:RUN
%PHP_PEAR_BIN_DIR%\phpunit -v