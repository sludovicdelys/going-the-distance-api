## Back-end: Going the Distance API

### 🐞 Known Issues

#### Error 500 on Run Creation from Front-End App

When attempting to create a run from the front-end application (https://github.com/sludovicdelys/go_the_distance), an HTTP 500 error may occur. This issue happens when sending the following data to the API:

```json
{
  "type": "Hill",
  "start_date": "2024-05-21",
  "start_time": "18:00:00",
  "time": "20:00:00",
  "distance": 20,
  "comments": "",
  "user": {
    "username": "sabrina_delys"
  }
}
```

The error response from the API is as follows :

```bash
{
  "@id": "/api/errors",
  "@type": "hydra:Error",
  "detail": "An exception occurred while executing a query: SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'average_speed' cannot be null",
  "hydra:description": "An exception occurred while executing a query: SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'average_speed' cannot be null",
  "hydra:title": "An error occurred",
  "status": 500,
  "title": "An error occurred",
  "type": "/errors/500"
}
```

🕵️‍♀️ The issue might be happening in the `RunController` or the `Run` entity. I am working on a fix, but if you have any suggestions or solutions, they are more than welcome.

### Overview

This is the back-end API for the "Going the Distance" application, built with Symfony and API Platform.

### Prerequisites

Ensure you have the following software installed:
- **Git**
- **PHP** (version 7.4 or higher)
- **Composer**
- **MySQL** (or any other database supported by Doctrine)

### Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/sludovicdelys/going-the-distance-api.git
    cd going-the-distance-api
    ```

2. **Install PHP dependencies:**
    ```bash
    composer install
    ```

3. **Configure environment variables:**
    ```bash
    cp .env .env.local
    ```

    Update the `.env.local` file with your database configuration:
    ```env
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    ```

    Add the following line to your `.env.local` file for the runner password:
    ```env
    RUNNER_PASSWORD='$2y$12$KIXyo3UCuwu9B.oZb1k0SO9cJMLtbLvL1Xb8qVroGg5IBZDpyh0gG' # hashed 'secret'
    ```

4. **Set up the database:**
    ```bash
    php bin/console doctrine:database:drop --force
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:fixtures:load
    ```

5. **Start the Symfony server:**
    ```bash
    symfony server:start
    ```

    Alternatively, you can use the built-in PHP server:
    ```bash
    php -S localhost:8000 -t public
    ```

### Basic HTTP Authentication

Ensure the back-end API is configured for Basic HTTP Authentication. The `security.yaml` file is set up to use environment variables for the password.

### Testing the API

Visit `http://localhost:8000/api` in your browser to explore the API.

## Additional Notes

- **Environment Variables**: Ensure you have configured all required environment variables in your `.env.local` file.
- **Database Configuration**: Make sure your local database is properly configured and accessible.
- **Dependencies**: If you encounter any issues with dependencies, ensure you are using the correct versions of PHP, Composer, and MySQL.

## Troubleshooting

- **500 Internal Server Error**: Check the Symfony logs in `var/log` for detailed error messages.
- **404 Not Found**: Ensure routes are correctly defined and that you are accessing the correct URLs.
- **Authentication Issues**: Verify the Basic HTTP Authentication setup in the `security.yaml` file and ensure the credentials are correct.

By following these steps, you should be able to clone, set up, and run the back-end application locally. If you encounter any issues, refer to the troubleshooting section or consult the Symfony documentation.
