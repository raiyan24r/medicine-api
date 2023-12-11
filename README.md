
Drug Search and Tracker API
Welcome to the Drug Search and Tracker API, a Laravel-based service designed for drug information search and user-specific medication tracking. This API integrates with the National Library of Medicine's RxNorm APIs to provide comprehensive drug data.

Table of Contents
Installation
Authentication
Public Search Endpoint
Private User Medication Endpoints
Bonus Challenges
Technical Details
Submission
Installation
Clone the repository:

bash
Copy code
git clone https://github.com/yourusername/drug-search-tracker-api.git
Change into the project directory:

bash
Copy code
cd drug-search-tracker-api
Install dependencies:

bash
Copy code
composer install
Copy the .env.example file to create a .env file:

bash
Copy code
cp .env.example .env
Generate an application key:

bash
Copy code
php artisan key:generate
Configure the database connection in the .env file.

Run migrations:

bash
Copy code
php artisan migrate
Start the development server:

bash
Copy code
php artisan serve
Now, the API is up and running. You can access it at http://localhost:8000.

Authentication
The API uses token-based authentication. To authenticate, use the /api/login endpoint with your email and password to obtain an access token. Include this token in the Authorization header for subsequent requests.

Register User
Endpoint: /api/register
Method: POST
Payload: name, email, password
Login User
Endpoint: /api/login
Method: POST
Payload: email, password
Public Search Endpoint
Search for drugs using the RxNorm "getDrugs" endpoint.

Endpoint: /api/public-search
Method: GET
Parameters: drug_name (string)
Example
bash
Copy code
curl -X GET "http://localhost:8000/api/public-search?drug_name=aspirin" -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
Private User Medication Endpoints
Ensure all endpoints below are authenticated.

Add Drug
Add a new drug to the user's medication list.

Endpoint: /api/add-drug
Method: POST
Payload: rxcui (string)
Delete Drug
Delete a drug from the user's medication list.

Endpoint: /api/delete-drug/{rxcui}
Method: DELETE
Get User Drugs
Retrieve all drugs from the user's medication list.

Endpoint: /api/get-user-drugs
Method: GET
Bonus Challenges
Rate Limiter: Implemented to prevent abuse of the public search endpoint.
Caching: Added a caching layer for requests made to the RxNorm API.
Technical Details
Error Handling and Data Validation: Implemented for robustness.
Security: Ensured secure handling of user data and authentication.
Unit Tests: Achieved 90% coverage for key functionalities.
Submission
The codebase is available at repository link.

For testing the API, you can use the provided Postman Collection. Ensure to replace YOUR_ACCESS_TOKEN with the token obtained after login.

Feel free to reach out for any clarifications or feedback!
