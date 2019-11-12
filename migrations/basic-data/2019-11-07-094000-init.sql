INSERT INTO `users` (`id`, `first_name`, `last_name`, `birthday`, `gender`, `email`, `phone`, `username`, `nickname`, `password`, `new_password`, `street`, `city`, `psc`, `active`, `active_date_time`, `privacy`, `role`, `inserted`, `updated`) VALUES
(1,	'Admin',	'Devrun',	NULL,	1,	'admin@devrun.cz',	NULL,	'admin-dev',	'AD',	'3346b7152dc91ab74c8e5083544b3988',	NULL,	NULL,	NULL,	NULL,	1,	NULL,	1,	'supervisor',	'2019-10-15 10:27:59',	'2019-10-15 10:27:59');


INSERT INTO `package` (`id`, `user_id`, `name`, `module`, `analytic_code`, `theme_variables`, `theme_version`, `position`, `inserted`, `updated`) VALUES
(1,	NULL,	'Default',	'pexeso',	NULL,	'',	0,	1,	'2019-10-16 10:43:05',	'2019-10-16 10:43:05');


INSERT INTO `page` (`id`, `route_id`, `tree_root`, `parent_id`, `previous_id`, `type`, `tree_root_position`, `published`, `name`, `module`, `presenter`, `action`, `class`, `file`, `inserted`, `updated`, `lft`, `lvl`, `rgt`) VALUES
(1,	1,	1,	NULL,	NULL,	'static',	1,	1,	'pexeso:homepage:default',	'pexeso',	'homepage',	'default',	'PexesoModule\\Presenters\\HomepagePresenter',	'PexesoModule/Presenters/templates/Homepage/default.latte',	'2019-10-16 10:56:01',	'2019-10-16 10:56:01',	1,	0,	6),
(2,	2,	1,	1,	NULL,	'static',	1,	1,	'pexeso:form:default',	'pexeso',	'form',	'default',	'PexesoModule\\Presenters\\FormPresenter',	'PexesoModule/Presenters/templates/Form/default.latte',	'2019-10-16 11:00:27',	'2019-10-16 11:00:27',	2,	1,	3),
(3,	3,	1,	1,	NULL,	'static',	1,	1,	'pexeso:thankyou:default',	'pexeso',	'thankyou',	'default',	'PexesoModule\\Presenters\\ThankYouPresenter',	'PexesoModule/Presenters/templates/ThankYou/default.latte',	'2019-10-16 11:00:27',	'2019-10-16 11:00:27',	4,	1,	5);

INSERT INTO `page_sections` (`id`, `page_id`, `name`) VALUES
(1,	1,	'secondSection');

INSERT INTO `page_translation` (`id`, `translatable_id`, `title`, `category_name`, `description`, `notation`, `locale`) VALUES
(1,	1,	'homepage',	NULL,	NULL,	NULL,	'cs'),
(2,	2,	'formuář',	NULL,	NULL,	NULL,	'cs'),
(3,	3,	'děkujeme',	NULL,	NULL,	NULL,	'cs');

INSERT INTO `route` (`id`, `page_id`, `parent_id`, `package_id`, `uri`, `params`, `published`, `robots`, `changefreq`, `priority`, `expired`, `released`, `inserted`, `updated`) VALUES
(1,	1,	NULL,	1,	':Pexeso:Homepage:default',	'{"package":1}',	0,	'',	NULL,	NULL,	NULL,	'2019-10-16 10:56:01',	'2019-10-16 10:56:01',	'2019-10-16 10:56:01'),
(2,	2,	NULL,	1,	':Pexeso:Form:default',	'{"package":1}',	0,	'',	NULL,	NULL,	NULL,	'2019-10-16 11:00:27',	'2019-10-16 11:00:27',	'2019-10-16 11:00:27'),
(3,	3,	NULL,	1,	':Pexeso:ThankYou:default',	'{"package":1}',	0,	'',	NULL,	NULL,	NULL,	'2019-10-16 11:00:27',	'2019-10-16 11:00:27',	'2019-10-16 11:00:27');

INSERT INTO `route_translation` (`id`, `domain_id`, `translatable_id`, `name`, `title`, `url`, `domain_url`, `keywords`, `description`, `notation`, `locale`) VALUES
(1,	NULL,	1,	'pexeso page',	'Pexeso',	'',	NULL,	'desková hra Pexeso',	NULL,	NULL,	'cs'),
(2,	NULL,	2,	'pexeso page',	'Formulář',	'formular',	NULL,	NULL,	NULL,	NULL,	'cs'),
(3,	NULL,	3,	'pexeso page',	'Děkujeme',	'dekujeme',	NULL,	NULL,	NULL,	NULL,	'cs');




INSERT INTO `pexeso_settings_cards` (`id`, `package_id`, `created_by_id`, `updated_by_id`, `deleted_by_id`, `name`, `gtm_name`, `active`, `inserted`, `updated`) VALUES
(1,	1,	1,	1,	NULL,	'a',	'first pair',	1,	'2019-10-17 15:09:55',	'2019-10-17 15:09:55'),
(2,	1,	1,	1,	NULL,	'b',	'second pair',	1,	'2019-10-17 15:28:23',	'2019-10-17 15:28:23'),
(3,	1,	1,	1,	NULL,	'c',	'third pair',	1,	'2019-10-17 15:28:49',	'2019-10-17 15:28:49'),
(4,	1,	1,	1,	NULL,	'd',	'fourth pair',	1,	'2019-10-17 15:29:05',	'2019-10-17 15:29:05'),
(5,	1,	1,	1,	NULL,	'e',	'fifth pair',	1,	'2019-10-17 15:29:16',	'2019-10-17 15:29:16'),
(6,	1,	1,	1,	NULL,	'f',	'sixth pair',	1,	'2019-10-17 15:29:27',	'2019-10-17 15:29:27');

INSERT INTO `pexeso_settings_cards_translation` (`id`, `translatable_id`, `header`, `description`, `locale`) VALUES
(1,	1,	'Pyré Jablko švestka',	'100% ovocné pyré je vyrobeno z pečlivě vybraného, kvalitního ovoce, pocházející z certifikovaných sadů a od prověřených pěstitelů.',	'cs'),
(2,	2,	'Jogurt jahodový',	'V naší mlékárně v obci  Ohaře jsme unikátní ve svém přístupu k vlastnímu chovu kraviček. Díky tomu všemu dosahujeme u svých produktů té nejlepší kvality.',	'cs'),
(3,	3,	'Biosaurus',	'Kukuričná pochoutka Biosaurus Junior je v bio kvalitě a bez lepku. Skvěle chutná a je to výborná svačinka pro všechny.',	'cs'),
(4,	4,	'Slunečný pozdrav',	'Čaj Sluneční pozdrav v sobě nese tvář slunečních paprsků a je milým pozdravem všem blízkým.',	'cs'),
(5,	5,	'Zahradní limonáda',	'Zahradní limonády z Tátova sadu vyrábíme stejně jako cidery vlastníma rukama z našeho moštu.',	'cs'),
(6,	6,	'Ananas v čokoládě sypané kokosem',	'Lehká raw zmrzlina ze šťavnatých ananasů v hořké čokoládě. Lahodné osvěžení v netradičním podání.',	'cs');
