-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Ápr 29. 14:32
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `bestrent`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Sedan',
  `year` smallint(5) UNSIGNED NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `fuel_type` varchar(255) DEFAULT NULL,
  `transmission` varchar(255) DEFAULT NULL,
  `seats` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `daily_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL,
  `image_type` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `category`, `year`, `plate_number`, `color`, `fuel_type`, `transmission`, `seats`, `daily_price`, `status`, `image`, `image_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tesla', 'Model 3 Performance', 'Sedan', 2022, 'TES-301', 'Fekete', 'Elektromos', 'Automata', 5, 42000.00, 'available', 'https://www.pngplay.com/wp-content/uploads/13/Tesla-Model-3-PNG-Images-HD.png', 'link', 'Modern elektromos szedán, hosszú hatótávval.', '2026-04-23 07:43:56', '2026-04-23 08:35:19'),
(2, 'Audi', 'S4', 'Sedan', 2021, 'AUD-444', 'Fehér', 'Benzin', 'Automata', 5, 36000.00, 'available', 'https://carsales.pxcrush.net/car/spec/S0001Q8W.jpg?pxc_method=GravityFill&width=480&height=320&watermark=2124530066', 'link', 'Sportos, Kényelmes üzleti autó prémium felszereltséggel.', '2026-04-23 07:43:56', '2026-04-23 08:34:57'),
(3, 'BMW', '320i', 'Sedan', 2020, 'BMW-320', 'Szürke', 'Benzin', 'Automata', 5, 35000.00, 'available', 'https://s3-eu-west-1.amazonaws.com/photo-ref-carboatmedia-fr/Qk1X/U0VSSUUgMw==/397f0555ffb8ff53ea414de4e7f4bafd/MQ==/307f767efdc69f3d2b26bef5c0b514dc.png', 'link', 'Sportos vezetési élmény városi és országúti használatra.', '2026-04-23 07:43:56', '2026-04-23 08:36:58'),
(4, 'Mercedes', 'C200', 'Sedan', 2021, 'MER-200', 'Ezüst', 'Dízel', 'Automata', 5, 39000.00, 'available', 'https://img.pcauto.com/model/images/touPic/my/Mercedes-Benz-C-Class_160_small.png', 'link', 'Elegáns belső tér és kényelmes futómű.', '2026-04-23 07:43:56', '2026-04-23 08:42:58'),
(5, 'Toyota', 'Corolla', 'Sedan', 2022, 'TOY-123', 'Piros', 'Hibrid', 'Automata', 5, 28000.00, 'available', 'https://st2.depositphotos.com/3037725/45775/i/450/depositphotos_457759302-stock-photo-tula-russia-february-2021-toyota.jpg', 'link', 'Gazdaságos hibrid autó mindennapi használatra.', '2026-04-23 07:43:56', '2026-04-23 08:44:34'),
(6, 'Volkswagen', 'Golf R', 'Hatchback', 2020, 'VWG-888', 'Kék', 'Benzin', 'Automata', 5, 24000.00, 'available', 'https://www.goauto.com.au/assets/contents/1b6618eb81ca63feecfb3a249c7e8534e914f480.jpg', 'link', 'Kompakt és megbízható autó alacsony fogyasztással.', '2026-04-23 07:43:56', '2026-04-26 11:24:17'),
(7, 'Skoda', 'Octavia VRs', 'Kombi', 2024, 'SKO-777', 'Fehér', 'Benzin', 'Manuális', 5, 26000.00, 'available', 'https://cdn.nwi-ms.com/media/hu/C/mc/PV56YD6H/model/front.png?F=5X5X&P=ME&M=9VS@KA6@PJC@PK1@PYB&size=M&background=transparent', 'link', 'Tágas csomagtér és családbarát kialakítás.', '2026-04-23 07:43:56', '2026-04-23 08:47:41'),
(8, 'Ford', 'Focus ST', 'Hatchback', 2023, 'FOR-456', 'Fehér', 'Benzin', 'Manuális', 5, 22000.00, 'available', 'https://www.autobics.com/wp-content/uploads/2023/03/2023-Ford-Focus-ST-Frozen-White.jpg', 'link', 'Kezelhető városi autó, jó ár-érték aránnyal.', '2026-04-23 07:43:56', '2026-04-23 08:47:30'),
(9, 'Nissan', 'Qashqai', 'SUV', 2021, 'NIS-909', 'Piros', 'Hibrid', 'Automata', 5, 33000.00, 'available', 'https://www-europe.nissan-cdn.net/content/dam/Nissan/hu/vehicles/new_qashqai/QQMC-ICE-N-Design.png', 'link', 'Magasabb üléspozíció és kényelmes utazás hosszabb távra.', '2026-04-23 07:43:56', '2026-04-23 08:42:06'),
(10, 'Kia', 'Sportage', 'SUV', 2023, 'KIA-313', 'Zöld', 'Hibrid', 'Automata', 5, 34000.00, 'available', 'https://www.kia.com/content/dam/kia/us/en/vehicles/sportage/2026/trims/lx/exterior/10101/360/36.png', 'link', 'Modern SUV fejlett biztonsági rendszerekkel.', '2026-04-23 07:43:56', '2026-04-23 08:37:26'),
(11, 'Hyundai', 'i30 N', 'Hatchback', 2022, 'HYU-330', 'Fehér', 'Benzin', 'Manuális', 5, 25000.00, 'available', 'https://d2s8i866417m9.cloudfront.net/photo/39287289/photo/medium-fc29c5f27ae595df81ba14ca3b21704e.png', 'link', 'Kényelmes kompakt autó városi használatra.', '2026-04-23 07:51:21', '2026-04-23 08:48:09'),
(12, 'Renault', 'Clio', 'Hatchback', 2021, 'REN-221', 'Fehér', 'Benzin', 'Manuális', 5, 21000.00, 'available', 'https://images.ctfassets.net/3xid768u5joa/67uQojS19sdvMKIy8iRn9m/23c13f6c35f0b44421cf9e02f542e7ce/01._Glacier_White_solid.jpg', 'link', 'Kis fogyasztású városi autó, könnyű parkolással.', '2026-04-23 07:51:21', '2026-04-23 08:27:49'),
(13, 'Peugeot', '3008', 'SUV', 2022, 'PEU-308', 'Kék', 'Hibrid', 'Automata', 5, 34500.00, 'available', 'https://cdn.gablini.hu/uj-autok/modell/nagy/3008-car-selector-855x410-kxM141KdFv.png', 'link', 'Családi SUV modern infotainment rendszerrel.', '2026-04-23 07:51:21', '2026-04-23 08:28:56'),
(14, 'Opel', 'Astra', 'Kombi', 2020, 'OPE-404', 'Szürke', 'Dízel', 'Manuális', 5, 23500.00, 'available', 'https://api.vantage-leasing.com/storage/vehicles/VA033807/thumbs_astra-sports-tourer-vast-24b.jpg', 'link', 'Tágas kombi hosszabb utazásokhoz.', '2026-04-23 07:51:21', '2026-04-23 08:30:41'),
(15, 'Mazda', 'CX-5', 'SUV', 2023, 'MAZ-525', 'Bordó', 'Benzin', 'Automata', 5, 36500.00, 'available', 'https://media-assets.mazda.eu/image/upload/q_auto,f_auto/mazdahu/contentassets/e3f74746ad4a4828b9666eb05cc02401/new_cx-5-exclusiveline-klbceaa_46v-lhd-7-8.png?rnd=4a4c07', 'link', 'Prémium érzetű SUV kényelmes futóművel.', '2026-04-23 07:51:21', '2026-04-23 08:31:14'),
(16, 'Seat', 'Leon', 'Hatchback', 2021, 'SEA-610', 'Piros', 'Benzin', 'Manuális', 5, 22500.00, 'available', 'https://assets.nexuspointapex.co.uk/resize/480/tenant_d9013b5a-4990-4f3e-beec-4e1fc85990eb/media/10062083/seat-leon-15-tsi-evo-fr-sport-euro-6-ss-5dr-8a42ab079cfcb256019d6d0daaf94c8d-69d74be77f3c1.jpg', 'link', 'Sportos kompakt modell jó ár-érték aránnyal.', '2026-04-23 08:01:02', '2026-04-23 08:14:36'),
(17, 'Honda', 'Civic', 'Sedan', 2022, 'HON-917', 'Fekete', 'Hibrid', 'Automata', 5, 30500.00, 'available', 'https://autocarpet.hu/img/49420/08-1312/500x500/08-1312.webp?time=1678616538', 'link', 'Kényelmes és csendes hibrid szedán.', '2026-04-23 08:01:02', '2026-04-23 08:15:40'),
(18, 'Suzuki', 'Vitara', 'SUV', 2020, 'SUZ-442', 'Fehér', 'Benzin', 'Manuális', 5, 25500.00, 'available', 'https://autocarpet.hu/img/49420/78486/500x500/78486.webp?time=1716206595', 'link', 'Megbízható SUV mindennapi használatra.', '2026-04-23 08:01:02', '2026-04-23 08:16:07'),
(19, 'Dacia', 'Duster', 'SUV', 2021, 'DAC-525', 'Narancs', 'Dízel', 'Manuális', 5, 24500.00, 'available', 'https://w0.peakpx.com/wallpaper/107/490/HD-wallpaper-dacia-duster-crossovers-2018-cars-evolution-new-duster-orange-duster-dacia.jpg', 'link', 'Strapabíró modell rosszabb utakra is.', '2026-04-23 08:01:02', '2026-04-23 08:17:16'),
(20, 'Volvo', 'XC60', 'SUV', 2023, 'VOL-860', 'Szürke', 'Hibrid', 'Automata', 5, 44500.00, 'available', 'https://pictures.dealer.com/v/volvocarswesthoustonvcna/1234/2477a5a245a741e8956b7feeba804c8f.png?impolicy=downsize_bkpt&w=410', 'link', 'Prémium SUV fejlett biztonsági csomaggal.', '2026-04-23 08:01:02', '2026-04-23 08:17:56'),
(21, 'Mini', 'Cooper', 'Hatchback', 2014, 'MIN-111', 'Kék', 'Benzin', 'Automata', 4, 29000.00, 'available', 'https://www.shutterstock.com/image-photo/mini-cooper-model-s-german-600nw-2683049133.jpg', 'link', 'Kompakt, stílusos városi autó.', '2026-04-23 08:01:02', '2026-04-23 14:57:42'),
(22, 'Jeep', 'Compass', 'SUV', 2021, 'JEE-909', 'Zöld', 'Benzin', 'Automata', 5, 33500.00, 'available', 'https://www.jeep.hu/content/dam/jeep/crossmarket/compass-my-25/phev/06-defining-your-style/summit/figurines/JEEP-COMPASS-SUMMIT-PHEV-MY25-565x330-TECHNO-GREEN-BR-FIGURINE.png', 'link', 'Masszív SUV kényelmes utastérrel.', '2026-04-23 08:01:02', '2026-04-23 08:20:34'),
(23, 'Fiat', '500X', 'SUV', 2020, 'FIA-500', 'Fehér', 'Benzin', 'Manuális', 5, 21500.00, 'available', 'https://fiat-wa.com/wp-content/uploads/2018/08/500x_BLANC-GELATO_1450x600px.jpg', 'link', 'Kis méretű crossover városi közlekedésre.', '2026-04-23 08:01:02', '2026-04-23 08:21:02'),
(24, 'Alfa Romeo', 'Giulia', 'Sedan', 2022, 'ALF-777', 'Piros', 'Benzin', 'Automata', 5, 38500.00, 'available', 'https://www.alfaromeo.hu/content/dam/alfa/cross/giulia/white-label-update/figurini/AR-GIULIA-MY24-580X344-TRIM-HP-SPRINT.png', 'link', 'Dinamikus szedán olasz karakterrel.', '2026-04-23 08:01:02', '2026-04-23 08:21:27'),
(25, 'Citroen', 'C5 Aircross', 'SUV', 2023, 'CIT-615', 'Ezüst', 'Hibrid', 'Automata', 5, 35500.00, 'available', 'https://www.citroen.hu/content/dam/citroen/master/b2c/models/new-c5-aircross-2025/opening-of-order/trim-cards/new-c5-aircross-you-ice.webp?imwidth=768', 'link', 'Kényelmes futású SUV hosszabb utakra.', '2026-04-23 08:01:02', '2026-04-23 08:22:17'),
(26, 'BMW', 'Z4', 'Cabrio', 2021, 'CAB-404', 'Ezüst', 'Benzin', 'Automata', 2, 41000.00, 'available', 'https://platform.cstatic-images.com/in/v2/stock_photos/5ee55197-aca0-492d-a017-e6dc1e17c0fb/97a6a188-3db0-4659-b9a8-3338dcee047a.png', 'link', 'Nyitható tetős sportos cabrio élményautó.', '2026-04-23 08:01:02', '2026-04-23 08:07:36'),
(27, 'Toyota', 'Hilux', 'Pickup', 2022, 'PIC-202', 'Fehér', 'Dízel', 'Manuális', 5, 39500.00, 'available', 'https://stimg.cardekho.com/images/car-images/large/Toyota/Hilux/10924/1691990647375/224_super-white_ffffff.jpg?impolicy=resize&imwidth=420', 'link', 'Erős pickup nagy raktérrel és terepképességgel.', '2026-04-23 08:01:02', '2026-04-23 08:22:45'),
(28, 'Subaru', 'Forester', 'Terepjáró', 2023, 'TER-303', 'Zöld', 'Hibrid', 'Automata', 5, 43000.00, 'available', 'https://di-sitebuilder-assets.dealerinspire.com/Subaru/modelLandingPages/Forester/2024/Exterior/Autumn_Green_Metallic_RFH_TAP_360e_023.png', 'link', 'Stabil terepjáró nehezebb útviszonyokra is.', '2026-04-23 08:01:02', '2026-04-23 08:23:34'),
(29, 'Audi', 'TT', 'Coupe', 2020, 'COU-808', 'Piros', 'Benzin', 'Automata', 4, 36500.00, 'available', 'https://imgd.aeplcdn.com/370x208/ec/c5/3E/18867/img/m/Audi-TT-Right-Front-Three-Quarter-52278_ol.jpg?t=121521283&t=121521283&q=80', 'link', 'Kupé karosszériás sportos modell prémium belsővel.', '2026-04-23 08:01:02', '2026-04-23 08:24:18');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_24_110814_create_cars_table', 1),
(5, '2026_03_24_110814_create_reservations_table', 1),
(6, '2026_03_24_110815_create_payments_table', 1),
(7, '2026_03_24_130000_add_category_to_cars_table', 1),
(8, '2026_03_24_160000_add_image_type_to_cars_table', 1),
(9, '2026_04_23_120000_add_phone_number_to_users_table', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reservation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `payments`
--

INSERT INTO `payments` (`id`, `reservation_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 84000.00, 'card', 'paid', 'SEED-1', '2026-04-22 09:51:21', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(2, 2, 2, 108000.00, 'card', 'paid', 'SEED-2', '2026-04-22 09:51:21', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(3, 3, 3, 70000.00, 'card', 'pending', NULL, NULL, '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(4, 4, 4, 156000.00, 'card', 'pending', NULL, NULL, '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(5, 7, 4, 78000.00, 'card', 'pending', NULL, NULL, '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(6, 9, 2, 132000.00, 'card', 'paid', 'DEMO-6', '2026-04-28 07:29:40', '2026-04-23 09:51:21', '2026-04-28 07:29:40'),
(7, 12, 2, 42000.00, 'card', 'paid', 'DEMO-7', '2026-04-28 07:29:43', '2026-04-23 09:51:21', '2026-04-28 07:29:43'),
(8, 15, 2, 109500.00, 'card', 'paid', 'DEMO-8', '2026-04-28 07:29:44', '2026-04-23 09:51:21', '2026-04-28 07:29:44'),
(9, 5, 3, 140000.00, 'card', 'pending', NULL, NULL, '2026-04-28 07:15:18', '2026-04-28 07:15:18'),
(10, 16, 2, 42000.00, 'card', 'pending', NULL, NULL, '2026-04-28 07:30:36', '2026-04-28 07:30:36');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `pickup_location`, `dropoff_location`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2026-04-11', '2026-04-13', 'Budapest', 'Budapest', 84000.00, 'completed', 'Reptéri út', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(2, 2, 2, '2026-04-15', '2026-04-18', 'Győr', 'Győr', 108000.00, 'completed', 'Üzleti út', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(3, 3, 3, '2026-04-19', '2026-04-21', 'Szeged', 'Szeged', 70000.00, 'active', 'Aktív bérlés példa', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(4, 4, 4, '2026-04-22', '2026-04-26', 'Pécs', 'Pécs', 156000.00, 'confirmed', 'Hétvégi kirándulás', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(5, 3, 5, '2026-04-24', '2026-04-29', 'Debrecen', 'Debrecen', 140000.00, 'completed', 'Vár jóváhagyásra', '2026-04-23 09:51:21', '2026-04-28 07:15:18'),
(6, 2, 6, '2026-04-26', '2026-04-28', 'Budapest', 'Eger', 48000.00, 'pending', 'Rövid út', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(7, 4, 7, '2026-04-29', '2026-05-02', 'Miskolc', 'Miskolc', 78000.00, 'confirmed', 'Családi program', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(8, 3, 8, '2026-05-01', '2026-05-03', 'Győr', 'Sopron', 44000.00, 'pending', 'Városnézés', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(9, 2, 9, '2026-05-03', '2026-05-07', 'Budapest', 'Balatonfüred', 132000.00, 'confirmed', 'Nyaralás', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(10, 4, 10, '2026-05-06', '2026-05-09', 'Pécs', 'Pécs', 102000.00, 'pending', 'Céges út', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(11, 3, 11, '2026-05-08', '2026-05-10', 'Kecskemét', 'Kecskemét', 50000.00, 'pending', 'Minta foglalás 11', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(12, 2, 12, '2026-05-10', '2026-05-12', 'Nyíregyháza', 'Nyíregyháza', 42000.00, 'confirmed', 'Minta foglalás 12', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(13, 4, 13, '2026-05-12', '2026-05-15', 'Szolnok', 'Szolnok', 103500.00, 'pending', 'Minta foglalás 13', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(14, 3, 14, '2026-05-14', '2026-05-18', 'Veszprém', 'Veszprém', 94000.00, 'pending', 'Minta foglalás 14', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(15, 2, 15, '2026-05-17', '2026-05-20', 'Budapest', 'Budapest', 109500.00, 'confirmed', 'Minta foglalás 15', '2026-04-23 09:51:21', '2026-04-23 09:51:21'),
(16, 2, 12, '2026-04-28', '2026-04-29', 'Budapest Liszt Ferenc Nemzetközi Repülőtér (BUD)', 'Debrecen Nemzetközi Repülőtér', 42000.00, 'completed', 'Tavaszi túra', '2026-04-28 07:29:14', '2026-04-28 07:30:36');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@bestrent.com', '+36301234567', NULL, '$2y$12$bsn/yd2lUqwNVEOpsEogY.lPGU5GdAHb0jbydBXRSJFeiXoJZBQie', 1, NULL, '2026-04-23 09:43:56', '2026-04-23 11:02:29'),
(2, 'Felhasználó', 'user@bestrent.com', '+36201234567', NULL, '$2y$12$Po4gkVCzfnlmtkXAoGc3BuNVp48QC.QD6keutxX7GfzBE61Q4fObS', 0, NULL, '2026-04-23 09:43:56', '2026-04-23 11:02:29'),
(3, 'Nagy Anna', 'anna@bestrent.com', '+36306667777', NULL, '$2y$12$WZglZ3iDUfB5kIhFVjvK8evMNXOypV..PHgX5J.vCCOWa0UGgdIZG', 0, NULL, '2026-04-23 09:51:21', '2026-04-23 11:02:29'),
(4, 'Kiss Péter', 'peter@bestrent.com', '+36307778888', NULL, '$2y$12$BrRJcKS74Vb2hv4xdHJTr.mBDApw3aDl3Y1Oz1zsep9MPD3Zww7p6', 0, NULL, '2026-04-23 09:51:21', '2026-04-23 11:02:29');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- A tábla indexei `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- A tábla indexei `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cars_plate_number_unique` (`plate_number`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- A tábla indexei `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- A tábla indexei `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_reservation_id_foreign` (`reservation_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- A tábla indexei `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_user_id_foreign` (`user_id`),
  ADD KEY `reservations_car_id_foreign` (`car_id`);

--
-- A tábla indexei `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT a táblához `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
