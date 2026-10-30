-- =====================================================
-- Accessories names update (for public/accessories.php)
-- Run this ONCE in phpMyAdmin on the wedding_store database (SQL tab),
-- AFTER accessories_update.sql.
--
-- 1. Fixes "Bloch Heels" -> "Block Heels"
-- 2. Gives the 30 accessories that have a photo the names and descriptions from the page design
-- 3. Gives the shoe category cards (Derby, Loafers...) the descriptions from the page design
--
-- The 15 products without a photo keep their simple names (e.g. "Derby 2") for now.
-- =====================================================

START TRANSACTION;


-- 1. Fix spelling
UPDATE subcategories SET name = 'Block Heels', description = 'Block Heels products for weddings and special occasions.' WHERE id = 19;
UPDATE products SET product_name = REPLACE(product_name, 'Bloch Heels', 'Block Heels'),
                    description  = REPLACE(description,  'Bloch Heels', 'Block Heels')
WHERE subcategory_id = 19;


-- 2. Product names and descriptions (matched to each product by its photo)
UPDATE products SET product_name = 'The Classic Satin Glove', description = 'A short satin glove with a smooth, ceremonial finish.' WHERE id = 34;
UPDATE products SET product_name = 'The Evening Satin Glove', description = 'An evening-length glove for a formal, romantic gesture.' WHERE id = 35;
UPDATE products SET product_name = 'The Sheer Lace Glove', description = 'Sheer lace for a bride who loves vintage detail.' WHERE id = 36;
UPDATE products SET product_name = 'The Fine Gold Necklace', description = 'A delicate gold chain made for a low, elegant neckline.' WHERE id = 37;
UPDATE products SET product_name = 'The Pearl Drop Earrings', description = 'Pearl drops with a soft lustre and a timeless line.' WHERE id = 38;
UPDATE products SET product_name = 'The Gold Bangle', description = 'A polished bangle to layer with heirloom pieces.' WHERE id = 39;
UPDATE products SET product_name = 'The Crystal Tiara', description = 'A fine crystal tiara with a light, soaring profile.' WHERE id = 40;
UPDATE products SET product_name = 'The Silver Pearl Tiara', description = 'Silver filigree finished with pearls for a classical touch.' WHERE id = 41;
UPDATE products SET product_name = 'The Gold Heirloom Tiara', description = 'A warm gold headpiece with an antique, treasured feel.' WHERE id = 42;
UPDATE products SET product_name = 'The Fingertip Veil', description = 'A light fingertip veil that frames the face without hiding it.' WHERE id = 43;
UPDATE products SET product_name = 'The Chapel-Length Veil', description = 'A graceful chapel length made for a measured walk down the aisle.' WHERE id = 44;
UPDATE products SET product_name = 'The Cathedral Veil', description = 'A dramatic sweep of tulle for the grandest entrances.' WHERE id = 45;
UPDATE products SET product_name = 'The Heritage Derby', description = 'A burnished leather Derby with open lacing and a softly rounded toe.' WHERE id = 22;
UPDATE products SET product_name = 'The Cognac Loafer', description = 'A clean, laceless profile in warm cognac leather.' WHERE id = 25;
UPDATE products SET product_name = 'The Signature Monk Strap', description = 'A single-buckle monk strap with a hand-finished patina.' WHERE id = 28;
UPDATE products SET product_name = 'The Black Oxford', description = 'The formal standard: closed lacing, a sleek toe, and a mirror polish.' WHERE id = 31;
UPDATE products SET product_name = 'The Marble Block Heel', description = 'A white block heel with a stable stride and a sculptural finish.' WHERE id = 46;
UPDATE products SET product_name = 'The Ribbon Bow Heel', description = 'Romantic ribbon ties and a refined bridal heel.' WHERE id = 54;
UPDATE products SET product_name = 'The Classic Black Bow Tie', description = 'A timeless black-tie finish with a crisp, symmetrical knot.' WHERE id = 12;
UPDATE products SET product_name = 'The Midnight Bow Tie', description = 'Deep midnight tone, tied by hand for a considered finish.' WHERE id = 13;
UPDATE products SET product_name = 'The Ivory Ceremony Bow Tie', description = 'A soft ivory bow tie for daytime ceremonies and garden celebrations.' WHERE id = 14;
UPDATE products SET product_name = 'The Crimson Bow Tie', description = 'A rich red accent for grooms who prefer a little colour.' WHERE id = 15;
UPDATE products SET product_name = 'The Striped Silk Tie', description = 'Fine diagonal stripes for a quietly traditional wedding look.' WHERE id = 16;
UPDATE products SET product_name = 'The Polka Dot Silk Tie', description = 'A classic spotted pattern that adds character without noise.' WHERE id = 17;
UPDATE products SET product_name = 'The Crimson Silk Tie', description = 'A confident crimson tie with a clean, modern drape.' WHERE id = 18;
UPDATE products SET product_name = 'The Ivory Silk Square', description = 'A clean ivory fold that lets the tailoring lead.' WHERE id = 19;
UPDATE products SET product_name = 'The Folded Silk Square', description = 'A soft neutral square, folded for an understated finish.' WHERE id = 20;
UPDATE products SET product_name = 'The Floral Signature Square', description = 'A printed floral square for celebrations with a softer edge.' WHERE id = 21;
UPDATE products SET product_name = 'The Silver Bridal Sandal', description = 'Fine silver straps that catch the light with every step.' WHERE id = 49;
UPDATE products SET product_name = 'The Beaded Bridal Mule', description = 'A beaded mule with an easy slip-on shape for the reception.' WHERE id = 50;


-- 3. Category card descriptions
UPDATE subcategories SET description = 'Open lacing and a quietly rounded silhouette.' WHERE id = 15;   -- Derby
UPDATE subcategories SET description = 'Sleek, laceless ease for long celebrations.' WHERE id = 16;   -- Loafers
UPDATE subcategories SET description = 'A buckled profile with understated authority.' WHERE id = 17;   -- Monk Strap
UPDATE subcategories SET description = 'The formal benchmark, closed-laced and clean.' WHERE id = 18;   -- Oxford
UPDATE subcategories SET description = 'Grounded elegance from ceremony to last dance.' WHERE id = 19;   -- Block Heels
UPDATE subcategories SET description = 'Delicate straps with a celebratory shimmer.' WHERE id = 24;   -- Bridal Sandals
UPDATE subcategories SET description = 'Soft, graceful comfort for every step.' WHERE id = 25;   -- Flats
UPDATE subcategories SET description = 'A poised heel with a romantic finish.' WHERE id = 20;   -- High Heels


COMMIT;
