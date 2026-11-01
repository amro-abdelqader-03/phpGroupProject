ALTER TABLE orders
  ADD COLUMN estimated_delivery DATE NULL AFTER order_date;