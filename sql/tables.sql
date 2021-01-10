-- Create syntax for TABLE 'main_rooms'
CREATE TABLE `main_rooms`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `description` longtext COLLATE utf8_unicode_ci     NOT NULL,
    `price`       varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `created_at`  datetime                             NOT NULL,
    `updated_at`  datetime DEFAULT NULL,
    `position`    int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create syntax for TABLE 'main_bookings'
CREATE TABLE `main_bookings`
(
    `id`         int(10) unsigned NOT NULL AUTO_INCREMENT,
    `room_id`    int(10) unsigned NOT NULL,
    `date_start` date     NOT NULL,
    `date_end`   date     NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    `position`   int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY          `bookings_rooms_fk` (`room_id`),
    CONSTRAINT `bookings_rooms_fk` FOREIGN KEY (`room_id`) REFERENCES `main_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
