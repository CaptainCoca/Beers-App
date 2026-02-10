-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.4.3 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table maitre_houblon.beers : ~76 rows (environ)
INSERT INTO `beers` (`id`, `nom`, `brasserie`, `pays`, `ville`, `type`, `fermentation`, `degre`, `description`, `image_url`, `date_ajout`) VALUES
	(1, '1664', 'Kronenbourg', 'France', 'Strasbourg', 'Blonde', 'Basse', 5.5, 'Une bière blonde classique et rafraîchissante, pilier des brasseries françaises.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(2, '3 Monts', 'Brasserie de Saint-Sylvestre', 'France', 'Saint-Sylvestre-Cappel', 'Bière de Garde', 'Haute', 8.5, 'Une bière flamande de caractère, puissante et maltée.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(3, '8.6 Original', 'Bavaria', 'Pays-Bas', 'Lieshout', 'Lager Forte', 'Basse', 8.6, 'Une bière riche en alcool avec un goût de céréales prononcé.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(4, 'Abbaye de Vaucelles', 'Brasserie de Vaucelles', 'France', 'Vaucelles', 'Triple', 'Haute', 8.5, 'Une bière d\'abbaye artisanale, équilibrée avec des notes fruitées.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(5, 'Adelscott', 'Brasserie Fischer', 'France', 'Schiltigheim', 'Bière au Malt à Whisky', 'Basse', 5.8, 'Une bière ambrée originale avec des notes fumées uniques.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(6, 'Affligem Blonde', 'Affligem', 'Belgique', 'Opwijk', 'Blonde d\'Abbaye', 'Haute', 6.7, 'Une bière belge classique, fruitée et légèrement épicée.', '/Images/default_beer.png', '2026-02-09 13:40:39'),
	(7, 'Abbaye de Westmalle Trappist Ale', 'Westmalle', 'Belgique', 'Westmalle', 'Trappiste', 'Haute', 9.5, 'Ambrée foncée, mousse beige, nez de fruits mûrs et de levure. Puissante et complexe en bouche.', '/Images/default_beer.png', '2026-02-09 13:54:39'),
	(8, 'Abbot Ale', 'Greene King', 'Angleterre', 'Bury St Edmunds', 'Pale Ale', 'Haute', 5.0, 'Bière ambrée, mousse crémeuse, arômes de fruits secs et de caramel. Amertume équilibrée.', '/Images/default_beer.png', '2026-02-09 13:54:39'),
	(9, 'Achel Blonde', 'Brouwerij der Sint-Benedictusabdij', 'Belgique', 'Hamont-Achel', 'Trappiste', 'Haute', 8.0, 'Blonde dorée, nez frais de houblon et d\'agrumes. Bouche sèche et amère.', '/Images/default_beer.png', '2026-02-09 13:54:39'),
	(10, 'Adelscott', 'Fischer', 'France', 'Schiltigheim', 'Malt à Whisky', 'Basse', 5.8, 'Ambrée limpide, mousse beige peu stable. Nez de tourbe et de bois fumé très caractéristique.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(11, 'Affligem Blonde', 'Affligem', 'Belgique', 'Opwijk', 'Abbaye', 'Haute', 6.7, 'Dorée-blonde, mousse blanche fine. Nez de miel, de fleurs et de levure belge.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(12, 'Amnesia', 'De la Somme', 'France', 'Beauquesne', 'Double IPA', 'Haute', 8.5, 'Ambrée trouble, mousse persistante. Explosion de houblons, notes de résine et d\'agrumes.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(13, 'Anosteké Blonde', 'Du Pays Flamand', 'France', 'Merville', 'Blonde', 'Haute', 8.0, 'Blonde dorée, très aromatique (houblons fins). Notes de fleurs sauvages et d\'agrumes.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(14, 'Antwerpen', 'De Koninck', 'Belgique', 'Anvers', 'Spéciale Belge', 'Haute', 8.0, 'Ambrée cuivrée, mousse onctueuse. Goût de caramel, malt grillé et une légère amertume.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(15, 'Asahi Super Dry', 'Asahi Breweries', 'Japon', 'Tokyo', 'Lager', 'Basse', 5.0, 'Blonde très pâle, extrêmement limpide. Goût sec et net, finale très courte et rafraîchissante.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(16, 'Ayinger Celebrator', 'Ayinger Privatbrauerei', 'Allemagne', 'Aying', 'Doppelbock', 'Basse', 6.7, 'Noir profond, mousse café. Arômes de café, de chocolat noir et de fruits secs.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(17, 'Augustiner Lagerbier Hell', 'Augustiner-Bräu', 'Allemagne', 'Munich', 'Helles', 'Basse', 5.2, 'Blonde paille, limpide. Très équilibrée, notes de pain frais et de houblon floral.', '/Images/default_beer.png', '2026-02-09 13:55:16'),
	(18, 'Adelscott', 'Fischer/Pêcheur', 'France', 'Schiltigheim', 'Spéciale', 'basse', 6.6, 'Bière ambrée, limpide, arôme et goût très maltés (malt à whisky). Équilibrée.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(19, 'Adelscott noire', 'Fischer/Pêcheur', 'France', 'Schiltigheim', 'Spéciale', 'basse', 6.6, 'Bière brune, limpide, arôme et goût très maltés (malt à whisky).', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(20, 'Adnams Champion Pale Ale', 'Adnams', 'Angleterre', 'Southwold', 'Pale Ale', 'haute', 3.1, 'Pale Ale dorée, limpide. Nez fin et onctueux aux arômes de houblon.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(21, 'Aerts 1900 étiquette noire', 'Aerts', 'Belgique', 'Lembeek', 'Spéciale', 'haute', 7.0, 'Spéciale brune-orangée, limpide. Arômes caramélisés, subtilités de fermentation.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(22, 'Aerts 1900 étiquette rouge', 'Aerts', 'Belgique', 'Lembeek', 'Spéciale', 'haute', 7.0, 'Spéciale de couleur orange, limpide, mousse onctueuse. Arôme subtil.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(23, 'Affligem capsule blanche', 'De smedt', 'Belgique', 'Opwijk', 'Abbaye', 'haute', 7.0, 'Couleur ambrée limpide. Arôme distingué mais puissant (levures, miel, fruit).', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(24, 'Affligem capsule rouge', 'De smedt', 'Belgique', 'Opwijk', 'Abbaye', 'haute', 7.0, 'Marron-acajou, limpide. Nez puissant, réhaussé de houblon. Équilibre des saveurs.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(25, 'Affligem triple', 'De smedt', 'Belgique', 'Opwijk', 'Abbaye', 'haute', 8.5, 'Ambrée, limpide. Arôme affirmé de malt caramélisé, nez frais de houblon.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(26, 'Alkenmünster', 'Brauhaus Marktoberdorf', 'Allemagne', 'Marktoberdorf', 'Pils', 'basse', 5.0, 'Jaune assez pâle, limpide. Nez de malt et de houblon aromatique et léger.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(27, 'Amsel', 'Heineken', 'Hollande', 'Amsterdam', 'Pils', 'basse', 5.0, 'Une pils classique, bien saturée, à la mousse blanche et serrée.', '/Images/default_beer.png', '2026-02-09 14:04:33'),
	(28, 'Arabier', 'De Dolle Brouwers', 'Belgique', 'Esen', 'Spéciale', 'haute', 8.0, 'Blonde, dorée, limpide. Type de bière qu\'on ne rencontre pas souvent. Amère et corsée.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(29, 'Arten braü', 'Semeuse', 'France', 'Lille', 'Pils', 'basse', 4.8, 'Pils jaune limpide. Nez doux et profond. Bouche pleine bien maltée. Bonne pils classique.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(30, 'Artevelde Grand cru', 'Huyghe', 'Belgique', 'Melle', 'Spéciale', 'haute', 5.6, 'Belle mousse onctueuse, couleur ambrée. Bon arôme de malt et léger parfum de caramel.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(31, 'Augustijn anno 1295', 'Van Steenberge', 'Belgique', 'Ertvelde', 'Abbaye', 'haute', 8.0, 'Une abbaye belge assez classique. Assez bonne bière, pas extraordinaire.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(32, 'Augustiner Brau München Edelstoff', 'Augustiner', 'Allemagne', 'Munich', 'Münchner', 'basse', 5.5, 'Blonde jaune, transparente. Saveur houblonnée aromatique, fruitée et assez concentrée.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(33, 'Aulne des Peres', 'Leveau', 'Belgique', 'Charleroi', 'Abbaye', 'haute', 7.0, 'Bière blonde dorée.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(34, 'Australian Crocodile', 'Appeltoffstka', 'Australie', 'Sydney', 'Pils', 'basse', 4.5, 'Pils jaune pâle, cristalline. Arôme net de houblons aromatiques.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(35, 'Bacchus', 'Van Honsebrouck', 'Belgique', 'Ingelmunster', 'Spéciale', 'haute', 4.5, 'Marron-orangée, cristalline. Arôme acide, bouquet aigre-doux puissant.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(36, 'Barbar', 'Lefebvre', 'Belgique', 'Quenast', 'Spéciale', 'Haute', 8.0, 'Bière forte au miel refermentée en bouteille.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(37, 'Bécasse Framboise lambic', 'Brasseries bruxelloises', 'Belgique', 'Bruxelles', 'Lambic', 'spontanée', 5.2, 'Sucrée et astringente, comme la framboise dont elle est tirée.', '/Images/default_beer.png', '2026-02-09 14:06:04'),
	(38, 'Bécasse Gueuze lambic', 'Brasseries bruxelloises', 'Belgique', 'Bruxelles', 'Lambic', 'spontanée', 5.2, 'Gueuze cuivrée, mousse fugace. Lambic commercial assez classique.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(39, 'Beck\'s bier', 'Beck', 'Allemagne', 'Breme', 'Pils', 'basse', 5.0, 'Pils allemande, moyennement amère, belle mousse légèrement crémeuse.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(40, 'Belle-Vue Framboise', 'Vandenstock', 'Belgique', 'Bruxelles', 'Lambic', 'spontanée', 5.2, 'Gueuze parfumée à la framboise, goût sur mais un peu trop doux.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(41, 'Belzebuth', 'Jeanne d\'Arc', 'France', 'Ronchin', 'Spéciale', 'haute', 15.0, 'Bière blonde avec un très goût d\'alcool.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(42, 'Benedict', 'De kluis', 'Belgique', 'Hoegaarden', 'Abbaye', 'haute', 7.3, 'Brune foncée sur lie. Arôme caramel épicé, rond et profond.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(43, 'Benelux Pils', 'Saarfürst', 'Luxembourg', 'Merzig/Sarre', 'Pils', 'basse', 4.8, 'Jaune cristalline. Saveur ronde, aromatique, goût doux.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(44, 'Bernoville', 'Bernoville', 'France', 'Aisonville', 'Garde', 'haute', 5.5, 'Bière de garde classique.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(45, 'Bière de Garde Theillier', 'Theillier', 'France', 'Bavay', 'Garde', 'haute', 4.6, 'Couleur acajou, limpide. Saveur plutôt équilibrée, corps léger.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(46, 'Bière de luxe St Omer', 'Brasserie de St Omer', 'France', 'St Omer', 'Pils', 'basse', 5.0, 'Bière de table française, arôme bien houblonné.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(47, 'Bière de Mars Schutz', 'Schutzenberger', 'France', 'Schiltigheim', 'Spéciale', 'basse', 5.2, 'Ambrée-orangée, cristalline. Saveur équilibrée entre amertume et douceur.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(48, 'Bière de Noël', 'de Monceau St Waast', 'France', 'Monceau St Waast', 'Garde', 'haute', 6.0, 'Bière de garde de noël, couleur acajou. Saveur fine, vineuse, bien équilibrée.', '/Images/default_beer.png', '2026-02-09 14:08:59'),
	(49, 'Bière de Noël St Sylvestre', 'de St Sylvestre', 'France', 'St Sylvestre Cappel', 'Bière de Noël', 'haute', 8.0, 'Cuivrée-orangée, cristalline. Nez puissant et riche de houblon et caramel. Saveur pleine et ronde.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(50, 'Bière de Noël Semense', 'Semeuse', 'France', 'Lille', 'Bière de Noël', 'basse', 4.8, 'Orangée, cristalline. Arôme léger de malt houblonné, subtil nez de caramel.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(51, 'Bière de St Nicolas de Port', 'IFBM', 'France', 'Nancy', 'Pils', 'basse', 6.3, 'Pils dorée, limpide. Nez malté doux et profond. Saveur franche.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(52, 'Bière des sans culottes', 'La choulette', 'France', 'Hordain', 'Garde', 'haute', 7.5, 'Blonde abricot, trouble. Un goût fruité et légèrement amer, un peu acide.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(53, 'Bière du corsaire', 'Artevelde', 'Belgique', 'Melle', 'Spéciale', 'haute', 9.1, 'Spéciale blonde-abricot, trouble. Arôme relevé, épicé aux tons doux et piquants.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(54, 'Bière du démon', 'Enfants de Gayant', 'France', 'Douai', 'Spéciale basse', 'basse', 12.0, 'Jaune assez pâle, limpide. Arôme profond et piquant d\'alcool. Goût puissant et sucré.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(55, 'Bière du désert', 'Enfants de Gayant', 'France', 'Douai', 'Spéciale', 'haute', 7.0, 'Bière spéciale d\'un jaune très pâle, limpide. Arôme assez frais, équilibré malt-houblon.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(56, 'Bière du Mont St Aubert', 'de Brunehaut', 'Belgique', 'Brunehaut', 'Spéciale', 'haute', 8.0, 'Bière blonde.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(57, 'Bischoff', 'Bischoff', 'Allemagne', 'Winnweiler', 'Pils', 'basse', 4.3, 'Pils claire, cristalline. Nez de malt finement houblonné. Amertume marquée.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(58, 'Bitburger', 'Bitburger', 'Allemagne', 'Bitburg', 'Pils', 'basse', 4.8, 'Pils claire, cristalline. Nez de houblon aromatique. Saveur franche et agréable.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(59, 'Black Sheep Ale', 'Black Sheep', 'Angleterre', 'Masham', 'Ale', 'haute', 4.4, 'Ale rousse, limpide. Arôme fin de malt, légers tons de caramel. Saveur toute en finesse.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(60, 'Black Stout', 'Young & Co\'s', 'Angleterre', 'Londres', 'Stout', 'haute', 5.0, 'Stout ressemblant un peu à la Guinness mais pas aussi typé.', '/Images/default_beer.png', '2026-02-09 14:11:16'),
	(61, 'Boxer old lager', 'Boxer', 'Suisse', 'Romanel', 'Pils', 'basse', 5.3, 'Jaune matiné d\'ambre (très léger), assez forte odeur de malt. Bière assez peu amère.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(62, 'Brassin Robespierre', 'La choulette', 'France', 'Hordain', 'Garde', 'haute', 7.5, 'Bière ambrée rousse d\'excellente qualité. Brassée pour le bicentenaire de la révolution.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(63, 'Braunfelser', 'Schlossbrauerei', 'Allemagne', 'Braunfels', 'Pils', 'basse', 4.7, 'Pils dorée, limpide, à la mousse blanche moyennement serrée. Saveur franche et marquée.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(64, 'Brigand', 'Van Honsebrouck', 'Belgique', 'Ingelmunster', 'Spéciale', 'haute', 9.0, 'Ambrée au goût évolutif. Un peu aigre au début, elle reprend vite un goût plus moelleux.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(65, 'Brug-Ale', 'de Silly', 'Belgique', 'Silly', 'Pale Ale', 'haute', 5.0, 'Pale Ale orangée, légèrement troublée par la levure. Fine et délicate.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(66, 'Brugge Tripel', 'De gouden boom', 'Belgique', 'Bruges', 'Spéciale', 'haute', 9.5, 'Une triple très forte et de qualité moyenne. Bière ambrée et capiteuse.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(67, 'Brunehaut ambrée', 'de Brunehaut', 'Belgique', 'Brunehaut', 'Spéciale', 'haute', 6.5, 'Bière d\'une couleur orangée-marron intense. Arôme de malt fumé puissant.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(68, 'Brunehaut blonde', 'de Brunehaut', 'Belgique', 'Brunehaut', 'Spéciale', 'haute', 6.5, 'Bière dorée, transparente (sur levure). Saveur ronde, douce et acre.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(69, 'Brunette', 'Van steenberge', 'Belgique', 'Ertevelde', 'Spéciale', 'haute', 9.0, 'Acajou, limpide (sur levure). Odeur fraîche, aromatique, profondeur maltée.', '/Images/default_beer.png', '2026-02-09 14:31:26'),
	(70, 'Bud (Budweiser)', 'Anheuser Busch', 'U.S.A', 'St Louis', 'Pils', 'basse', 5.0, 'Pils jaune pâle, cristalline. Arôme délicat de céréales (malt et riz).', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(71, 'Budweiser Budvar', 'Budweiser Budvar', 'Tchéquie', 'Ceske Budejovice', 'Pils', 'basse', 5.0, 'Pils dorée, cristalline, finement saturée. Saveur simple, douce et ronde.', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(72, 'Bulldog Strong Ale', 'Courage', 'Angleterre', 'Reading', 'Pale Ale', 'haute', 6.3, 'Ale cuivrée, limpide. Arôme à dominante maltée, léger nez de caramel.', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(73, 'Burton Porter', 'Burton Bridge Brewery', 'Angleterre', 'Burton-on-trent', 'Porter', 'haute', 4.5, 'Bière noire-acajou, mousse brune peu serrée. Goût de pain grillé et de levure.', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(74, 'Bush beer', 'Dubuisson', 'Belgique', 'Pipaix', 'Spéciale', 'haute', 12.0, 'Bière cuivrée, limpide. Saveur maltée et sucrée, bouche moelleuse et forte.', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(75, 'Caffrey\'s', 'Thomas caffrey', 'Irlande', 'Antrim', 'Pale Ale', 'haute', 4.8, 'Une Ale irlandaise onctueuse et crémeuse.', '/Images/default_beer.png', '2026-02-09 14:31:55'),
	(76, 'Caledonian export ale 80%', 'Caledonian Brewery', 'Ecosse', 'Edimbourg', 'Pale Ale', 'haute', 4.1, 'Bière rousse, cristalline. Arôme bien malté, rond et profond.', '/Images/default_beer.png', '2026-02-09 14:31:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
