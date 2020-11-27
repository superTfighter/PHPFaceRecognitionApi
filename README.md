# Általános tudnivalók V2

## Függőségek
* node.js
* composer

## További függőség
gulp-cli
```
sudo npm install gulp-cli -g
```
bower
```
sudo npm install bower -g
```

## Fejlesztői környezet kialakítása
A repóból való clone-ozás után töltsük le a php-s függőségeket
```
$ composer install
```
Projecthez tartozó többi függőség letöltése (opcionális)
```
$ bower install
$ npm install
```

## LESS fordító indítása
Az előző opcionális lépésből az npm install ebben az esetben kötelező.
```
$ screen -S screen_name
$ gulp
```
Az összes app\Resouces\less -ben található .less fájl lefordul a public\css könyvtárba.
Figyelem: A gulp script úgy van megírva, hogy a .less fájlokat figyeli, így ha valami változás van benne akkor rögtön fordítja is .css-re (watch funkció)

## Külső JS és CSS csomagok frissítés saját projekten belűl
A project gyökerében adjuk ki az alábbi parancsokat
```
$ bower update
$ gulp build
```

# Hasznos dokumentációk

* [Slim Framework](https://www.slimframework.com/)
* [Twig](https://twig.symfony.com/doc/2.x/)
# PHPFaceRecognitionApi
