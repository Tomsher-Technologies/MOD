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


-- -- Jisha New Changes
-- ALTER TABLE `delegates` CHANGE `delegation_id` `delegation_id` BIGINT UNSIGNED NULL DEFAULT NULL;
-- ALTER TABLE `delegates` ADD FOREIGN KEY (`delegation_id`) REFERENCES `delegations`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;
-- ALTER TABLE `delegates` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `badge_printed`;
-- ALTER TABLE `delegates` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;

-- ALTER TABLE `escorts` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `nationality_id`;
-- ALTER TABLE `escorts` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE SET NULL;

-- ALTER TABLE `drivers` ADD `current_room_assignment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `status`;
-- ALTER TABLE `drivers` ADD FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- ALTER TABLE `delegates` DROP FOREIGN KEY `delegates_ibfk_2`; ALTER TABLE `delegates` ADD CONSTRAINT `delegates_ibfk_2` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;

-- ALTER TABLE `escorts` DROP FOREIGN KEY `escorts_ibfk_2`; ALTER TABLE `escorts` ADD CONSTRAINT `escorts_ibfk_2` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;

-- ALTER TABLE `drivers` DROP FOREIGN KEY `drivers_ibfk_1`; ALTER TABLE `drivers` ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`current_room_assignment_id`) REFERENCES `room_assignments`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;



-- -- Shamil New changes

-- INSERT INTO `permissions` (`module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- ('deligate', 61, 'del_edit_delegate', 'Edit Delegate', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_add_delegate', 'Add Delegate', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_delete_delegate', 'Delete Delegate', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_view_delegate', 'Delete Delegate', 'web', 1, NOW(), NOW()),

-- ('deligate', 61, 'del_add_interviews', 'Add Interviews', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_edit_interviews', 'Edit Interviews', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_delete_interviews', 'Delete Interviews', 'web', 1, NOW(), NOW()),

-- ('deligate', 61, 'del_view_travels', 'View Travels', 'web', 1, NOW(), NOW()),
-- ('deligate', 61, 'del_add_travels', 'Add Travels', 'web', 1, NOW(), NOW()),

-- ('escort', 77, 'escort_view_travels', 'View Travels', 'web', 1, NOW(), NOW()),
-- ('driver', 88, 'driver_view_travels', 'View Travels', 'web', 1, NOW(), NOW()),
-- ('hotel', 99, 'accomodation_view_travels', 'View Travels', 'web', 1, NOW(), NOW()),


-- ('escort', 77, 'escort_view_delegate', 'View Delegate', 'web', 1, NOW(), NOW()),
-- ('driver', 88, 'driver_view_delegate', 'View Delegate', 'web', 1, NOW(), NOW()),
-- ('hotel', 99, 'accomodation_view_delegate', 'View Delegate', 'web', 1, NOW(), NOW());



-- INSERT INTO `permissions` (`module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- ('escort', 72, 'escort_assign_escorts', 'Assign Escorts', 'web', 1, NOW(), NOW()),
-- ('escort', 72, 'escort_unassign_escorts', 'Unassign Escorts', 'web', 1, NOW(), NOW()),

-- ('driver', 83, 'driver_assign_drivers', 'Assign Drivers', 'web', 1, NOW(), NOW()),
-- ('driver', 83, 'driver_unassign_drivers', 'Unassign Drivers', 'web', 1, NOW(), NOW());


-- INSERT INTO `permissions` (`module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- ('admin', 22, 'view_interviews', 'View Interviews', 'web', 1, NOW(), NOW());



-- INSERT INTO `permissions` (`module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- ('deligate', 61, 'del_view_interviews', 'View Interviews', 'web', 1, NOW(), NOW()),

-- ('escort', 77, 'escort_view_interviews', 'View Interviews', 'web', 1, NOW(), NOW()),

-- ('driver', 88, 'driver_view_interviews', 'View Interviews', 'web', 1, NOW(), NOW()),

-- ('hotel', 99, 'accomodation_view_interviews', 'View Interviews', 'web', 1, NOW(), NOW());



-- INSERT INTO `permissions` (`module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
-- ('deligate', 61, 'del_manage_other_interview_members', 'Manage Other Interview Members', 'web', 1, NOW(), NOW()),

-- ('escort', 77, 'escort_manage_other_interview_members', 'Manage Other Interview Members', 'web', 1, NOW(), NOW()),

-- ('driver', 88, 'driver_manage_other_interview_members', 'Manage Other Interview Members', 'web', 1, NOW(), NOW()),

-- ('hotel', 99, 'accommodation_manage_other_interview_members', 'Manage Other Interview Members', 'web', 1, NOW(), NOW());







-- NEW TABLE

INSERT INTO `permissions` 
(`id`, `module`, `parent_id`, `name`, `title`, `guard_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, 'manage_roles', 'Manage Roles', 'web', 1, NULL, NULL),
(2, 'admin', 1, 'view_roles', 'View Roles', 'web', 1, NULL, NULL),
(3, 'admin', 1, 'add_role', 'Add Roles', 'web', 1, NULL, NULL),
(4, 'admin', 1, 'edit_role', 'Edit Roles', 'web', 1, NULL, NULL),
(5, 'admin', 1, 'delete_role', 'Delete Roles', 'web', 1, NULL, NULL),

(6, 'admin', NULL, 'manage_staff', 'Manage Staff', 'web', 1, NULL, NULL),
(7, 'admin', 6, 'view_staff', 'View Staff', 'web', 1, NULL, NULL),
(8, 'admin', 6, 'add_staff', 'Add Staff', 'web', 1, NULL, NULL),
(9, 'admin', 6, 'edit_staff', 'Edit Staff', 'web', 1, NULL, NULL),
(10, 'admin', 6, 'delete_staff', 'Delete Staff', 'web', 1, NULL, NULL),

(11, 'admin', NULL, 'manage_events', 'Manage Events', 'web', 1, NULL, NULL),
(12, 'admin', 11, 'view_event', 'View Events', 'web', 1, NULL, NULL),
(13, 'admin', 11, 'add_event', 'Add Events', 'web', 1, NULL, NULL),
(14, 'admin', 11, 'edit_event', 'Edit Events', 'web', 1, NULL, NULL),
(15, 'admin', 11, 'delete_event', 'Delete Events', 'web', 1, NULL, NULL),
(16, 'admin', 11, 'assign_event', 'Assign Event Staffs', 'web', 1, NULL, NULL),

(17, 'admin', NULL, 'manage_dropdowns', 'Manage Dynamic Dropdown Contents', 'web', 1, NULL, NULL),
(18, 'admin', 17, 'view_dropdowns', 'View Dropdown Options', 'web', 1, NULL, NULL),
(19, 'admin', 17, 'add_dropdowns', 'Add Dropdown Options', 'web', 1, NULL, NULL),
(20, 'admin', 17, 'edit_dropdowns', 'Edit Dropdown Options', 'web', 1, NULL, NULL),
(21, 'admin', 17, 'delete_dropdowns', 'Delete Dropdown Options', 'web', 1, NULL, NULL),

(22, 'admin', NULL, 'manage_labels', 'Manage Label Translations', 'web', 1, NULL, NULL),
(23, 'admin', 22, 'view_labels', 'View Label Translations', 'web', 1, NULL, NULL),
(24, 'admin', 22, 'edit_labels', 'Edit Label Translations', 'web', 1, NULL, NULL),

(25, 'admin', NULL, 'manage_delegations', 'Manage Delegations', 'web', 1, NULL, NULL),
(26, 'admin', 25, 'view_delegations', 'View Delegations', 'web', 1, NULL, NULL),
(27, 'admin', 25, 'add_delegations', 'Add Delegations', 'web', 1, NULL, NULL),
(28, 'admin', 25, 'edit_delegations', 'Edit Delegations', 'web', 1, NULL, NULL),
(29, 'admin', 25, 'delete_delegations', 'Delete Delegations', 'web', 1, NULL, NULL),

(30, 'admin', 25, 'view_interviews', 'View Interviews', 'web', 1, NULL, NULL),
(31, 'admin', 25, 'add_interviews', 'Add Interviews', 'web', 1, NULL, NULL),
(32, 'admin', 25, 'edit_interviews', 'Edit Interviews', 'web', 1, NULL, NULL),
(33, 'admin', 25, 'delete_interviews', 'Delete Interviews', 'web', 1, NULL, NULL),

(34, 'admin', 25, 'view_travels', 'View Travels', 'web', 1, NULL, NULL),
(35, 'admin', 25, 'add_travels', 'Add Travels', 'web', 1, NULL, NULL),
(36, 'admin', 25, 'edit_travels', 'Edit Travels', 'web', 1, NULL, NULL),
(37, 'admin', 25, 'delete_travels', 'Delete Travels', 'web', 1, NULL, NULL),

(38, 'admin', 25, 'view_delegates', 'View Delegates', 'web', 1, NULL, NULL),
(39, 'admin', 25, 'add_delegates', 'Add Delegates', 'web', 1, NULL, NULL),
(40, 'admin', 25, 'edit_delegates', 'Edit Delegates', 'web', 1, NULL, NULL),
(41, 'admin', 25, 'delete_delegates', 'Delete Delegates', 'web', 1, NULL, NULL),

(42, 'admin', NULL, 'manage_escorts', 'Manage Escorts', 'web', 1, NULL, NULL),
(43, 'admin', 42, 'view_escorts', 'View Escorts', 'web', 1, NULL, NULL),
(44, 'admin', 42, 'add_escorts', 'Add Escorts', 'web', 1, NULL, NULL),
(45, 'admin', 42, 'edit_escorts', 'Edit Escorts', 'web', 1, NULL, NULL),
(46, 'admin', 42, 'delete_escorts', 'Delete Escorts', 'web', 1, NULL, NULL),
(47, 'admin', 42, 'assign_escorts', 'Assign Escorts', 'web', 1, NULL, NULL),
(48, 'admin', 42, 'unassign_escorts', 'Unassign Escorts', 'web', 1, NULL, NULL),

(49, 'admin', NULL, 'manage_drivers', 'Manage Drivers', 'web', 1, NULL, NULL),
(50, 'admin', 49, 'view_drivers', 'View Drivers', 'web', 1, NULL, NULL),
(51, 'admin', 49, 'add_drivers', 'Add Drivers', 'web', 1, NULL, NULL),
(52, 'admin', 49, 'edit_drivers', 'Edit Drivers', 'web', 1, NULL, NULL),
(53, 'admin', 49, 'delete_drivers', 'Delete Drivers', 'web', 1, NULL, NULL),
(54, 'admin', 49, 'assign_drivers', 'Assign Drivers', 'web', 1, NULL, NULL),
(55, 'admin', 49, 'unassign_drivers', 'Unassign Drivers', 'web', 1, NULL, NULL),

(56, 'admin', NULL, 'manage_accommodations', 'Manage Accommodations', 'web', 1, NULL, NULL),
(57, 'admin', 56, 'view_accommodations', 'View Accommodations', 'web', 1, NULL, NULL),
(58, 'admin', 56, 'add_accommodations', 'Add Accommodations', 'web', 1, NULL, NULL),
(59, 'admin', 56, 'edit_accommodations', 'Edit Accommodations', 'web', 1, NULL, NULL),
(60, 'admin', 56, 'delete_accommodations', 'Delete Accommodations', 'web', 1, NULL, NULL),
(61, 'admin', 56, 'assign_accommodations', 'Assign Accommodations', 'web', 1, NULL, NULL),
(62, 'admin', 56, 'import_accommodations', 'Bulk Import Accommodations', 'web', 1, NULL, NULL),
(63, 'admin', 56, 'view_accommodation_delegations', 'View Accommodation Delegations', 'web', 1, NULL, NULL),

(64, 'admin', NULL, 'manage_other_interview_members', 'Manage Other Interview Members', 'web', 1, NULL, NULL),
(65, 'admin', 64, 'view_other_interview_members', 'View Other Interview Members', 'web', 1, NULL, NULL),
(66, 'admin', 64, 'add_other_interview_members', 'Add Other Interview Members', 'web', 1, NULL, NULL),
(67, 'admin', 64, 'edit_other_interview_members', 'Edit Other Interview Members', 'web', 1, NULL, NULL),
(68, 'admin', 64, 'delete_other_interview_members', 'Delete Other Interview Members', 'web', 1, NULL, NULL),

(69, 'deligate', NULL, 'delegate_manage_delegations', 'Manage Delegations', 'web', 1, NULL, NULL),
(70, 'deligate', 69, 'delegate_view_delegations', 'View Delegations', 'web', 1, NULL, NULL),
(71, 'deligate', 69, 'delegate_add_delegations', 'Add Delegations', 'web', 1, NULL, NULL),
(72, 'deligate', 69, 'delegate_edit_delegations', 'Edit Delegations', 'web', 1, NULL, NULL),
(73, 'deligate', 69, 'delegate_delete_delegations', 'Delete Delegations', 'web', 1, NULL, NULL),

(74, 'deligate', NULL, 'delegate_manage_escorts', 'Manage Escorts', 'web', 1, NULL, NULL),
(75, 'deligate', 74, 'delegate_view_escorts', 'View Escorts', 'web', 1, NULL, NULL),

(76, 'deligate', NULL, 'delegate_manage_drivers', 'Manage Drivers', 'web', 1, NULL, NULL),
(77, 'deligate', 76, 'delegate_view_drivers', 'View Drivers', 'web', 1, NULL, NULL),

(78, 'deligate', NULL, 'delegate_manage_accommodations', 'Manage Accommodations', 'web', 1, NULL, NULL),
(79, 'deligate', 78, 'delegate_view_accommodations', 'View Accommodations', 'web', 1, NULL, NULL),


(113, 'deligate', 69, 'delegate_view_delegates', 'View Delegates', 'web', 1, NULL, NULL),
(114, 'deligate', 69, 'delegate_add_delegates', 'Add Delegates', 'web', 1, NULL, NULL),
(115, 'deligate', 69, 'delegate_edit_delegates', 'Edit Delegates', 'web', 1, NULL, NULL),
(116, 'deligate', 69, 'delegate_delete_delegates', 'Delete Delegates', 'web', 1, NULL, NULL),


(80, 'escort', NULL, 'escort_manage_escorts', 'Manage Escorts', 'web', 1, NULL, NULL),
(81, 'escort', 80, 'escort_view_escorts', 'View Escorts', 'web', 1, NULL, NULL),
(82, 'escort', 80, 'escort_add_escorts', 'Add Escorts', 'web', 1, NULL, NULL),
(83, 'escort', 80, 'escort_edit_escorts', 'Edit Escorts', 'web', 1, NULL, NULL),
(84, 'escort', 80, 'escort_delete_escorts', 'Delete Escorts', 'web', 1, NULL, NULL),

(85, 'escort', NULL, 'escort_manage_delegations', 'Manage Delegations', 'web', 1, NULL, NULL),
(86, 'escort', 85, 'escort_view_delegations', 'View Delegations', 'web', 1, NULL, NULL),

(87, 'escort', NULL, 'escort_manage_drivers', 'Manage Drivers', 'web', 1, NULL, NULL),
(88, 'escort', 87, 'escort_view_drivers', 'View Drivers', 'web', 1, NULL, NULL),

(89, 'escort', NULL, 'escort_manage_accommodations', 'Manage Accommodations', 'web', 1, NULL, NULL),
(90, 'escort', 89, 'escort_view_accommodations', 'View Accommodations', 'web', 1, NULL, NULL),

(91, 'driver', NULL, 'driver_manage_drivers', 'Manage Drivers', 'web', 1, NULL, NULL),
(92, 'driver', 91, 'driver_view_drivers', 'View Drivers', 'web', 1, NULL, NULL),
(93, 'driver', 91, 'driver_add_drivers', 'Add Drivers', 'web', 1, NULL, NULL),
(94, 'driver', 91, 'driver_edit_drivers', 'Edit Drivers', 'web', 1, NULL, NULL),
(95, 'driver', 91, 'driver_delete_drivers', 'Delete Drivers', 'web', 1, NULL, NULL),

(96, 'driver', NULL, 'driver_manage_delegations', 'Manage Delegations', 'web', 1, NULL, NULL),
(97, 'driver', 96, 'driver_view_delegations', 'View Delegations', 'web', 1, NULL, NULL),

(98, 'driver', NULL, 'driver_manage_escorts', 'Manage Escorts', 'web', 1, NULL, NULL),
(99, 'driver', 98, 'driver_view_escorts', 'View Escorts', 'web', 1, NULL, NULL),

(100, 'driver', NULL, 'driver_manage_accommodations', 'Manage Accommodations', 'web', 1, NULL, NULL),
(101, 'driver', 100, 'driver_view_accommodations', 'View Accommodations', 'web', 1, NULL, NULL),

(102, 'hotel', NULL, 'hotel_manage_accommodations', 'Manage Accommodations', 'web', 1, NULL, NULL),
(103, 'hotel', 102, 'hotel_view_accommodations', 'View Accommodations', 'web', 1, NULL, NULL),
(104, 'hotel', 102, 'hotel_add_accommodations', 'Add Accommodations', 'web', 1, NULL, NULL),
(105, 'hotel', 102, 'hotel_edit_accommodations', 'Edit Accommodations', 'web', 1, NULL, NULL),
(106, 'hotel', 102, 'hotel_delete_accommodations', 'Delete Accommodations', 'web', 1, NULL, NULL),

(107, 'hotel', NULL, 'hotel_manage_delegations', 'Manage Delegations', 'web', 1, NULL, NULL),
(108, 'hotel', 107, 'hotel_view_delegations', 'View Delegations', 'web', 1, NULL, NULL),

(109, 'hotel', NULL, 'hotel_manage_escorts', 'Manage Escorts', 'web', 1, NULL, NULL),
(110, 'hotel', 109, 'hotel_view_escorts', 'View Escorts', 'web', 1, NULL, NULL),

(111, 'hotel', NULL, 'hotel_manage_drivers', 'Manage Drivers', 'web', 1, NULL, NULL),
(112, 'hotel', 111, 'hotel_view_drivers', 'View Drivers', 'web', 1, NULL, NULL);



