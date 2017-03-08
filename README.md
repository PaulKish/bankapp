# Folder structure

```
bankapp/
├── application/
├── composer.json
├── composer.lock
├── public/
│   ├── .htaccess
│   └── index.php
└── vendor/
    └── codeigniter/
        └── framework/
            └── system/
    ...
└── schema/
```

# Instructions
- Clone Repo into folder
- Run `composer install` to install dependencies
- Setup schema into a MySQL instance. Schema can be found in schema folder
- Edit `application/config/database.php` to correspond to your db user/password
- If on a bash enabled system run:

```
$ cd /path/to/bankapp
$ bin/server.sh
```

- On Windows you can run
```
cd /path/to/bankapp/public
php -S localhost:8000
```

# Tests
- Ensure you have PHPUnit setup in your system and is callable via phpunit on the terminal or cmd
- To runs tests:

```
$ cd /path/to/bankapp/application/tests
$ phpunit
```


# Bank Account API

## End points
- / - default
	- GET/POST methods allowed
	- No parameters required
- /balance - returns account balance
	- GET/POST methods allowed
	- No parameters required
- /deposit - allows depositing to account
	- POST method only
	- amount needed - send as form-data / e.g `<?php $_POST['amount'] ?>` should contain a numeric value
- /withdraw - allows withdrawal
	- POST method only
	- amount needed - send as form-data / e.g `<?php $_POST['amount'] ?>` should contain a numeric value

## Response structure

```
{
  "version": "0.1",
  "links": [
    {
      "href": "/balance",
      "method": "GET"
    },
    {
      "href": "/withdraw",
      "method": "POST"
    },
    {
      "href": "/deposit",
      "method": "POST"
    }
  ],
  "status": "Success",
  "message": "Ok"
}
```

- Status can be False or Success depending on whether the process was successful
- Message contains either Ok or the specifics of the error message
- Account contains the balance
- Transacation contains the transaction info

```
  "account": {
    "balance": 30000
  },
  "transaction": {
    "type": "Deposit",
    "amount": "30000",
    "date": "2017-03-08"
  }
```

- Status codes include
	- 200 - Ok
	- 201 - Created
	- 400 - Bad request
	- 404 - Not found
	- 405 - Method not allowed

## Misc
- App is built on CodeIgniter 3.
- Composer support is provided by [Composer Installer](https://github.com/kenjis/codeigniter-composer-installer)
- PHPUnit support is by [CodeIgniter PHPUnit](https://github.com/kenjis/ci-phpunit-test)