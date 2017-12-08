/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/********************* Hedayat [21/Feb/17] Start ******************/
CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_code` varchar(10) NOT NULL,
  `logo` varchar(150) NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>Not Active, 1=>Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>Not Active, 1=>Active',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/********************* Hedayat [21/Feb/17] End ******************/
/*********************  Hedayat [24/feb/17] END *************************************/
ALTER TABLE `batches`
ADD `session_id` smallint(6) NOT NULL AFTER `course_id`,
ADD `semester_id` smallint(6) NOT NULL AFTER `session_id`;
/*********************  Hedayat [24/feb/17] END *************************************/

/********************* Hedayat [2/march/17] start****************************************************/
CREATE TABLE `user_semester_maping` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `role_id` tinyint(4) NOT NULL
);

ALTER TABLE `user_semester_maping`
CHANGE `batch_id` `session_id` int(11) NOT NULL AFTER `user_id`,
ADD FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);

ALTER TABLE `user_semester_maping`
RENAME TO `user_semester_mappings`;

ALTER TABLE `user_semester_mappings`
ADD `is_guest_faculty` tinyint(4) NOT NULL COMMENT '0=>Permanent, 1=>Guest ';
/*********************  Hedayat [2/march/17] END *************************************/

/********************* Hedayat [7/march/17] start****************************************************/
ALTER TABLE `sessions`
CHANGE `logo` `logo` varchar(150) COLLATE 'latin1_swedish_ci' NULL AFTER `short_code`;

ALTER TABLE `batches`
ADD `clone_from_id` smallint(6) NULL AFTER `semester_id`;

ALTER TABLE `batches`
ADD `clone_status` smallint(6) NULL COMMENT '0=>started,1=>completed,2=>notCompleted' AFTER `clone_from_id`,
ADD `clone_failed_reason` varchar(255) NULL AFTER `clone_status`;

/*********************  Hedayat [7/march/17] END *************************************/



/********************** LMS CORE CHANGES WORK MERGED ON DATED 21-March-17*************/

/******************************* Dasarath [15 Mar 2017] [SYNCED ON dev]***************************/
CREATE TABLE IF NOT EXISTS `poll_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` int(11) NOT NULL DEFAULT '0' COMMENT '1=course poll,0=site poll',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `createdby` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `poll_question_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `options` varchar(255) NOT NULL,
  `qid` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `poll_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `optionid` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

/**************************** End ************************************************/


/******************************* ROHIT [20 March 2017] START [SYNCED ON dev]************************/
CREATE TABLE IF NOT EXISTS `user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `device_id` varchar(50) NOT NULL,
  `device_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '0=>Android, 1=>Iphone, 2=>Web',
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `api_auth_logs` CHANGE `device_type` `device_type` TINYINT( 4 ) NOT NULL COMMENT '0=>Android,1=>ios,2=>web';

/**************************** End ************************************************/

/******************************* UJJWAL [20 March 2017] START ************************/

UPDATE  `notification_templates` SET  `web_message` = 'A Live class {content_title} has been scheduled on {content_start_date} in the course {batch_name} by faculty {faculty_name}.' WHERE `notification_templates`.`id` =4;
UPDATE  `notification_templates` SET  `web_message` =  'A {content_type} {content_title} has been added to the course {batch_name} by faculty {faculty_name}.' WHERE  `notification_templates`.`id` =3;

/**************************** End ************************************************/


/********************** LMS CORE CHAGES WORK END**************************************/

/******************************* LMS MERGE PR 56 - 7- April-17[START]************************/

    CREATE TABLE IF NOT EXISTS `chats` (
      `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
      `room_id` int(11) NOT NULL DEFAULT '0',
      `message` mediumtext NOT NULL,
      `from_user` int(11) NOT NULL,
      `to_user` int(11) NOT NULL DEFAULT '0',
      `ip_address` varchar(50) NOT NULL,
      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` tinyint(4) NOT NULL DEFAULT '1',
      `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

    CREATE TABLE IF NOT EXISTS `chat_rooms` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(50) NOT NULL,
      `room_level` tinyint(3) unsigned NOT NULL,
      `room_level_id` int(10) unsigned NOT NULL,
      `created_by` int(11) NOT NULL,
      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `status` tinyint(4) NOT NULL DEFAULT '1',
      `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

    CREATE TABLE IF NOT EXISTS `chat_users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `ip_address` varchar(50) NOT NULL,
      `token` varchar(255) NOT NULL,
      `created` datetime NOT NULL,
      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `status` tinyint(4) NOT NULL DEFAULT '1',
      `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

/******************************* UJJWAL [28 March 2017] END ************************/

/******************************* ANUJ [29 March 2017] START 	[SYNCED ON dev]************************/

ALTER TABLE `questions` DROP `test_id`;
ALTER TABLE `questions` CHANGE `content_id` `batch_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `questions` ADD `is_questionbank` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>''No'',1=>''Yes''' AFTER `is_practice`;

/******************************* ANUJ [29 March 2017] END ************************/

/******************************* LMS MERGE PR 56 [END]************************/

/******************************* Virendra [28 APR 2017] START [PR #62]**********************/
DROP TABLE IF EXISTS `notification_templates`;
CREATE TABLE IF NOT EXISTS `notification_templates` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `event_type` varchar(100) NOT NULL,
  `web_message` text,
  `sms_message` text,
  `email_subject` varchar(255) DEFAULT NULL,
  `email_message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

INSERT INTO `notification_templates` (`id`, `event_type`, `web_message`, `sms_message`, `email_subject`, `email_message`) VALUES
(1, 'Student_Enrollment_In_First_Batch', 'Welcome to the {course_name} from {institute_name}', 'Welcome to the {course_name} from {institute_name}', 'Welcome to the {course_name} from {institute_name}', '<p>\n    Dear {first_name},\n</p>\n<p>\n    Greetings!\n</p>\n<p>\n    <strong>Welcome to the world of live &amp; interactive digital learning, a virtual classroom brought to you by TALENTEDGE!!!</strong>\n</p>\n<p align="justify">\n    <a name="_GoBack"></a>\nWe are pleased to share with you that the programme <u><strong>{course_name} from {institute_name}</strong></u> will commence on <strong>{start_date} ({start_day})</strong>. The intimation of timings & schedule will be shared with you prior to the batch and class commencement.\n</p>\n<p align="justify">\n    We remain confident this programme will not only enable you to derive great value, but will also significantly enhance your knowledge, and hone-up your\n    skills.\n</p>\n<strong>\n<ol type="A">\n    <li>\n        <p align="justify">\n            <u><strong>Login Id &amp; password for the ''Learning Management System'' (LMS) &amp; Platform Familiarisation Sessions</strong></u>\n        </p>\n    </li>\n</ol>\n</strong>\n<ol>\n    <li style="list-style-type: none;">\n        <p align="justify">\n    Please find below your login id &amp; password for the LMS...\n	</p>\n    </li>\n</ol>\n<ol>\n    <li style="list-style-type: none;">\n    Login Id: {username}\n    </li>\n    <li style="list-style-type: none;">\n    Password: {password}\n    </li>\n</ol>\n\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	<em><u>Please note</u> - </em>\n       	<em>Access to live classes is activated only post the payment criteria for the programme is satisfied by the participant as listed in the Talentedge website.</em>\n    </li>\n</ol>\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	We provide <u><strong>platform familiarisation sessions</strong></u> to you prior to the start of the programme to familiarize you with the LMS platform. Your Student Relations Manager shall reach out to you separately regarding these sessions.\n    </li>\n</ol>	\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	Link to LMS is <strong><a href="{product_url}" style="font-size: 19px;">{product_url}</a></strong>\n    </li>\n</ol> \n\n<strong>\n<ol type="A" start="2">\n    <li>\n        <p>\n            <u>Meet your Student Relations Manager</u>\n        </p>\n    </li>\n</ol>\n</strong>\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    {srm_name} is your <strong>Student Relations Manager</strong> (SRM). {srm_name} shall be communicating with you at every step of this programme as we move along...to assist, enable & help you with anything related to the programme... be it any special requests, issues, sharing any feedback, or for any assistance that you may seek!\n    </li>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    As your SRM, {srm_name} will be attending the virtual class-room sessions along with you, and will also reach out to you telephonically, on email, and through the LMS from time to time.\n    </li>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    In short, your SRM shall stay connected with you as we progress, till you successfully complete this programme with flying colours!\n    </li>\n</ol>\n<p>\n    <em>\n        <strong>Wish you all the best with this programme which we sincerely believe shall be enriching for you, both personally &amp; professionally.</strong>\n    </em>\n</p>\n<p>\n    Happy Learning ...\n</p>\n<p>\n    Best Wishes!\n</p>\n<p>\n    SLIQ Team - Talentedge\n</p>\n\n'),
(2, 'Batch_Start_Reminder', '{batch_name} will start in next {reminder_duration}. Happy Learning!', 'Dear {first_name}, The course that you have enrolled in, {batch_name} is going to start in next {reminder_duration}. Happy Learning!', '{batch_name} is starting in next {reminder_duration} on {start_date}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\nWe will like to inform you that only {reminder_duration} is/are left before the course {batch_name} that have enrolled for starts on {start_date}.<br /><br />\n\nWe hope you have started planning for it and have also allocated time for the notes, live classes, assessments and assignments that will be part of it.<br /><br />\n\nPlease refer to the help section {help_section_link} to learn and understand more about how to use the portal.<br /><br />\n\nWe will encourage you to download our mobile app: {android_link} {ios_link}<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br /> \n\nWe wish you all the best and Happy learning!<br /><br />\n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(3, 'Added_Learning_Content', 'A {content_type} {content_title} has been added to the course {batch_name} by faculty {faculty_name}.', NULL, '{content_title} has been added to the course {batch_name}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\n{content_title} has been added to the course {batch_name} by faculty {faculty_name}.  Please click on the following link to access it<br /><br />\n\n<a href="{content_link}" target=''blank''>click here</a><br /><br />\n\nWe will also encourage you to download our mobile app: {android_link} {ios_link}<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br />\n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(4, 'Added_Live_Class', 'A Live class {content_title} has been scheduled on {content_start_date} in the course {batch_name} by faculty {faculty_name}.', 'Dear {first_name}, Live class {content_title} has been scheduled on {content_start_date}. Kindly attend the session {start_live_class_before_minutes} minutes before start.', 'A Live class {content_title} has been scheduled on {content_start_date}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\nA Live class {content_title} has been scheduled on {content_start_date} as part of course {batch_name} being taught by Professor {faculty_name}.<br /><br />      \n\nThe link to the Live class will be available on {product_name} portal. Please click on the following link to access it\n<br /><br />\n<a href="{content_link}" target=''blank''>click here</a>\n<br /><br />\nWe would request you to plan in advance to attend the scheduled live class and be prepared at least {start_live_class_before_minutes} minutes before the live class starts.<br /><br />\n\nPlease ensure that your your mic is working fine and that you are at a place which has good internet connectivity. Please refer to the link {live_class_requirements_link} to know more about the system requirements that are required to attend the live class.<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br />\n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(5, 'Live_Class_Reminder', 'Live class {content_title} in course {batch_name} will start in next {reminder_duration}. Please plan to attend.', 'Dear {first_name}, Live Class {content_title} is going to start in next {reminder_duration}. Please plan to attend.', 'Live class {content_title} for course {batch_name} is starting in next {reminder_duration} on {content_start_date}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\nWe would like to inform you that only {reminder_duration} is/are left before the Live class {content_title} in course {batch_name} starts on {content_start_date}.<br /><br /> \n\nWe would request you to attend the live class positively and be prepared at least {start_live_class_before_minutes} minutes before live class starts.<br /><br />\n\nThe link to the Live class will be available on {product_name} portal. Please click on the following link to access it\n<br /><br />\n<a href={content_link} target=''blank''>click here</a>\n<br /><br />\n\nPlease also make sure that your mic is working fine and you are at a place which has good internet connection. Please refer to the link {live_class_requirements_link} to know the system requirements to be able to attend the live class.<br /><br />\n\nPlease refer to the help section {help_section_link} if you have any queries regarding our online portal.<br /><br />\n\nWe would also encourage you to download our mobile app: {android_link} {ios_link}.<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br />\n\nWe wish you all the best and Happy learning!<br /><br />\n\n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(6, 'Added_Assignment', 'An assignment {content_title} has been created in the course {batch_name} by faculty {faculty_name} with start date {content_start_date} and due date {content_end_date}.', 'An assignment {content_title} has been created in the course {batch_name} by faculty {faculty_name} with start date {content_start_date} and due date {content_end_date}.', 'An assignment {content_title} has been created in the course {batch_name}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\n\nAn assignment - {content_title} has been created in the course {batch_name} by faculty {faculty_name}. Please click on the following link to access it.<br /><br />\n\n<a href="{content_link}" target=''blank''>click here</a><br /><br />\n		\nThe assignment starts on {content_start_date} and needs to be submitted by {content_end_date}.<br /><br />\n\nWe would also encourage you to download our mobile app: {android_link} {ios_link}.<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br /> \n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(7, 'Added_Assessment', 'An assessment {content_title} has been created in the course {batch_name} by faculty {faculty_name} with start date {content_start_date} and due date {content_end_date}.', 'An assessment {content_title} has been created in the course {batch_name} by faculty {faculty_name} with start date {content_start_date} and due date {content_end_date}.', 'An assessment {content_title} has been created in the course {batch_name}', '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\n\nAn assessment - {content_title} has been created in the course {batch_name} by faculty {faculty_name}. Please click on the following link to access it.<br /><br />\n\n<a href="{content_link}" target=''blank''>click here</a><br /><br />\n		\nThe assessment starts on {content_start_date} and needs to be submitted by {content_end_date}.<br /><br />\n\nWe would also encourage you to download our mobile app: {android_link} {ios_link}.<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br /> \n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>'),
(8, 'Student_Enrollment_In_Batch', 'Welcome to the {course_name} from {institute_name}', 'Welcome to the {course_name} from {institute_name}', 'Welcome to the {course_name} from {institute_name}', '<p>\n    Dear {first_name},\n</p>\n<p>\n    Greetings!\n</p>\n<p>\n    <strong>Welcome to the world of live &amp; interactive digital learning, a virtual classroom brought to you by TALENTEDGE!!!</strong>\n</p>\n<p align="justify">\n    <a name="_GoBack"></a>\nWe are pleased to share with you that the programme <u><strong>{course_name} from {institute_name}</strong></u> will commence on <strong>{start_date} ({start_day})</strong>. The intimation of timings & schedule will be shared with you prior to the batch and class commencement.\n</p>\n<p align="justify">\n    We remain confident this programme will not only enable you to derive great value, but will also significantly enhance your knowledge, and hone-up your\n    skills.\n</p>\n<strong>\n<ol type="A">\n    <li>\n        <p align="justify">\n            <u><strong>''Learning Management System'' (LMS) &amp; Platform Familiarisation Sessions</strong></u>\n        </p>\n    </li>\n</ol>\n</strong>\n\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	<em><u>Please note</u> - </em>\n       	<em>Access to live classes is activated only post the payment criteria for the programme is satisfied by the participant as listed in the Talentedge website.</em>\n    </li>\n</ol>\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	We provide <u><strong>platform familiarisation sessions</strong></u> to you prior to the start of the programme to familiarize you with the LMS platform. Your Student Relations Manager shall reach out to you separately regarding these sessions.\n    </li>\n</ol>	\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n	Link to LMS is <strong><a href="{product_url}" style="font-size: 19px;">{product_url}</a></strong>\n    </li>\n</ol> \n\n<strong>\n<ol type="A" start="2">\n    <li>\n        <p>\n            <u>Meet your Student Relations Manager</u>\n        </p>\n    </li>\n</ol>\n</strong>\n<ol>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    {srm_name} is your <strong>Student Relations Manager</strong> (SRM). {srm_name} shall be communicating with you at every step of this programme as we move along...to assist, enable & help you with anything related to the programme... be it any special requests, issues, sharing any feedback, or for any assistance that you may seek!\n    </li>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    As your SRM, {srm_name} will be attending the virtual class-room sessions along with you, and will also reach out to you telephonically, on email, and through the LMS from time to time.\n    </li>\n    <li style="list-style-type: none; margin-bottom: 10px;">\n    In short, your SRM shall stay connected with you as we progress, till you successfully complete this programme with flying colours!\n    </li>\n</ol>\n<p>\n    <em>\n        <strong>Wish you all the best with this programme which we sincerely believe shall be enriching for you, both personally &amp; professionally.</strong>\n    </em>\n</p>\n<p>\n    Happy Learning ...\n</p>\n<p>\n    Best Wishes!\n</p>\n<p>\n    SLIQ Team - Talentedge\n</p>\n\n');
/******************************* Virendra [28 APR 2017] END **********************/

/******************************* LMS MERGE PR 56 to 62[END]************************/
/*======================================= All Above query are synced to DEV server and PRODUCTION SERVER==============================================*/




/*======================================= V1.0.0 START [4-May-2017]=============================================================*/

/******************************* Virendra [2 MAY 2017] START [SYNCED ON dev/qa/sliq]**********************/
ALTER TABLE `notification_templates` ADD `frequency` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '1=>Event Based, 2=>Reminder',
ADD `level` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '1=>Batch, 2=>Content';

UPDATE `notification_templates` SET frequency =1 WHERE id IN ( 1, 3, 4, 6, 7, 8 );
UPDATE `notification_templates` SET frequency =2 WHERE id IN ( 2, 5 );

UPDATE `notification_templates` SET level =1 WHERE id IN (1, 2, 8); 
UPDATE `notification_templates` SET level =2 WHERE id IN (3, 4, 5, 6, 7);

ALTER TABLE `notifications` ADD `ref_id` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'batch_id or content_id based on template level' AFTER `notification_template_id` ;
/******************************* Virendra [2 MAY 2017] END **********************/

/******************************* ANUJ [30 April 2017] START [SYNCED ON dev/qa/sliq]************************/
ALTER TABLE `questions` ADD `is_editor_enabled` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes' AFTER `is_practice`;
ALTER TABLE `test_questions` ADD `question_marks` INT NOT NULL DEFAULT '0' AFTER `question_id`;
ALTER TABLE `tests` ADD `question_format` TINYINT(2) NOT NULL DEFAULT '0' COMMENT '0=>Normal,1=>Normal+Random,2=>Normal+Filters,3=>Dynamic+Random,4=>Dynamic+Filters' AFTER `test_type`;
ALTER TABLE `tests` ADD `question_settings` TEXT NOT NULL DEFAULT '' AFTER `question_format`;
ALTER TABLE `student_test_attempts` ADD `is_dynamic` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>''No'',1=>''Yes''' AFTER `is_latest`;
ALTER TABLE `student_test_attempts` ADD `is_attempt` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>''No'',1=>''Yes''' AFTER `is_dynamic`;
ALTER TABLE `student_test_questions` CHANGE `marks_obtain` `marks_obtain` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `student_test_questions` CHANGE `selected_option_id` `selected_option_id` VARCHAR(250) NOT NULL;
/******************************* ANUJ [30 April 2017] END ************************/

/******************************* Virendra [4 MAY 2017] START [SYNCED ON dev/qa/sliq]**********************/
UPDATE `notification_templates` SET `email_message` = '<html>\n<head>\n  <title>Talentedge</title>\n</head>\n<body>\n<table style="width:80%; border: 1px solid #e6e6ff;">\n<tr><td>\n<img src="http://talentedge.in/images/talentedge-home-page-logo.png" alt="" class="tlt-logo"></td>\n</td></tr>\n<tr><td>\n<table style="width:100%; border: 1px solid #e6e6ff; font-family: verdana;"><tr><td><br />\nDear {first_name},<br /><br />\n\nA Live class {content_title} has been scheduled on {content_start_date} as part of course {batch_name}.<br /><br />      \n\nThe link to the Live class will be available on {product_name} portal. Please click on the following link to access it\n<br /><br />\n<a href="{content_link}" target=''blank''>click here</a>\n<br /><br />\nWe would request you to plan in advance to attend the scheduled live class and be prepared at least {start_live_class_before_minutes} minutes before the live class starts.<br /><br />\n\nPlease ensure that your your mic is working fine and that you are at a place which has good internet connectivity. Please refer to the link {live_class_requirements_link} to know more about the system requirements that are required to attend the live class.<br /><br />\n\nIn case you have queries we will encourage you to contact our support team through email - {contact_email_link} or phone {contact_phone}. We are available from {contact_start_time} to {contact_end_time}.<br /><br />\n\nWarm Regards,<br />\n{product_name} Support Team<br /><br />\n\n{facebook_link} {twitter_link}\n<br /><br /></td></tr></table></td></tr>\n<tr style="background:grey" align="center">\n          <td style="font-family:arial; font-size:11px; color:#fff; line-height:30px">Copyright &copy; 2016 TALENTEDGE All rights reserved.</td>\n</tr>\n</table>\n</body>\n</html>' WHERE `notification_templates`.`id` = 4;
/******************************* Virendra [4 MAY 2017] END **********************/

/******************************* Anuj [15 MAY 2017] START  [SYNCED ON dev/qa/sliq]**********************/
ALTER TABLE `student_test_questions`     ADD COLUMN `question_marks` SMALLINT(6) DEFAULT '0' NOT NULL AFTER `marks_obtain`;
/******************************* Anuj [15 MAY 2017] START**********************/

/******************************* Virendra [26 MAY 2017] START [SYNCED ON dev/qa/sliq] [Code merged on dated 29-May up to #73 to #79]**********************/
ALTER TABLE `assignments` ADD `show_grades_only` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `allowed_multiple` ;
/******************************* Virendra [26 MAY 2017] END **********************/
/*======================================= All Above query are synced to DEV server and PRODUCTION SERVER [Deployed to production on 9 June 2017]==============================================*/



/*======================================= V1.0.2 START [12-June-2017 (V1.0.1 code commited on 9 june 2017)]=============================================================*/

/******************************* Rohit Roy [31 MAY 2017] START [SYNCED ON dev/sliq]*****************************/
ALTER TABLE `user_login_log` ADD `logout_date` DATETIME NULL DEFAULT NULL AFTER `login_date` ,
ADD `session_id` VARCHAR( 50 ) NULL DEFAULT NULL AFTER `logout_date` ; /*Average Time spent on LMS related changes*/
/******************************* Rohit Roy [31 MAY 2017] END*****************************/

/*======================================= V1.0.2 END [22-June-2017]=============================================================*/


/*======================================= V1.0.4 START [23-June-2017]==========================================================*/

/******************************* ANUJ [28 JUNE 2017] START [SYNCED ON dev/sliq]*****************************/
ALTER TABLE `questions` ADD `updated_by_bulkimport_data` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=>No,1=>Yes' AFTER `modified_on`;

CREATE TABLE IF NOT EXISTS `question_temps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_type_id` int(11) unsigned NOT NULL,
  `batch_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT '0',
  `statement` text CHARACTER SET utf8 NOT NULL,
  `marks` int(11) NOT NULL,
  `difficulty_level_id` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>active, 1=>Inactive',
  `is_practice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>Practice, 1=>Real',
  `is_questionbank` int(11) NOT NULL DEFAULT '0' COMMENT '0=>''No'',1=>''Yes''',
  `is_editor_enabled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>''No'',1=>''Yes''',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>New ques, 1=>Old Ques',
  `question_master_id` int(11) NOT NULL DEFAULT '0' COMMENT 'question id of real table',
  `updated_data` tinyint(4) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `question_type_idx` (`question_type_id`),
  KEY `is_deleted_idx` (`is_deleted`),
  KEY `is_practice_idx` (`is_practice`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `question_option_temps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL,
  `is_correct_option` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=>no, 1=>yes',
  `option_statement` text CHARACTER SET utf8 NOT NULL,
  `explanation` text CHARACTER SET utf8 NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `question_idx` (`question_id`)
) ENGINE=InnoDB;
/******************************* ANUJ [28 JUNE 2017] END *****************************/
/*======================================= V1.0.4 END [30-June-2017]==========================================================*/

