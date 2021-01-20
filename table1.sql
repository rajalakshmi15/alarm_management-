CREATE TABLE `setpoints` (
  `sp_type` varchar(30) NOT NULL,
  `set_val` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `vlvstatus` (
  `valve_id` varchar(30) NOT NULL,
  `valve_stat` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1
