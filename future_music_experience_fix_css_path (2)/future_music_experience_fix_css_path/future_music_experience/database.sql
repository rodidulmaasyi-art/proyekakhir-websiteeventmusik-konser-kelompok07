CREATE DATABASE IF NOT EXISTS future_music_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE future_music_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS ticket_scans;
DROP TABLE IF EXISTS wishlist;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS ticket_types;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS artists;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS venues;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    membership ENUM('Free','Neon','Galaxy','Legend') NOT NULL DEFAULT 'Free',
    phone VARCHAR(30) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE venues (
    id_venue INT AUTO_INCREMENT PRIMARY KEY,
    venue_name VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    capacity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE events (
    id_event INT AUTO_INCREMENT PRIMARY KEY,
    id_venue INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    category VARCHAR(80) NOT NULL,
    status ENUM('upcoming','popular','featured','completed') NOT NULL DEFAULT 'upcoming',
    image_url VARCHAR(255) NOT NULL,
    video_url VARCHAR(255) NULL,
    seat_total INT NOT NULL DEFAULT 1000,
    seat_available INT NOT NULL DEFAULT 1000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_venue FOREIGN KEY (id_venue) REFERENCES venues(id_venue)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE artists (
    id_artist INT AUTO_INCREMENT PRIMARY KEY,
    artist_name VARCHAR(150) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    bio TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    instagram VARCHAR(120) NULL,
    youtube VARCHAR(120) NULL,
    spotify VARCHAR(120) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE schedules (
    id_schedule INT AUTO_INCREMENT PRIMARY KEY,
    id_event INT NOT NULL,
    id_artist INT NOT NULL,
    stage_name VARCHAR(100) NOT NULL,
    perform_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT fk_schedule_event FOREIGN KEY (id_event) REFERENCES events(id_event)
    ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_schedule_artist FOREIGN KEY (id_artist) REFERENCES artists(id_artist)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ticket_types (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    id_event INT NOT NULL,
    ticket_name ENUM('Regular','VIP','VVIP','Backstage Pass') NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    quota INT NOT NULL,
    sold INT NOT NULL DEFAULT 0,
    benefits TEXT NOT NULL,
    CONSTRAINT fk_ticket_event FOREIGN KEY (id_event) REFERENCES events(id_event)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE purchases (
    id_purchase INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    id_ticket INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    buyer_name VARCHAR(120) NOT NULL,
    buyer_email VARCHAR(150) NOT NULL,
    status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
    qr_code VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchase_user FOREIGN KEY (id_user) REFERENCES users(id_user)
    ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_purchase_event FOREIGN KEY (id_event) REFERENCES events(id_event)
    ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_purchase_ticket FOREIGN KEY (id_ticket) REFERENCES ticket_types(id_ticket)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE payments (
    id_payment INT AUTO_INCREMENT PRIMARY KEY,
    id_purchase INT NOT NULL,
    provider VARCHAR(80) NOT NULL DEFAULT 'Midtrans Demo',
    transaction_code VARCHAR(100) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    status ENUM('pending','settlement','failed') NOT NULL DEFAULT 'pending',
    paid_at DATETIME NULL,
    CONSTRAINT fk_payment_purchase FOREIGN KEY (id_purchase) REFERENCES purchases(id_purchase)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE wishlist (
    id_wishlist INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_event INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_event (id_user,id_event),
    CONSTRAINT fk_wishlist_user FOREIGN KEY (id_user) REFERENCES users(id_user)
    ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_wishlist_event FOREIGN KEY (id_event) REFERENCES events(id_event)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ticket_scans (
    id_scan INT AUTO_INCREMENT PRIMARY KEY,
    id_purchase INT NOT NULL,
    scan_status ENUM('valid','invalid','used') NOT NULL DEFAULT 'valid',
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_scan_purchase FOREIGN KEY (id_purchase) REFERENCES purchases(id_purchase)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name,email,password,role,membership,phone) VALUES
('Admin Future','admin@futuremusic.test','$2y$10$QBTphVAeHtwi2iNVHLFo3eQ6CJvEtpfaHgUiF0zQRxscg8Oo2qpte','admin','Legend','081111111111'),
('Gibran Hendarto','user@futuremusic.test','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Galaxy','082222222222'),
('Rara Neon','rara@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Neon','083333333333'),
('Bimo Wave','bimo@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Free','084444444444'),
('Sasha Pulse','sasha@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Neon','085555555555'),
('Dion Orbit','dion@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Galaxy','086666666666'),
('Mira Echo','mira@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Free','087777777777'),
('Naya Flux','naya@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Legend','088888888888'),
('Rio Synth','rio@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Free','089999999999'),
('Luna Bass','luna@test.com','$2y$10$2XQdQYiPQiTwc21bP7vqm.NNBP.aAIMZbCM80nICFOKT/s6rqpsay','user','Neon','080000000000');



INSERT INTO users (name,email,password,role,membership,phone) VALUES
('Admin_BARU','BARU@futuremusic.test','123456','admin','Legend','081111111111');


INSERT INTO venues (venue_name,city,address,capacity) VALUES
('Neon Dome Arena','Jakarta','Jl. Future Light No. 10, Jakarta',14000),
('Cyber Bay Stage','Bandung','Jl. Synthwave No. 22, Bandung',9000),
('Aurora Sky Park','Surabaya','Jl. Galaxy Selatan No. 7, Surabaya',12000),
('Quantum Hall','Yogyakarta','Jl. Orbit Musik No. 12, Yogyakarta',7000),
('Starlight Beach Club','Bali','Jl. Pantai Digital No. 18, Bali',15000),
('Pulse Convention Center','Semarang','Jl. Pulse Raya No. 9, Semarang',8000),
('Echo Stadium','Medan','Jl. Bassline No. 3, Medan',11000),
('Nova Expo Field','Makassar','Jl. Nova Timur No. 6, Makassar',10000),
('Vortex Hall','Malang','Jl. Neon Garden No. 4, Malang',6500),
('Laser Grid Square','Tangerang','Jl. Grid City No. 99, Tangerang',13000);

INSERT INTO events (id_venue,title,slug,description,event_date,start_time,category,status,image_url,video_url,seat_total,seat_available) VALUES
(1,'Future Music Experience 2026','future-music-experience-2026','Festival musik futuristik dengan AI stage, hologram artist, dan digital ticketing.',DATE_ADD(CURDATE(), INTERVAL 45 DAY),'18:00:00','Festival','featured','https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80','https://cdn.coverr.co/videos/coverr-concert-crowd-6235/1080p.mp4',14000,9120),
(2,'Neon Pulse Night','neon-pulse-night','Malam EDM cyber-modern dengan light show electric pink dan neon purple.',DATE_ADD(CURDATE(), INTERVAL 12 DAY),'19:30:00','EDM','popular','https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=1400&q=80','',9000,5275),
(3,'AI Synthwave Summit','ai-synthwave-summit','Konser synthwave dengan rekomendasi lagu berbasis AI dan visual generatif.',DATE_ADD(CURDATE(), INTERVAL 22 DAY),'20:00:00','Synthwave','upcoming','https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1400&q=80','',12000,7800),
(4,'Galaxy Bass Carnival','galaxy-bass-carnival','Bass music, techno, dan stage interaktif berbasis sensor gerak.',DATE_ADD(CURDATE(), INTERVAL 63 DAY),'17:00:00','Bass','upcoming','https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=80','',7000,3610),
(5,'Hologram Pop Fest','hologram-pop-fest','Pop futuristik dengan hologram performance dan AR crowd experience.',DATE_ADD(CURDATE(), INTERVAL 31 DAY),'18:30:00','Pop','popular','https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=1400&q=80','',15000,11350),
(6,'Quantum Jazz Electronic','quantum-jazz-electronic','Fusion jazz dan electronic dengan panggung immersive 360.',DATE_ADD(CURDATE(), INTERVAL 54 DAY),'19:00:00','Electronic Jazz','upcoming','https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=1400&q=80','',8000,4820),
(7,'Cyber K-Pop Wave','cyber-kpop-wave','K-Pop dance festival dengan fan zone digital dan QR access.',DATE_ADD(CURDATE(), INTERVAL 8 DAY),'18:00:00','K-Pop','featured','https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=1400&q=80','',11000,1420),
(8,'Electric Indie Space','electric-indie-space','Indie futuristic showcase dengan visual galaksi dan membership lounge.',DATE_ADD(CURDATE(), INTERVAL 38 DAY),'17:30:00','Indie','upcoming','https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=1400&q=80','',10000,6400),
(9,'Vortex Techno Arena','vortex-techno-arena','Techno rave dengan laser grid, live seat availability, dan smart pass.',DATE_ADD(CURDATE(), INTERVAL 70 DAY),'21:00:00','Techno','popular','https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1400&q=80','',6500,2890),
(10,'Starlight Acoustic Future','starlight-acoustic-future','Acoustic night dengan sentuhan AI ambience dan visual bintang.',DATE_ADD(CURDATE(), INTERVAL 18 DAY),'19:00:00','Acoustic','upcoming','https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2?auto=format&fit=crop&w=1400&q=80','',13000,8350);

INSERT INTO artists (artist_name,genre,bio,image_url,instagram,youtube,spotify) VALUES
('Nova Aria','EDM / Future Bass','DJ dengan karakter sound futuristic dan visual laser performance.','https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=80','@novaaria','NovaAriaLive','Nova Aria'),
('Synthra','Synthwave','Artist synthwave dengan nuansa retro cyber dan vokal cinematic.','https://images.unsplash.com/photo-1521337581100-8ca9a73a5f79?auto=format&fit=crop&w=900&q=80','@synthra','SynthraOfficial','Synthra'),
('DJ Orbit','Techno','Producer techno dengan set gelap, cepat, dan immersive.','https://images.unsplash.com/photo-1511735111819-9a3f7709049c?auto=format&fit=crop&w=900&q=80','@djorbit','DJOrbit','DJ Orbit'),
('Luna Voltage','Pop Electronic','Penyanyi pop electronic dengan stage act holographic.','https://images.unsplash.com/photo-1487180144351-b8472da7d491?auto=format&fit=crop&w=900&q=80','@lunavoltage','LunaVoltage','Luna Voltage'),
('Bass Vector','Bass Music','Duo bass music dengan drop intens dan visual AI generated.','https://images.unsplash.com/photo-1508973379184-7517410fb0bc?auto=format&fit=crop&w=900&q=80','@bassvector','BassVector','Bass Vector'),
('Echo Prime','Indie Electronic','Band indie electronic dengan aransemen atmosferik dan eksperimental.','https://images.unsplash.com/photo-1521336575822-6da63fb45455?auto=format&fit=crop&w=900&q=80','@echoprime','EchoPrime','Echo Prime'),
('Mira Neon','K-Pop Dance','Performer dance-pop dengan koreografi neon dan energy tinggi.','https://images.unsplash.com/photo-1541532713592-79a0317b6b77?auto=format&fit=crop&w=900&q=80','@miraneon','MiraNeon','Mira Neon'),
('Pulse Theory','Electronic Jazz','Grup electronic jazz dengan improvisasi live synth.','https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&w=900&q=80','@pulsetheory','PulseTheory','Pulse Theory'),
('Astra Beat','House','DJ house dengan groove festival internasional.','https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=900&q=80','@astrabeat','AstraBeat','Astra Beat'),
('Cyber Choir','Choral Electronic','Kolektif vokal elektronik dengan ambience cinematic.','https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=900&q=80','@cyberchoir','CyberChoir','Cyber Choir');

INSERT INTO schedules (id_event,id_artist,stage_name,perform_date,start_time,end_time) VALUES
(1,1,'Main Neon Stage',DATE_ADD(CURDATE(), INTERVAL 45 DAY),'19:00:00','20:00:00'),
(1,2,'AI Dome',DATE_ADD(CURDATE(), INTERVAL 45 DAY),'20:15:00','21:15:00'),
(1,5,'Galaxy Stage',DATE_ADD(CURDATE(), INTERVAL 45 DAY),'21:30:00','22:30:00'),
(2,1,'Pulse Stage',DATE_ADD(CURDATE(), INTERVAL 12 DAY),'20:00:00','21:00:00'),
(3,2,'Synth Stage',DATE_ADD(CURDATE(), INTERVAL 22 DAY),'20:30:00','21:30:00'),
(4,5,'Bass Arena',DATE_ADD(CURDATE(), INTERVAL 63 DAY),'18:00:00','19:30:00'),
(5,4,'Holo Pop Stage',DATE_ADD(CURDATE(), INTERVAL 31 DAY),'19:30:00','20:30:00'),
(6,8,'Quantum Stage',DATE_ADD(CURDATE(), INTERVAL 54 DAY),'20:00:00','21:15:00'),
(7,7,'Cyber Wave Stage',DATE_ADD(CURDATE(), INTERVAL 8 DAY),'19:00:00','20:30:00'),
(9,3,'Vortex Stage',DATE_ADD(CURDATE(), INTERVAL 70 DAY),'22:00:00','23:30:00');

INSERT INTO ticket_types (id_event,ticket_name,price,quota,sold,benefits) VALUES
(1,'Regular',350000,5000,1200,'Festival access, digital ticket, public zone'),
(1,'VIP',750000,2500,880,'Priority gate, VIP area, merchandise'),
(1,'VVIP',1250000,900,310,'Front stage, lounge access, exclusive merch'),
(1,'Backstage Pass',2500000,150,60,'Backstage access, meet artist, premium lounge'),
(2,'Regular',250000,3000,900,'Event access, digital ticket'),
(2,'VIP',600000,1000,425,'VIP area, priority entrance'),
(3,'Regular',280000,4500,600,'Event access, digital ticket'),
(3,'VIP',650000,1500,320,'VIP deck, premium view'),
(7,'VVIP',1500000,700,560,'VVIP lounge, premium view, fast lane'),
(9,'Backstage Pass',2200000,100,40,'Backstage access and artist meet');

INSERT INTO purchases (id_user,id_event,id_ticket,quantity,total_price,buyer_name,buyer_email,status,qr_code) VALUES
(2,1,1,2,700000,'Gibran Hendarto','user@futuremusic.test','paid','FMX-USER-0001'),
(3,2,5,1,250000,'Rara Neon','rara@test.com','paid','FMX-USER-0002'),
(4,3,7,2,560000,'Bimo Wave','bimo@test.com','pending','FMX-USER-0003'),
(5,7,9,1,1500000,'Sasha Pulse','sasha@test.com','paid','FMX-USER-0004'),
(6,1,2,1,750000,'Dion Orbit','dion@test.com','paid','FMX-USER-0005'),
(7,9,10,1,2200000,'Mira Echo','mira@test.com','pending','FMX-USER-0006'),
(8,1,4,1,2500000,'Naya Flux','naya@test.com','paid','FMX-USER-0007'),
(9,3,8,2,1300000,'Rio Synth','rio@test.com','paid','FMX-USER-0008'),
(10,2,6,1,600000,'Luna Bass','luna@test.com','cancelled','FMX-USER-0009'),
(2,7,9,1,1500000,'Gibran Hendarto','user@futuremusic.test','paid','FMX-USER-0010');

INSERT INTO payments (id_purchase,provider,transaction_code,amount,status,paid_at) VALUES
(1,'Midtrans Demo','MID-0001',700000,'settlement',NOW()),
(2,'Midtrans Demo','MID-0002',250000,'settlement',NOW()),
(3,'Midtrans Demo','MID-0003',560000,'pending',NULL),
(4,'Midtrans Demo','MID-0004',1500000,'settlement',NOW()),
(5,'Midtrans Demo','MID-0005',750000,'settlement',NOW()),
(6,'Midtrans Demo','MID-0006',2200000,'pending',NULL),
(7,'Midtrans Demo','MID-0007',2500000,'settlement',NOW()),
(8,'Midtrans Demo','MID-0008',1300000,'settlement',NOW()),
(9,'Midtrans Demo','MID-0009',600000,'failed',NULL),
(10,'Midtrans Demo','MID-0010',1500000,'settlement',NOW());

INSERT INTO wishlist (id_user,id_event) VALUES
(2,1),(2,3),(2,7),(3,1),(4,2),(5,3),(6,4),(7,5),(8,6),(9,9);

INSERT INTO ticket_scans (id_purchase,scan_status) VALUES
(1,'valid'),(2,'valid'),(4,'used'),(5,'valid'),(7,'valid'),(8,'valid'),(10,'valid'),(3,'invalid'),(6,'valid'),(9,'invalid');

select * from artists;
        