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
