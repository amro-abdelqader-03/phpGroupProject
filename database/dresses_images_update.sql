USE wedding_store;

-- Remove old image records for the five original dresses only
DELETE FROM product_images
WHERE product_id IN (1, 2, 3, 4, 5);

-- Insert the new images for the original five dresses
INSERT INTO product_images
(product_id, image_url, is_primary)
VALUES

-- A-Line Dress 1
(1, '/Data/women/dress/a-line/product1/Front.jpg', 1),
(1, '/Data/women/dress/a-line/product1/back.jpg', 0),
(1, '/Data/women/dress/a-line/product1/another.jpg', 0),

-- Mermaid Dress 1
(2, '/Data/women/dress/mermaid/product1/front.jpg', 1),
(2, '/Data/women/dress/mermaid/product1/back.jpg', 0),
(2, '/Data/women/dress/mermaid/product1/another.jpg', 0),
(2, '/Data/women/dress/mermaid/product1/another2.jpg', 0),

-- Princess Dress 1
(3, '/Data/women/dress/princessDress/product1/princess.jpg', 1),
(3, '/Data/women/dress/princessDress/product1/princess1.jpg', 0),
(3, '/Data/women/dress/princessDress/product1/princess2.jpg', 0),
(3, '/Data/women/dress/princessDress/product1/princess3.jpg', 0),

-- Royal Dress 1
(4, '/Data/women/dress/royal/product1/front.webp', 1),
(4, '/Data/women/dress/royal/product1/back.webp', 0),
(4, '/Data/women/dress/royal/product1/another.webp', 0),
(4, '/Data/women/dress/royal/product1/another2.webp', 0),

-- Sheath Dress 1
(5, '/Data/women/dress/sheath/product1/front.jpg', 1),
(5, '/Data/women/dress/sheath/product1/back.jpg', 0),
(5, '/Data/women/dress/sheath/product1/another.jpg', 0);




USE wedding_store;

-- A-Line Dresses
INSERT INTO product_images (product_id, image_url, is_primary)
VALUES
(57, '/Data/women/dress/a-line/a-line2/front.jpg', 1),
(57, '/Data/women/dress/a-line/a-line2/back.jpg', 0),

(58, '/Data/women/dress/a-line/a-line3/front.jpg', 1),
(58, '/Data/women/dress/a-line/a-line3/back.jpg', 0),
(58, '/Data/women/dress/a-line/a-line3/another.jpg', 0),

(59, '/Data/women/dress/a-line/a-line4/front.webp', 1),
(59, '/Data/women/dress/a-line/a-line4/back.webp', 0),
(59, '/Data/women/dress/a-line/a-line4/another.webp', 0);

-- Mermaid Dresses
INSERT INTO product_images (product_id, image_url, is_primary)
VALUES
(60, '/Data/women/dress/mermaid/product2/front.jpg', 1),
(60, '/Data/women/dress/mermaid/product2/back.jpg', 0),
(60, '/Data/women/dress/mermaid/product2/another.jpg', 0),

(61, '/Data/women/dress/mermaid/product3/front.jpg', 1),
(61, '/Data/women/dress/mermaid/product3/back.jpg', 0),
(61, '/Data/women/dress/mermaid/product3/another.jpg', 0),
(61, '/Data/women/dress/mermaid/product3/another2.jpg', 0),

(62, '/Data/women/dress/mermaid/product4/front.jpg', 1),
(62, '/Data/women/dress/mermaid/product4/back.jpg', 0),
(62, '/Data/women/dress/mermaid/product4/another.jpg', 0),
(62, '/Data/women/dress/mermaid/product4/another2.jpg', 0);

-- Princess Dresses
INSERT INTO product_images (product_id, image_url, is_primary)
VALUES
(63, '/Data/women/dress/princessDress/product2/princessDress.jpg', 1),
(63, '/Data/women/dress/princessDress/product2/princess1.jpg', 0),
(63, '/Data/women/dress/princessDress/product2/princess3.jpg', 0),

(64, '/Data/women/dress/princessDress/product3/princess.jpg', 1),
(64, '/Data/women/dress/princessDress/product3/princess2.jpg', 0),
(64, '/Data/women/dress/princessDress/product3/princess3.jpg', 0),
(64, '/Data/women/dress/princessDress/product3/princess4.jpg', 0),

(65, '/Data/women/dress/princessDress/product4/front.jpg', 1),
(65, '/Data/women/dress/princessDress/product4/back.jpg', 0),
(65, '/Data/women/dress/princessDress/product4/princess3.jpg', 0);

-- Royal Dresses
INSERT INTO product_images (product_id, image_url, is_primary)
VALUES
(66, '/Data/women/dress/royal/product2/front.jpg', 1),
(66, '/Data/women/dress/royal/product2/backk.jpg', 0),
(66, '/Data/women/dress/royal/product2/another.jpg', 0),
(66, '/Data/women/dress/royal/product2/another2.jpg', 0),

(67, '/Data/women/dress/royal/product3/front.webp', 1),
(67, '/Data/women/dress/royal/product3/back.webp', 0),
(67, '/Data/women/dress/royal/product3/another.webp', 0),
(67, '/Data/women/dress/royal/product3/another2.webp', 0),

(68, '/Data/women/dress/royal/product4/front.jpeg', 1),
(68, '/Data/women/dress/royal/product4/back.jpeg', 0),
(68, '/Data/women/dress/royal/product4/another.jpeg', 0),
(68, '/Data/women/dress/royal/product4/anthore2.jpeg', 0);

-- Sheath Dresses
INSERT INTO product_images (product_id, image_url, is_primary)
VALUES
(69, '/Data/women/dress/sheath/product2/front.jpg', 1),
(69, '/Data/women/dress/sheath/product2/back.jpg', 0),
(69, '/Data/women/dress/sheath/product2/another.jpg', 0),

(70, '/Data/women/dress/sheath/product3/front.jpg', 1),
(70, '/Data/women/dress/sheath/product3/back.jpg', 0),
(70, '/Data/women/dress/sheath/product3/another.jpg', 0),

(71, '/Data/women/dress/sheath/product4/front.jpg', 1),
(71, '/Data/women/dress/sheath/product4/back.jpg', 0),
(71, '/Data/women/dress/sheath/product4/another.jpg', 0),
(71, '/Data/women/dress/sheath/product4/another2.jpg', 0);
