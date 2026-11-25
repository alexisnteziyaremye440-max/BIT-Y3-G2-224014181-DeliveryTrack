USE delivery_tracker;
-- All seeded accounts use the password:  password   (bcrypt hash below). Change after first login.
INSERT INTO users (user_id,username,email,password_hash,role) VALUES
(1,'admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
(2,'rider_james','james@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','rider'),
(3,'rider_aline','aline@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','rider'),
(4,'alice','alice@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','customer'),
(5,'bob','bob@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','customer'),
(6,'carol','carol@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','customer');

INSERT INTO customers (customer_id,user_id,full_name,phone,address) VALUES
(1,4,'Alice Uwase','+250788000001','KG 11 Ave, Kimironko, Kigali'),
(2,5,'Bob Mugisha','+250788000002','KN 3 Rd, Nyarugenge, Kigali'),
(3,6,'Carol Ingabire','+250788000003','KK 15 Rd, Kicukiro, Kigali'),
(4,NULL,'David Nshuti (walk-in)','+250788000004','KG 7 Ave, Remera, Kigali');

INSERT INTO orders (order_id,customer_id,details,order_date,total,status) VALUES
(1,1,'Pizza x2 + soda','2026-11-20 12:10:00',18.50,'delivered'),
(2,1,'Weekly groceries bag','2026-11-22 09:30:00',42.00,'in_transit'),
(3,2,'Laptop sleeve','2026-11-22 10:05:00',25.00,'assigned'),
(4,2,'Birthday cake','2026-11-22 11:20:00',30.00,'pending'),
(5,3,'Textbooks (3)','2026-11-22 11:45:00',64.90,'pending'),
(6,4,'Phone charger','2026-11-19 15:00:00',12.00,'delivered'),
(7,3,'Flowers bouquet','2026-11-21 08:00:00',15.00,'cancelled');

INSERT INTO deliveries (order_id,rider_id,picked_at,delivered_at,status) VALUES
(1,2,'2026-11-20 12:30:00','2026-11-20 13:05:00','delivered'),
(2,2,'2026-11-22 10:00:00',NULL,'picked_up'),
(3,3,NULL,NULL,'assigned'),
(6,3,'2026-11-19 15:20:00','2026-11-19 16:00:00','delivered');
