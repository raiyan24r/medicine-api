
# Medicine Search and Tracker API

Welcome to the Medicine Search and Tracker API, a Laravel-based service designed for drug information search and user-specific medication tracking. This API integrates with the National Library of Medicine's RxNorm APIs to provide comprehensive drug data.

## About

The Medicine Search and Tracker API is a RESTful web service that enables users to search for drug information and manage their personal medication lists. Built with Laravel 10 and PHP 8.1+, it leverages the RxNorm database from the National Library of Medicine to provide accurate and up-to-date drug information including drug names, base names, and dose form details.

### Key Features

- **Drug Search**: Search for medications by name using the RxNorm API integration
- **User Authentication**: Secure token-based authentication using Laravel Sanctum
- **Medication Tracking**: Personal medication list management for authenticated users
- **Rate Limiting**: Public endpoints are rate-limited to prevent abuse (30 requests/minute)
- **Response Caching**: Configurable caching for RxNorm API responses to improve performance

### Technology Stack

- **Framework**: Laravel 10
- **Language**: PHP 8.1+
- **Authentication**: Laravel Sanctum
- **External API**: National Library of Medicine RxNorm APIs
- **Testing**: PHPUnit

## Table of Contents

1. [Installation](#installation)
2. [Test](#test)
3. [Authentication](#authentication)
4. [Postman](#postman)
5. [Bonus Challenges](#bonus-challenges)

## Installation

1. **Clone the repository:**
   ``` git clone https://github.com/raiyan24r/medicine-api ```

2. **Change into the project directory**
   ``` cd medicine-api ```

3. **Install dependencies:**
   ``` composer install ```

4. **Copy the .env.example file to create a .env file:**
   ``` cp .env.example .env ```

5. **Configure the database connection in the .env file.**

6. **Run migrations:**
   ``` php artisan migrate ```

7. **Start the development server:**
   ``` php artisan serve ```

Now, the API is up and running. You can access it at `http://localhost:8000`
## Test

Run the command ``` php artisan test ``` to run all the tests in the project.
The project has a total of 12 tests with 25 assertions

## Authentication

To register, use the `/api/register` endpoint with name, email and password.
The API uses token-based authentication. To authenticate, use the `/api/login` endpoint with your email and password to obtain an access token. Include this token in the Authorization header for subsequent requests.

## Postman

The postman link is here https://api.postman.com/collections/29698203-8f208111-7d54-4cce-b634-00cace511504?access_key={request_for_access_key}

The public documentation link is also attached
https://documenter.getpostman.com/view/29698203/2s9Ykhi5K7

``{{url}}`` in the all postman requests all have suffix `/api` So the url is actually `http://localhost:8000/api`

Response examples have also been added to to each request

## Bonus Challenges

#### Rate Limiter
Rate limiter has been implemented for the public search endpoint. A maximum of 30 requests can be made in 1 minute using the public search endpoint.

#### Caching
The response from the requests made to the RxNorm API are being cached. The cache expiration time can be modified by setting the value of the .env variable `CACHE_EXPIRATION`
By default the cache expiration time is 10 minutes which can be increased or decreased as required.




