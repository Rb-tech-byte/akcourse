-- AK23 Academy database for MySQL 8.0.21+
-- Uses BINARY(16) UUIDs for compact indexes. API views expose standard UUID strings.
-- TSH is stored in whole shillings using BIGINT (price_minor naming is kept for API consistency).

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS ak23_academy
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE ak23_academy;

CREATE TABLE IF NOT EXISTS profiles (
  id BINARY(16) PRIMARY KEY,
  email VARCHAR(320) NULL,
  display_name VARCHAR(160) NULL,
  phone_e164 VARCHAR(20) NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY profiles_email_uq (email),
  KEY profiles_phone_idx (phone_e164)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS series (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  slug VARCHAR(120) NOT NULL,
  title VARCHAR(200) NOT NULL,
  short_description VARCHAR(500) NOT NULL,
  description TEXT NOT NULL,
  audience_level VARCHAR(40) NOT NULL DEFAULT 'beginner',
  sequence_no SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY series_slug_uq (slug),
  KEY series_status_sequence_idx (status, sequence_no),
  CONSTRAINT series_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS courses (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  series_id BINARY(16) NOT NULL,
  slug VARCHAR(160) NOT NULL,
  title VARCHAR(200) NOT NULL,
  short_name VARCHAR(80) NOT NULL,
  stage_label VARCHAR(120) NOT NULL,
  learning_outcome VARCHAR(600) NOT NULL,
  description TEXT NULL,
  sequence_no SMALLINT UNSIGNED NOT NULL,
  lesson_count SMALLINT UNSIGNED NULL,
  video_duration_seconds INT UNSIGNED NULL,
  accent_color CHAR(7) NULL,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  requirements JSON NOT NULL,
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY courses_slug_uq (slug),
  UNIQUE KEY courses_series_sequence_uq (series_id, sequence_no),
  KEY courses_status_idx (status),
  CONSTRAINT courses_series_fk FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE RESTRICT,
  CONSTRAINT courses_requirements_json CHECK (JSON_VALID(requirements)),
  CONSTRAINT courses_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS software_packages (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  slug VARCHAR(160) NOT NULL,
  vendor_name VARCHAR(160) NULL,
  product_name VARCHAR(200) NOT NULL,
  edition VARCHAR(240) NULL,
  license_model VARCHAR(80) NULL,
  license_terms_url VARCHAR(2048) NULL,
  installer_url VARCHAR(2048) NULL,
  compatibility JSON NOT NULL,
  activation_instructions TEXT NULL,
  terms_confirmed BOOLEAN NOT NULL DEFAULT FALSE,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY software_slug_uq (slug),
  KEY software_status_idx (status),
  CONSTRAINT software_compatibility_json CHECK (JSON_VALID(compatibility)),
  CONSTRAINT software_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS course_software (
  course_id BINARY(16) NOT NULL,
  software_package_id BINARY(16) NOT NULL,
  is_required BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  PRIMARY KEY (course_id, software_package_id),
  KEY course_software_software_idx (software_package_id),
  CONSTRAINT course_software_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT course_software_package_fk FOREIGN KEY (software_package_id) REFERENCES software_packages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  sku VARCHAR(100) NOT NULL,
  slug VARCHAR(180) NOT NULL,
  product_type ENUM('course','bundle') NOT NULL,
  series_id BINARY(16) NOT NULL,
  course_id BINARY(16) NULL,
  name VARCHAR(240) NOT NULL,
  description TEXT NOT NULL,
  price_minor BIGINT UNSIGNED NULL,
  currency CHAR(3) NOT NULL DEFAULT 'TZS',
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  is_featured BOOLEAN NOT NULL DEFAULT FALSE,
  terms_version VARCHAR(80) NULL,
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY products_sku_uq (sku),
  UNIQUE KEY products_slug_uq (slug),
  KEY products_series_status_idx (series_id, status),
  KEY products_course_idx (course_id),
  CONSTRAINT products_series_fk FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE RESTRICT,
  CONSTRAINT products_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT,
  CONSTRAINT products_metadata_json CHECK (JSON_VALID(metadata)),
  CONSTRAINT products_target_check CHECK (
    (product_type = 'course' AND course_id IS NOT NULL)
    OR (product_type = 'bundle' AND course_id IS NULL)
  ),
  CONSTRAINT active_products_price_check CHECK (status <> 'active' OR price_minor IS NOT NULL)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_courses (
  product_id BINARY(16) NOT NULL,
  course_id BINARY(16) NOT NULL,
  PRIMARY KEY (product_id, course_id),
  KEY product_courses_course_idx (course_id),
  CONSTRAINT product_courses_product_fk FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT product_courses_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_software (
  product_id BINARY(16) NOT NULL,
  software_package_id BINARY(16) NOT NULL,
  PRIMARY KEY (product_id, software_package_id),
  KEY product_software_software_idx (software_package_id),
  CONSTRAINT product_software_product_fk FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT product_software_package_fk FOREIGN KEY (software_package_id) REFERENCES software_packages(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lessons (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  course_id BINARY(16) NOT NULL,
  title VARCHAR(240) NOT NULL,
  description TEXT NULL,
  sequence_no SMALLINT UNSIGNED NOT NULL,
  duration_seconds INT UNSIGNED NULL,
  preview_url VARCHAR(2048) NULL,
  is_preview BOOLEAN NOT NULL DEFAULT FALSE,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY lessons_course_sequence_uq (course_id, sequence_no),
  KEY lessons_preview_idx (is_preview, status),
  CONSTRAINT lessons_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS download_assets (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  course_id BINARY(16) NULL,
  lesson_id BINARY(16) NULL,
  asset_type ENUM('video','audio','project','practice_file','document','archive') NOT NULL,
  title VARCHAR(240) NOT NULL,
  storage_bucket VARCHAR(160) NOT NULL,
  storage_path VARCHAR(1024) NOT NULL,
  mime_type VARCHAR(160) NULL,
  file_size_bytes BIGINT UNSIGNED NULL,
  checksum_sha256 CHAR(64) NULL,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY download_assets_path_uq (storage_bucket, storage_path(255)),
  KEY download_assets_course_idx (course_id, status),
  KEY download_assets_lesson_idx (lesson_id),
  CONSTRAINT download_assets_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  CONSTRAINT download_assets_lesson_fk FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
  CONSTRAINT download_assets_target_check CHECK (course_id IS NOT NULL OR lesson_id IS NOT NULL),
  CONSTRAINT download_assets_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS site_content (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  series_id BINARY(16) NULL,
  content_key VARCHAR(120) NOT NULL,
  content JSON NOT NULL,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY site_content_series_key_uq (series_id, content_key),
  CONSTRAINT site_content_series_fk FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE,
  CONSTRAINT site_content_json CHECK (JSON_VALID(content))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS faqs (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  series_id BINARY(16) NOT NULL,
  question VARCHAR(500) NOT NULL,
  answer TEXT NOT NULL,
  sequence_no SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('draft','active','archived') NOT NULL DEFAULT 'draft',
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY faqs_series_sequence_uq (series_id, sequence_no),
  CONSTRAINT faqs_series_fk FOREIGN KEY (series_id) REFERENCES series(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  order_number VARCHAR(64) NOT NULL,
  user_id BINARY(16) NULL,
  customer_email VARCHAR(320) NOT NULL,
  customer_phone_e164 VARCHAR(20) NULL,
  status ENUM('pending','processing','paid','cancelled','refunded','failed') NOT NULL DEFAULT 'pending',
  payment_status ENUM('initialized','pending','successful','failed','cancelled','expired','refunded') NOT NULL DEFAULT 'initialized',
  currency CHAR(3) NOT NULL DEFAULT 'TZS',
  subtotal_minor BIGINT UNSIGNED NOT NULL,
  tax_minor BIGINT UNSIGNED NOT NULL DEFAULT 0,
  total_minor BIGINT UNSIGNED NOT NULL,
  idempotency_key VARCHAR(160) NOT NULL,
  accepted_terms_version VARCHAR(80) NULL,
  paid_at TIMESTAMP(6) NULL,
  cancelled_at TIMESTAMP(6) NULL,
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY orders_number_uq (order_number),
  UNIQUE KEY orders_idempotency_uq (idempotency_key),
  KEY orders_user_created_idx (user_id, created_at DESC),
  KEY orders_email_created_idx (customer_email, created_at DESC),
  KEY orders_payment_status_idx (payment_status, created_at DESC),
  CONSTRAINT orders_total_check CHECK (total_minor = subtotal_minor + tax_minor),
  CONSTRAINT orders_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  order_id BINARY(16) NOT NULL,
  product_id BINARY(16) NULL,
  sku VARCHAR(100) NOT NULL,
  product_name VARCHAR(240) NOT NULL,
  product_type ENUM('course','bundle') NOT NULL,
  unit_price_minor BIGINT UNSIGNED NOT NULL,
  quantity SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  total_minor BIGINT UNSIGNED NOT NULL,
  product_snapshot JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  UNIQUE KEY order_items_order_sku_uq (order_id, sku),
  KEY order_items_product_idx (product_id),
  CONSTRAINT order_items_order_fk FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT order_items_product_fk FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
  CONSTRAINT order_items_quantity_check CHECK (quantity = 1),
  CONSTRAINT order_items_total_check CHECK (total_minor = unit_price_minor * quantity),
  CONSTRAINT order_items_snapshot_json CHECK (JSON_VALID(product_snapshot))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payment_intents (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  order_id BINARY(16) NOT NULL,
  provider VARCHAR(40) NOT NULL DEFAULT 'selcom',
  provider_order_id VARCHAR(180) NULL,
  provider_transaction_id VARCHAR(180) NULL,
  status ENUM('initialized','pending','successful','failed','cancelled','expired','refunded') NOT NULL DEFAULT 'initialized',
  amount_minor BIGINT UNSIGNED NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'TZS',
  channel VARCHAR(80) NULL,
  customer_phone_e164 VARCHAR(20) NULL,
  request_payload JSON NOT NULL,
  response_payload JSON NOT NULL,
  failure_code VARCHAR(120) NULL,
  failure_message VARCHAR(1000) NULL,
  requested_at TIMESTAMP(6) NULL,
  completed_at TIMESTAMP(6) NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY payment_provider_order_uq (provider, provider_order_id),
  UNIQUE KEY payment_provider_transaction_uq (provider, provider_transaction_id),
  KEY payment_intents_order_idx (order_id),
  KEY payment_intents_status_idx (status, created_at),
  CONSTRAINT payment_intents_order_fk FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT payment_request_json CHECK (JSON_VALID(request_payload)),
  CONSTRAINT payment_response_json CHECK (JSON_VALID(response_payload))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payment_events (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  payment_intent_id BINARY(16) NULL,
  provider VARCHAR(40) NOT NULL DEFAULT 'selcom',
  provider_event_id VARCHAR(200) NOT NULL,
  event_type VARCHAR(120) NULL,
  signature_value VARCHAR(1000) NULL,
  signature_verified BOOLEAN NOT NULL DEFAULT FALSE,
  payload JSON NOT NULL,
  processing_status ENUM('received','processed','ignored','failed') NOT NULL DEFAULT 'received',
  processing_error TEXT NULL,
  received_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  processed_at TIMESTAMP(6) NULL,
  UNIQUE KEY payment_events_provider_event_uq (provider, provider_event_id),
  KEY payment_events_unprocessed_idx (processing_status, received_at),
  CONSTRAINT payment_events_intent_fk FOREIGN KEY (payment_intent_id) REFERENCES payment_intents(id) ON DELETE SET NULL,
  CONSTRAINT payment_events_payload_json CHECK (JSON_VALID(payload))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS entitlements (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  order_id BINARY(16) NOT NULL,
  order_item_id BINARY(16) NOT NULL,
  user_id BINARY(16) NULL,
  customer_email VARCHAR(320) NOT NULL,
  entitlement_type ENUM('course','software') NOT NULL,
  course_id BINARY(16) NULL,
  software_package_id BINARY(16) NULL,
  status ENUM('pending','ready','delivered','activated','failed','revoked') NOT NULL DEFAULT 'ready',
  granted_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  revoked_at TIMESTAMP(6) NULL,
  metadata JSON NOT NULL,
  course_dedupe BINARY(16) GENERATED ALWAYS AS (
    IF(entitlement_type = 'course', course_id, NULL)
  ) STORED,
  software_dedupe BINARY(16) GENERATED ALWAYS AS (
    IF(entitlement_type = 'software', software_package_id, NULL)
  ) STORED,
  UNIQUE KEY entitlements_course_once (order_item_id, course_dedupe),
  UNIQUE KEY entitlements_software_once (order_item_id, software_dedupe),
  KEY entitlements_user_status_idx (user_id, status),
  KEY entitlements_email_status_idx (customer_email, status),
  CONSTRAINT entitlements_order_fk FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT entitlements_item_fk FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
  CONSTRAINT entitlements_course_fk FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT,
  CONSTRAINT entitlements_software_fk FOREIGN KEY (software_package_id) REFERENCES software_packages(id) ON DELETE RESTRICT,
  CONSTRAINT entitlements_target_check CHECK (
    (entitlement_type = 'course' AND course_id IS NOT NULL AND software_package_id IS NULL)
    OR (entitlement_type = 'software' AND software_package_id IS NOT NULL AND course_id IS NULL)
  ),
  CONSTRAINT entitlements_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS license_deliveries (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  entitlement_id BINARY(16) NOT NULL,
  status ENUM('pending','ready','delivered','activated','failed','revoked') NOT NULL DEFAULT 'pending',
  provider_reference VARCHAR(200) NULL,
  license_key_ciphertext TEXT NULL,
  installer_url VARCHAR(2048) NULL,
  activation_instructions TEXT NULL,
  delivered_at TIMESTAMP(6) NULL,
  activated_at TIMESTAMP(6) NULL,
  last_error TEXT NULL,
  metadata JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  UNIQUE KEY license_deliveries_entitlement_uq (entitlement_id),
  CONSTRAINT license_deliveries_entitlement_fk FOREIGN KEY (entitlement_id) REFERENCES entitlements(id) ON DELETE CASCADE,
  CONSTRAINT license_deliveries_metadata_json CHECK (JSON_VALID(metadata))
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS download_grants (
  id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID(), 1)),
  entitlement_id BINARY(16) NOT NULL,
  asset_id BINARY(16) NOT NULL,
  expires_at TIMESTAMP(6) NULL,
  download_count INT UNSIGNED NOT NULL DEFAULT 0,
  last_downloaded_at TIMESTAMP(6) NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  UNIQUE KEY download_grants_entitlement_asset_uq (entitlement_id, asset_id),
  KEY download_grants_asset_idx (asset_id),
  CONSTRAINT download_grants_entitlement_fk FOREIGN KEY (entitlement_id) REFERENCES entitlements(id) ON DELETE CASCADE,
  CONSTRAINT download_grants_asset_fk FOREIGN KEY (asset_id) REFERENCES download_assets(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_log (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  actor_user_id BINARY(16) NULL,
  action VARCHAR(160) NOT NULL,
  entity_type VARCHAR(100) NOT NULL,
  entity_id VARCHAR(200) NULL,
  payload JSON NOT NULL,
  created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  KEY audit_entity_idx (entity_type, entity_id),
  KEY audit_created_idx (created_at),
  CONSTRAINT audit_payload_json CHECK (JSON_VALID(payload))
) ENGINE=InnoDB;

-- API-safe catalog view. Sensitive payment and license material is intentionally excluded.
CREATE OR REPLACE VIEW v_catalog_products AS
SELECT
  BIN_TO_UUID(p.id, 1) AS id,
  p.sku,
  p.slug,
  p.product_type,
  p.name,
  p.description,
  p.price_minor,
  p.currency,
  p.status,
  p.is_featured,
  s.slug AS series_slug,
  s.title AS series_title,
  s.audience_level,
  c.slug AS course_slug,
  c.stage_label,
  c.learning_outcome,
  c.sequence_no,
  COALESCE((
    SELECT JSON_ARRAYAGG(JSON_OBJECT(
      'id', BIN_TO_UUID(sp.id, 1),
      'slug', sp.slug,
      'vendor', sp.vendor_name,
      'name', sp.product_name,
      'edition', sp.edition,
      'license_model', sp.license_model,
      'terms_confirmed', sp.terms_confirmed,
      'compatibility', sp.compatibility
    ))
    FROM product_software ps
    JOIN software_packages sp ON sp.id = ps.software_package_id
    WHERE ps.product_id = p.id
  ), JSON_ARRAY()) AS included_software,
  COALESCE((
    SELECT JSON_ARRAYAGG(JSON_OBJECT(
      'id', BIN_TO_UUID(ic.id, 1),
      'slug', ic.slug,
      'title', ic.title,
      'stage', ic.stage_label,
      'sequence', ic.sequence_no
    ))
    FROM product_courses pc
    JOIN courses ic ON ic.id = pc.course_id
    WHERE pc.product_id = p.id
  ), JSON_ARRAY()) AS included_courses
FROM products p
JOIN series s ON s.id = p.series_id
LEFT JOIN courses c ON c.id = p.course_id
WHERE p.status IN ('draft','active')
  AND s.status IN ('draft','active');

CREATE OR REPLACE VIEW v_public_series AS
SELECT
  BIN_TO_UUID(id, 1) AS id,
  slug,
  title,
  short_description,
  description,
  audience_level,
  sequence_no,
  status,
  metadata
FROM series
WHERE status IN ('draft','active');

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_quote_checkout$$
CREATE PROCEDURE sp_quote_checkout(IN p_product_ids JSON)
SQL SECURITY INVOKER
READS SQL DATA
BEGIN
  DECLARE v_requested INT DEFAULT 0;
  DECLARE v_found INT DEFAULT 0;
  DECLARE v_currency_count INT DEFAULT 0;
  DECLARE v_subtotal BIGINT UNSIGNED DEFAULT 0;
  DECLARE v_overlap_count INT DEFAULT 0;

  IF p_product_ids IS NULL OR JSON_TYPE(p_product_ids) <> 'ARRAY' OR JSON_LENGTH(p_product_ids) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'At least one product is required';
  END IF;

  DROP TEMPORARY TABLE IF EXISTS tmp_selected_products;
  CREATE TEMPORARY TABLE tmp_selected_products (
    id BINARY(16) PRIMARY KEY
  ) ENGINE=MEMORY;

  INSERT IGNORE INTO tmp_selected_products (id)
  SELECT UUID_TO_BIN(j.product_id, 1)
  FROM JSON_TABLE(
    p_product_ids,
    '$[*]' COLUMNS(product_id VARCHAR(36) PATH '$')
  ) AS j;

  SET v_requested = JSON_LENGTH(p_product_ids);
  IF (SELECT COUNT(*) FROM tmp_selected_products) <> v_requested THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Duplicate or invalid product identifiers are not allowed';
  END IF;

  SELECT COUNT(*), COUNT(DISTINCT p.currency), COALESCE(SUM(p.price_minor), 0)
  INTO v_found, v_currency_count, v_subtotal
  FROM products p
  JOIN tmp_selected_products t ON t.id = p.id
  WHERE p.status = 'active' AND p.price_minor IS NOT NULL;

  IF v_found <> v_requested THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'One or more products are unavailable or do not have confirmed pricing';
  END IF;
  IF v_currency_count <> 1 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'All products must use the same currency';
  END IF;

  SELECT COUNT(*)
  INTO v_overlap_count
  FROM products bundle
  JOIN tmp_selected_products tb ON tb.id = bundle.id
  JOIN products single_product
    ON single_product.product_type = 'course'
   AND single_product.series_id = bundle.series_id
  JOIN tmp_selected_products ts ON ts.id = single_product.id
  WHERE bundle.product_type = 'bundle';

  IF v_overlap_count > 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Bundle overlap: remove the complete series or its individual course';
  END IF;

  SELECT JSON_OBJECT(
    'valid', TRUE,
    'currency', MIN(p.currency),
    'subtotal_minor', v_subtotal,
    'tax_minor', 0,
    'total_minor', v_subtotal,
    'items', JSON_ARRAYAGG(JSON_OBJECT(
      'id', BIN_TO_UUID(p.id, 1),
      'sku', p.sku,
      'name', p.name,
      'product_type', p.product_type,
      'price_minor', p.price_minor
    ))
  ) AS quote
  FROM products p
  JOIN tmp_selected_products t ON t.id = p.id;
END$$

DROP PROCEDURE IF EXISTS sp_create_checkout_order$$
CREATE PROCEDURE sp_create_checkout_order(
  IN p_user_id CHAR(36),
  IN p_customer_email VARCHAR(320),
  IN p_customer_phone_e164 VARCHAR(20),
  IN p_product_ids JSON,
  IN p_idempotency_key VARCHAR(160),
  IN p_accepted_terms_version VARCHAR(80)
)
SQL SECURITY DEFINER
MODIFIES SQL DATA
proc: BEGIN
  DECLARE v_order_id BINARY(16);
  DECLARE v_existing_id BINARY(16);
  DECLARE v_order_number VARCHAR(64);
  DECLARE v_requested INT DEFAULT 0;
  DECLARE v_found INT DEFAULT 0;
  DECLARE v_overlap_count INT DEFAULT 0;
  DECLARE v_currency CHAR(3);
  DECLARE v_subtotal BIGINT UNSIGNED DEFAULT 0;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;

  IF p_customer_email IS NULL OR p_customer_email NOT LIKE '%_@_%._%' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A valid customer email is required';
  END IF;
  IF p_idempotency_key IS NULL OR CHAR_LENGTH(p_idempotency_key) < 16 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A strong idempotency key is required';
  END IF;
  IF p_product_ids IS NULL OR JSON_TYPE(p_product_ids) <> 'ARRAY' OR JSON_LENGTH(p_product_ids) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'At least one product is required';
  END IF;

  SELECT id INTO v_existing_id
  FROM orders
  WHERE idempotency_key = p_idempotency_key
  LIMIT 1;

  IF v_existing_id IS NOT NULL THEN
    SELECT JSON_OBJECT(
      'order_id', BIN_TO_UUID(id, 1),
      'order_number', order_number,
      'payment_status', payment_status,
      'idempotent_replay', TRUE
    ) AS result
    FROM orders WHERE id = v_existing_id;
    LEAVE proc;
  END IF;

  DROP TEMPORARY TABLE IF EXISTS tmp_checkout_products;
  CREATE TEMPORARY TABLE tmp_checkout_products (
    id BINARY(16) PRIMARY KEY
  ) ENGINE=MEMORY;

  INSERT IGNORE INTO tmp_checkout_products (id)
  SELECT UUID_TO_BIN(j.product_id, 1)
  FROM JSON_TABLE(
    p_product_ids,
    '$[*]' COLUMNS(product_id VARCHAR(36) PATH '$')
  ) AS j;

  SET v_requested = JSON_LENGTH(p_product_ids);
  IF (SELECT COUNT(*) FROM tmp_checkout_products) <> v_requested THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Duplicate or invalid product identifiers are not allowed';
  END IF;

  SELECT COUNT(*), MIN(p.currency), COALESCE(SUM(p.price_minor), 0)
  INTO v_found, v_currency, v_subtotal
  FROM products p
  JOIN tmp_checkout_products t ON t.id = p.id
  WHERE p.status = 'active' AND p.price_minor IS NOT NULL;

  IF v_found <> v_requested THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'One or more products are unavailable or do not have confirmed pricing';
  END IF;
  IF (SELECT COUNT(DISTINCT p.currency) FROM products p JOIN tmp_checkout_products t ON t.id=p.id) <> 1 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'All products must use the same currency';
  END IF;

  SELECT COUNT(*) INTO v_overlap_count
  FROM products bundle
  JOIN tmp_checkout_products tb ON tb.id=bundle.id
  JOIN products single_product
    ON single_product.product_type='course'
   AND single_product.series_id=bundle.series_id
  JOIN tmp_checkout_products ts ON ts.id=single_product.id
  WHERE bundle.product_type='bundle';

  IF v_overlap_count > 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Bundle overlap: remove the complete series or its individual course';
  END IF;

  SET v_order_id = UUID_TO_BIN(UUID(), 1);
  SET v_order_number = CONCAT('AK23-', DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d'), '-', UPPER(SUBSTRING(REPLACE(UUID(), '-', ''), 1, 12)));

  START TRANSACTION;

  INSERT INTO orders (
    id, order_number, user_id, customer_email, customer_phone_e164,
    currency, subtotal_minor, tax_minor, total_minor,
    idempotency_key, accepted_terms_version, metadata
  ) VALUES (
    v_order_id,
    v_order_number,
    IF(p_user_id IS NULL OR p_user_id='', NULL, UUID_TO_BIN(p_user_id, 1)),
    LOWER(TRIM(p_customer_email)),
    NULLIF(TRIM(p_customer_phone_e164), ''),
    v_currency,
    v_subtotal,
    0,
    v_subtotal,
    p_idempotency_key,
    p_accepted_terms_version,
    JSON_OBJECT()
  );

  INSERT INTO order_items (
    order_id, product_id, sku, product_name, product_type,
    unit_price_minor, quantity, total_minor, product_snapshot
  )
  SELECT
    v_order_id,
    p.id,
    p.sku,
    p.name,
    p.product_type,
    p.price_minor,
    1,
    p.price_minor,
    JSON_OBJECT(
      'id', BIN_TO_UUID(p.id, 1),
      'sku', p.sku,
      'slug', p.slug,
      'name', p.name,
      'product_type', p.product_type,
      'price_minor', p.price_minor,
      'currency', p.currency,
      'terms_version', p.terms_version
    )
  FROM products p
  JOIN tmp_checkout_products t ON t.id=p.id;

  INSERT INTO payment_intents (
    order_id, provider, status, amount_minor, currency,
    customer_phone_e164, request_payload, response_payload
  ) VALUES (
    v_order_id, 'selcom', 'initialized', v_subtotal, v_currency,
    NULLIF(TRIM(p_customer_phone_e164), ''), JSON_OBJECT(), JSON_OBJECT()
  );

  INSERT INTO audit_log (actor_user_id, action, entity_type, entity_id, payload)
  VALUES (
    IF(p_user_id IS NULL OR p_user_id='', NULL, UUID_TO_BIN(p_user_id, 1)),
    'checkout.created',
    'order',
    BIN_TO_UUID(v_order_id, 1),
    JSON_OBJECT('order_number', v_order_number)
  );

  COMMIT;

  SELECT JSON_OBJECT(
    'order_id', BIN_TO_UUID(v_order_id, 1),
    'order_number', v_order_number,
    'payment_status', 'initialized',
    'currency', v_currency,
    'total_minor', v_subtotal,
    'idempotent_replay', FALSE
  ) AS result;
END$$

DROP PROCEDURE IF EXISTS sp_fulfill_paid_order$$
CREATE PROCEDURE sp_fulfill_paid_order(
  IN p_order_id CHAR(36),
  IN p_provider_transaction_id VARCHAR(180),
  IN p_payment_payload JSON
)
SQL SECURITY DEFINER
MODIFIES SQL DATA
proc: BEGIN
  DECLARE v_order_id BINARY(16);
  DECLARE v_payment_status VARCHAR(20);
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;

  SET v_order_id = UUID_TO_BIN(p_order_id, 1);

  START TRANSACTION;

  SELECT payment_status INTO v_payment_status
  FROM orders
  WHERE id=v_order_id
  FOR UPDATE;

  IF v_payment_status IS NULL THEN
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Order not found';
  END IF;

  IF v_payment_status = 'successful' THEN
    COMMIT;
    SELECT JSON_OBJECT('order_id', p_order_id, 'status', 'already_fulfilled') AS result;
    LEAVE proc;
  END IF;

  UPDATE payment_intents
  SET
    status='successful',
    provider_transaction_id=COALESCE(provider_transaction_id, p_provider_transaction_id),
    response_payload=JSON_MERGE_PATCH(response_payload, COALESCE(p_payment_payload, JSON_OBJECT())),
    completed_at=COALESCE(completed_at, CURRENT_TIMESTAMP(6))
  WHERE order_id=v_order_id AND provider='selcom';

  UPDATE orders
  SET status='paid', payment_status='successful', paid_at=COALESCE(paid_at, CURRENT_TIMESTAMP(6))
  WHERE id=v_order_id;

  INSERT IGNORE INTO entitlements (
    order_id, order_item_id, user_id, customer_email,
    entitlement_type, course_id, status, metadata
  )
  SELECT
    o.id, oi.id, o.user_id, o.customer_email,
    'course', pc.course_id, 'ready', JSON_OBJECT()
  FROM orders o
  JOIN order_items oi ON oi.order_id=o.id
  JOIN product_courses pc ON pc.product_id=oi.product_id
  WHERE o.id=v_order_id;

  INSERT IGNORE INTO entitlements (
    order_id, order_item_id, user_id, customer_email,
    entitlement_type, software_package_id, status, metadata
  )
  SELECT
    o.id, oi.id, o.user_id, o.customer_email,
    'software', ps.software_package_id, 'pending', JSON_OBJECT()
  FROM orders o
  JOIN order_items oi ON oi.order_id=o.id
  JOIN product_software ps ON ps.product_id=oi.product_id
  WHERE o.id=v_order_id;

  INSERT IGNORE INTO license_deliveries (entitlement_id, status, metadata)
  SELECT id, 'pending', JSON_OBJECT()
  FROM entitlements
  WHERE order_id=v_order_id AND entitlement_type='software';

  INSERT IGNORE INTO download_grants (entitlement_id, asset_id)
  SELECT e.id, da.id
  FROM entitlements e
  JOIN download_assets da ON da.course_id=e.course_id AND da.status='active'
  WHERE e.order_id=v_order_id AND e.entitlement_type='course';

  INSERT INTO audit_log (action, entity_type, entity_id, payload)
  VALUES (
    'order.fulfilled',
    'order',
    p_order_id,
    JSON_OBJECT('provider_transaction_id', p_provider_transaction_id)
  );

  COMMIT;

  SELECT JSON_OBJECT('order_id', p_order_id, 'status', 'fulfilled') AS result;
END$$

DELIMITER ;

-- Static catalog seed ---------------------------------------------------------
INSERT INTO series (slug,title,short_description,description,audience_level,sequence_no,status,metadata)
VALUES
  ('vocal-mixing','Vocal Mixing Series','Learn vocal processing one plugin at a time.','Five practical beginner courses with matching plugin licenses.','beginner',1,'draft',JSON_OBJECT()),
  ('audio-editing-software','Audio Editing Software Series','Get started with the audio software you want to use.','Six beginner courses covering popular audio workstations.','beginner',2,'draft',JSON_OBJECT()),
  ('beat-making','Beat Making Series','Build your production skills from the first loop to a finished beat.','Six practical beginner courses with matching software licenses.','beginner',3,'draft',JSON_OBJECT())
AS new
ON DUPLICATE KEY UPDATE
  title=new.title,
  short_description=new.short_description,
  description=new.description,
  audience_level=new.audience_level,
  sequence_no=new.sequence_no;

INSERT INTO courses (
  series_id,slug,title,short_name,stage_label,learning_outcome,
  sequence_no,accent_color,status,requirements,metadata
)
VALUES
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'tuned-plugins','Tuned Plugins','TUNED','Getting Started','Build your first simple vocal-processing workflow.',1,'#d9a742','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'cymatics-voxity','Cymatics Voxity','VOXITY','Beginner','Shape vocal tone, dynamics, and space with confidence.',2,'#de784d','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'nuro-audio-xvox-pro','Nuro Audio Xvox Pro','XVOX PRO','Developing Your Skills','Balance compression, de-essing, tone, and effects.',3,'#9b77d2','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'izotope-nectar-4','iZotope Nectar 4','NECTAR 4','Intermediate','Develop a more detailed vocal-processing workflow.',4,'#ed7c66','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'waves-ultimate-17','Waves Ultimate 17','WAVES 17','Advanced Chain Building','Assemble a vocal chain using selected individual processors.',5,'#4fa6c9','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'fender-studio-8','Fender Studio 8','FENDER 8','Getting Started','Set up your first project, record audio, and complete a simple edit.',1,'#f0a62f','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'cubase','Cubase','CUBASE','Beginner','Record, arrange, edit, and export a clean audio project.',2,'#d34d56','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'fl-studio','FL Studio','FL STUDIO','Beginner','Navigate the workflow, arrange audio, and finish your first session.',3,'#f5822b','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'ableton-live','Ableton Live','LIVE','Developing Your Skills','Work with Session and Arrangement views to build an audio project.',4,'#c9c9c9','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'logic-pro','Logic Pro','LOGIC PRO','Developing Your Skills','Record, edit, organize, and export audio in a clear Mac workflow.',5,'#66aee8','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'pro-tools','Pro Tools','PRO TOOLS','Studio Workflow','Build a structured recording and editing workflow from start to export.',6,'#8d6bd2','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'beat-making-foundations','Beat Making Foundations','BEAT START','Getting Started','Set up a simple beat session and understand the core building blocks.',1,'#f0a62f','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'drum-programming','Drum Programming','DRUM LAB','Beginner','Build clear, energetic drum patterns with groove and variation.',2,'#e7653f','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'melodies-and-chords','Melodies & Chords','MELODY','Building Musical Ideas','Create simple chord progressions and memorable melodic parts.',3,'#d95ea6','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'bass-and-808s','Bass & 808s','808 LAB','Developing Your Sound','Write bass parts that support the drums, melody, and energy of the beat.',4,'#7c76dc','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'sampling-and-sound-design','Sampling & Sound Design','SAMPLE','Creative Production','Shape samples and sounds into original parts that fit your production.',5,'#55a8c5','draft',JSON_OBJECT(),JSON_OBJECT()),
  ((SELECT id FROM series WHERE slug='beat-making'),'arrangement-and-beat-mix','Arrangement & Beat Mix','FINISH','Complete Your Beat','Turn a loop into a complete arrangement and prepare a clear final mix.',6,'#5fb883','draft',JSON_OBJECT(),JSON_OBJECT())
AS new
ON DUPLICATE KEY UPDATE
  title=new.title,
  short_name=new.short_name,
  stage_label=new.stage_label,
  learning_outcome=new.learning_outcome,
  sequence_no=new.sequence_no,
  accent_color=new.accent_color;

INSERT INTO software_packages (
  slug,vendor_name,product_name,edition,terms_confirmed,status,compatibility,metadata
)
VALUES
  ('tuned-plugins-package','Tuned Plugins','Tuned Plugins','[Confirm exact products and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('cymatics-voxity-license','Cymatics','Voxity','[Confirm license edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('nuro-xvox-pro-license','Nuro Audio','Xvox Pro','[Confirm license edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('izotope-nectar-4-license','iZotope','Nectar 4','[Confirm edition and license term]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('waves-ultimate-17-license','Waves','Ultimate 17','[Confirm edition and course processors]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('fender-studio-8-license','Fender','Studio 8','[Confirm exact product name and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('cubase-license','Steinberg','Cubase','[Confirm included edition and license term]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('fl-studio-license','Image-Line','FL Studio','[Confirm included edition and license term]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('ableton-live-license','Ableton','Live','[Confirm included edition and license term]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('logic-pro-license','Apple','Logic Pro','[Confirm license delivery and requirements]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('pro-tools-license','Avid','Pro Tools','[Confirm included edition and license term]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('beat-foundations-software',NULL,'Beat Making Foundations software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('drum-programming-software',NULL,'Drum Programming software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('melody-chords-software',NULL,'Melody & Chords software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('bass-808-software',NULL,'Bass & 808 software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('sampling-sound-design-software',NULL,'Sampling & Sound Design software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT()),
  ('arrangement-mix-software',NULL,'Arrangement & Beat Mix software','[Confirm package and edition]',FALSE,'draft',JSON_OBJECT(),JSON_OBJECT())
AS new
ON DUPLICATE KEY UPDATE
  vendor_name=new.vendor_name,
  product_name=new.product_name,
  edition=new.edition,
  terms_confirmed=new.terms_confirmed;

INSERT IGNORE INTO course_software (course_id,software_package_id)
SELECT c.id, sp.id
FROM (
  SELECT 'tuned-plugins' course_slug,'tuned-plugins-package' software_slug UNION ALL
  SELECT 'cymatics-voxity','cymatics-voxity-license' UNION ALL
  SELECT 'nuro-audio-xvox-pro','nuro-xvox-pro-license' UNION ALL
  SELECT 'izotope-nectar-4','izotope-nectar-4-license' UNION ALL
  SELECT 'waves-ultimate-17','waves-ultimate-17-license' UNION ALL
  SELECT 'fender-studio-8','fender-studio-8-license' UNION ALL
  SELECT 'cubase','cubase-license' UNION ALL
  SELECT 'fl-studio','fl-studio-license' UNION ALL
  SELECT 'ableton-live','ableton-live-license' UNION ALL
  SELECT 'logic-pro','logic-pro-license' UNION ALL
  SELECT 'pro-tools','pro-tools-license' UNION ALL
  SELECT 'beat-making-foundations','beat-foundations-software' UNION ALL
  SELECT 'drum-programming','drum-programming-software' UNION ALL
  SELECT 'melodies-and-chords','melody-chords-software' UNION ALL
  SELECT 'bass-and-808s','bass-808-software' UNION ALL
  SELECT 'sampling-and-sound-design','sampling-sound-design-software' UNION ALL
  SELECT 'arrangement-and-beat-mix','arrangement-mix-software'
) mappings
JOIN courses c ON c.slug=mappings.course_slug
JOIN software_packages sp ON sp.slug=mappings.software_slug;

INSERT INTO products (
  sku,slug,product_type,series_id,course_id,name,description,
  price_minor,currency,status,is_featured,metadata
)
SELECT
  CONCAT('AK23-COURSE-',UPPER(REPLACE(c.slug,'-','_'))),
  CONCAT(c.slug,'-course-package'),
  'course',
  c.series_id,
  c.id,
  CONCAT(c.title,' Course + Software'),
  c.learning_outcome,
  NULL,
  'TZS',
  'draft',
  FALSE,
  JSON_OBJECT()
FROM courses c
ON DUPLICATE KEY UPDATE
  name=VALUES(name),
  description=VALUES(description),
  series_id=VALUES(series_id),
  course_id=VALUES(course_id);

INSERT INTO products (
  sku,slug,product_type,series_id,course_id,name,description,
  price_minor,currency,status,is_featured,metadata
)
VALUES
  ('AK23-BUNDLE-VOCAL','complete-vocal-mixing-series','bundle',(SELECT id FROM series WHERE slug='vocal-mixing'),NULL,'Complete Vocal Mixing Series','All five courses and five matching software packages.',NULL,'TZS','draft',TRUE,JSON_OBJECT()),
  ('AK23-BUNDLE-DAW','complete-audio-software-series','bundle',(SELECT id FROM series WHERE slug='audio-editing-software'),NULL,'Complete Audio Software Series','All six courses and six matching software packages.',NULL,'TZS','draft',TRUE,JSON_OBJECT()),
  ('AK23-BUNDLE-BEATS','complete-beat-making-series','bundle',(SELECT id FROM series WHERE slug='beat-making'),NULL,'Complete Beat Making Series','All six courses and six matching software packages.',NULL,'TZS','draft',TRUE,JSON_OBJECT())
AS new
ON DUPLICATE KEY UPDATE
  name=new.name,
  description=new.description,
  series_id=new.series_id;

INSERT IGNORE INTO product_courses (product_id,course_id)
SELECT p.id,c.id
FROM products p
JOIN courses c ON
  (p.product_type='course' AND p.course_id=c.id)
  OR (p.product_type='bundle' AND p.series_id=c.series_id);

INSERT IGNORE INTO product_software (product_id,software_package_id)
SELECT DISTINCT pc.product_id,cs.software_package_id
FROM product_courses pc
JOIN course_software cs ON cs.course_id=pc.course_id;

INSERT INTO site_content (series_id,content_key,content,status)
VALUES
  ((SELECT id FROM series WHERE slug='vocal-mixing'),'sales_page',JSON_OBJECT(
    'headline','Learn to Mix Vocals. One Plugin at a Time.',
    'progression',JSON_ARRAY('Understand the tools','Control vocal levels','Shape tone','Add space and character','Build a complete vocal chain'),
    'benefits',JSON_ARRAY('Downloadable video lessons','Matching plugin licenses included','Step-by-step practical training')
  ),'draft'),
  ((SELECT id FROM series WHERE slug='audio-editing-software'),'sales_page',JSON_OBJECT(
    'headline','Start Editing Audio. Choose Your Software.',
    'progression',JSON_ARRAY('Set up the software','Record or import audio','Edit and organize','Balance your project','Export the final file'),
    'benefits',JSON_ARRAY('Choose one DAW','Software license included','Downloadable lessons')
  ),'draft'),
  ((SELECT id FROM series WHERE slug='beat-making'),'sales_page',JSON_OBJECT(
    'headline','Build Better Beats. One Skill at a Time.',
    'progression',JSON_ARRAY('Build the foundation','Program the drums','Add melody and bass','Shape your sounds','Arrange and mix'),
    'benefits',JSON_ARRAY('Six practical skills','Software license included','Downloadable lessons')
  ),'draft')
AS new
ON DUPLICATE KEY UPDATE content=new.content;

INSERT INTO faqs (series_id,question,answer,sequence_no,status)
SELECT s.id,q.question,q.answer,q.sequence_no,'draft'
FROM series s
JOIN (
  SELECT 1 sequence_no,'Which software license comes with each course?' question,'Each course includes the matching software shown on its course card. Exact edition and license terms must be confirmed before activation.' answer UNION ALL
  SELECT 2,'What is included in the complete series?','Every course in the series and its corresponding licensed software package, plus confirmed supporting resources.' UNION ALL
  SELECT 3,'How do I download and watch the lessons?','Full lessons are downloaded for offline learning after payment is confirmed.' UNION ALL
  SELECT 4,'How do I receive and activate my software?','Software delivery is shown separately with its actual status, official installer, registration, and activation instructions.' UNION ALL
  SELECT 5,'How do support and refunds work?','[Add confirmed support and refund terms before publishing.]'
) q
ON TRUE
ON DUPLICATE KEY UPDATE
  question=VALUES(question),
  answer=VALUES(answer);

-- Optional least-privilege MySQL roles. Assign these roles to application users,
-- never directly to browser clients.
CREATE ROLE IF NOT EXISTS 'ak23_catalog_reader', 'ak23_checkout_api', 'ak23_payment_worker';
GRANT SELECT ON ak23_academy.v_catalog_products TO 'ak23_catalog_reader';
GRANT SELECT ON ak23_academy.v_public_series TO 'ak23_catalog_reader';
GRANT EXECUTE ON PROCEDURE ak23_academy.sp_quote_checkout TO 'ak23_checkout_api';
GRANT EXECUTE ON PROCEDURE ak23_academy.sp_create_checkout_order TO 'ak23_checkout_api';
GRANT EXECUTE ON PROCEDURE ak23_academy.sp_fulfill_paid_order TO 'ak23_payment_worker';
