# IUCN Digital PA

![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

------------------------------------------------------------

DESCRIZIONE DEL PROGETTO

IUCN Digital PA è un'applicazione web sviluppata in Laravel 11 
che consente la consultazione e visualizzazione dei dati 
della IUCN Red List API v4.

Il progetto è stato realizzato con finalità accademiche 
per dimostrare competenze in:

- Integrazione con API REST esterne
- Gestione configurazione tramite .env
- Service Layer Architecture
- Caching Laravel
- Paginazione dati
- Separazione Controller / Service / View
- Gestione errori HTTP

------------------------------------------------------------

STACK TECNOLOGICO

- PHP 8.1+
- Laravel 11
- Node.js 18+
- Vite
- Bootstrap
- jQuery
- Guzzle HTTP Client
- PHPUnit

------------------------------------------------------------

REQUISITI DI SISTEMA

- PHP 8.1 o superiore
- Composer
- Node.js + npm
- Estensioni PHP abilitate:
    - curl
    - openssl
    - mbstring
    - json

------------------------------------------------------------

INSTALLAZIONE

1) Clonare il repository

git clone <repo-url>
cd iucndigitalpa

2) Installare dipendenze PHP

composer install

3) Installare dipendenze frontend

npm install
npm run dev

Per produzione:

npm run build

4) Configurazione ambiente

cp .env.example .env
php artisan key:generate

------------------------------------------------------------

CONFIGURAZIONE API

Nel file .env impostare:

IUCN_API_TOKEN=your_token_here

IUCN_BASE_URL=https://api.iucnredlist.org/api/v4/

CACHE_DRIVER=file

Dopo modifica eseguire:

php artisan config:clear
php artisan cache:clear

------------------------------------------------------------

CONFIGURAZIONE HTTPS (WINDOWS)

Se si verificano errori SSL:

1) Scaricare cacert.pem da:
   https://curl.haxx.se/docs/caextract.html

2) Copiarlo in:
   C:\php\extras\ssl\

3) Modificare php.ini:

curl.cainfo = "C:\\php\\extras\\ssl\\cacert.pem"
openssl.cafile = "C:\\php\\extras\\ssl\\cacert.pem"

Riavviare il server web.

------------------------------------------------------------

AVVIO DEL PROGETTO

php artisan serve

Aprire nel browser:

http://127.0.0.1:8000

------------------------------------------------------------

STRUTTURA PRINCIPALE

app/
 └── Services/
     └── Iucn/
         └── IucnApiService.php

config/
 └── iucn.php

resources/
 └── views/

routes/
 └── web.php

------------------------------------------------------------

GESTIONE CACHE

Driver configurabile nel file .env:

CACHE_DRIVER=file

Supportati anche:
- redis
- database
- array

------------------------------------------------------------

TEST

php artisan test

------------------------------------------------------------

TROUBLESHOOTING

Errore: cURL error 3
- Verificare IUCN_BASE_URL
- Verificare presenza token
- Verificare che l'URL termini con "/"
- Eseguire php artisan config:clear

Errore 404 API
- Verificare endpoint /api/v4/
- Verificare validità token
- Verificare configurazione .env

------------------------------------------------------------

Licenza: MIT
Versione: 1.0.0
