CREATE TABLE pexeso_settings_cards_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, header VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_F20F6B792C2AC5D3 (translatable_id), UNIQUE INDEX pexeso_settings_cards_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE result_pexeso (id INT AUTO_INCREMENT NOT NULL, package_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, quiz_one VARCHAR(255) DEFAULT NULL, quiz_two VARCHAR(255) DEFAULT NULL, quiz_three VARCHAR(255) DEFAULT NULL, quiz_four VARCHAR(255) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_3625B847F44CABFF (package_id), INDEX IDX_3625B847B03A8386 (created_by_id), INDEX IDX_3625B847896DBBDE (updated_by_id), INDEX IDX_3625B847C76F1F52 (deleted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE pexeso_settings_cards (id INT AUTO_INCREMENT NOT NULL, package_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, gtm_name VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_282DB5DEF44CABFF (package_id), INDEX IDX_282DB5DEB03A8386 (created_by_id), INDEX IDX_282DB5DE896DBBDE (updated_by_id), INDEX IDX_282DB5DEC76F1F52 (deleted_by_id), UNIQUE INDEX name_package_idx (name, package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE pexeso_settings (id INT AUTO_INCREMENT NOT NULL, package_id INT DEFAULT NULL, cards SMALLINT NOT NULL, form_redirect TINYINT(1) NOT NULL, thanks_redirect TINYINT(1) NOT NULL, INDEX IDX_E35716FAF44CABFF (package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE phantom_images (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, reference_identifier VARCHAR(255) NOT NULL, capture_name VARCHAR(255) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, identifier VARCHAR(255) NOT NULL, `namespace` VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, sha VARCHAR(40) DEFAULT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, type VARCHAR(16) DEFAULT NULL, INDEX IDX_6DA7068234ECB4E6 (route_id), INDEX phantom_identifier_idx (identifier), INDEX phantom_namespace_name_idx (namespace, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) NOT NULL, birthday DATE DEFAULT NULL, gender TINYINT(1) DEFAULT NULL, email VARCHAR(128) NOT NULL, phone VARCHAR(13) DEFAULT NULL, username VARCHAR(64) NOT NULL, nickname VARCHAR(64) DEFAULT NULL, password VARCHAR(32) NOT NULL, new_password VARCHAR(32) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, city VARCHAR(128) DEFAULT NULL, psc VARCHAR(16) DEFAULT NULL, active TINYINT(1) NOT NULL, active_date_time DATETIME DEFAULT NULL, privacy TINYINT(1) NOT NULL, role VARCHAR(255) NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX newPassword_idx (new_password), INDEX first_last_name_idx (first_name, last_name), INDEX active_idx (active), INDEX role_idx (role), INDEX nickname_idx (nickname), INDEX user_email_idx (email), UNIQUE INDEX username_email_idx (username, email), UNIQUE INDEX username_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE packages_users (user_entity_id INT NOT NULL, package_entity_id INT NOT NULL, INDEX IDX_A85D694181C5F0B9 (user_entity_id), INDEX IDX_A85D69412BCBB096 (package_entity_id), PRIMARY KEY(user_entity_id, package_entity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, package_id INT DEFAULT NULL, uri VARCHAR(255) DEFAULT NULL, params VARCHAR(255) DEFAULT NULL, published TINYINT(1) NOT NULL, robots VARCHAR(255) NOT NULL, changefreq VARCHAR(255) DEFAULT NULL, priority INT DEFAULT NULL, expired DATETIME DEFAULT NULL, released DATETIME NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_2C42079C4663E4 (page_id), INDEX IDX_2C42079727ACA70 (parent_id), INDEX IDX_2C42079F44CABFF (package_id), INDEX expired_idx (expired), INDEX released_idx (released), UNIQUE INDEX uri_params_idx (uri, params), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, identify_id INT DEFAULT NULL, route_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, public TINYINT(1) DEFAULT '1' NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_E01FBE6A12469DE2 (category_id), INDEX IDX_E01FBE6AE51E104A (identify_id), INDEX IDX_E01FBE6A34ECB4E6 (route_id), INDEX IDX_E01FBE6AB03A8386 (created_by_id), INDEX IDX_E01FBE6A896DBBDE (updated_by_id), INDEX IDX_E01FBE6AC76F1F52 (deleted_by_id), INDEX image_public_idx (public), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE image_identify (id INT AUTO_INCREMENT NOT NULL, reference_identifier VARCHAR(255) NOT NULL, `namespace` VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, INDEX image_identify_namespace_name_idx (namespace, name), UNIQUE INDEX reference_identifier_unique_idx (reference_identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, previous_id INT DEFAULT NULL, type VARCHAR(64) DEFAULT 'static' NOT NULL, tree_root_position INT NOT NULL, published TINYINT(1) NOT NULL, name VARCHAR(255) DEFAULT NULL, module VARCHAR(32) NOT NULL, presenter VARCHAR(32) NOT NULL, action VARCHAR(32) NOT NULL, class VARCHAR(128) NOT NULL, file VARCHAR(255) NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, INDEX IDX_140AB62034ECB4E6 (route_id), INDEX IDX_140AB620A977936C (tree_root), INDEX IDX_140AB620727ACA70 (parent_id), INDEX IDX_140AB6202DE62210 (previous_id), INDEX class_idx (class), INDEX page_module_idx (module), INDEX presenter_idx (presenter), UNIQUE INDEX page_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, target VARCHAR(255) DEFAULT NULL, target_key INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, action VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, context LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', level SMALLINT NOT NULL, level_name VARCHAR(50) NOT NULL, extra LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', inserted DATETIME NOT NULL, INDEX IDX_8F3F68C5A76ED395 (user_id), INDEX inserted_idx (inserted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, module VARCHAR(255) NOT NULL, analytic_code VARCHAR(32) DEFAULT NULL, theme_variables LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', theme_version SMALLINT DEFAULT 0 NOT NULL, position SMALLINT DEFAULT 1 NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_DE686795A76ED395 (user_id), INDEX package_name_idx (name), INDEX package_module_idx (module), UNIQUE INDEX name_package_idx (name, module), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE page_sections (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, INDEX IDX_AB0944B8C4663E4 (page_id), INDEX page_section_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE package_users (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, INDEX IDX_8187A20EA76ED395 (user_id), UNIQUE INDEX group_username_idx (user_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE settings (id VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, validate VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE route_translation (id INT AUTO_INCREMENT NOT NULL, domain_id INT DEFAULT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT '' NOT NULL, url VARCHAR(255) NOT NULL, domain_url VARCHAR(255) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, notation LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_8A09457E115F0EE5 (domain_id), INDEX IDX_8A09457E2C2AC5D3 (translatable_id), INDEX url_idx (url), INDEX domain_url_idx (domain_url), UNIQUE INDEX domain_url_locale_idx (domain_id, domain_url, locale), UNIQUE INDEX url_locale_idx (url, locale), UNIQUE INDEX route_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, `name` VARCHAR(255) NOT NULL, valid TINYINT(1) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_A7A91E0BA76ED395 (user_id), INDEX domain_name_idx (name), INDEX domain_valid_idx (valid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE package_translation (id INT AUTO_INCREMENT NOT NULL, domain_id INT DEFAULT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_79B25F09115F0EE5 (domain_id), INDEX IDX_79B25F092C2AC5D3 (translatable_id), UNIQUE INDEX package_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE images_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, identifier VARCHAR(255) DEFAULT NULL, sha VARCHAR(40) DEFAULT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, type VARCHAR(16) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_C9DF7DF32C2AC5D3 (translatable_id), INDEX path_idx (path), INDEX image_translation_identifier_idx (identifier), UNIQUE INDEX images_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE page_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, category_name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, notation MEDIUMTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_A3D51B1D2C2AC5D3 (translatable_id), INDEX title_idx (title), UNIQUE INDEX page_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE imageCategory (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_1E62CD36B03A8386 (created_by_id), INDEX IDX_1E62CD36896DBBDE (updated_by_id), INDEX IDX_1E62CD36C76F1F52 (deleted_by_id), INDEX image_category_name_idx (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, route_id INT DEFAULT NULL, identify_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, public TINYINT(1) DEFAULT '0' NOT NULL, published_from DATETIME DEFAULT NULL, published_to DATETIME DEFAULT NULL, position SMALLINT DEFAULT 0 NOT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_23A0E6634ECB4E6 (route_id), INDEX IDX_23A0E66E51E104A (identify_id), INDEX IDX_23A0E66B03A8386 (created_by_id), INDEX IDX_23A0E66896DBBDE (updated_by_id), INDEX IDX_23A0E66C76F1F52 (deleted_by_id), INDEX article_public_idx (public), INDEX published_from_to_idx (published_from, published_to), INDEX position_idx (position), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_pages (article_entity_id INT NOT NULL, page_entity_id INT NOT NULL, INDEX IDX_57C397645B102D79 (article_entity_id), INDEX IDX_57C39764BACBC292 (page_entity_id), PRIMARY KEY(article_entity_id, page_entity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_sections (article_entity_id INT NOT NULL, page_sections_entity_id INT NOT NULL, INDEX IDX_7E7EB7F35B102D79 (article_entity_id), INDEX IDX_7E7EB7F38805D939 (page_sections_entity_id), PRIMARY KEY(article_entity_id, page_sections_entity_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, header MEDIUMTEXT DEFAULT NULL, sub_header MEDIUMTEXT DEFAULT NULL, perex MEDIUMTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_2EEA2F082C2AC5D3 (translatable_id), UNIQUE INDEX article_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_identify (id INT AUTO_INCREMENT NOT NULL, `namespace` VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, identifier VARCHAR(255) NOT NULL, options LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', INDEX article_identify_namespace_idx (namespace), INDEX article_identify_identifier_idx (identifier), UNIQUE INDEX namespace_name_idx (namespace, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_image (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, reference_identifier VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, `namespace` VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, sha VARCHAR(40) DEFAULT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, type VARCHAR(16) DEFAULT NULL, UNIQUE INDEX UNIQ_B28A764E7294869C (article_id), INDEX IDX_B28A764EB03A8386 (created_by_id), INDEX IDX_B28A764E896DBBDE (updated_by_id), INDEX IDX_B28A764EC76F1F52 (deleted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE article_images (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, deleted_by_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, inserted DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, reference_identifier VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, `namespace` VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, sha VARCHAR(40) DEFAULT NULL, width SMALLINT DEFAULT NULL, height SMALLINT DEFAULT NULL, type VARCHAR(16) DEFAULT NULL, INDEX IDX_8AD829EA7294869C (article_id), INDEX IDX_8AD829EAB03A8386 (created_by_id), INDEX IDX_8AD829EA896DBBDE (updated_by_id), INDEX IDX_8AD829EAC76F1F52 (deleted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE pexeso_settings_cards_translation ADD CONSTRAINT FK_F20F6B792C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES pexeso_settings_cards (id) ON DELETE CASCADE;
ALTER TABLE result_pexeso ADD CONSTRAINT FK_3625B847F44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE result_pexeso ADD CONSTRAINT FK_3625B847B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE result_pexeso ADD CONSTRAINT FK_3625B847896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE result_pexeso ADD CONSTRAINT FK_3625B847C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE pexeso_settings_cards ADD CONSTRAINT FK_282DB5DEF44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE pexeso_settings_cards ADD CONSTRAINT FK_282DB5DEB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE pexeso_settings_cards ADD CONSTRAINT FK_282DB5DE896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE pexeso_settings_cards ADD CONSTRAINT FK_282DB5DEC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE pexeso_settings ADD CONSTRAINT FK_E35716FAF44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE phantom_images ADD CONSTRAINT FK_6DA7068234ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE packages_users ADD CONSTRAINT FK_A85D694181C5F0B9 FOREIGN KEY (user_entity_id) REFERENCES users (id) ON DELETE CASCADE;
ALTER TABLE packages_users ADD CONSTRAINT FK_A85D69412BCBB096 FOREIGN KEY (package_entity_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE route ADD CONSTRAINT FK_2C42079C4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE;
ALTER TABLE route ADD CONSTRAINT FK_2C42079727ACA70 FOREIGN KEY (parent_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE route ADD CONSTRAINT FK_2C42079F44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A12469DE2 FOREIGN KEY (category_id) REFERENCES imageCategory (id) ON DELETE CASCADE;
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AE51E104A FOREIGN KEY (identify_id) REFERENCES image_identify (id) ON DELETE CASCADE;
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE page ADD CONSTRAINT FK_140AB62034ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE page ADD CONSTRAINT FK_140AB620A977936C FOREIGN KEY (tree_root) REFERENCES page (id) ON DELETE CASCADE;
ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id) ON DELETE CASCADE;
ALTER TABLE page ADD CONSTRAINT FK_140AB6202DE62210 FOREIGN KEY (previous_id) REFERENCES page (id);
ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;
ALTER TABLE package ADD CONSTRAINT FK_DE686795A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;
ALTER TABLE page_sections ADD CONSTRAINT FK_AB0944B8C4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE;
ALTER TABLE package_users ADD CONSTRAINT FK_8187A20EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;
ALTER TABLE route_translation ADD CONSTRAINT FK_8A09457E115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) ON DELETE SET NULL;
ALTER TABLE route_translation ADD CONSTRAINT FK_8A09457E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE domain ADD CONSTRAINT FK_A7A91E0BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;
ALTER TABLE package_translation ADD CONSTRAINT FK_79B25F09115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) ON DELETE CASCADE;
ALTER TABLE package_translation ADD CONSTRAINT FK_79B25F092C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES package (id) ON DELETE CASCADE;
ALTER TABLE images_translation ADD CONSTRAINT FK_C9DF7DF32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES images (id) ON DELETE CASCADE;
ALTER TABLE page_translation ADD CONSTRAINT FK_A3D51B1D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES page (id) ON DELETE CASCADE;
ALTER TABLE imageCategory ADD CONSTRAINT FK_1E62CD36B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE imageCategory ADD CONSTRAINT FK_1E62CD36896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE imageCategory ADD CONSTRAINT FK_1E62CD36C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE article ADD CONSTRAINT FK_23A0E6634ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE;
ALTER TABLE article ADD CONSTRAINT FK_23A0E66E51E104A FOREIGN KEY (identify_id) REFERENCES article_identify (id) ON DELETE CASCADE;
ALTER TABLE article ADD CONSTRAINT FK_23A0E66B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE article ADD CONSTRAINT FK_23A0E66896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE article ADD CONSTRAINT FK_23A0E66C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE article_pages ADD CONSTRAINT FK_57C397645B102D79 FOREIGN KEY (article_entity_id) REFERENCES article (id) ON DELETE RESTRICT;
ALTER TABLE article_pages ADD CONSTRAINT FK_57C39764BACBC292 FOREIGN KEY (page_entity_id) REFERENCES page (id) ON DELETE RESTRICT;
ALTER TABLE article_sections ADD CONSTRAINT FK_7E7EB7F35B102D79 FOREIGN KEY (article_entity_id) REFERENCES article (id) ON DELETE RESTRICT;
ALTER TABLE article_sections ADD CONSTRAINT FK_7E7EB7F38805D939 FOREIGN KEY (page_sections_entity_id) REFERENCES page_sections (id) ON DELETE RESTRICT;
ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES article (id) ON DELETE CASCADE;
ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE;
ALTER TABLE article_image ADD CONSTRAINT FK_B28A764EB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE article_image ADD CONSTRAINT FK_B28A764EC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);
ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EA7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE;
ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EAB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id);
ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EA896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id);
ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EAC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id);