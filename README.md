# Yook Technical Test Project

## Installation

```make run```

```make composer```

```make migrate```

```make fixtures```

## Run

The server is up to http://localhost

The different endpoints are

http://localhost/api/offsets
http://localhost/api/offsets/{year}
http://localhost/api/price-offsets/{year}/{price}

Command to output offsets :

```php bin/console app:calculate-offset-v1 {year}```

For now, only the year 2020; 2030, 2040 and 2050 and available. 

TODO:

- Error gestion generally, especially when missing date.
- Fix OpenAPI /api/doc not finding NelmioBundle Controller
- Develop an algorithm to manager eithe more year of manage any DateTime
- Develop the command for price offsets
- Set in DB a yearly budget (Develop entity)
- Develop a cron managing the budgets (every X months)