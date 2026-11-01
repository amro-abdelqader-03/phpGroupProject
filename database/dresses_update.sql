
USE wedding_store;

-- Update the five existing dress products
UPDATE products
SET product_name = 'A-Line Bridal Dress 1',
    image_url = '/Data/women/dress/a-line/product1/Front.jpg'
WHERE id = 1 AND subcategory_id = 1;

UPDATE products
SET product_name = 'Mermaid Bridal Dress 1',
    image_url = '/Data/women/dress/mermaid/product1/front.jpg'
WHERE id = 2 AND subcategory_id = 2;

UPDATE products
SET product_name = 'Princess Bridal Dress 1',
    image_url = '/Data/women/dress/princessDress/product1/princess.jpg'
WHERE id = 3 AND subcategory_id = 3;

UPDATE products
SET product_name = 'Royal Bridal Dress 1',
    image_url = '/Data/women/dress/royal/product1/front.webp'
WHERE id = 4 AND subcategory_id = 4;

UPDATE products
SET product_name = 'Sheath Bridal Dress 1',
    image_url = '/Data/women/dress/sheath/product1/front.jpg'
WHERE id = 5 AND subcategory_id = 5;

-- Add the remaining 15 dresses
INSERT INTO products
(subcategory_id, product_name, description,
 cost_price, price, image_url, stock_quantity)
VALUES

(1, 'A-Line Bridal Dress 2',
 'Elegant A-Line wedding dress.',
 180, 299,
 '/Data/women/dress/a-line/a-line2/front.jpg', 5),

(1, 'A-Line Bridal Dress 3',
 'Elegant A-Line wedding dress.',
 180, 299,
 '/Data/women/dress/a-line/a-line3/front.jpg', 5),

(1, 'A-Line Bridal Dress 4',
 'Elegant A-Line wedding dress.',
 180, 299,
 '/Data/women/dress/a-line/a-line4/front.webp', 5),

(2, 'Mermaid Bridal Dress 2',
 'Elegant Mermaid wedding dress.',
 180, 299,
 '/Data/women/dress/mermaid/product2/front.jpg', 5),

(2, 'Mermaid Bridal Dress 3',
 'Elegant Mermaid wedding dress.',
 180, 299,
 '/Data/women/dress/mermaid/product3/front.jpg', 5),

(2, 'Mermaid Bridal Dress 4',
 'Elegant Mermaid wedding dress.',
 180, 299,
 '/Data/women/dress/mermaid/product4/front.jpg', 5),

(3, 'Princess Bridal Dress 2',
 'Elegant Princess wedding dress.',
 180, 299,
 '/Data/women/dress/princessDress/product2/princessDress.jpg', 5),

(3, 'Princess Bridal Dress 3',
 'Elegant Princess wedding dress.',
 180, 299,
 '/Data/women/dress/princessDress/product3/princess.jpg', 5),

(3, 'Princess Bridal Dress 4',
 'Elegant Princess wedding dress.',
 180, 299,
 '/Data/women/dress/princessDress/product4/front.jpg', 5),

(4, 'Royal Bridal Dress 2',
 'Elegant Royal wedding dress.',
 180, 299,
 '/Data/women/dress/royal/product2/front.jpg', 5),

(4, 'Royal Bridal Dress 3',
 'Elegant Royal wedding dress.',
 180, 299,
 '/Data/women/dress/royal/product3/front.webp', 5),

(4, 'Royal Bridal Dress 4',
 'Elegant Royal wedding dress.',
 180, 299,
 '/Data/women/dress/royal/product4/front.jpeg', 5),

(5, 'Sheath Bridal Dress 2',
 'Elegant Sheath wedding dress.',
 180, 299,
 '/Data/women/dress/sheath/product2/front.jpg', 5),

(5, 'Sheath Bridal Dress 3',
 'Elegant Sheath wedding dress.',
 180, 299,
 '/Data/women/dress/sheath/product3/front.jpg', 5),

(5, 'Sheath Bridal Dress 4',
 'Elegant Sheath wedding dress.',
 180, 299,
 '/Data/women/dress/sheath/product4/front.jpg', 5);
