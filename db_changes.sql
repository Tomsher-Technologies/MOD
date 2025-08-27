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


CREATE TABLE countries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    short_code VARCHAR(10),
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    flag VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);


ALTER TABLE countries 
ADD COLUMN continent_id BIGINT UNSIGNED NULL AFTER name;


ALTER TABLE delegate_transports DROP FOREIGN KEY delegate_transports_ibfk_4;
ALTER TABLE delegate_transports CHANGE COLUMN status_id status VARCHAR(256);

alter table delegates add column participation_status varchar(256);

insert into permissions (module, parent_id, name, title, guard_name, is_active) VALUES 
('admin', 38, 'assign_escorts', 'Assign Escorts', 'web', 1),
('admin', 42, 'assign_drivers', 'Assign Drivers', 'web', 1);

alter table delegation_drivers add column start_date varchar(256);
alter table delegation_drivers add column end_date varchar(256);

insert into dropdowns (name, code, status) VALUES ('Unit', 'unit', 1);

insert into dropdown_options (dropdown_id, value, sort_order, status) VALUES (19, 'Military', 1, 1);

alter table drivers add column code varchar(256);

alter table escorts add column code varchar(256);
