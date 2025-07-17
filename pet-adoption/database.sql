SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `pets`, `dog_breeds`, `cat_breeds`, `species`, `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `species` (
  `SpeciesID` int(11) NOT NULL,
  `SpeciesName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `species` (`SpeciesID`, `SpeciesName`) VALUES
(0, 'Dog'),
(1, 'Cat');

CREATE TABLE `cat_breeds` (
  `BreedID` int(11) NOT NULL,
  `BreedName` varchar(100) NOT NULL,
  `SpeciesID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cat_breeds` (`BreedID`, `BreedName`, `SpeciesID`) VALUES
(100, 'Bengal', 1),
(101, 'Persian', 1),
(102, 'Siamese', 1),
(103, 'PusPin', 1),
(104, 'Maine Coon', 1),
(105, 'Tabby', 1);

CREATE TABLE `dog_breeds` (
  `BreedID` int(11) NOT NULL,
  `BreedName` varchar(100) NOT NULL,
  `SpeciesID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dog_breeds` (`BreedID`, `BreedName`, `SpeciesID`) VALUES
(0, 'Aspin', 0),
(1, 'Shih Tzu', 0),
(2, 'Pug', 0),
(3, 'Labrador Retriever', 0),
(4, 'Golden Retriever', 0),
(5, 'Chihuahua', 0);

CREATE TABLE `pets` (
  `PetID` int(11) NOT NULL,
  `PetName` varchar(100) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT 'Unknown',
  `is_spayed_neutered` tinyint(1) NOT NULL DEFAULT 0,
  `days_in_shelter` int(11) DEFAULT 0,
  `description` varchar(255) DEFAULT '',
  `personality_tags` varchar(100) DEFAULT '',
  `SpeciesID` int(11) DEFAULT NULL,
  `BreedID` int(11) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `PetAvail` varchar(20) DEFAULT NULL,
  `OwnerName` varchar(100) DEFAULT NULL,
  `OwnerContact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pets` (`PetID`, `PetName`, `Age`, `gender`, `is_spayed_neutered`, `days_in_shelter`, `description`, `personality_tags`, `SpeciesID`, `BreedID`, `ImageURL`, `PetAvail`, `OwnerName`, `OwnerContact`) VALUES
(1, 'Lyka', 1, 'Female', 1, 0, '', 'Curious, Gentle', 1, 103, 'uploads/lyka.jpg', '1', NULL, NULL),
(2, 'Nina', 1, 'Female', 1, 0, '', 'Playful, Affectionate', 1, 103, 'uploads/nina.jpg', '1', NULL, NULL),
(3, 'San Cia', 1, 'Female', 1, 0, '', 'Loyal, Smart', 0, 0, 'uploads/sancia.jpg', '1', NULL, NULL),
(4, 'Uno', 3, 'Male', 1, 0, '', 'Energetic, Friendly', 0, 0, 'uploads/uno.jpg', '1', NULL, NULL),
(5, 'Khali', 2, 'Female', 1, 0, '', 'Sweet, Calm', 0, 0, 'uploads/khali.jpg', '1', NULL, NULL),
(6, 'Spot', 5, 'Female', 1, 500, 'After waiting 500 long days, Spot, who was rescued when her previous owner could no longer care for her, finally found her forever playmate in a loving family.', 'Loving, Loyal', 0, 0, 'uploads/spot.jpg', '0', 'a loving family', NULL),
(7, 'Kitkat', 2, 'Female', 1, 203, 'Waiting 203 days was worth it! This sweet girl, found as a stray, charmed her way into the hearts of a wonderful family and is now showered with love.', 'Good with kids, Sweet', 1, 103, 'uploads/kitkat.jpg', '0', 'a loving family', NULL),
(8, 'Blackster', 3, 'Male', 1, 199, 'Blackster’s 199-day wait is over! Rescued from the streets, this energetic boy’s playful spirit won over a family who gives him all the adventures he deserves.', 'Energetic, Playful', 0, 0, 'uploads/blackster.jpg', '0', 'a loving family', NULL);

ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `cat_breeds` ADD PRIMARY KEY (`BreedID`), ADD KEY `fk_cat_species` (`SpeciesID`);
ALTER TABLE `dog_breeds` ADD PRIMARY KEY (`BreedID`), ADD KEY `fk_dog_species` (`SpeciesID`);
ALTER TABLE `pets` ADD PRIMARY KEY (`PetID`), ADD KEY `SpeciesID` (`SpeciesID`), ADD KEY `BreedID` (`BreedID`);
ALTER TABLE `species` ADD PRIMARY KEY (`SpeciesID`);

ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pets` MODIFY `PetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `cat_breeds` ADD CONSTRAINT `fk_cat_species` FOREIGN KEY (`SpeciesID`) REFERENCES `species` (`SpeciesID`);
ALTER TABLE `dog_breeds` ADD CONSTRAINT `fk_dog_species` FOREIGN KEY (`SpeciesID`) REFERENCES `species` (`SpeciesID`);
ALTER TABLE `pets` ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`SpeciesID`) REFERENCES `species` (`SpeciesID`);

COMMIT;