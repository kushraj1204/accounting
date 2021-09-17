--
-- Table structure for table `student_fee_advance`
--

CREATE TABLE `student_fee_advance` (
   `id` int(11) UNSIGNED NOT NULL,
   `student_id` int(11) UNSIGNED NOT NULL,
   `student_session_id` int(11) UNSIGNED DEFAULT NULL,
   `session_id` int(11) UNSIGNED DEFAULT NULL,
   `advance_amount` decimal(8,2) NOT NULL,
   `advance_date` datetime NOT NULL,
   `remarks` text NOT NULL,
   `extra_data` text NOT NULL,
   `student_session_fees_id` int(10) UNSIGNED DEFAULT NULL,
   `added_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_fee_advance`
--
ALTER TABLE `student_fee_advance`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_fee_advance`
--
ALTER TABLE `student_fee_advance`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;