-- Create database
CREATE DATABASE IF NOT EXISTS gawang_pinas;
USE gawang_pinas;

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  origin VARCHAR(255),
  tag VARCHAR(100),
  tag_class VARCHAR(50),
  description TEXT,
  image_url VARCHAR(500),
  sector VARCHAR(50),
  vote_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Votes table (to track user votes and prevent duplicate voting)
CREATE TABLE IF NOT EXISTS votes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_vote (product_id, user_id)
);

-- Insert sample products
INSERT INTO products (title, origin, tag, tag_class, description, image_url, sector, vote_count) VALUES
('Burnay Drinking Glass', 'Vigan, Ilocos Sur, Philippines', 'Home Goods', 'home', 'Handblown from premium recycled silica compounds by Ilocano craft leaders.', 'https://placehold.co/400x300/2B1F17/C9A97A?text=Burnay+Glass', 'home', 0),
('Piña Weave T-Shirt', 'Kalibo, Aklan, Philippines', 'Apparel', 'fashion', 'Delicately interlaced pineapple plant fiber providing top-tier cooling traits.', 'https://placehold.co/400x300/2B1F17/C9A97A?text=Piña+Shirt', 'apparel', 0),
('Sukang Iloko', 'Ilocos Region, Philippines', 'Pantry Items', 'food', 'Naturally fermented sugarcane vinegar infused with native leaves and spices.', 'https://placehold.co/400x300/2B1F17/C9A97A?text=Sukang+Iloko', 'pantry', 0),
('Basey Banig Mat', 'Basey, Samar, Philippines', 'Home Goods', 'home', 'Traditional mats handcrafted from dyed tikog reeds for a rustic native look.', 'https://placehold.co/400x300/2B1F17/C9A97A?text=Banig+Mat', 'home', 0),
('Capiz Shell Lotus Lantern', 'Samal, Bataan, Philippines', 'Decor/Handicrafts', 'decor', 'Hand-cut marine windowpane oyster shells framed neatly into warm lotus light fixtures.', 'https://placehold.co/400x300/2B1F17/C4522A?text=Capiz+Lantern', 'decor', 0),
('Paete Wooden Table Sculpture', 'Paete, Laguna, Philippines', 'Decor/Handicrafts', 'decor', 'Finely chiseled local wood pieces depicting ancestral countryside farming activities.', 'https://placehold.co/400x300/2B1F17/C4522A?text=Paete+Woodcarving', 'decor', 0),
('Abaca Twine Basket Vase', 'Daraga, Albay, Philippines', 'Decor/Handicrafts', 'decor', 'Heavy-duty dried banana stalk fibers coiled tightly into durable structural statement pieces.', 'https://placehold.co/400x300/2B1F17/C4522A?text=Abaca+Vase', 'decor', 0),
('Traditional Wire Filigree Ornament', 'Sorsogon City, Philippines', 'Decor/Handicrafts', 'decor', 'Intricate metallic silver wire weaves shaped by hand into native structural designs.', 'https://placehold.co/400x300/2B1F17/C4522A?text=Filigree+Ornament', 'decor', 0);