
# Foodics Test

## Installation

```bash
# Clone the repo
git clone https://github.com/Mohannad-tests/foodics-test

# Install dependencies
composer install
```

### Docker/Sail - MySQL database

```bash
# Build and run the containers
./vendor/bin/sail up --remove-orphans --build -d

# Run migrations
./vendor/bin/sail artisan migrate:fresh --seed

# Run queue worker
./vendor/bin/sail artisan queue:work
```

Exposed ports:

- App: http://localhost:8081
- Mailpit Dashboard: http://localhost:8025
- Other: MySQL: 33061 , Redis: 6379, Mailpit: 1025

### Local environment or Valet - SQLite database file

Note: a SQLite file at `database/database.sqlite` should have been created -through the `post-root-package-install` composer.json script-.

```bash
# Run migrations
php artisan migrate:fresh --seed

# Run the local server
php artisan serve
```

- App: http://localhost:8000

## Testing

### Docker/Sail - MySQL database

```bash
# Run the tests
./vendor/bin/sail test
```

### Local environment - SQLite database file

```bash
# Run the tests
php artisan test
```

## Usage:

### Web/GUI

The welcome page contains:

- a basic view of the database tables
- a button to create a new order
- clicking on ingredients rows will allow you to add quantities to them


### API/CLI

```bash
export HOST=http://localhost:8000
# export HOST=http://localhost:8081 # if using Sail
# export HOST=http://foodics-test.test # if using Valet

# Create an order
curl -X POST -H "Content-Type: application/json" -d '{"products": [{"id": 1, "quantity": 2}]}' ${HOST}/api/order

# Get stock ingredients quantities
curl -X GET ${HOST}/api/stock/1
```

