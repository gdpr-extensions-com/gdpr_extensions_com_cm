CREATE TABLE tx_gdprextensionscomcm_domain_model_cookie (
	category varchar(255) NOT NULL DEFAULT '',
	domain varchar(255) NOT NULL DEFAULT '',
	platform varchar(255) NOT NULL DEFAULT '',
	name varchar(255) NOT NULL DEFAULT '',
	description Text NOT NULL DEFAULT '',
	session varchar(255) NOT NULL DEFAULT '',
    root_pid int(11) NOT NULL DEFAULT '0',
	type varchar(255) NOT NULL DEFAULT '',
	pages_list LONGTEXT NOT NULL DEFAULT '',
	expires varchar(255) NOT NULL DEFAULT '',
);

CREATE TABLE tx_gdprextensionscomcm_domain_model_externalresource (
	url Text NOT NULL DEFAULT '',
   external_resource_list LONGTEXT NOT NULL DEFAULT '',
   root_pid int(11) NOT NULL DEFAULT '0'
);
CREATE TABLE tx_gdprextensionscomcm_domain_model_report (

	root_pid int(11) NOT NULL DEFAULT '0' ,
	report Text NOT NULL DEFAULT '',
);
CREATE TABLE tt_content (
	functional LONGTEXT NOT NULL DEFAULT '',
	non_functional LONGTEXT NOT NULL DEFAULT '',
	statistics LONGTEXT NOT NULL DEFAULT '',
	analytics LONGTEXT NOT NULL DEFAULT '',
	others LONGTEXT NOT NULL DEFAULT '',
	marketing LONGTEXT NOT NULL DEFAULT '',
	consent_header LONGTEXT NOT NULL DEFAULT '',
	consent_header_title varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_gdprextensionscomcm_domain_model_gdprmanager (
	uid int(11) DEFAULT '0' NOT NULL,
	apiconnect_id int(11) NOT NULL DEFAULT '0',
	apiconnect_title varchar(255) NOT NULL DEFAULT '',
	valid_from int(11) NOT NULL DEFAULT '0',
	valid_to int(11) NOT NULL DEFAULT '0',
	create_time varchar(255) NOT NULL DEFAULT '',
	root_pid int(11) NOT NULL DEFAULT '0',
);

CREATE TABLE pages (
	multi_locations int(11) unsigned DEFAULT '0' NOT NULL,
);

CREATE TABLE multilocations (

	dashboard_api_key varchar(255) NOT NULL DEFAULT '',
	location_page_id varchar(255) NOT NULL DEFAULT '',
	api_create_time varchar(255) NOT NULL DEFAULT '',
	pages int(11) unsigned DEFAULT '0'

);
CREATE TABLE gdpr_cookie_consent (
	location_page_id varchar(255) NOT NULL DEFAULT '',
   	icon_url varchar(255) NOT NULL,
	icon_placement VARCHAR(255) DEFAULT '',
   	header_title VARCHAR(255) NOT NULL,
    header_description TEXT NOT NULL,
	background_color VARCHAR(255) NOT NULL,
   	text_color VARCHAR(255) NOT NULL,
	two_click_desc TEXT NOT NULL,
	uploaded_file_name TEXT NOT NULL
);
CREATE TABLE gdpr_cookie_categories (
   	category_title VARCHAR(255) NOT NULL DEFAULT '',
	location_page_id varchar(255) NOT NULL DEFAULT '',
    category_description TEXT NOT NULL DEFAULT ''
);
