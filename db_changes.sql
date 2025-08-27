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