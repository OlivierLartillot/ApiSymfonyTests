# Doc api et symfo 

## Rest

### Niveaux de conformité
1-  
2-  
3-  

##  Installer Symfo

Installer la version minimale
```shell 
    symfony new Books
```

### les bundles
Maker:
```shell 
    composer require symfony/maker-bundle --dev
``` 
Doctrine:
```shell 
    composer require orm
```
Fixtures:
```shell 
    composer require orm-fixtures --dev
```
Serializer:
```shell 
composer require symfony/serializer-pack
```

## Bdd et manipulations de base

Création database:
```shell 
    php bin/console doctrine:database:create
```

Migrations: 

```shell 
soit: 
    php bin/console make:migration
    php bin/console d:m:m

ou
    php bin/console doctrine:schema:update --force
```

Fixtures:
```shell 
php bin/console doctrine:fixtures:load 
```

### Contrôleur

Installer un premier contrôleur. Les routes doivent être disponibles dans Postman. En mode Api la route doit être direct en json !!!

