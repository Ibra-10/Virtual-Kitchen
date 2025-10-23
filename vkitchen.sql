

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `rid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `type` enum('French','Italian','Chinese','Indian','Mexican','Others') NOT NULL,
  `Cookingtime` int(4) ,
  `ingredients` varchar(1000) ,
  `instructions` varchar(1000) ,
  `image` varchar(200),
  `uid` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
COMMIT;


INSERT INTO `users` (`username`, `password`, `email`) VALUES
('dJohnyy21', '12122004', 'davidj21@gmail.com'),
('lmessicr7', 'goats123', 'lmessironaldo@gmail.com'),
('themexicanguy', '123vamos', 'elmejor@gmail.com');

INSERT INTO `recipes` (`name`, `description`, `type`, `Cookingtime`, `ingredients`, `instructions`, `image`, `uid`) VALUES
('Spaghetti Bolognese', 'A classic Italian pasta dish with a rich meat sauce.', 'Italian', 30, 'spaghetti, ground beef, tomato sauce, onion, garlic, olive oil, salt, pepper', 'Cook spaghetti. In a pan, sauté onion and garlic in olive oil. Add ground beef and cook until browned. Stir in tomato sauce and simmer. Serve over spaghetti.', 'spaghetti_bolognese.jpg', 1),
('Chicken Curry', 'A spicy Indian dish made with chicken and a variety of spices.', 'Indian', 45, 'chicken, curry powder, coconut milk, onion, garlic, ginger, salt, pepper', 'Sauté onion, garlic, and ginger in a pan. Add chicken and cook until browned. Stir in curry powder and coconut milk. Simmer until chicken is cooked through.', 'chicken_curry.jpg', 2),
('Tacos', 'A traditional Mexican dish consisting of a folded or rolled tortilla filled with various mixtures.', 'Mexican', 20, 'tortillas, ground beef, lettuce, tomato, cheese, salsa', 'Cook ground beef in a pan. Fill tortillas with beef and top with lettuce, tomato, cheese, and salsa.', 'tacos.jpg', 3);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
