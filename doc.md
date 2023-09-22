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
Validator (asserts):
```shell 
    composer require symfony/validator doctrine/annotations
```
Security:
```shell 
    composer require security // and make:user
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

User:
```shell 
php bin/console make:user 
```
**créer la fonction => getUsername() pour JWT**

```php
    /**
     * Méthode getUsername qui permet de retourner le champ qui est utilisé pour l'authentification.
     *
     * @return string
     */
    public function getUsername(): string {
        return $this->getUserIdentifier();
    }
```
```shell
    composer require lexik/jwt-authentication-bundle
```

## JWT !

Si après avoir installé JWT le dossier jwt n'apparait pas dans config/jwt il faut le créer !

Générer les clés publiques et privées 
- ouvrir git bash
- taper la même pass phrase
- taper la commande clé private
```bash
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
```
- taper la commande clé publilque
```bash
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```


### Contrôleur

Installer un premier contrôleur. Les routes doivent être disponibles dans Postman. En mode Api la route doit être direct en json !!!

