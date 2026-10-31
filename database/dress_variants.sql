USE wedding_store;

INSERT INTO product_variants
    (product_id, size, color, stock_quantity)

SELECT
    p.id,
    s.size,
    NULL,
    2
FROM products p

CROSS JOIN (
    SELECT 'S' AS size
    UNION ALL SELECT 'M'
    UNION ALL SELECT 'L'
    UNION ALL SELECT 'XL'
) s

WHERE p.id IN (
    57, 58, 59,
    60, 61, 62,
    63, 64, 65,
    66, 67, 68,
    69, 70, 71
)

AND NOT EXISTS (
    SELECT 1
    FROM product_variants pv
    WHERE pv.product_id = p.id
      AND pv.size = s.size
      AND pv.color IS NULL
);