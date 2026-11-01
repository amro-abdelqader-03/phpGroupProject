USE wedding_store;

START TRANSACTION;

INSERT INTO product_variants (product_id, size, color, stock_quantity)
SELECT
    p.id,
    sizes.size,
    NULL,
    FLOOR(p.stock_quantity / 4)
        + CASE WHEN sizes.position <= MOD(p.stock_quantity, 4) THEN 1 ELSE 0 END
FROM products AS p
INNER JOIN subcategories AS s ON s.id = p.subcategory_id
CROSS JOIN (
    SELECT 'S' AS size, 1 AS position
    UNION ALL SELECT 'M', 2
    UNION ALL SELECT 'L', 3
    UNION ALL SELECT 'XL', 4
) AS sizes
WHERE s.category_id IN (1, 3)
  AND p.stock_quantity > 0
  AND NOT EXISTS (
      SELECT 1
      FROM product_variants AS existing
      WHERE existing.product_id = p.id
  );

INSERT INTO product_variants (product_id, size, color, stock_quantity)
SELECT
    p.id,
    sizes.size,
    NULL,
    FLOOR(p.stock_quantity / 6)
        + CASE WHEN sizes.position <= MOD(p.stock_quantity, 6) THEN 1 ELSE 0 END
FROM products AS p
INNER JOIN subcategories AS s ON s.id = p.subcategory_id
CROSS JOIN (
    SELECT '38' AS size, 1 AS position
    UNION ALL SELECT '39', 2
    UNION ALL SELECT '40', 3
    UNION ALL SELECT '41', 4
    UNION ALL SELECT '42', 5
    UNION ALL SELECT '43', 6
) AS sizes
WHERE s.category_id IN (4, 5, 7)
  AND p.stock_quantity > 0
  AND NOT EXISTS (
      SELECT 1
      FROM product_variants AS existing
      WHERE existing.product_id = p.id
  );

COMMIT;
