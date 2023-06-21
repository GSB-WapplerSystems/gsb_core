CREATE TABLE sys_file_metadata
(
	is_accessible tinyint(4) unsigned DEFAULT '0' NOT NULL,
);

CREATE TABLE sys_file_reference
(
	longdesc varchar(1024) DEFAULT '' NOT NULL,
	outline tinyint(4) unsigned DEFAULT '0' NOT NULL,
	allow_download tinyint(4) unsigned DEFAULT '0' NOT NULL,
  is_accessible tinyint(4) unsigned DEFAULT '0' NOT NULL,
);

CREATE TABLE tt_content
(
	tx_header_inside               tinyint(4)       DEFAULT '0' NOT NULL,
	tx_header_style                tinytext,
	header_kicker                  tinytext,
	tx_video_caption               tinytext,
	tx_video_poster_video          tinytext,
	tx_video_video                 tinytext,
	tx_video_videourl              tinytext,
	tx_audio_poster                int(11) unsigned DEFAULT '0' NOT NULL,
	tx_audio_audio                 tinytext,
	tx_audio_audiourl              tinytext,
	tx_stage_switch                tinyint(4)       DEFAULT '0' NOT NULL,
	tx_stage_image                  int(11) unsigned DEFAULT '0' NOT NULL,
	tx_stage_video                 tinytext,
	tx_stage_bgcolor               int(11)          DEFAULT '0' NOT NULL,
	tx_stage_position              tinytext,
	tx_stage_bg                    int(11)          DEFAULT '0' NOT NULL,
	tx_stage_salutation            int(11)          DEFAULT '0' NOT NULL,
	tx_banner_image                int(11) unsigned DEFAULT '0' NOT NULL,
	container_tab_open             int(11) unsigned DEFAULT '1' NOT NULL,
	container_accordion_toggle_all int(11) unsigned DEFAULT '0' NOT NULL,
	container_accordion_toggle     int(11) unsigned DEFAULT '0' NOT NULL,
	container_accordion_open       int(11) unsigned DEFAULT '1' NOT NULL,
	container_headline             tinytext,
	slider                         tinyint(4)       DEFAULT '0' NOT NULL,
	slider_type                    tinytext,
	slider_columns                 tinytext,
	grid_type                      tinytext,
	grid_columns                   tinytext,
	grid_bgimage                   int(11) unsigned DEFAULT '0' NOT NULL,
	grid_parallax                  tinyint(4)       DEFAULT '0' NOT NULL,
	grid_imgbg                     tinyint(4)       DEFAULT '0' NOT NULL,
	grid_bottom_image              tinytext,
	grid_bgcolor                   varchar(10),
	grid_bgfullsize                tinyint(4)       DEFAULT '0' NOT NULL,
	grid_container                 tinyint(4)       DEFAULT '0' NOT NULL,
	grid_light                     tinyint(4)       DEFAULT '0' NOT NULL,
	gallery_file                   int(11) unsigned DEFAULT '0' NOT NULL,
	gallery_bg                     int(11)          DEFAULT '0' NOT NULL,
	gallery_layout                 tinytext,
	gallery_textcolor              int(11)          DEFAULT '0' NOT NULL,
	tx_link                        tinytext,
	tx_link_layout                 tinytext,
	tx_link_text                   tinytext,
	tx_link_position               tinytext
);

CREATE TABLE pages
(
	newsletter                  varchar(255) DEFAULT ''  NOT NULL,
	socialmedia                 varchar(255) DEFAULT ''  NOT NULL,
	breadcrumb                  varchar(255) DEFAULT ''  NOT NULL,
	highlight                   tinyint(1)   DEFAULT '0' NOT NULL,
	teaser_description          text,
	category_title              text
);
