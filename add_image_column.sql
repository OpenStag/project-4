-- Add event_image column to the event table
ALTER TABLE `event` ADD COLUMN `event_image` VARCHAR(255) DEFAULT NULL AFTER `all_seat`;

-- This column will store the filename of the uploaded image
-- Images will be stored in the images/events/ directory
