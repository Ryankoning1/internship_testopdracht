# Symfony Programming Challenge

## Overview

This is a programming challenge designed to test your skills in PHP and the Symfony framework. The challenge involves setting up a Symfony project and implementing three different features, each with its own set of requirements and specifications. You will also need to write unit tests for each feature and fix any bugs or errors that are introduced.

## Setup

To get started with the challenge, clone the Symfony project from the provided repository:

```
git clone https://github.com/yourusername/yourrepository.git
```

Then, install the required dependencies:

```
composer install
```

## Starting Symfony

To start the Symfony project, run the following command:

```
symfony server:start
```

This will start the Symfony server and make the project accessible at `http://localhost:8000`.

## Migrate the database

Because it is a hassle to set up a database, we have provided a SQLite database file that you can use for this challenge. To migrate the database, run the following command:

```
php bin/console doctrine:migrations:migrate
```

## Postman

To test the api we recommend to use Postman to test the api endpoints


## Functionality

There are three different features that you will need to implement or fix for this challenge. Each feature has its own set of requirements and specifications, which are outlined below:

### Feature 1

Description: We found out that the user's profile is not stored but did not receive a validation error when we use the following lastname `Školski Bačka Venac`. We need to store the user's profile in the database.

Requirements: We cannot adjust the database to store the user's profile. We need to make sure that the code reflects the business rules of saving the entity to the database

Specifications:
- You should fix the bug that the user is not stored in the database.
- You should also make sure that the test are changed if that is needed.

### Feature 2

Description: Finish the implementation of the retrieval of the user's profile from an external API.

Requirements: Some developer started the implementation of the random user api to fill in the accounts into the database but never finished the work. You need to finish the implementation and make sure the data is stored in the database.

Specifications: 
- You should implement the retrieval of the user's profile from the random user api.
- You should also make sure that the user's profile is stored in the database.
- Check if you can use the work from the previous developer, he created the service (`App\Acme\Service\RandomUserClient`) and command (`App\Account\Command\EnrichAccountsFromRandomUserApi`)
- The unit test and integration test are already written for this feature and should be green.

### Feature 3

Description: Add basic authentication to the API endpoints.

Requirements: Make sure you can only register an account with the role `ROLE_ADMIN` or higher.

Specifications:
- You should implement basic_auth as described in the symfony documentation.
- You should also make sure that the user is authenticated before they can access the API endpoints.
- There should be database migrations to create the users.
- All the endpoints /api/v1/* should be protected with the role `ROLE_USER` or higher.
- All the endpoints /api/v1/account* should be protected with the role `ROLE_ADMIN` or higher.
- The unit test and integration test are already written for this feature and should be green.

## Unit tests

For each feature, you will need to check if there is test written, or you need to  write a set of tests that verify that the code is working correctly. To run the unit tests, use the following command:

```
php bin/phpunit
```

This will run all the unit tests and display the results.

## Bug fixing

As part of the challenge, we will introduce bugs or errors into some of the features that you will need to fix. This will test your ability to debug and troubleshoot code.

## Timeframe

You will have 2 hours to complete the challenge. Please make sure to manage your time effectively and prioritize tasks as needed.

## Conclusion

This challenge is designed to test your skills in PHP and Symfony, as well as your ability to write unit tests and follow best practices for software development. We wish you the best of luck and look forward to seeing your completed work!
