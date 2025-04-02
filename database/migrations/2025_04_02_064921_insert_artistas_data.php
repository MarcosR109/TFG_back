<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("INSERT INTO artistas (id, nombre) VALUES
-- Rock Clásico / Rock Alternativo
(2, 'The Rolling Stones'),
(3, 'Led Zeppelin'),
(4, 'Pink Floyd'),
(5, 'Queen'),
(6, 'The Who'),
(7, 'The Doors'),
(8, 'AC/DC'),
(9, 'The Eagles'),
(10, 'The Kinks'),
(11, 'The Beach Boys'),
(12, 'Fleetwood Mac'),
(13, 'David Bowie'),
(14, 'The Police'),
(15, 'The Cure'),
(16, 'R.E.M.'),
(17, 'U2'),
(18, 'Radiohead'),
(19, 'Oasis'),
(20, 'The Smashing Pumpkins'),
(21, 'Foo Fighters'),
(22, 'The Strokes'),
(23, 'Arctic Monkeys'),
(24, 'The White Stripes'),
(25, 'The Killers'),
(26, 'Coldplay'),
(27, 'Green Day'),
(28, 'Nirvana'),
(29, 'Pearl Jam'),
(30, 'Soundgarden'),
(31, 'Alice In Chains'),
(32, 'Stone Temple Pilots'),

-- Metal / Hard Rock
(33, 'Metallica'),
(34, 'Iron Maiden'),
(35, 'Black Sabbath'),
(36, 'Deep Purple'),
(37, 'Judas Priest'),
(38, 'Motörhead'),
(39, 'Slayer'),
(40, 'Dream Theater'),
(41, 'Guns N'' Roses'),
(42, 'Bon Jovi'),
(43, 'Def Leppard'),
(44, 'Kiss'),
(45, 'Mötley Crüe'),
(46, 'Barón Rojo'),
(47, 'Obús'),

-- Pop Internacional
(48, 'Michael Jackson'),
(49, 'Madonna'),
(50, 'Prince'),
(51, 'Whitney Houston'),
(52, 'Mariah Carey'),
(53, 'Céline Dion'),
(54, 'Britney Spears'),
(55, 'Christina Aguilera'),
(56, 'Jennifer Lopez'),
(57, 'Shakira'),
(58, 'Taylor Swift'),
(59, 'Ariana Grande'),
(60, 'Billie Eilish'),
(61, 'Dua Lipa'),
(62, 'Ed Sheeran'),
(63, 'Justin Bieber'),
(64, 'Harry Styles'),
(65, 'Selena Gomez'),
(66, 'Camila Cabello'),
(67, 'Shawn Mendes'),
(68, 'Miley Cyrus'),
(69, 'Katy Perry'),
(70, 'Lady Gaga'),
(71, 'Beyoncé'),
(72, 'Rihanna'),
(73, 'Olivia Rodrigo'),
(74, 'Lana Del Rey'),
(75, 'ABBA'),

-- Hip-Hop / Rap
(76, 'Eminem'),
(77, 'Kanye West'),
(78, 'Kase.O'),
(79, 'Natos y Waor'),
(80, 'Violadores del verso'),
(81, 'Hoke'),
(82, 'C. Tangana'),
(83, 'Rels B'),
(84, 'Bad Gyal'),
(85, 'Yung Beef'),
(86, 'Kidd Keo'),
(87, 'Bejo'),
(88, 'Cardi B'),
(89, 'Nicki Minaj'),
(90, 'Doja Cat'),
(91, 'SZA'),

-- Rock/Pop Español
(92, 'Héroes del Silencio'),
(93, 'Extremoduro'),
(94, 'Platero y Tú'),
(95, 'Marea'),
(96, 'Rosendo'),
(97, 'Bunbury'),
(98, 'Andrés Calamaro'),
(99, 'Joaquín Sabina'),
(100, 'Fito & Fitipaldis'),
(101, 'Los Rodríguez'),
(102, 'Leiva'),
(103, 'Duncan Dhu'),
(104, 'Los Secretos'),
(105, 'Nacha Pop'),
(106, 'Hombres G'),
(107, 'Revólver'),
(108, 'Manolo García'),
(109, 'Miguel Ríos'),
(110, 'Robe'),
(111, 'Iván Ferreiro'),

-- Indie/Alternativo Español
(112, 'Vetusta Morla'),
(113, 'Izal'),
(114, 'Love of Lesbian'),
(115, 'Deluxe'),
(116, 'Supersubmarina'),
(117, 'Lori Meyers'),
(118, 'Antílopez'),
(119, 'Pereza'),
(120, 'La Oreja de Van Gogh'),
(121, 'Amaral'),
(122, 'El Canto del Loco'),
(123, 'Mecano'),
(124, 'El Kanka'),
(125, 'Zahara'),
(126, 'Silvana Estrada'),
(127, 'Sidonie'),
(128, 'Kiko Veneno'),

-- Reggaeton/Latino
(129, 'Bad Bunny'),
(130, 'J Balvin'),
(131, 'Maluma'),
(132, 'Ozuna'),
(133, 'Anuel AA'),
(134, 'Karol G'),
(135, 'Nicky Jam'),
(136, 'Farruko'),
(137, 'Don Omar'),
(138, 'Wisin & Yandel'),
(139, 'Yandel'),
(140, 'Zion & Lennox'),
(141, 'Luis Fonsi'),
(142, 'Pitbull'),
(143, 'Daddy Yankee'),
(144, 'Rosalía'),
(145, 'Nene Malo'),

-- Otros (Flamenco, Folk, Varios)
(146, 'Paco de Lucía'),
(147, 'Camela'),
(148, 'Estopa'),
(149, 'Celtas Cortos'),
(150, 'Jarabe de Palo'),
(151, 'Macaco'),
(152, 'Albert Pla'),
(153, 'Bebe'),
(154, 'Melendi'),
(155, 'Don Patricio'),
(156, 'La Pegatina'),
(157, 'Ska-P'),
(158, 'Txarrena'),
(159, 'Kishi Bashi'),
(160, 'Pablo Alborán'),
(161, 'Aitana'),
(162, 'Alaska'),
(163, 'Ergo Pro'),
(164, 'Cecilio G'),
(165, 'Dover'),
(166, 'M-Clan'),
(167, 'Sabrina Carpenter'),
(168, 'Lizzo'),
(169, 'Halsey'),
(170, 'The Clash'),
(171, 'The Smiths'),
(172, 'Yes'),
(173, 'Genesis');
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
