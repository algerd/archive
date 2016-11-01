@echo off
if not exist main (
	mkdir main
	mkdir main\001
	mkdir main\002
	mkdir main\001\a
	mkdir main\001\b
	mkdir main\002\c
	mkdir main\002\d
	echo "Created directory tree"
) else (
	echo "Directory main already exist"
)
@echo on



@echo off

for %%i in (1,3,5,7,9,11) do echo %%i
for /L %%i in (1,2,11) do echo %%i
if not exist bin (
    mkdir bin
	for /L %%i in (1,5,100) do mkdir bin\folder%%i
)
for /R %%i in (*) do echo %%i 

for %%i in (*.*) do (
	echo %%i
)
for %%i in (*.*) do (
	echo %%i
)
for /R c:\Windows %%i in (*.exe) do (
	echo %%i
)
@echo on

rem копирование файлов *.txt из директории c:\test в новую папку d:\folder
@echo off
if not exist d:\folder (
	mkdir d:\folder
	for /R c:\test %%i in (*.txt) do (
		echo %%i
		copy %%i d:\folder
	)
) else (
	echo "D:\folder already exists"		
)
 
@echo on

