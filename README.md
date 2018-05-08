# Sandbox api

The sandbox api project is a how-to example for implementing a DDD application using [cubiche](https://github.com/cubiche/cubiche) library.

This project implements a conference management system, an example taken from the books "Microservices for everyone" (2017) by Matthias Noback and "Exploring CQRS and Event Sourcing" (2012) by Dominic Betts.

The project contains a graphql endpoints that will be used by the [frontend](https://github.com/cubiche/sandbox-frontend) application.

- http://api.sandbox.local/graphql

In development mode you will have a graphiql instance to play with it:

* [http://api.sandbox.local/graphiql](http://api.sandbox.local/graphiql)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Clone the repository

```bash
git clone git@github.com:cubiche/sandbox-api.git
```

Add the development domain names

```bash
# Get your current ip and add it to the hosts file
sudo pico /etc/hosts
your.current.ip.address sandbox.local www.sandbox.local api.sandbox.local coverage.sandbox.local mongodb.sandbox.local
```

### Installing

```bash
cd sandbox-api
docker-compose up -d
docker exec -it sandbox-api /www-data-shell
composer global require "hirak/prestissimo:^0.3"
composer install --prefer-dist -n
bin/console app:install
```

## The API

The ```app:install``` command creates the fixtures to be able to start using the application. You will have a set of countries, languages, currencies, two roles (ADMIN, CUSTOMER) and two users:
- Username: **admin** Password: **admin**
- Username: **customer** Password: **customer**

### What can we do?

1. Create conferences
2. Manage seats availability for the conference
3. Create roles
4. Create users
5. Create orders
6. Login/logout users

### How to use the graphql API?

The flow to be able to make a graphql query or mutation is the following:

- Go to the graphiql endpoint (http://api.sandbox.local/graphiql)
- You need a user (you can use the "admin" or "customer" users or you can create it using the [command line]())
- You can make login using the user credentials (you will get a Json Web Token)
- You can make queries or mutations now filling the token input in graphiql using the JWT

## Tests

We're using full-stack Behavior-Driven-Development, with [atoum](http://atoum.org/) and [Behat](http://behat.org). In the root directory, you will find a ```.quality``` file, where you can define the code quality rules and all your unit tests suite.

### Running all the unit tests suite

```bash
bin/test-unit
# Or if you prefer the tests with colored output 
bin/atoum 
```

### Running a unit tests suite

```bash
bin/test-unit test_suite_name
# Or if you prefer the tests with colored output 
bin/atoum -c src/Sandbox/Directory-Name/.atoum.php
```

### Generating tests classes

```bash
bin/test-generator generate:test:class /path/to/the/file
bin/test-generator generate:test:directory /path/to/the/directory
```

### Adding a new test suite

```bash
pico quality.yml
```

Add a new entry

```bash
test:
    suites:
        test_suite_name:
            config_file: 'src/Sandbox/Directory-Name/.atoum.php'
            bootstrap_file: ~
            directories: ~
            triggered_by: 'php'
```

### Running all the tests coverage

```bash
# Follow this steps just once
# Create the coverage directory
mkdir /var/www/coverage
# Make sure the coverage directory is world writable
# Copy the dist file just once
cp .atoum.html.dist .atoum.html.php
```

```bash
# Run the tests coverage
bin/test-coverage
# Check it at coverage.sandbox.local
```

### Running the test coverage of a directory

```bash
# Follow this steps just once
# Create the coverage directory if not exists
mkdir /var/www/coverage
# Make sure the coverage directory is world writable
# Copy the dist file just once
cp src/Sandbox/Directory-Name/.atoum.html.dist src/Sandbox/Directory-Name/.atoum.html.php
```

```bash
bin/atoum -c src/Sandbox/Directory-Name/.atoum.html.php
```

### Running the end to end tests

```
bin/behat
```

### Checking coding style

```
bin/check-code-style
```

### Fixing coding style after a commit

```
bin/php-cs-fix-commit fix
```

## Built With

* [Symfony](http://symfony.com/) - The base web framework
* [Cubiche](https://github.com/cubiche/cubiche) - DDD library

## License

Sandbox api is completely free and released under the [MIT License](https://github.com/cubiche/cubiche/blob/master/LICENSE).

## Authors

* [Ivan Suárez Jerez](https://github.com/ivannis)

