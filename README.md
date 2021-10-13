# POSmini

---
Start by cloning this repo and going inside the project's folder:

```shell
$ git clone git@github.com:nabilfiras93/POSmini.git
```


Run :

```shell
$ composer install
```

Config DB in .env

```shell
database.default.hostname = localhost
database.default.database = YOUR_DB
database.default.username = YOUR_USERNAME
database.default.password = YOUR_PASS
database.default.DBDriver = MySQLi
```

Import DB : posmini.sql


Run :

```shell
php spark serve
```
