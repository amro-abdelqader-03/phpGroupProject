-- =====================================================
-- Accessories update (for public/accessories.php)
-- Run this ONCE in phpMyAdmin on the wedding_store database (SQL tab).
--
-- 1. Fixes the spelling of 3 subcategory names
-- 2. Points each accessory product to its image in public/assets/images/accessories/
--
-- 15 products have no photo yet and keep their old image path for now:
--   Groom shoes:  Derby 2-3 (23, 24), Loafers 2-3 (26, 27), Monk Strap 2-3 (29, 30), Oxford 2-3 (32, 33)
--   Bridal shoes: Bloch Heels 2-3 (47, 48), High Heels 2-3 (55, 56), Bridal Sandal 3 (51), Flats 1-2 (52, 53)
-- When their photos are added, give them a path the same way as below.
-- =====================================================

START TRANSACTION;


-- 1. Fix spelling
UPDATE subcategories SET name = 'Jewelry',           description = 'Jewelry products for weddings and special occasions.'           WHERE id = 7;
UPDATE subcategories SET name = 'Tiaras And Crowns', description = 'Tiaras And Crowns products for weddings and special occasions.' WHERE id = 8;
UPDATE subcategories SET name = 'Loafers',           description = 'Loafers products for weddings and special occasions.'           WHERE id = 16;

UPDATE products SET product_name = REPLACE(product_name, 'Jewlery', 'Jewelry')                  WHERE subcategory_id = 7;
UPDATE products SET product_name = REPLACE(product_name, 'Taras And Crown', 'Tiaras And Crowns') WHERE subcategory_id = 8;
UPDATE products SET product_name = REPLACE(product_name, 'Loarfers', 'Loafers')                 WHERE subcategory_id = 16;


-- 2. Image paths (relative to the public folder)

-- Bridal gloves
UPDATE products SET image_url = 'assets/images/accessories/dresses/gloves/classic-satin.jpg'  WHERE id = 34;
UPDATE products SET image_url = 'assets/images/accessories/dresses/gloves/evening-satin.jpg'  WHERE id = 35;
UPDATE products SET image_url = 'assets/images/accessories/dresses/gloves/sheer-lace.jpg'     WHERE id = 36;

-- Bridal jewelry
UPDATE products SET image_url = 'assets/images/accessories/dresses/jewelry/gold-necklace.jpg'  WHERE id = 37;
UPDATE products SET image_url = 'assets/images/accessories/dresses/jewelry/pearl-earrings.jpg' WHERE id = 38;
UPDATE products SET image_url = 'assets/images/accessories/dresses/jewelry/gold-bangle.jpg'    WHERE id = 39;

-- Tiaras and crowns
UPDATE products SET image_url = 'assets/images/accessories/dresses/tiaras-crowns/crystal-tiara.jpg'      WHERE id = 40;
UPDATE products SET image_url = 'assets/images/accessories/dresses/tiaras-crowns/silver-pearl-tiara.jpg' WHERE id = 41;
UPDATE products SET image_url = 'assets/images/accessories/dresses/tiaras-crowns/gold-tiara.jpg'         WHERE id = 42;

-- Bridal veils
UPDATE products SET image_url = 'assets/images/accessories/dresses/veils/fingertip-veil.jpg' WHERE id = 43;
UPDATE products SET image_url = 'assets/images/accessories/dresses/veils/chapel-veil.jpg'    WHERE id = 44;
UPDATE products SET image_url = 'assets/images/accessories/dresses/veils/cathedral-veil.jpg' WHERE id = 45;

-- Groom shoes (one of each type has an image)
UPDATE products SET image_url = 'assets/images/accessories/suits/shoes/derby-heritage.jpg'       WHERE id = 22;
UPDATE products SET image_url = 'assets/images/accessories/suits/shoes/loafer-cognac.jpg'        WHERE id = 25;
UPDATE products SET image_url = 'assets/images/accessories/suits/shoes/monk-strap-signature.jpg' WHERE id = 28;
UPDATE products SET image_url = 'assets/images/accessories/suits/shoes/oxford-black.jpg'         WHERE id = 31;

-- Bridal shoes
UPDATE products SET image_url = 'assets/images/accessories/dresses/shoes/block-heel-marble.jpg' WHERE id = 46;
UPDATE products SET image_url = 'assets/images/accessories/dresses/shoes/ribbon-bow-heel.jpg'   WHERE id = 54;
UPDATE products SET image_url = 'assets/images/accessories/dresses/shoes/silver-sandal.jpg'     WHERE id = 49;
UPDATE products SET image_url = 'assets/images/accessories/dresses/shoes/beaded-mule.jpg'       WHERE id = 50;

-- Bow ties
UPDATE products SET image_url = 'assets/images/accessories/suits/bow-ties/classic-black.jpg'  WHERE id = 12;
UPDATE products SET image_url = 'assets/images/accessories/suits/bow-ties/midnight.jpg'       WHERE id = 13;
UPDATE products SET image_url = 'assets/images/accessories/suits/bow-ties/ivory-ceremony.jpg' WHERE id = 14;
UPDATE products SET image_url = 'assets/images/accessories/suits/bow-ties/crimson.jpg'        WHERE id = 15;

-- Neck ties
UPDATE products SET image_url = 'assets/images/accessories/suits/neck-ties/striped-silk.jpg' WHERE id = 16;
UPDATE products SET image_url = 'assets/images/accessories/suits/neck-ties/polka-dot.jpg'    WHERE id = 17;
UPDATE products SET image_url = 'assets/images/accessories/suits/neck-ties/crimson.jpg'      WHERE id = 18;

-- Pocket squares
UPDATE products SET image_url = 'assets/images/accessories/suits/pocket-squares/ivory-silk.jpg'       WHERE id = 19;
UPDATE products SET image_url = 'assets/images/accessories/suits/pocket-squares/folded-silk.jpg'      WHERE id = 20;
UPDATE products SET image_url = 'assets/images/accessories/suits/pocket-squares/floral-signature.jpg' WHERE id = 21;

-- Use the same image in the product_images table (it still had the old broken paths)
UPDATE product_images pi
JOIN products p ON p.id = pi.product_id
SET pi.image_url = p.image_url
WHERE p.image_url LIKE 'assets/images/accessories/%';


COMMIT;
