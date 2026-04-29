# BestRent - Projekt dokumentáció

## Projekt készítői

Ezt a projektet a csapatunk készítette:

- Kollár Ádám
- Szabó Marcell
- Strasszer Zsombor

## Tartalomjegyzék

1. [Projekt áttekintés](#projekt-áttekintés)
2. [Projekt készítői](#projekt-készítői)
3. [Fő funkciók és jogosultságok](#fő-funkciók-és-jogosultságok)
4. [Technológiai stack](#technológiai-stack)
5. [Mappastruktúra](#mappastruktúra)
6. [Webalkalmazás (Laravel)](#webalkalmazás-laravel)
7. [Mobil admin alkalmazás (.NET MAUI)](#mobil-admin-alkalmazás-net-maui)
8. [API dokumentáció](#api-dokumentáció)
9. [Adatbázis](#adatbázis)
10. [Telepítés és futtatás - Web](#telepítés-és-futtatás---web)
11. [Telepítés és futtatás - Mobil](#telepítés-és-futtatás---mobil)
12. [Telepítési útmutató - Mobil](#telepitesi-utmutato-mobil)
13. [Tesztadatok (admin és user)](#tesztadatok-admin-és-user)
14. [Képek](#képek)

## Projekt áttekintés

A BestRent egy autóbérlési rendszer, amely két kliensrétegből áll:

1. Webes felület (Laravel): ügyfél + admin funkciók.
2. Mobil admin felület (.NET MAUI): autók és bérlések kezelése API-n keresztül.

A rendszer felépítése:

- Az üzleti logika és az adatok kezelése a Laravel backendben történik.
- A mobil alkalmazás a backend REST API-ját használja.


## Fő funkciók és jogosultságok

### Ügyfél funkciók

- Regisztráció és bejelentkezés biztonságos jelszókezeléssel.
- Autók böngészése listanézetben, részletes adatok megtekintésével.
- Keresés és szűrés márka, kategória, státusz és egyéb jellemzők alapján.
- Foglalás létrehozása kezdő és záró dátummal, valamint felvételi és leadási helyszínnel.
- Saját foglalások áttekintése státusz szerint (pending, confirmed, active, cancelled, completed).
- Fizetés rögzítése a completed foglalásokhoz kapcsolódóan.
- Profiladatok módosítása (név, elérhetőség), valamint fiók törlése.

### Admin funkciók (web)

- Teljes autókezelés (CRUD): új autó felvétele, meglévő autó módosítása, törlése.
- Foglalások kezelése és státuszfrissítése.
- Fizetések kezelése és nyomon követése (összeg, státusz).
- Dashboard statisztikák: gyors áttekintés az autókról, foglalásokról és bevételekről.
- Admin jogosultság alapú hozzáférésvédelem az admin felületeken.

### Admin funkciók (mobil)

- Három fő fül: Autók, Bérlések, Fizetések.
- Autók kezelése: listázás, létrehozás, módosítás, törlés.
- Bérlések kezelése: listázás, módosítás, törlés.
- Mobil API kapcsolat ugyanarra a backend logikára épül, mint a webes felület.

### Fő rendszer tulajdonságok

- Közös backend: a web és a mobil kliens ugyanazt a REST API-t használja.
- Konzisztens üzleti szabályok: validáció és státuszkezelés backend oldalon történik.
- Relációs adatmodell: users, cars, reservations, payments táblákra épülő működés.
- Bővíthető architektúra: külön modulok webes és mobil admin felülethez.

## Technológiai stack

### Backend és web

- PHP 8.x
- Laravel 12
- Blade
- Vite
- Bootstrap 5
- MySQL

### Mobil

- .NET 10
- .NET MAUI
- C#

### Telepítő

- Inno Setup 6

## Mappastruktúra

Az alábbi mappafa a projekt logikai felépítését mutatja:

```text
BestRent/
├─ BestRent/
│  ├─ app/
│  │  ├─ Http/
│  │  │  ├─ Controllers/
│  │  │  │  ├─ Api/
│  │  │  │  │  ├─ CarApiController.php
│  │  │  │  │  └─ ReservationApiController.php
│  │  │  │  ├─ CarController.php
│  │  │  │  ├─ ReservationController.php
│  │  │  │  └─ PaymentController.php
│  │  └─ Models/
│  │     ├─ Car.php
│  │     ├─ Reservation.php
│  │     ├─ Payment.php
│  │     └─ User.php
│  ├─ bootstrap/
│  ├─ config/
│  │  └─ reservation.php
│  ├─ database/
│  │  ├─ migrations/
│  │  ├─ factories/
│  │  └─ seeders/
│  ├─ public/
│  ├─ resources/
│  │  └─ views/
│  ├─ routes/
│  │  ├─ web.php
│  │  └─ api.php
│  ├─ storage/
│  └─ tests/
│
├─ BestRentMobile/
   ├─ AppShell.xaml
   ├─ Models/
   │  ├─ CarItem.cs
   │  ├─ ReservationItem.cs
   │  └─ ReservationsResponse.cs
   ├─ Pages/
   │  ├─ CarsPage.xaml
   │  ├─ CarsPage.xaml.cs
   │  ├─ ReservationsPage.xaml
   │  └─ ReservationsPage.xaml.cs
   ├─ Services/
   │  └─ CarApiService.cs
   ├─ Resources/
   ├─ Platforms/
   └─ Installer/
      ├─ BestRentMobile.iss
      ├─ build-installer.ps1
      └─ build-installer.cmd
└─ Docs/
  ├─ README.md
  └─ Images/
    ├─ web/
    ├─ mobile/
    ├─ installer/
    └─ database/
```

## Webalkalmazás (Laravel)

### Fontosabb route-csoportok

- Publikus: kezdőlap, autólista, autó részletek, bejelentkezés, regisztráció.
- Auth: ügyfél profil, foglalások, fizetések, kijelentkezés.
- Admin: dashboard, autók, foglalások, fizetések admin felülete.

### Fizetési logika

Ha egy foglalás completed állapotba kerül, a rendszer automatikusan létrehozza a kapcsolódó payment rekordot pending státusszal (ha még nem létezik),
így a pénzügyi nyomon követés konzisztens marad.
Amint egy foglalás befejezett státuszba kerül a felhasználónál megjelenik egy fizetési kérelem, amit ha teljesít, megjelenik az admin felületen a bevételnél.

## Mobil admin alkalmazás (.NET MAUI)

### Navigáció

- Fő fülek: Autók, Bérlések

- Autók fül
  - autók listája
  - új autó létrehozása
  - autó módosítása
  - autó törlése
- Bérlések fül
  - bérlések listája
  - bérlés módosítása
  - bérlés törlése

### API kapcsolat

- http://127.0.0.1:8000/api

## API dokumentáció

### Cars

- GET /cars
- POST /cars
- PUT /cars/{car}
- DELETE /cars/{car}

### Reservations

- GET /reservations
- GET /reservations/locations
- GET /reservations/{reservation}
- POST /reservations
- PATCH /reservations/{reservation}
- DELETE /reservations/{reservation}


- A státusz- és ütközéskezelés backend oldalon validált.
- A mobil és a web ugyanazokra az API-kra és üzleti szabályokra támaszkodik.

## Adatbázis

Az adatbázis a rendszer központi adattára: a webes felület ide menti és innen olvassa ki az üzleti adatokat,
és ugyanebből az adatforrásból dolgozik a mobil admin alkalmazás is az API-n keresztül.
Ez biztosítja, hogy minden kliens ugyanazokat a foglalásokat, autóadatokat és fizetési állapotokat lássa.

### Adatbázis modell

![Adatbázis modell](Docs/Images/database/Database-model.png)

## Táblák

| Tábla | Cél | Kulcs mezők |
| --- | --- | --- |
| users | Felhasználók és adminok tárolása. | id, name, email, phone_number, password, is_admin, timestamps |
| cars | Bérelhető autók adatainak tárolása. | id, brand, model, category, year, plate_number, color, fuel_type, transmission, seats, daily_price, status, image, image_type, description, timestamps |
| reservations | Foglalások adatainak tárolása. | id, user_id, car_id, start_date, end_date, pickup_location, dropoff_location, total_price, status, notes, timestamps |
| payments | Fizetési események és állapotok tárolása. | id, reservation_id, user_id, amount, method, status, transaction_id, paid_at, timestamps |

### Kapcsolatok

- users 1 - N reservations
- users 1 - N payments
- cars 1 - N reservations
- reservations 1 - N payments

### Használt állapotok

Autó státusz:

- available
- maintenance
- unavailable

Foglalás státusz:

- pending
- confirmed
- active
- cancelled
- completed

# Telepítés és futtatás - Web

### Előfeltételek

- PHP 8.x
- Composer
- Node.js + npm
- MySQL

### Lépések

```bash
cd BestRent
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Ha az adatbázis és táblák már léteznek, és csak a seed futtatása kell:

```bash
php artisan db:seed
```

Elérés:

- http://127.0.0.1:8000

Ajánlott gyors ellenőrzés:

```bash
php artisan route:list
```

## Telepítés és futtatás - Mobil

### Előfeltételek

- Windows rendszer
- A kész telepítőfájl elérése: BestRentMobile/BestRentMobile-Setup.exe

### Elsődleges telepítési mód: kész installer használata

1. Nyisd meg a következő fájlt a projekt gyökérkönyvtárában:
  - **BestRentMobile/BestRentMobile-Setup.exe**
2. Futtasd az installert (szükség esetén rendszergazdai jogosultsággal).
3. Kövesd a telepítő lépéseit (Next, Install, Finish).
4. Indítás után ellenőrizd, hogy az alkalmazás elindult-e.

### Telepítési útmutató - Mobil

![Installer 1](Docs/Images/installer/1.png)
![Installer 2](Docs/Images/installer/2.png)
![Installer 3](Docs/Images/installer/3.png)
![Installer 4](Docs/Images/installer/4.png)

Megjegyzés:

- Ha az API helyi gépen fut, a backend legyen elindítva, különben az adatok betöltése sikertelen lehet.

### Másodlagos megoldás hiba esetén: telepítő készítése

Ha a kész telepítő nem fut vagy sérült, készíts új telepítőt az alábbi rövid lépésekkel:

1. Telepítsd az Inno Setup 6 eszközt.
2. Futtasd a build scriptet az installer mappában:

```bash
cd BestRentMobile/Installer
build-installer.cmd
```

Új kimenet helye:

- BestRentMobile/Installer/Output/BestRentMobile-Setup.exe

## Tesztadatok (admin és user)

Az alábbi tesztfiókok a weboldal használatához szükséges.

### Admin tesztfiók

- Név: Admin Teszt
- Email: admin@bestrent.com
- Jelszó: password
- is_admin: true

### User tesztfiók

- Név: User Teszt
- Email: user@bestrent.com
- Jelszó: password
- is_admin: false


## Képek

### Webes felület

![Kezdőoldal](Docs/Images/web/web-home.png)
![Autólista](Docs/Images/web/web-cars.png)
![Autó részletek](Docs/Images/web/web-car-details.png)
![Bejelentkezés](Docs/Images/web/web-login.png)
![Regisztráció](Docs/Images/web/web-register.png)
![Ügyfél dashboard](Docs/Images/web/web-client-dashboard.png)
![Ügyfél profil](Docs/Images/web/web-client-profile.png)
![Foglalás oldal](Docs/Images/web/web-client-reserve-price.png)
![Admin dashboard](Docs/Images/web/web-admin-dashboard.png)
![Admin autó létrehozás](Docs/Images/web/web-admin-car-create.png)
![Admin foglalások](Docs/Images/web/web-admin-reservations.png)
![Admin fizetések](Docs/Images/web/web-admin-payments.png)

### Mobil felület

![Autók oldal](Docs/Images/mobile/Mobile-Cars.png)
![Bérlések oldal](Docs/Images/mobile/Mobile-Reservations.png)

