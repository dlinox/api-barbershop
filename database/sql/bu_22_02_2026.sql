-- Volcando datos para la tabla db_barbershop.academy_branches: ~0 rows (aproximadamente)
INSERT INTO `academy_branches` (`id`, `name`, `address`, `ubication`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Escuela Bárbaros Juliaca', 'Jr. Unión N° 209', 'Centro', 1, '2026-02-16 16:40:08', '2026-02-17 05:16:20');

-- Volcando datos para la tabla db_barbershop.core_infrastructures: ~1 rows (aproximadamente)
INSERT INTO `core_infrastructures` (`id`, `infrastructurable_type`, `infrastructurable_id`, `created_at`, `updated_at`) VALUES
	(1, 'academy_branches', 1, '2026-02-23 17:29:18', '2026-02-23 17:29:19');

-- Volcando datos para la tabla db_barbershop.academy_rooms: ~4 rows (aproximadamente)
INSERT INTO `academy_rooms` (`id`, `branch_id`, `number`, `description`, `capacity`, `floor`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 101, NULL, 20, 1, 1, '2026-02-16 16:40:08', '2026-02-16 16:40:08'),
	(2, 1, 102, NULL, 20, 1, 1, '2026-02-17 07:53:07', '2026-02-17 07:53:07'),
	(3, 1, 103, NULL, 20, 1, 1, '2026-02-17 19:12:31', '2026-02-17 19:12:31'),
	(4, 1, 104, NULL, 20, 1, 1, '2026-02-17 19:12:59', '2026-02-17 19:12:59');

-- Volcando datos para la tabla db_barbershop.academy_schedules: ~6 rows (aproximadamente)
INSERT INTO `academy_schedules` (`id`, `shift`, `start_time`, `end_time`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'morning', '09:00:00', '12:30:00', 1, '2026-02-16 16:40:08', '2026-02-17 05:17:26'),
	(2, 'afternoon', '14:00:00', '16:00:00', 1, '2026-02-17 05:17:46', '2026-02-20 23:00:37'),
	(3, 'afternoon', '16:00:00', '18:00:00', 1, '2026-02-17 05:17:58', '2026-02-20 23:00:55'),
	(4, 'morning', '18:00:00', '20:00:00', 1, '2026-02-17 05:18:10', '2026-02-20 23:03:00'),
	(5, 'morning', '09:00:00', '19:00:00', 1, '2026-02-17 07:46:56', '2026-02-17 07:46:56'),
	(7, 'afternoon', '14:00:00', '17:30:00', 1, '2026-02-20 23:00:26', '2026-02-20 23:00:26');

-- Volcando datos para la tabla db_barbershop.academy_levels: ~3 rows (aproximadamente)
INSERT INTO `academy_levels` (`id`, `order`, `name`, `description`, `duration_months`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Intermedio', '2h', 3, 1, '2026-02-16 16:40:08', '2026-02-19 13:42:16'),
	(2, 3, 'Avanzado', '2h', 3, 1, '2026-02-17 05:27:48', '2026-02-19 13:42:06'),
	(4, 1, 'Básico', '3h 30', 2, 1, '2026-02-17 07:50:24', '2026-02-19 13:46:28');

-- Volcando datos para la tabla db_barbershop.academy_groups: ~5 rows (aproximadamente)
INSERT INTO `academy_groups` (`id`, `branch_id`, `level_id`, `schedule_id`, `room_id`, `teacher_id`, `name`, `start_date`, `end_date`, `enrollment_price`, `monthly_price`, `days_of_week`, `attendance_tolerance_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
	(3, 1, 4, 1, 1, NULL, '2 MESES (9-12:30) - JESUS SALAS', '2026-01-08', '2026-03-08', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-17 19:19:56', '2026-02-19 13:54:06'),
	(9, 1, 4, 1, 2, NULL, '2 MESES (9-12:30) - RONALDINO CALCINA', '2026-01-08', '2026-03-08', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-19 13:53:45', '2026-02-19 13:53:58'),
	(10, 1, 4, 1, 4, NULL, '2 MESES (9-12:30) - MIDWAR SANDOVAL', '2026-02-09', '2026-04-09', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-19 14:02:23', '2026-02-19 14:02:42'),
	(11, 1, 4, 1, 3, NULL, '2 MESES (9-12:30) - ALEX SANTOS', '2026-01-16', '2026-03-16', 250.00, 650.00, '1,3,2,4,5', 20, 1, '2026-02-19 14:11:13', '2026-02-19 14:11:31'),
	(12, 1, 1, 2, 2, NULL, '3 MESES (2-4) - RONALDINO CALCINA', '2026-02-09', '2026-05-09', 250.00, 450.00, '1,2,3,5,4', 20, 1, '2026-02-20 19:38:43', '2026-02-20 23:21:56');

-- Volcando datos para la tabla db_barbershop.academy_group_payment_plans: ~16 rows (aproximadamente)
INSERT INTO `academy_group_payment_plans` (`id`, `group_id`, `type`, `start_date`, `end_date`, `amount`, `created_at`, `updated_at`) VALUES
	(18, 9, 'enrollment', '2026-01-08', '2026-03-08', 250.00, '2026-02-19 13:53:58', '2026-02-19 13:53:58'),
	(19, 9, 'monthly', '2026-01-08', '2026-02-08', 650.00, '2026-02-19 13:53:58', '2026-02-19 13:53:58'),
	(20, 9, 'monthly', '2026-02-08', '2026-03-08', 650.00, '2026-02-19 13:53:58', '2026-02-19 13:53:58'),
	(21, 3, 'enrollment', '2026-01-08', '2026-03-08', 250.00, '2026-02-19 13:54:06', '2026-02-19 13:54:06'),
	(22, 3, 'monthly', '2026-01-08', '2026-02-08', 650.00, '2026-02-19 13:54:06', '2026-02-19 13:54:06'),
	(23, 3, 'monthly', '2026-02-08', '2026-03-08', 650.00, '2026-02-19 13:54:06', '2026-02-19 13:54:06'),
	(27, 10, 'enrollment', '2026-02-09', '2026-04-09', 250.00, '2026-02-19 14:02:42', '2026-02-19 14:02:42'),
	(28, 10, 'monthly', '2026-02-09', '2026-03-09', 650.00, '2026-02-19 14:02:42', '2026-02-19 14:02:42'),
	(29, 10, 'monthly', '2026-03-09', '2026-04-09', 650.00, '2026-02-19 14:02:42', '2026-02-19 14:02:42'),
	(33, 11, 'enrollment', '2026-01-16', '2026-03-16', 250.00, '2026-02-19 14:11:31', '2026-02-19 14:11:31'),
	(34, 11, 'monthly', '2026-01-16', '2026-02-16', 650.00, '2026-02-19 14:11:31', '2026-02-19 14:11:31'),
	(35, 11, 'monthly', '2026-02-16', '2026-03-16', 650.00, '2026-02-19 14:11:31', '2026-02-19 14:11:31'),
	(44, 12, 'enrollment', '2026-02-09', '2026-05-09', 250.00, '2026-02-20 23:21:56', '2026-02-20 23:21:56'),
	(45, 12, 'monthly', '2026-02-09', '2026-03-09', 450.00, '2026-02-20 23:21:56', '2026-02-20 23:21:56'),
	(46, 12, 'monthly', '2026-03-09', '2026-04-09', 450.00, '2026-02-20 23:21:56', '2026-02-20 23:21:56'),
	(47, 12, 'monthly', '2026-04-09', '2026-05-09', 450.00, '2026-02-20 23:21:56', '2026-02-20 23:21:56');


INSERT INTO `behavior_roles` (`id`, `name`, `display_name`, `redirect_to`, `level`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'Super Admin', '/admin', '0', 1, '2026-02-16 16:40:07', '2026-02-16 16:40:07'),
	(2, 'estudiante', 'Estudiante', '/admin', '3', 1, '2026-02-16 16:40:08', '2026-02-16 16:40:08'),
	(3, 'administrador', 'Administrador', '/admin', '1', 1, '2026-02-16 16:41:10', '2026-02-16 16:41:10'),
	(4, 'docentes', 'Docentes', '/admin', '2', 1, '2026-02-16 16:40:08', '2026-02-16 16:40:08');


-- Volcando datos para la tabla db_barbershop.core_persons: ~36 rows (aproximadamente)
INSERT INTO `core_persons` (`id`, `document_type`, `document_number`, `name`, `paternal_surname`, `maternal_surname`, `date_birth`, `phone`, `email`, `gender`, `address`, `city`, `country`, `created_at`, `updated_at`) VALUES
	(1, '1', '00000000', 'Lino', 'Puma', NULL, NULL, NULL, 'super@admin.com', NULL, NULL, NULL, 'PE', '2026-02-16 16:40:07', '2026-02-16 16:40:07'),
	(2, '1', '00000001', 'Admin', 'Admin', 'Test', NULL, '900000001', 'admin@test.com', NULL, NULL, NULL, NULL, '2026-02-16 16:41:52', '2026-02-16 16:41:52'),
	(6, '1', '76832299', 'BRITNEY LISBET', 'CANSAYA', 'GEMIO', NULL, '961258362', 'C@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:20:41', '2026-02-19 14:20:41'),
	(7, '1', '61893646', 'RENZO PAUL', 'GOMEZ', 'TAIPE', NULL, '961264940', 'C1@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:23:34', '2026-02-19 14:23:34'),
	(8, '1', '62805432', 'CHRISTIAN GUILARDINO', 'MAMANI', 'CONDORI', NULL, '922127589', 'C2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:24:45', '2026-02-19 14:24:45'),
	(9, '1', '73820089', 'FERMINA FLORA', 'CALCINA', 'MAMANI', NULL, '957772458', 'C3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:25:53', '2026-02-19 14:25:53'),
	(10, '1', '61939362', 'ANDREW ALEXANDER', 'PAREDES', 'POMA', NULL, '61939362', 'C4@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:26:58', '2026-02-19 14:26:58'),
	(11, '1', '61783700', 'YIMER RUSSEL', 'AHUMADA', 'QUISPE', NULL, '965194393', 'C5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:27:52', '2026-02-19 14:27:52'),
	(12, '1', '63017506', 'KEVIN JUNIOR', 'LUQUE', 'CHAIÑA', NULL, '900466355', 'C6@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:28:58', '2026-02-19 14:28:58'),
	(13, '1', '48022417', 'ROXANA', 'CUNO', 'HUANCA', NULL, '998424104', 'C7@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:30:01', '2026-02-19 14:30:01'),
	(14, '1', '61892341', 'PRINCE ARTURO GABRIEL', 'GOMEZ', 'FLORES', NULL, '921666999', 'C8@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:31:13', '2026-02-19 14:31:13'),
	(15, '1', '61321205', 'MARCO RUBIÑO', 'QUISPE', 'SUCAPUCA', NULL, '941735090', 'C9@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:32:07', '2026-02-19 14:32:07'),
	(16, '1', '62528271', 'DIEGO LEONARDO', 'COYA', 'COAQUIRA', NULL, '973128544', '10@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:33:23', '2026-02-19 14:33:23'),
	(17, '1', '62480235', 'JEAN FRANK RONALDHO', 'CASTILLO', 'ROJAS', NULL, '993138481', '11@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:34:19', '2026-02-19 14:34:19'),
	(18, '1', '45910327', 'BERTHA', 'CALSINA', 'CCASO', NULL, '929120254', '12@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:41:41', '2026-02-19 14:41:41'),
	(19, '1', '12312301', 'ARISON HAIR', 'HUAHUAMULLO', 'SANCHEZ', NULL, '925818582', '13@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:48:28', '2026-02-19 14:48:28'),
	(20, '1', '12312302', 'MARIA DEL VALLE', 'MONTILLA', 'ALBORNOZ', NULL, '915075991', '14@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 14:49:28', '2026-02-19 14:51:16'),
	(21, '1', '12312303', 'GUSTAVO MAXIMO', 'APAZA', 'DELGADO', NULL, '910523360', '16@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 15:04:23', '2026-02-19 15:04:23'),
	(22, '1', '72233424', 'ROBERTO CARLOS', 'ROCHA', 'BUTRON', NULL, '980511864', 'M1@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:31:13', '2026-02-20 19:31:13'),
	(23, '1', '70838415', 'SUSY EDITH', 'CANTUTA', 'QUISPE', NULL, '929507195', 'M2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:32:19', '2026-02-20 19:32:19'),
	(24, '1', '74310292', 'JHON BRAYAN', 'CRUZ', 'QUISPE', NULL, '947736178', 'M3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:43:15', '2026-02-20 19:43:28'),
	(25, '1', '61091109', 'JENNIFER ANAHIS', 'CHOQUEMAQUE', 'CALAPUJA', NULL, '969567586', 'M4@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:46:50', '2026-02-20 19:46:50'),
	(26, '1', '73651532', 'ROY ALVARO', 'PALOMINO', 'QUISPE', NULL, '935269221', 'M5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:48:24', '2026-02-20 19:52:50'),
	(27, '1', '73541260', 'DELMA MERY', 'CCANCAPA', 'PIZARRO', NULL, '921614240', 'M6@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:50:22', '2026-02-20 19:50:22'),
	(28, '1', '61000410', 'FERNANDO SERGIO', 'MAMANI', 'CONDORI', NULL, '953886034', 'M7@GMIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:55:07', '2026-02-20 19:55:07'),
	(29, '1', '71654064', 'MARLETH MILAGROS', 'CHAMBI', 'HUAMAN', NULL, '910333619', 'M8@GMAI.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:58:16', '2026-02-20 19:58:16'),
	(30, '1', '60665191', 'KEVIN', 'UCHIRI', 'RAMOS', NULL, '933420822', 'M9@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 19:59:12', '2026-02-20 19:59:12'),
	(31, '1', '73171929', 'FRANK ANTHONY', 'TICONA', 'APAZA', NULL, '991733788', 'M10@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 20:03:25', '2026-02-20 20:03:25'),
	(32, '1', '62824124', 'KENYI LEONEL', 'TICONA', 'APAZA', NULL, '991292613', 'M11@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 20:05:53', '2026-02-20 20:05:53'),
	(33, '1', '60417095', 'NOLBERTO RICHARD', 'RAMOS', 'MOROCCO', NULL, '917111765', 'M12@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 20:10:05', '2026-02-20 20:10:05'),
	(34, '1', '60597092', 'RAUL EDUARDO', 'HUAMAN', 'SURCO', NULL, '942361760', 'M13@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 20:11:30', '2026-02-20 20:11:30'),
	(35, '1', '75899252', 'YONATAN HEBER', 'QUISPE', 'MAMANI', NULL, '921660409', 'M14@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 20:14:01', '2026-02-20 20:14:01'),
	(36, '1', '62486607', 'GARCIA YANA LUIS ALBERTO', 'GARCIA', 'YANA', NULL, '989976297', 'ALFIL.CONTA5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 23:35:01', '2026-02-20 23:35:01'),
	(37, '1', '60908831', 'ROGER FRANCES', 'ADCO', 'MACEDO', NULL, '957107771', 'R1@GAMIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 23:37:51', '2026-02-20 23:37:51'),
	(38, '1', '61906283', 'ALVARO GEFFEN', 'CALATAYUD', 'MONRROY', NULL, '989689563', 'R2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 23:38:58', '2026-02-20 23:38:58'),
	(39, '1', '76507044', 'LUIS YAMPIER', 'CONDORI', 'FLORES', NULL, '917519654', 'R3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 23:40:25', '2026-02-20 23:40:25');



-- Volcando datos para la tabla db_barbershop.profile_admins: ~2 rows (aproximadamente)
INSERT INTO `profile_admins` (`core_person_id`, `created_at`, `updated_at`) VALUES
	(1, '2026-02-16 16:40:08', '2026-02-16 16:40:08'),
	(2, '2026-02-16 16:41:53', '2026-02-16 16:41:53');

-- Volcando datos para la tabla db_barbershop.profile_students: ~34 rows (aproximadamente)
INSERT INTO `profile_students` (`core_person_id`, `created_at`, `updated_at`) VALUES
	(6, '2026-02-19 14:20:41', '2026-02-19 14:20:41'),
	(7, '2026-02-19 14:23:35', '2026-02-19 14:23:35'),
	(8, '2026-02-19 14:24:45', '2026-02-19 14:24:45'),
	(9, '2026-02-19 14:25:53', '2026-02-19 14:25:53'),
	(10, '2026-02-19 14:26:58', '2026-02-19 14:26:58'),
	(11, '2026-02-19 14:27:53', '2026-02-19 14:27:53'),
	(12, '2026-02-19 14:28:58', '2026-02-19 14:28:58'),
	(13, '2026-02-19 14:30:01', '2026-02-19 14:30:01'),
	(14, '2026-02-19 14:31:13', '2026-02-19 14:31:13'),
	(15, '2026-02-19 14:32:07', '2026-02-19 14:32:07'),
	(16, '2026-02-19 14:33:23', '2026-02-19 14:33:23'),
	(17, '2026-02-19 14:34:20', '2026-02-19 14:34:20'),
	(18, '2026-02-19 14:41:41', '2026-02-19 14:41:41'),
	(19, '2026-02-19 14:48:29', '2026-02-19 14:48:29'),
	(20, '2026-02-19 14:49:28', '2026-02-19 14:49:28'),
	(21, '2026-02-19 15:04:24', '2026-02-19 15:04:24'),
	(22, '2026-02-20 19:31:14', '2026-02-20 19:31:14'),
	(23, '2026-02-20 19:32:19', '2026-02-20 19:32:19'),
	(24, '2026-02-20 19:43:15', '2026-02-20 19:43:15'),
	(25, '2026-02-20 19:46:50', '2026-02-20 19:46:50'),
	(26, '2026-02-20 19:48:24', '2026-02-20 19:48:24'),
	(27, '2026-02-20 19:50:23', '2026-02-20 19:50:23'),
	(28, '2026-02-20 19:55:08', '2026-02-20 19:55:08'),
	(29, '2026-02-20 19:58:16', '2026-02-20 19:58:16'),
	(30, '2026-02-20 19:59:12', '2026-02-20 19:59:12'),
	(31, '2026-02-20 20:03:25', '2026-02-20 20:03:25'),
	(32, '2026-02-20 20:05:54', '2026-02-20 20:05:54'),
	(33, '2026-02-20 20:10:05', '2026-02-20 20:10:05'),
	(34, '2026-02-20 20:11:30', '2026-02-20 20:11:30'),
	(35, '2026-02-20 20:14:01', '2026-02-20 20:14:01'),
	(36, '2026-02-20 23:35:01', '2026-02-20 23:35:01'),
	(37, '2026-02-20 23:37:51', '2026-02-20 23:37:51'),
	(38, '2026-02-20 23:38:59', '2026-02-20 23:38:59'),
	(39, '2026-02-20 23:40:25', '2026-02-20 23:40:25');


-- Volcando datos para la tabla db_barbershop.auth_users: ~39 rows (aproximadamente)
INSERT INTO `auth_users` (`id`, `username`, `email`, `password`, `is_active`, `email_verified_at`, `last_sign_in_at`, `created_at`, `updated_at`) VALUES
	(1, 'linox', 'super@admin.com', '$2y$12$eIgLsPURLrE9XbYLsP3KZ.2.7sGLXE17ZRVM04/VTT4gDZTFK2XuK', 1, '2026-02-16 16:40:08', '2026-02-19 04:06:25', '2026-02-16 16:40:08', '2026-02-19 04:06:25'),
	(2, '00000001', 'admin@test.com', '$2y$12$vCODGEASNMuJI.GlnRkpfeBmdTl3DH3qCAbpqLCxYuT8O.vRXelDi', 1, NULL, '2026-02-23 06:18:23', '2026-02-16 16:41:53', '2026-02-23 06:18:23'),
	(3, '76063570', 'carlos@gmail.com', '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa', 0, NULL, NULL, '2026-02-17 08:11:41', '2026-02-17 08:14:38'),
	(4, '70063570', 'STIP@GMAIL.COM', '$2y$12$I0a2epusoMCU5tNBDLipCOyTGcsil9efPYgn1emXxGEzAiwa50hZq', 0, NULL, NULL, '2026-02-18 05:01:21', '2026-02-18 05:01:21'),
	(5, '12312323', 'linox@gmail.com', '$2y$12$r.1AkaWTyvhZmFBlo0ye5O0uQFmfY/AXyu8lXp2CoUJ22dJ4IGZ8S', 0, NULL, NULL, '2026-02-18 07:55:33', '2026-02-18 07:55:33'),
	(6, '76832299', 'C@GMAIL.COM', '$2y$12$O7vtgFa/FERbAnN.UQJjn.3GbjDH6r98L/f/r5G/KrZspbK.Qb.cK', 0, NULL, NULL, '2026-02-19 14:20:41', '2026-02-19 14:20:41'),
	(7, '61893646', 'C1@GMAIL.COM', '$2y$12$2v32e.Lvyk8/HFdBdZtzTeomN1Xohgx2nI5CY.iIap2Wj1EfznQdS', 0, NULL, NULL, '2026-02-19 14:23:35', '2026-02-19 14:23:35'),
	(8, '62805432', 'C2@GMAIL.COM', '$2y$12$.CIypFjolrQrOKxHvTBkLezZBa.ZSoQ8Maky.Nps5Mkk2lYQ/7D9G', 0, NULL, NULL, '2026-02-19 14:24:45', '2026-02-19 14:24:45'),
	(9, '73820089', 'C3@GMAIL.COM', '$2y$12$PTBMqaIL7GF4lqUQIt4mpewuMBrlY4jcf5dNyCki8DxZIdxGUDxQW', 0, NULL, NULL, '2026-02-19 14:25:53', '2026-02-19 14:25:53'),
	(10, '61939362', 'C4@GMAIL.COM', '$2y$12$mDsN4.zKdDnJwdnVrtrE8O174zK62WY0HM0.lQZSfDP4qjTmxyInO', 0, NULL, NULL, '2026-02-19 14:26:58', '2026-02-19 14:26:58'),
	(11, '61783700', 'C5@GMAIL.COM', '$2y$12$IK12WY7QrAD4LI7R50btA.kBcKI/4jbGXrA38myYWWYvtnabJsIE.', 0, NULL, NULL, '2026-02-19 14:27:53', '2026-02-19 14:27:53'),
	(12, '63017506', 'C6@GMAIL.COM', '$2y$12$2qeAa.EYewO/3Q1hXinGseLlucni.Z5FS9hdcaQ2TdLZvKlUmFUyi', 0, NULL, NULL, '2026-02-19 14:28:58', '2026-02-19 14:28:58'),
	(13, '48022417', 'C7@GMAIL.COM', '$2y$12$zrdfWdgPfrPkcah8/F5yTOmVze/rPh7tDwFU4MPpNDfAIl2lzoNTi', 0, NULL, NULL, '2026-02-19 14:30:01', '2026-02-19 14:30:01'),
	(14, '61892341', 'C8@GMAIL.COM', '$2y$12$SELnPVunL3yDfbkd/onxruuPtt5m4gHfrlZJ0BUIam81mdyW5IPyW', 0, NULL, NULL, '2026-02-19 14:31:13', '2026-02-19 14:31:13'),
	(15, '61321205', 'C9@GMAIL.COM', '$2y$12$A4y8VYuvjcFmA4MUOdHC5eyBPVoJu/.yZZVNCweBYxIwmLQg3L2M.', 0, NULL, NULL, '2026-02-19 14:32:07', '2026-02-19 14:32:07'),
	(16, '62528271', '10@GMAIL.COM', '$2y$12$Ts7pMU5CWRrc.kyFgdDuhexJ6LIamvlPvFk.c0t8iVKglbVyO4d2C', 0, NULL, NULL, '2026-02-19 14:33:23', '2026-02-19 14:33:23'),
	(17, '62480235', '11@GMAIL.COM', '$2y$12$t/U5jez0qq38OqADNYHkZORJWjSxhnI/4g.gnnTvVBM7974jUy21a', 0, NULL, NULL, '2026-02-19 14:34:20', '2026-02-19 14:34:20'),
	(18, '45910327', '12@GMAIL.COM', '$2y$12$hnmq2EGKWmjg2Y8GMDNRgezqhRZGvnjOhpOkRtRI3YFFXDZkR7ODK', 0, NULL, NULL, '2026-02-19 14:41:41', '2026-02-19 14:41:41'),
	(19, '12312301', '13@GMAIL.COM', '$2y$12$RMNPL6Ud0Iesqw2vNgqxiOjCrvnxxI40kO/WD1LAgitQoilW4jkAS', 0, NULL, NULL, '2026-02-19 14:48:29', '2026-02-19 14:48:29'),
	(20, '12312302', '14@GMAIL.COM', '$2y$12$LObMuDJrHfyVTA4XVUqlxu59Mnj9.wOPCv.IfN8c9nT20s8bTOT0e', 0, NULL, NULL, '2026-02-19 14:49:28', '2026-02-19 14:51:17'),
	(21, '12312303', '16@GMAIL.COM', '$2y$12$R/DPpa3tU8H8eu8xPMPrdea/QVlu6nN7bSrPlWjCgiEyZ/rZey/2W', 0, NULL, NULL, '2026-02-19 15:04:24', '2026-02-19 15:04:24'),
	(22, '72233424', 'M1@GMAIL.COM', '$2y$12$vvsklgEa6DpTHCJd6yCamepJ9G25RAcu7jh/VW9CCORpmUEDA/CE6', 0, NULL, NULL, '2026-02-20 19:31:14', '2026-02-20 19:31:14'),
	(23, '70838415', 'M2@GMAIL.COM', '$2y$12$axVeDLho2Vt8aSXCFOb1a.NETRdh9sAUuBg/cLM7ognkuG.sWpi/K', 0, NULL, NULL, '2026-02-20 19:32:19', '2026-02-20 19:32:19'),
	(24, '74310292', 'M3@GMAIL.COM', '$2y$12$aJnBehBnv6L4Rq1NP43wTeqUe0yehMH3v1.jQhU0ePL0hPp2DSqt.', 0, NULL, NULL, '2026-02-20 19:43:15', '2026-02-20 19:43:29'),
	(25, '61091109', 'M4@GMAIL.COM', '$2y$12$ZsAZfvUUMeR13AKdnbSdGOgnwrNYOnCHjYZhoV50RQch0.NHquIBG', 0, NULL, NULL, '2026-02-20 19:46:50', '2026-02-20 19:46:50'),
	(26, '73651532', 'M5@GMAIL.COM', '$2y$12$7kjOTfjkbmogUZtxB.nAZ.TmKkcc1vQAvhgMDSP0FGRx3gijkuNZW', 0, NULL, NULL, '2026-02-20 19:48:24', '2026-02-20 19:52:50'),
	(27, '73541260', 'M6@GMAIL.COM', '$2y$12$.l4qTeAHkaMp07GP5K6CnumJRxbUsWpQckm5el6E89L41eZKd3nEG', 0, NULL, NULL, '2026-02-20 19:50:23', '2026-02-20 19:50:23'),
	(28, '61000410', 'M7@GMIL.COM', '$2y$12$BqzIiqh/JEQIHVDLB6yYjuzpDT3mwAaN66dryyaEQAs5JEbAKeUPC', 0, NULL, NULL, '2026-02-20 19:55:08', '2026-02-20 19:55:08'),
	(29, '71654064', 'M8@GMAI.COM', '$2y$12$Gho/IAz5Kmxv24Z0uqwE8OAV4otQjNVVBGW7Wwh4z/rqbYxB/ozwu', 0, NULL, NULL, '2026-02-20 19:58:16', '2026-02-20 19:58:16'),
	(30, '60665191', 'M9@GMAIL.COM', '$2y$12$sdx6oSSM02db8WPeXqARZ.MZB8zG0f9I5/5nrxgg2LsIC1qjPbI9a', 0, NULL, NULL, '2026-02-20 19:59:12', '2026-02-20 19:59:12'),
	(31, '73171929', 'M10@GMAIL.COM', '$2y$12$EXxMQkC2gXslFLTwYGAL5OnOpzFWX52Le0/LXfz/XVm7xZl6vpLsG', 0, NULL, NULL, '2026-02-20 20:03:25', '2026-02-20 20:03:25'),
	(32, '62824124', 'M11@GMAIL.COM', '$2y$12$Z/bOuixTXvCh/0EGGd/XZeB3kcsDYDcRKE0C1gDI8bYPXMJpcKb9u', 0, NULL, NULL, '2026-02-20 20:05:54', '2026-02-20 20:05:54'),
	(33, '60417095', 'M12@GMAIL.COM', '$2y$12$/aQhs8j5KchiBW9deTo8ZuZtKf1GzYpthNBdyem1MTZazo/AS70D2', 0, NULL, NULL, '2026-02-20 20:10:05', '2026-02-20 20:10:05'),
	(34, '60597092', 'M13@GMAIL.COM', '$2y$12$fZ1EBD1pNYnMAbPYv9Dv9OXsiRV5a9lH8fkdXEC4nFg03NYpt.j1W', 0, NULL, NULL, '2026-02-20 20:11:30', '2026-02-20 20:11:30'),
	(35, '75899252', 'M14@GMAIL.COM', '$2y$12$fVmdT2yHdvCn6iOF9HgTZ.dAh4Q.O3yfyc1E4otFrmShNJDEgIyl.', 0, NULL, NULL, '2026-02-20 20:14:01', '2026-02-20 20:14:01'),
	(36, '62486607', 'ALFIL.CONTA5@GMAIL.COM', '$2y$12$bpjwoCBlf.3rVa3nFVRpb.E9TuHG4TLjy0L8DreM1SHPGBZL1khwW', 0, NULL, NULL, '2026-02-20 23:35:01', '2026-02-20 23:35:01'),
	(37, '60908831', 'R1@GAMIL.COM', '$2y$12$D/dKIfLwgXnqV0ZcsG1WceBbi5wEZimpkU7A5GFwtSxvzvx3Toqcm', 0, NULL, NULL, '2026-02-20 23:37:51', '2026-02-20 23:37:51'),
	(38, '61906283', 'R2@GMAIL.COM', '$2y$12$snx.x2t7CCwmWUbsZf6Pvupz6PUdktVNXbGCnGqu7pXpLI1hZiyIG', 0, NULL, NULL, '2026-02-20 23:38:59', '2026-02-20 23:38:59'),
	(39, '76507044', 'R3@GMAIL.COM', '$2y$12$0zo/rbwgxQTPNck15lqyVOGORlczDgKDBAv6DxcYqh.2RLGI8H8Pq', 0, NULL, NULL, '2026-02-20 23:40:25', '2026-02-20 23:40:25');

-- Volcando datos para la tabla db_barbershop.behavior_permissions: ~0 rows (aproximadamente)

-- Volcando datos para la tabla db_barbershop.behavior_profiles: ~36 rows (aproximadamente)
INSERT INTO `behavior_profiles` (`id`, `auth_user_id`, `profileable_type`, `profileable_id`, `behavior_role_id`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'profile_admins', 1, 1, 1, '2026-02-16 16:40:08', '2026-02-16 16:40:08'),
	(2, 2, 'profile_admins', 2, 3, 1, '2026-02-16 16:41:53', '2026-02-16 16:41:53'),
	(6, 6, 'profile_students', 6, 2, 1, '2026-02-19 14:20:41', '2026-02-19 14:20:41'),
	(7, 7, 'profile_students', 7, 2, 1, '2026-02-19 14:23:35', '2026-02-19 14:23:35'),
	(8, 8, 'profile_students', 8, 2, 1, '2026-02-19 14:24:45', '2026-02-19 14:24:45'),
	(9, 9, 'profile_students', 9, 2, 1, '2026-02-19 14:25:53', '2026-02-19 14:25:53'),
	(10, 10, 'profile_students', 10, 2, 1, '2026-02-19 14:26:58', '2026-02-19 14:26:58'),
	(11, 11, 'profile_students', 11, 2, 1, '2026-02-19 14:27:53', '2026-02-19 14:27:53'),
	(12, 12, 'profile_students', 12, 2, 1, '2026-02-19 14:28:58', '2026-02-19 14:28:58'),
	(13, 13, 'profile_students', 13, 2, 1, '2026-02-19 14:30:01', '2026-02-19 14:30:01'),
	(14, 14, 'profile_students', 14, 2, 1, '2026-02-19 14:31:13', '2026-02-19 14:31:13'),
	(15, 15, 'profile_students', 15, 2, 1, '2026-02-19 14:32:07', '2026-02-19 14:32:07'),
	(16, 16, 'profile_students', 16, 2, 1, '2026-02-19 14:33:23', '2026-02-19 14:33:23'),
	(17, 17, 'profile_students', 17, 2, 1, '2026-02-19 14:34:20', '2026-02-19 14:34:20'),
	(18, 18, 'profile_students', 18, 2, 1, '2026-02-19 14:41:41', '2026-02-19 14:41:41'),
	(19, 19, 'profile_students', 19, 2, 1, '2026-02-19 14:48:29', '2026-02-19 14:48:29'),
	(20, 20, 'profile_students', 20, 2, 1, '2026-02-19 14:49:28', '2026-02-19 14:49:28'),
	(21, 21, 'profile_students', 21, 2, 1, '2026-02-19 15:04:24', '2026-02-19 15:04:24'),
	(22, 22, 'profile_students', 22, 2, 1, '2026-02-20 19:31:14', '2026-02-20 19:31:14'),
	(23, 23, 'profile_students', 23, 2, 1, '2026-02-20 19:32:19', '2026-02-20 19:32:19'),
	(24, 24, 'profile_students', 24, 2, 1, '2026-02-20 19:43:15', '2026-02-20 19:43:15'),
	(25, 25, 'profile_students', 25, 2, 1, '2026-02-20 19:46:50', '2026-02-20 19:46:50'),
	(26, 26, 'profile_students', 26, 2, 1, '2026-02-20 19:48:24', '2026-02-20 19:48:24'),
	(27, 27, 'profile_students', 27, 2, 1, '2026-02-20 19:50:23', '2026-02-20 19:50:23'),
	(28, 28, 'profile_students', 28, 2, 1, '2026-02-20 19:55:08', '2026-02-20 19:55:08'),
	(29, 29, 'profile_students', 29, 2, 1, '2026-02-20 19:58:16', '2026-02-20 19:58:16'),
	(30, 30, 'profile_students', 30, 2, 1, '2026-02-20 19:59:12', '2026-02-20 19:59:12'),
	(31, 31, 'profile_students', 31, 2, 1, '2026-02-20 20:03:25', '2026-02-20 20:03:25'),
	(32, 32, 'profile_students', 32, 2, 1, '2026-02-20 20:05:54', '2026-02-20 20:05:54'),
	(33, 33, 'profile_students', 33, 2, 1, '2026-02-20 20:10:05', '2026-02-20 20:10:05'),
	(34, 34, 'profile_students', 34, 2, 1, '2026-02-20 20:11:30', '2026-02-20 20:11:30'),
	(35, 35, 'profile_students', 35, 2, 1, '2026-02-20 20:14:01', '2026-02-20 20:14:01'),
	(36, 36, 'profile_students', 36, 2, 1, '2026-02-20 23:35:01', '2026-02-20 23:35:01'),
	(37, 37, 'profile_students', 37, 2, 1, '2026-02-20 23:37:51', '2026-02-20 23:37:51'),
	(38, 38, 'profile_students', 38, 2, 1, '2026-02-20 23:38:59', '2026-02-20 23:38:59'),
	(39, 39, 'profile_students', 39, 2, 1, '2026-02-20 23:40:25', '2026-02-20 23:40:25');

-- Volcando datos para la tabla db_barbershop.academy_enrollments: ~32 rows (aproximadamente)
INSERT INTO `academy_enrollments` (`id`, `profile_student_id`, `group_id`, `date`, `status`, `created_at`, `updated_at`) VALUES
	(6, 6, 3, '2026-02-19', 'active', '2026-02-19 14:52:11', '2026-02-19 14:52:11'),
	(7, 7, 3, '2026-02-19', 'active', '2026-02-19 14:52:50', '2026-02-19 14:52:50'),
	(8, 8, 3, '2026-02-19', 'active', '2026-02-19 14:53:05', '2026-02-19 14:53:05'),
	(9, 9, 3, '2026-02-19', 'active', '2026-02-19 14:54:52', '2026-02-19 14:54:52'),
	(10, 10, 3, '2026-02-19', 'active', '2026-02-19 14:55:11', '2026-02-19 14:55:11'),
	(11, 11, 3, '2026-02-19', 'active', '2026-02-19 14:55:24', '2026-02-19 14:55:24'),
	(12, 12, 3, '2026-02-19', 'active', '2026-02-19 14:56:17', '2026-02-19 14:56:17'),
	(13, 13, 3, '2026-02-19', 'active', '2026-02-19 14:56:41', '2026-02-19 14:56:41'),
	(14, 14, 3, '2026-02-19', 'active', '2026-02-19 14:57:29', '2026-02-19 14:57:29'),
	(15, 15, 3, '2026-02-19', 'active', '2026-02-19 14:57:56', '2026-02-19 14:57:56'),
	(16, 16, 3, '2026-02-19', 'active', '2026-02-19 14:58:48', '2026-02-19 14:58:48'),
	(17, 17, 3, '2026-02-19', 'active', '2026-02-19 14:59:07', '2026-02-19 14:59:07'),
	(18, 18, 3, '2026-02-19', 'active', '2026-02-19 14:59:38', '2026-02-19 14:59:38'),
	(19, 20, 3, '2026-02-19', 'active', '2026-02-19 15:04:54', '2026-02-19 15:04:54'),
	(20, 21, 3, '2026-02-19', 'active', '2026-02-19 15:05:08', '2026-02-19 15:05:08'),
	(21, 22, 10, '2026-02-20', 'active', '2026-02-20 19:41:27', '2026-02-20 19:41:27'),
	(22, 23, 10, '2026-02-20', 'active', '2026-02-20 19:41:44', '2026-02-20 19:41:44'),
	(23, 24, 10, '2026-02-20', 'active', '2026-02-20 19:43:56', '2026-02-20 19:43:56'),
	(24, 25, 10, '2026-02-20', 'active', '2026-02-20 19:47:03', '2026-02-20 19:47:03'),
	(25, 26, 10, '2026-02-20', 'active', '2026-02-20 19:48:38', '2026-02-20 19:48:38'),
	(26, 27, 10, '2026-02-20', 'active', '2026-02-20 19:51:17', '2026-02-20 19:51:17'),
	(27, 28, 10, '2026-02-20', 'active', '2026-02-20 19:55:30', '2026-02-20 19:55:30'),
	(28, 29, 10, '2026-02-20', 'active', '2026-02-20 19:58:30', '2026-02-20 19:58:30'),
	(29, 30, 10, '2026-02-20', 'active', '2026-02-20 19:59:33', '2026-02-20 19:59:33'),
	(30, 31, 10, '2026-02-20', 'active', '2026-02-20 20:04:40', '2026-02-20 20:04:40'),
	(31, 32, 10, '2026-02-20', 'active', '2026-02-20 20:06:02', '2026-02-20 20:06:02'),
	(32, 33, 10, '2026-02-20', 'active', '2026-02-20 20:10:23', '2026-02-20 20:10:23'),
	(33, 34, 10, '2026-02-20', 'active', '2026-02-20 20:12:54', '2026-02-20 20:12:54'),
	(34, 35, 10, '2026-02-20', 'active', '2026-02-20 20:14:09', '2026-02-20 20:14:09'),
	(35, 36, 12, '2026-02-20', 'active', '2026-02-20 23:36:02', '2026-02-20 23:36:02'),
	(36, 37, 12, '2026-02-20', 'active', '2026-02-20 23:38:04', '2026-02-20 23:38:04'),
	(37, 38, 12, '2026-02-20', 'active', '2026-02-20 23:39:20', '2026-02-20 23:39:20');
