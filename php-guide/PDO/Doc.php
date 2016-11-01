<?php
1. Для соединения  использовать объект PDO (см. PDO_connect.php).
   Для задания свойств(аттрибутов) соединения использовать PDO::setAttribute() и PDO::getAttribute()
2. Для отлавливания ошибок работы с бд использовать объект PDOException (см. PDOException.php и PDO_error.php).
3. Для выполнения запросов к бд без выборки данных 	использовать PDO::exec() (см. PDO_exec.php).
   Для получения последнего id вставки использовать PDO::lastInsertId() (см. PDO_exec.php).
4. Для выполнения транзакционных запросов см. PDO_transaction.php	
5. Для экранирования данных, передаваемых в бд, использовать PDO::quote() (см. PDO_quote.php).
6. Для выполнения выборки данных из бд (SELECT)	использовать PDO::query() (см. PDO_query.php).
   PDO::query()	возвращает объект PDOStatement и для дальнейшей выборки использовать его методы fetch...()
7. Для создания подготовленного запроса использовать PDO::prepare() (см. PDO_prepare.php).
   PDO::prepare() возвращает объект PDOStatement и для связывания передаваемых переменных ипользовать его методы
   bind...() или execute(). Для отправки подготовленного запроса использовать PDOStatement::execute().
8. Для запуска подготовленного запроса использовать PDOStatement::execute() (см. PDOStatement_execute.php).
9. Для связывания переменных с подготовленным запросом использовать PDOStatement::bind...(см. PDOStatement:_bind.php)
   или как аргументы PDOStatement::execute().
10. Для детализированной выборки данных из результата запроса(SELECT) использовать PDOStatement::fetch...(см. PDOStatement:_fetch....php)	
11. Для задания режима выборки данных использовать PDOStatement::setFetchMode (см. PDOStatement_setFetchMode.php)		
12. Для получения дополнительной информации подготовленного запроса использовать методы PDOStatement:
	columnCount(), getColumnMeta(), nextRowset(), irowCount()
13. Для освобождения памяти от результата предыдущего SQL-запроса (соединение с бд остаётся) использовать
	PDOStatement::closeCursor()	
		