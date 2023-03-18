CREATE SCHEMA `ai-writer` ;
CREATE TABLE `ai-writer`.`files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `status` ENUM('pending', 'inprogress', 'completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`));
CREATE TABLE `ai-writer`.`file_prompts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `file_id` INT NOT NULL,
  `prompt` VARCHAR(1000) NOT NULL,
  `result` LONGTEXT NULL,
  `status` ENUM('pending', 'inprogress', 'completed') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));

    ALTER TABLE `ai-writer`.`files` 
ENGINE = InnoDB ;

  ALTER TABLE `ai-writer`.`file_prompts` 
ENGINE = InnoDB ;
ALTER TABLE `ai-writer`.`file_prompts` 
ADD COLUMN `topic` VARCHAR(200) NOT NULL AFTER `file_id`;

