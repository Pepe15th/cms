# CMS API

## Instalace

```
composer install
```

## Konfigurace

```
cp .env .env.local
cp .env.test .env.test.local
cp phpunit.xml.dist phpunit.xml
```

V těchto souborech je potřeba následně upravit DATABASE_URL pro jednotlivá prostředí.

```
composer init-db
```

## Spuštění

```
symfony server:start
```


## Testy

```
composer init-tests    # pouze pro samotné vytvoření testovací databáze
composer update-tests  # pouze při změně databázové struktury
bin/phpunit
```


## Dokumentace

Všechnu potřebnou dokumentaci naleznete na adrese **/api/doc**.
