rem в пакетный файл должны быть переданы аргументы-имя папки откуда кпируется файл и имя папки куда, которые будут доступны в переменных %1 и %2 
@echo off
if not exist %2 (
	mkdir %2
	for /R %1 %%i in (*.txt) do (
		echo "%%i"
		copy "%%i" "%2"
	)
) else (
	echo "%2 already exists"		
)
 
@echo on