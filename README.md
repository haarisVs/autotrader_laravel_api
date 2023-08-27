<h1>Autotrader Laravel API</h1>

<p>Autotrader, a platform for buying and selling vehicles, does offer an API (Application Programming Interface) for developers to integrate its services into their applications or websites. The Autotrader API allows you to access vehicle listings, dealer information, and more. </p>

## Install

    composer install

## Setup .env
## Run migration and seed

    php artisan migrate

# REST API

## SET ENV VARIABLES.
AUTOTRADER_API_KEY=
AUTOTRADER_API_SECRET=


The REST API to the example app is described below.

## Get Stocks list in the app.

### Request

`GET /api/v1/stock`

## Get Stocks Data for the website with pagination.

### Request

`GET /api/v1/stock-data`

## Get Stock detail by Id.

### Request

`GET /api/v1/stock-detail/{stockId}`

## PUT WebHook for Stock Update.

### Request

`PUT /api/v1/webhook`

