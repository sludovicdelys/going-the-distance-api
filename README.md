# Back-end: Going the Distance API
## Overview
This is the back-end API for the "Going the Distance" application, built with Symfony and API Platform.

## Prerequisites
Ensure you have the following software installed:

- Git
- PHP (version 7.4 or higher)
- Composer
- MySQL (or any other database supported by Doctrine)

## Installation
### 1. Clone the repository:

```
git clone https://github.com/sludovicdelys/going-the-distance-api.git
cd going-the-distance-api
```

### 2. Install PHP dependencies:

```
composer install
```

### 3.Configure environment variables:

```
cp .env .env.local
```

### 4. Update the `.env.local` file with your database configuration:

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```

### 5. Set up the database:

```
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

### 6. Start the Symfony server:

```
symfony server:start
```

Alternatively, you can use the built-in PHP server:

```
php -S localhost:8000 -t public
```

### 7. Basic HTTP Authentication
Ensure the back-end API is configured for Basic HTTP Authentication. Add the following to your `config/packages/security.yaml`:

```
security:
    providers:
        in_memory:
            memory:
                users:
                    runner:
                        password: '$2y$12$KIXyo3UCuwu9B.oZb1k0SO9cJMLtbLvL1Xb8qVroGg5IBZDpyh0gG' # hashed 'secret'
                        roles: ['ROLE_FRONTEND']

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        main:
            anonymous: false
            http_basic: ~

    access_control:
        - { path: ^/api, roles: ROLE_FRONTEND }
```

### 8. Testing the API
Visit http://localhost:8000/api in your browser to explore the API.
