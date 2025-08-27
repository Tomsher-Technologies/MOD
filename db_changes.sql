-- INSERT INTO `dropdowns` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES (NULL, 'Room Type', 'room_type', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ALTER TABLE `dropdown_options` CHANGE `sort_order` `sort_order` INT NULL DEFAULT '0';
-- ALTER TABLE `permissions` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `updated_at` `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', NULL, 'manage_accommodations', 'Manage Accommodations', 'web', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'add_accommodations', 'Add Accommodations', 'web', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'edit_accommodations', 'Edit Accommodations', 'web', '1', '2025-08-21 14:59:05', '2025-08-21 14:59:05');

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'view_accommodations', 'View Accommodations', 'web', '1', '2025-08-21 14:59:05', '2025-08-21 14:59:05');

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'assign_accommodations', 'Assign Accommodations', 'web', '1', '2025-08-21 14:59:05', '2025-08-21 14:59:05');

-- ALTER TABLE `escorts`
-- ADD COLUMN `code` VARCHAR(255) UNIQUE AFTER `id`;

-- ALTER TABLE `drivers`
-- ADD COLUMN `code` VARCHAR(255) UNIQUE AFTER `id`;

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'import_accommodations', 'Bulk Import Accommodations', 'web', '1', '2025-08-21 14:59:05', '2025-08-21 14:59:05');


-- INSERT INTO permissions (module, parent_id, name, title, guard_name, is_active) VALUES
-- ('admin', NULL, 'manage_other_interview_members', 'Manage Other Interview Members', 'web', 1);


-- INSERT INTO permissions (module, parent_id, name, title, guard_name, is_active) VALUES
-- ('admin', 52, 'add_other_interview_members', 'Add Other Interview Members', 'web', 1),
-- ('admin', 52, 'edit_other_interview_members', 'Add Other Interview Members', 'web', 1),
-- ('admin', 52, 'view_other_interview_members', 'View Other Interview Members', 'web', 1);

-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES (NULL, 'admin', '46', 'view_accommodation_delegations', 'View Accommodation Delegations', 'web', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- ALTER TABLE `delegate_transports` DROP FOREIGN KEY `delegate_transports_ibfk_1`;
-- ALTER TABLE `delegate_transports` ADD  CONSTRAINT `delegate_transports_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
-- ALTER TABLE `delegate_transports` DROP FOREIGN KEY `delegate_transports_ibfk_2`;
-- ALTER TABLE `delegate_transports` ADD  CONSTRAINT `delegate_transports_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
-- ALTER TABLE `delegate_transports` DROP FOREIGN KEY `delegate_transports_ibfk_3`;
-- ALTER TABLE `delegate_transports` ADD  CONSTRAINT `delegate_transports_ibfk_3` FOREIGN KEY (`delegate_id`) REFERENCES `delegates`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
-- ALTER TABLE `delegate_transports` ADD  FOREIGN KEY (`status_id`) REFERENCES `dropdown_options`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;



-- CREATE TABLE countries (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     name VARCHAR(255) NOT NULL,
--     short_code VARCHAR(10),
--     sort_order INT DEFAULT 0,
--     status TINYINT(1) DEFAULT 1,
--     flag VARCHAR(255),
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );


-- ALTER TABLE countries 
-- ADD COLUMN continent_id BIGINT UNSIGNED NULL AFTER name;


-- ALTER TABLE delegate_transports DROP FOREIGN KEY delegate_transports_ibfk_4;
-- ALTER TABLE delegate_transports CHANGE COLUMN status_id status VARCHAR(256);

-- alter table delegates add column participation_status varchar(256);

-- insert into permissions (module, parent_id, name, title, guard_name, is_active) VALUES 
-- ('admin', 38, 'assign_escorts', 'Assign Escorts', 'web', 1),
-- ('admin', 42, 'assign_drivers', 'Assign Drivers', 'web', 1);

-- alter table delegation_drivers add column start_date varchar(256);
-- alter table delegation_drivers add column end_date varchar(256);

-- insert into dropdowns (name, code, status) VALUES ('Unit', 'unit', 1);

-- insert into dropdown_options (dropdown_id, value, sort_order, status) VALUES (19, 'Military', 1, 1);

-- alter table drivers add column code varchar(256);

-- alter table escorts add column code varchar(256);

-- insert into permissions (module, parent_id, name, title, guard_name, is_active) VALUES ('admin', '38', 'view_escorts', "View Escorts", 'web', 1);

-- insert into permissions (module, parent_id, name, title, guard_name, is_active) VALUES ('admin', '42', 'view_drivers', "View Drivers", 'web', 1);


-- -- Delegation Module
-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- (61, 'deligate', NULL, 'del_manage_delegation', 'Manage Delegations', 'web', 1, NOW(), NOW()),
-- (62, 'deligate', 61, 'del_view_delegation', 'View Delegations', 'web', 1, NOW(), NOW()),
-- (63, 'deligate', 61, 'del_add_delegation', 'Add Delegations', 'web', 1, NOW(), NOW()),
-- (64, 'deligate', 61, 'del_edit_delegation', 'Edit Delegations', 'web', 1, NOW(), NOW()),
-- (65, 'deligate', 61, 'del_delete_delegation', 'Delete Delegations', 'web', 1, NOW(), NOW()),
-- -- View/Manage other modules from Delegation
-- -- Escort
-- (66, 'deligate', NULL, 'del_manage_escort', 'Manage Escorts', 'web', 1, NOW(), NOW()),
-- (67, 'deligate', 66, 'del_view_escort', 'View Escorts', 'web', 1, NOW(), NOW()),
-- -- Driver
-- (68, 'deligate', NULL, 'del_manage_driver', 'Manage Drivers', 'web', 1, NOW(), NOW()),
-- (69, 'deligate', 68, 'del_view_driver', 'View Drivers', 'web', 1, NOW(), NOW()),
-- -- Accommodation
-- (70, 'deligate', NULL, 'del_manage_accommodation', 'Manage Accommodations', 'web', 1, NOW(), NOW()),
-- (71, 'deligate', 70, 'del_view_accommodation', 'View Accommodations', 'web', 1, NOW(), NOW());

-- -- Escort Module
-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- (72, 'escort', NULL, 'escort_manage_escort', 'Manage Escorts', 'web', 1, NOW(), NOW()),
-- (73, 'escort', 72, 'escort_view_escort', 'View Escorts', 'web', 1, NOW(), NOW()),
-- (74, 'escort', 72, 'escort_add_escort', 'Add Escorts', 'web', 1, NOW(), NOW()),
-- (75, 'escort', 72, 'escort_edit_escort', 'Edit Escorts', 'web', 1, NOW(), NOW()),
-- (76, 'escort', 72, 'escort_delete_escort', 'Delete Escorts', 'web', 1, NOW(), NOW()),
-- -- Delegation view/manage
-- (77, 'escort', NULL, 'escort_manage_delegation', 'Manage Delegations', 'web', 1, NOW(), NOW()),
-- (78, 'escort', 77, 'escort_view_delegation', 'View Delegations', 'web', 1, NOW(), NOW()),
-- -- Driver view/manage
-- (79, 'escort', NULL, 'escort_manage_driver', 'Manage Drivers', 'web', 1, NOW(), NOW()),
-- (80, 'escort', 79, 'escort_view_driver', 'View Drivers', 'web', 1, NOW(), NOW()),
-- -- Accommodation view/manage
-- (81, 'escort', NULL, 'escort_manage_accommodation', 'Manage Accommodations', 'web', 1, NOW(), NOW()),
-- (82, 'escort', 81, 'escort_view_accommodation', 'View Accommodations', 'web', 1, NOW(), NOW());

-- -- Driver Module
-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- (83, 'driver', NULL, 'driver_manage_driver', 'Manage Drivers', 'web', 1, NOW(), NOW()),
-- (84, 'driver', 83, 'driver_view_driver', 'View Drivers', 'web', 1, NOW(), NOW()),
-- (85, 'driver', 83, 'driver_add_driver', 'Add Drivers', 'web', 1, NOW(), NOW()),
-- (86, 'driver', 83, 'driver_edit_driver', 'Edit Drivers', 'web', 1, NOW(), NOW()),
-- (87, 'driver', 83, 'driver_delete_driver', 'Delete Drivers', 'web', 1, NOW(), NOW()),
-- -- Delegation view/manage
-- (88, 'driver', NULL, 'driver_manage_delegation', 'Manage Delegations', 'web', 1, NOW(), NOW()),
-- (89, 'driver', 88, 'driver_view_delegation', 'View Delegations', 'web', 1, NOW(), NOW()),
-- -- Escort view/manage
-- (90, 'driver', NULL, 'driver_manage_escort', 'Manage Escorts', 'web', 1, NOW(), NOW()),
-- (91, 'driver', 90, 'driver_view_escort', 'View Escorts', 'web', 1, NOW(), NOW()),
-- -- Accommodation view/manage
-- (92, 'driver', NULL, 'driver_manage_accommodation', 'Manage Accommodations', 'web', 1, NOW(), NOW()),
-- (93, 'driver', 92, 'driver_view_accommodation', 'View Accommodations', 'web', 1, NOW(), NOW());

-- -- Accommodation Module
-- INSERT INTO `permissions` (`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- (94, 'hotel', NULL, 'accommodation_manage_accommodation', 'Manage Accommodations', 'web', 1, NOW(), NOW()),
-- (95, 'hotel', 94, 'accommodation_view_accommodation', 'View Accommodations', 'web', 1, NOW(), NOW()),
-- (96, 'hotel', 94, 'accommodation_add_accommodation', 'Add Accommodations', 'web', 1, NOW(), NOW()),
-- (97, 'hotel', 94, 'accommodation_edit_accommodation', 'Edit Accommodations', 'web', 1, NOW(), NOW()),
-- (98, 'hotel', 94, 'accommodation_delete_accommodation', 'Delete Accommodations', 'web', 1, NOW(), NOW()),
-- -- Delegation view/manage
-- (99, 'hotel', NULL, 'accommodation_manage_delegation', 'Manage Delegations', 'web', 1, NOW(), NOW()),
-- (100, 'hotel', 99, 'accommodation_view_delegation', 'View Delegations', 'web', 1, NOW(), NOW()),
-- -- Escort view/manage
-- (101, 'hotel', NULL, 'accommodation_manage_escort', 'Manage Escorts', 'web', 1, NOW(), NOW()),
-- (102, 'hotel', 101, 'accommodation_view_escort', 'View Escorts', 'web', 1, NOW(), NOW()),
-- -- Driver view/manage
-- (103, 'hotel', NULL, 'accommodation_manage_driver', 'Manage Drivers', 'web', 1, NOW(), NOW()),
-- (104, 'hotel', 103, 'accommodation_view_driver', 'View Drivers', 'web', 1, NOW(), NOW());


-- ALTER TABLE drivers
-- ADD COLUMN unit_id BIGINT UNSIGNED NULL AFTER status,
-- ADD CONSTRAINT fk_drivers_unit_id
--     FOREIGN KEY (unit_id) REFERENCES dropdown_options(id)
--     ON DELETE SET NULL;

-- ALTER TABLE escorts
-- ADD COLUMN unit_id BIGINT UNSIGNED NULL AFTER status,
-- ADD CONSTRAINT fk_escorts_unit_id
--     FOREIGN KEY (unit_id) REFERENCES dropdown_options(id)
--     ON DELETE SET NULL;







-- Jisha New Changes
ALTER TABLE `delegates` CHANGE `delegation_id` `delegation_id` BIGINT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `delegates` ADD FOREIGN KEY (`delegation_id`) REFERENCES `delegations`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;
ALTER TABLE `delegates` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `badge_printed`;
ALTER TABLE `delegates` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;

ALTER TABLE `escorts` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `nationality_id`;
ALTER TABLE `escorts` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;

ALTER TABLE `drivers` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `drivers` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `delegates` DROP FOREIGN KEY `delegates_ibfk_2`; ALTER TABLE `delegates` ADD CONSTRAINT `delegates_ibfk_2` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `escorts` DROP FOREIGN KEY `escorts_ibfk_2`; ALTER TABLE `escorts` ADD CONSTRAINT `escorts_ibfk_2` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;

ALTER TABLE `drivers` DROP FOREIGN KEY `drivers_ibfk_1`; ALTER TABLE `drivers` ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;