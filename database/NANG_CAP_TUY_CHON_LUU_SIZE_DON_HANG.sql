-- XAM SNEAKER V2: Optional safe schema migration for order item size.
-- Back up the existing database first and execute this file only ONCE.
-- Do not import database/xamsneaker_mvp.sql again; it deletes existing tables!
ALTER TABLE order_items ADD COLUMN shoe_size VARCHAR(5) NULL AFTER product_id;
