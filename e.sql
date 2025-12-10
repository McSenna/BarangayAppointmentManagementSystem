ALTER TABLE certificate_requests
ADD COLUMN status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending';
