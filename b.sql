DROP TABLE IF EXISTS `certificate_request`;

CREATE TABLE `certificate_request` (
  `a_i` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(255) NOT NULL,
  `residence_id` varchar(255) NOT NULL,
  `certificate_type` varchar(255) NOT NULL DEFAULT 'none',   -- document type
  `purpose` varchar(255) NOT NULL DEFAULT 'none',
  `message` varchar(255) NOT NULL DEFAULT 'none',

  `date_request` varchar(255) NOT NULL DEFAULT 'none',        -- selected date
  `time_request` varchar(255) NOT NULL DEFAULT 'none',        -- selected time
  `datetime_request` varchar(255) NOT NULL DEFAULT 'none',    -- combined date+time

  `date_issued` varchar(255) NOT NULL DEFAULT 'none',
  `date_expired` varchar(255) NOT NULL DEFAULT 'none',
  `status` varchar(255) NOT NULL DEFAULT 'none',

  PRIMARY KEY (`a_i`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