/*======================================= V1.0.5 START [1-July-2017]==========================================================*/
/* LMS code merged related changes for V1.6.7 to V1.6.7.1 Patch*/

/******************************* UJJWAL KUMAR START [SYNCED ON dev/sliq]*****************************/
		CREATE TABLE IF NOT EXISTS `lc_quiz_results` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `content_id` int(11) NOT NULL COMMENT 'content_id of live_class',
		  `quiz_id` int(11) NOT NULL COMMENT 'content_id of assesment whose parent_id id is live_class_id',
		  `user_id` int(11) NOT NULL COMMENT 'user_id of student',
		  `attempted` smallint(6) unsigned NOT NULL,
		  `correct` smallint(6) unsigned NOT NULL,
		  `grade` smallint(6) unsigned NOT NULL COMMENT 'in %',
		  `time_taken` time NOT NULL,
		  `created` datetime NOT NULL,
		  `status` tinyint(1) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
/******************************* UJJWAL KUMAR END **********************/

/******************************* ROhit Roy START [SYNCED ON dev/sliq]*****************************/
 CREATE TABLE IF NOT EXISTS `social_network_profile` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `profile_type` tinyint(4) NOT NULL COMMENT '1=>LinkedIn',
  `profile_data` text NOT NULL,
  `last_synced_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/******************************* ROhit Roy END *****************************/

/************************ Virendra Start [SYNCED ON dev/sliq]*******************************************/
ALTER TABLE `batches` CHANGE `class_participation_percentage_in_live_class` `class_participation_percentage_in_live_class` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
CHANGE `class_participation_percentage_in_recorded_class` `class_participation_percentage_in_recorded_class` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0';
/************************ Virendra End *********************************************/

/* LMS code merged related changes for V1.6.7 to V1.6.7.1 Patch END*/
