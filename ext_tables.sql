CREATE TABLE sys_file_reference
(
    longdesc varchar(1024) DEFAULT '' NOT NULL
);

CREATE TABLE tt_content
(
    tx_header_inside      tinyint(4) DEFAULT '0' NOT NULL,
    tx_header_style       tinytext,
    header_kicker         tinytext,
    tx_video_caption      tinytext,
    tx_video_poster_image int(11) unsigned DEFAULT '0' NOT NULL,
    tx_video_poster_video tinytext,
    tx_video_mainstage    tinyint(4) DEFAULT '0' NOT NULL,
    tx_video_video        tinytext,
    tx_video_videourl     tinytext,
    tx_audio_poster       int(11) unsigned DEFAULT '0' NOT NULL,
    tx_audio_audio        tinytext,
    tx_audio_audiourl     tinytext,

		tx_gsbbanner_file int(11) unsigned DEFAULT '0' NOT NULL,
		tx_gsbbanner_link tinytext,
		tx_gsbbanner_link_layout tinytext,
		tx_gsbbanner_link_text tinytext,
		tx_gsbbanner_link_background tinytext,
		tx_gsbbanner_link_bold int(11) unsigned DEFAULT '0' NOT NULL,

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
		grid_bottom_image              tinyint(4)       DEFAULT '0' NOT NULL,
		grid_bgcolor                   varchar(10),
		grid_bgfullsize                tinyint(4)       DEFAULT '0' NOT NULL,
		grid_container                 tinyint(4)       DEFAULT '0' NOT NULL,
		grid_light                     tinyint(4)       DEFAULT '0' NOT NULL,
		columns_grid_col_1             tinytext,
		columns_grid_col_2             tinytext,
		columns_grid_col_3             tinytext,
		columns_grid_col_4             tinytext,

		tx_gsbfile_file int(11) unsigned DEFAULT '0' NOT NULL,

		tx_gsbgallery_file int(11) unsigned DEFAULT '0' NOT NULL,
		tx_gsbgallery_gallery_bg int(11) DEFAULT '0' NOT NULL,
		tx_gsbgallery_gallery_layout tinytext,
		tx_gsbgallery_gallery_textcolor int(11) DEFAULT '0' NOT NULL,

		tx_gsbsingleteaser_link             tinytext,
		tx_gsbsingleteaser_twitterlink      tinytext,
		tx_gsbsingleteaser_twittertext      text,
		tx_gsbsingleteaser_twitterhashtag   tinytext,
		tx_gsbsingleteaser_twittervia       tinytext,
		tx_gsbsingleteaser_twitterrelated   tinytext,
		tx_gsbsingleteaser_link_layout      tinytext,
		tx_gsbsingleteaser_link_text        tinytext,
		tx_gsbsingleteaser_link_position    tinytext,
		tx_gsbsingleteaser_event_startdate  varchar(255) default NULL,
		tx_gsbsingleteaser_event_enddate    varchar(255) default NULL,
		tx_gsbsingleteaser_bgimage          int(11) unsigned DEFAULT '0' NOT NULL,
		tx_gsbsingleteaser_place            text,
		tx_gsbsingleteaser_kicker           text,

		tx_gsbstage_file             int(11) unsigned DEFAULT '0' NOT NULL,
		tx_gsbstage_stage_bg         int(11)          DEFAULT '0' NOT NULL,
		tx_gsbstage_stage_position   tinytext,
		tx_gsbstage_stage_textcolor  int(11)          DEFAULT '0' NOT NULL,
		tx_gsbstage_stage_salutation int(11)          DEFAULT '0' NOT NULL,
);

CREATE TABLE pages
(
    newsletter                      varchar(255) DEFAULT '' NOT NULL,
    socialmedia                     varchar(255) DEFAULT '' NOT NULL,
    breadcrumb                      varchar(255) DEFAULT '' NOT NULL,
    event                           varchar(255) DEFAULT '0' NOT NULL,
    toggleEvent                     varchar(255) DEFAULT '' NOT NULL,
    event_location                  varchar(255) default NULL,
    event_category                  varchar(255) default NULL,
    event_startdate                 varchar(255) default NULL,
    event_enddate                   varchar(255) default NULL,
    event_address                   varchar(1024) default NULL,
    event_signup_link               varchar(255) default NULL,
    event_signup_link_label         varchar(255) default NULL,
    event_past                      varchar(255) default NULL,
    highlight                       tinyint(1) DEFAULT '0' NOT NULL,
    teaser_description              text,
    teaser_description_overview     text,
    category_title                  text
);

CREATE TABLE tx_gsbfile_file (
		 uid int(11) NOT NULL auto_increment,
		 pid int(11) DEFAULT '0' NOT NULL,
		 parentid int(11) DEFAULT '0' NOT NULL,
		 parenttable varchar(255) DEFAULT '' NOT NULL,
		 sorting int(11) unsigned DEFAULT '0' NOT NULL,
		 t3ver_oid int(11) unsigned DEFAULT '0' NOT NULL,
		 t3ver_id int(11) DEFAULT '0' NOT NULL,
		 t3ver_wsid int(11) DEFAULT '0' NOT NULL,
		 t3ver_label varchar(255) DEFAULT '' NOT NULL,
		 t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
		 t3ver_stage int(11) DEFAULT '0' NOT NULL,
		 t3ver_count int(11) DEFAULT '0' NOT NULL,
		 t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
		 t3ver_move_id int(11) DEFAULT '0' NOT NULL,
		 tstamp int(11) unsigned DEFAULT '0' NOT NULL,
		 crdate int(11) unsigned DEFAULT '0' NOT NULL,
		 cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
		 deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
		 hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
		 starttime int(11) unsigned DEFAULT '0' NOT NULL,
		 endtime int(11) unsigned DEFAULT '0' NOT NULL,
		 sys_language_uid int(11) DEFAULT '0' NOT NULL,
		 l10n_parent int(11) unsigned DEFAULT '0' NOT NULL,
		 l10n_diffsource mediumblob,
		 PRIMARY KEY (uid),
		 KEY parent (pid),
		 KEY t3ver_oid (t3ver_oid,t3ver_wsid),
		 KEY language (l10n_parent,sys_language_uid)
);
