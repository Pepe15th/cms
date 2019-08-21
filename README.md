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

## Spuštění

```
symfony server:start
```


## Testy

```
composer update-tests
/bin/phpunit
```

Příkaz update-tests stačí spustit pouze při změně databázové struktury.


## Dokumentace

Všechnu potřebnou dokumentaci naleznete na adrese /api/doc.
