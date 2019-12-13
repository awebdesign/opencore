/admin/controller/common/header.php
    => IF exists in /awebcore/App/Controllers/Admin/common/header.php => we load it from here
    => IF DOES NOT exists in /awebcore/App/Controllers/Admin/common/header.php => we load the system default one
same for model, lang, view, etc



1. trebuie rulata comanda: php artisan migrate:install

ATENTIE LA MIGRARE !!!
tabela actuala migrations trebuie redenumita in altceva in caz contrar o sa avem erori
