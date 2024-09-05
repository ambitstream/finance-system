![Finance Managment System](ui/img/logo-web.png)

*Home finance managment system designed for own usage. You can add outcomes/incomes to common finance flow with custom categories. Allows using multiple currenices and different time-periods. Flexible filter allows you to organize your data with any category and periods, and make comparison. All calculations occurs with main currency (USD). Language - russian.*

## Technologies:
- [FatFree PHP framework](http://fatfree.sf.net/)
- [Bootstrap 3](http://getbootstrap.com/)
- [MySQL](https://www.mysql.com/)

## Installation:
1. Clone this repo.
2. Create folder named 'tmp' in root of project and make it writeble.
3. Create local database 'finance_system' and import tables from /db/starter_db.sql
4. Enter your database's host, dbname, user, pass in /config/config.ini
5. Create virtual host (for example 'finance.local') and start Apache server.
6. Run virtual host ('finance.local') in browser
7. Login to system with:
	- Login: test@inbox.com
	- Password: 12345
