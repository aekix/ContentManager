# ContentManager
School projet of a community project to submit content and publish it on social networks

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```

Node.js
Yarn
Npm
composer

```

### Installing

A step by step series of examples that tell you how to get a development env running

Clone the repo
```
$ git clone https://github.com/aekix/ContentManager
```

Install dépendencies

```
$ yarn install
```

```
$ npm install
```

Automate installation of librairies 
```
$ npm start
```
To create the database
```
$ php bin/console d:d:c
```
Update database schema
```
$ php bin/console d:s:u --force
```

To compile css/js
```
$ yarn run dev [--watch]
```



## Fixtures

Load fixtures of tests
```
$ php bin/console d:f:l
```

## Contributing

1. Fork it (<https://github.com/aekix/ContentManager/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request


## Authors

* **Jimmy Mathurin** - *Initial work* - [Ascensia](https://github.com/aekix/ContentManager)
* **Ryan LABENI**


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
