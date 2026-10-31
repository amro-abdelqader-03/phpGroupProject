-- =====================================================
-- Category card photos (for public/accessories.php)
-- Run this ONCE in phpMyAdmin on the wedding_store database (SQL tab).
--
-- Adds an image_url column to subcategories, so the photos of the
-- shoe category cards (Derby, Loafers, ..., Flats) come from the database.
-- =====================================================

START TRANSACTION;

ALTER TABLE subcategories ADD COLUMN image_url varchar(500) DEFAULT NULL AFTER description;

-- Groom shoes
UPDATE subcategories SET image_url = 'assets/images/accessories/suits/shoes/derby-category.jpg'  WHERE id = 15;
UPDATE subcategories SET image_url = 'assets/images/accessories/suits/shoes/loafers.jpg'         WHERE id = 16;
UPDATE subcategories SET image_url = 'assets/images/accessories/suits/shoes/monk2.jpg'           WHERE id = 17;
UPDATE subcategories SET image_url = 'assets/images/accessories/suits/shoes/oxford-category.jpg' WHERE id = 18;

-- Bridal shoes
UPDATE subcategories SET image_url = 'assets/images/accessories/dresses/shoes/block-heel-category.jpg'    WHERE id = 19;
UPDATE subcategories SET image_url = 'assets/images/accessories/dresses/shoes/highHeels.jpg'              WHERE id = 20;
UPDATE subcategories SET image_url = 'assets/images/accessories/dresses/shoes/bridal-sandal-category.jpg' WHERE id = 24;
UPDATE subcategories SET image_url = 'assets/images/accessories/dresses/shoes/flat-category.jpg'          WHERE id = 25;

COMMIT;
