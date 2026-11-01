CREATE TABLE IF NOT EXISTS product_reviews (
  id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  rating tinyint NOT NULL,
  review_text varchar(1200) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY uq_product_review_user (product_id, user_id),
  KEY idx_product_reviews_created (product_id, created_at),
  CONSTRAINT chk_product_review_rating CHECK (rating BETWEEN 1 AND 5),
  CONSTRAINT fk_product_reviews_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
  CONSTRAINT fk_product_reviews_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;