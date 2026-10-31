-- =====================================================
-- Cuff Links section (for public/accessories.php)
-- Run this ONCE in phpMyAdmin on the wedding_store database (SQL tab).
--
-- Adds a "Cuff Links" subcategory under Men's Accessories (category 6).
-- The products are added later, one by one, with their photos.
-- =====================================================

INSERT INTO subcategories (id, name, category_id, description)
VALUES (26, 'Cuff Links', 6, 'Cuff links to finish the groom''s shirt with a polished detail.');
