-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 04 月 23 日 06:37
-- 服务器版本: 5.0.45
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `mall`
--

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_activity`
--

CREATE TABLE `mallbuilder_activity` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'ID',
  `title` varchar(100) NOT NULL COMMENT '活动名',
  `desc` text NOT NULL COMMENT '描述',
  `ads_code` varchar(100) NOT NULL COMMENT '广告代码',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `end_time` int(10) NOT NULL COMMENT '结束时间',
  `templates` varchar(30) NOT NULL COMMENT '调用模板',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态',
  `displayorder` smallint(6) NOT NULL default '0' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_activity`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_activity_product_list`
--

CREATE TABLE `mallbuilder_activity_product_list` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `activity_id` int(10) NOT NULL COMMENT '活动ID',
  `product_id` int(10) NOT NULL COMMENT '产品ID',
  `product_name` varchar(100) NOT NULL COMMENT '产品名',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `member_name` varchar(30) NOT NULL COMMENT '会员名',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `displayorder` smallint(6) NOT NULL default '0' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动产品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_activity_product_list`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin`
--

CREATE TABLE `mallbuilder_admin` (
  `id` int(3) NOT NULL auto_increment COMMENT 'ID',
  `user` char(30) NOT NULL COMMENT '帐号',
  `name` varchar(50) default NULL COMMENT '用户名',
  `password` char(35) NOT NULL COMMENT '密码',
  `group_id` smallint(5) NOT NULL default '0' COMMENT '会员组',
  `desc` text COMMENT '描述',
  `logonums` int(5) default '0' COMMENT '登录次数',
  `lastlogotime` int(11) default NULL COMMENT '最后登录时间',
  `logoip` varchar(30) default NULL COMMENT '登录IP',
  `province` varchar(60) default NULL COMMENT '省',
  `city` varchar(60) default NULL COMMENT '市',
  `area` varchar(60) default NULL COMMENT '区',
  `type` smallint(1) unsigned default NULL COMMENT '1manager',
  `lang` varchar(10) default NULL COMMENT '语言',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=97 ;

--
-- 导出表中的数据 `mallbuilder_admin`
--

INSERT INTO `mallbuilder_admin` (`id`, `user`, `name`, `password`, `group_id`, `desc`, `logonums`, `lastlogotime`, `logoip`, `province`, `city`, `area`, `type`, `lang`) VALUES
(1, 'admin', NULL, '2cd1ca5806fb1cc08ae5d21561581088', 0, NULL, 2100, 1398217553, '180.173.96.122', NULL, NULL, NULL, 1, 'cn'),
(96, 'test', 'test', '098f6bcd4621d373cade4e832627b4f6', 56, '', 6, 1389772904, '127.0.0.1', '', '', '', 1, 'cn');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin_group`
--

CREATE TABLE `mallbuilder_admin_group` (
  `group_id` smallint(5) unsigned NOT NULL auto_increment COMMENT '组Id',
  `group_name` varchar(60) NOT NULL COMMENT '组名称',
  `group_perms` text NOT NULL COMMENT '组权限',
  `group_desc` varchar(250) NOT NULL COMMENT '组描述',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员用户组表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_admin_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin_menu`
--

CREATE TABLE `mallbuilder_admin_menu` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `displayorder` int(3) NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_admin_menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_admin_operation_log`
--

CREATE TABLE `mallbuilder_admin_operation_log` (
  `id` int(5) NOT NULL auto_increment COMMENT 'ID',
  `user` varchar(20) default NULL COMMENT '管理员帐号',
  `scriptname` varchar(50) default NULL COMMENT '文件名',
  `url` varchar(200) default NULL COMMENT '地址',
  `time` int(11) default NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员操作记录表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_admin_operation_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_advs`
--

CREATE TABLE `mallbuilder_advs` (
  `ID` int(4) NOT NULL auto_increment COMMENT 'ID',
  `width` varchar(10) default NULL COMMENT '宽度',
  `height` varchar(10) default NULL COMMENT '高度',
  `ad_type` tinyint(1) NOT NULL default '1' COMMENT '类型',
  `name` varchar(50) NOT NULL COMMENT '广告位名',
  `group` varchar(50) default NULL COMMENT '组',
  `con` mediumtext COMMENT '描述',
  `date` datetime default NULL COMMENT '创建时间',
  `price` decimal(10,2) default NULL COMMENT '价格',
  `unit` enum('day','week','month') NOT NULL COMMENT '单位',
  `total` tinyint(4) default '0' COMMENT '广告数量',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='广告位表' AUTO_INCREMENT=12 ;

--
-- 导出表中的数据 `mallbuilder_advs`
--

INSERT INTO `mallbuilder_advs` (`ID`, `width`, `height`, `ad_type`, `name`, `group`, `con`, `date`, `price`, `unit`, `total`) VALUES
(1, '787', '327', 0, '首页-1', '首页', '', '2014-04-23 10:21:03', 0.00, 'day', 0),
(2, '787', '161', 0, '首页-2', '首页', '', '2014-04-23 10:21:42', 0.00, 'day', 0),
(3, '193', '110', 0, '首页即时抢购', '首页', '', '2014-04-23 10:23:18', 0.00, 'day', 0),
(4, '226', '470', 0, '首页右边', '首页', '', '2014-04-23 10:24:05', 0.00, 'day', 0),
(5, '1200', '119', 0, '首页-3', '首页', '', '2014-04-23 10:24:34', 0.00, 'day', 0),
(6, '220', '465', 0, '首页左边产品分类', '首页', '', '2014-04-23 10:25:54', 0.00, 'day', 0),
(7, '500', '320', 0, '登录', '登录', '', '2014-04-23 10:26:40', 0.00, 'day', 0),
(8, '186', '97', 0, '热门活动', '买家中心', '', '2014-04-23 10:27:29', 0.00, 'day', 0),
(9, '186', '200', 0, '商品推荐', '买家中心', '', '2014-04-23 10:28:03', 0.00, 'day', 0),
(10, '759', '366', 0, '积分商城', '积分商城', '', '2014-04-23 10:31:28', 0.00, 'day', 0),
(11, '240', '312', 0, '新品首发', '积分商城', '', '2014-04-23 10:32:07', 0.00, 'day', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_advs_con`
--

CREATE TABLE `mallbuilder_advs_con` (
  `ID` int(4) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) default NULL COMMENT '会员ID',
  `group_id` int(5) default NULL COMMENT '广告位ID',
  `name` varchar(50) NOT NULL COMMENT '广告名',
  `type` varchar(20) default NULL COMMENT '类型',
  `url` varchar(200) default NULL COMMENT '地址',
  `con` mediumtext COMMENT '内容',
  `picName` varchar(255) NOT NULL COMMENT '图片',
  `isopen` int(1) default '0' COMMENT '是否开启',
  `ctime` int(11) default NULL COMMENT '创建时间',
  `province` varchar(50) default NULL COMMENT '省',
  `city` varchar(50) default NULL COMMENT '市',
  `area` varchar(50) default NULL COMMENT '区',
  `width` char(4) default NULL COMMENT '宽度',
  `height` char(4) default NULL COMMENT '高度',
  `catid` int(8) default NULL COMMENT '类别ID',
  `unit` enum('day','week','month') default NULL COMMENT '单位',
  `show_time` tinyint(4) default '0' COMMENT '展出时间',
  `statu` tinyint(1) default '0' COMMENT '0:待支付,1:购买成功,',
  `shownum` int(11) unsigned default '1' COMMENT '展示次数',
  `stime` int(10) unsigned default NULL COMMENT '开始时间',
  `etime` int(10) unsigned default NULL COMMENT '结束时间',
  `sort_num` tinyint(3) unsigned default '0' COMMENT '排序',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_advs_con`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_album`
--

CREATE TABLE `mallbuilder_album` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `catid` int(11) unsigned default NULL COMMENT '类别ID',
  `userid` int(8) default NULL COMMENT '会员ID',
  `zname` varchar(50) default NULL COMMENT '名称',
  `con` text COMMENT '描述',
  `user` varchar(30) default NULL COMMENT '会员名',
  `time` int(11) default NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='相册表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_album`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_announcement`
--

CREATE TABLE `mallbuilder_announcement` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `url` varchar(100) default NULL COMMENT '跳转链接',
  `create_time` int(10) unsigned NOT NULL COMMENT '发布时间',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态 0 为关闭 1为开启',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_announcement`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_auditing`
--

CREATE TABLE `mallbuilder_auditing` (
  `itemid` int(11) NOT NULL auto_increment,
  `itemtype` varchar(10) NOT NULL,
  `argument` varchar(100) NOT NULL,
  `uid` varchar(30) NOT NULL,
  `uptime` int(11) NOT NULL,
  `statu` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_auditing`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_brand`
--

CREATE TABLE `mallbuilder_brand` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(80) NOT NULL COMMENT '品牌名',
  `char_index` char(1) NOT NULL COMMENT '首字母',
  `catid` int(11) NOT NULL COMMENT '分类ID',
  `logo` varchar(150) NOT NULL COMMENT 'LOGO',
  `displayorder` smallint(6) NOT NULL default '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL default '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `hits` int(11) NOT NULL default '0' COMMENT '点击数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_brand`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_brand_cat`
--

CREATE TABLE `mallbuilder_brand_cat` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `parent_id` int(11) NOT NULL default '0' COMMENT '父类ID',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  `catname` varchar(100) NOT NULL COMMENT '分类名',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_brand_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_comment`
--

CREATE TABLE `mallbuilder_comment` (
  `id` int(11) NOT NULL auto_increment,
  `conid` int(10) default NULL COMMENT '被评论内容的ID',
  `fromuid` int(10) default NULL COMMENT '评论者会员ID',
  `fromname` varchar(50) default NULL,
  `ctype` int(1) default NULL COMMENT '被评论内容的类型',
  `rank` tinyint(1) unsigned NOT NULL default '1',
  `content` text COMMENT '评论的内容',
  `uptime` int(11) default NULL COMMENT '评论时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_contags`
--

CREATE TABLE `mallbuilder_contags` (
  `tagname` char(20) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `type` tinyint(4) default NULL,
  KEY `tid` (`tid`),
  KEY `tagname_2` (`tagname`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_contags`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_cron`
--

CREATE TABLE `mallbuilder_cron` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(50) default NULL COMMENT '名称',
  `script` varchar(50) default NULL COMMENT '脚本',
  `lasttransact` int(10) default NULL COMMENT '上次执行时间',
  `nexttransact` int(10) default NULL COMMENT '下次执行时间',
  `week` varchar(12) default '-1' COMMENT '每周',
  `day` varchar(2) default '-1' COMMENT '每周',
  `hours` varchar(2) default '00' COMMENT '小时',
  `minutes` varchar(2) default '00' COMMENT '分钟',
  `active` tinyint(1) default '0' COMMENT '是否启用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='计划任务表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_cron`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_custom_cat`
--

CREATE TABLE `mallbuilder_custom_cat` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `pid` int(11) unsigned default '0' COMMENT '父类ID',
  `sys_cat` int(11) default NULL COMMENT '类别ID',
  `userid` int(8) default NULL COMMENT '会员ID',
  `type` tinyint(4) default NULL COMMENT '类型',
  `name` varchar(60) default NULL COMMENT '名称',
  `des` char(200) default NULL COMMENT '描述',
  `tj` tinyint(1) default '0' COMMENT '推荐',
  `nums` int(11) default '0' COMMENT '数量',
  `pic` varchar(100) default NULL COMMENT '图片',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员自定义类别表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_custom_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_custom_service`
--

CREATE TABLE `mallbuilder_custom_service` (
  `id` int(10) NOT NULL auto_increment COMMENT '客服id',
  `uid` int(10) NOT NULL COMMENT '会员id',
  `name` varchar(20) NOT NULL COMMENT '客服名称',
  `tool` tinyint(1) NOT NULL COMMENT '客服工具',
  `number` varchar(30) NOT NULL COMMENT '客服账号',
  `type` tinyint(1) NOT NULL COMMENT '客服类型 0-售前客服 1-售后客服',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_custom_service`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_delivery_address`
--

CREATE TABLE `mallbuilder_delivery_address` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) unsigned NOT NULL COMMENT '会员ID',
  `name` varchar(40) NOT NULL COMMENT '联系人',
  `provinceid` int(11) NOT NULL COMMENT '省ID',
  `cityid` int(11) NOT NULL COMMENT '市ID',
  `areaid` int(11) NOT NULL COMMENT '区ID',
  `area` varchar(255) NOT NULL COMMENT '省市区',
  `address` varchar(50) NOT NULL COMMENT '地址',
  `zip` varchar(20) default NULL COMMENT '邮编',
  `tel` varchar(30) default NULL COMMENT '电话',
  `mobile` varchar(20) default NULL COMMENT '手机',
  `default` tinyint(1) default '0' COMMENT '是否默认',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收货地址表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_delivery_address`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_district`
--

CREATE TABLE `mallbuilder_district` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '地区名',
  `pid` mediumint(8) unsigned NOT NULL default '0' COMMENT '父类ID',
  `sorting` smallint(6) NOT NULL default '0' COMMENT '排序',
  PRIMARY KEY  (`id`),
  KEY `upid` (`pid`,`sorting`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地区表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_district`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_fast_mail`
--

CREATE TABLE `mallbuilder_fast_mail` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `company` varchar(30) default NULL COMMENT '快递名',
  `introduction` text COMMENT '描述',
  `url` varchar(30) default NULL COMMENT '地址',
  `logo` varchar(30) default NULL COMMENT 'LOGO',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='快递表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_fast_mail`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_feed`
--

CREATE TABLE `mallbuilder_feed` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(10) default NULL COMMENT '会员ID',
  `company` varchar(100) default NULL COMMENT '公司名称',
  `contact` varchar(30) default NULL COMMENT '联系人',
  `email` varchar(30) default NULL COMMENT '邮箱',
  `mes` text COMMENT '内容',
  `iflook` char(2) default NULL COMMENT '是否观看',
  `province` varchar(30) default NULL COMMENT '省',
  `city` varchar(30) default NULL COMMENT '市',
  `tell` varchar(30) default NULL COMMENT '电话',
  `addr` varchar(100) default NULL COMMENT '地址',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='反馈表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_feed`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_filter_keyword`
--

CREATE TABLE `mallbuilder_filter_keyword` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `keyword` varchar(50) default NULL COMMENT '关键字',
  `replace` varchar(50) default NULL COMMENT '替代',
  `statu` tinyint(1) default '1' COMMENT '状态',
  `time` int(11) unsigned default NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='过滤关键词表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_filter_keyword`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_logistics_temp`
--

CREATE TABLE `mallbuilder_logistics_temp` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) default NULL COMMENT '会员ID',
  `title` varchar(50) default NULL COMMENT '模板名',
  `price_type` varchar(50) default NULL COMMENT '按件数  按重量  按体积 ',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义物流模板表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_logistics_temp`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_logistics_temp_con`
--

CREATE TABLE `mallbuilder_logistics_temp_con` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `temp_id` int(11) default NULL COMMENT '自定义物流模板ID',
  `logistics_type` varchar(50) default NULL COMMENT 'EMS,平邮,快递',
  `default_num` smallint(3) default NULL COMMENT '默认数量',
  `default_price` float(5,0) default NULL COMMENT '默认运费',
  `add_num` smallint(3) default NULL COMMENT '增加数量',
  `add_price` float(5,0) default NULL COMMENT '增加运费',
  `define_citys` text COMMENT '默认城市',
  PRIMARY KEY  (`id`),
  KEY `temp_id` (`temp_id`,`logistics_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义物流模板内容表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_logistics_temp_con`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_mail_mod`
--

CREATE TABLE `mallbuilder_mail_mod` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `subject` varchar(100) default NULL COMMENT '主题',
  `title` varchar(100) default NULL COMMENT '标题',
  `message` text COMMENT '内容',
  `flag` varchar(30) default NULL COMMENT '标示',
  `type` tinyint(1) NOT NULL default '0' COMMENT '类型',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邮件模板' AUTO_INCREMENT=3 ;

--
-- 导出表中的数据 `mallbuilder_mail_mod`
--

INSERT INTO `mallbuilder_mail_mod` (`id`, `subject`, `title`, `message`, `flag`, `type`) VALUES
(1, '[给用户] 邮箱验证', '请激活您在[weburl_name]账户 ', '<table width="650" style="background-color:#F3F3F2;font-size:12px;margin:auto;" cellpadding="0" cellspacing="0">\r\n<tr>\r\n<td style="padding-bottom:15px;">\r\n	<div style="height:30px;line-height:30px;text-align:center;color:#8c8b8b; font-family:Arial, Helvetica, sans-serif;">为了您能够正常收到来自[weburl_name]邮件，请将<a href="mailto:[weburl_email]" target="_blank">[weburl_email]</a>添加进您的通讯录</div>\r\n   \r\n    <table style="width:630px;background-color:#FFF;margin:0 10px;" cellpadding="0" cellspacing="0">\r\n    	<tr><td valign="middle" style="height:60px;padding-left:25px;">[logo]</td></tr>\r\n    </table>\r\n   \r\n    <table width="650" height="33" style="background-color:#CC0B0B;color:#FFF;" cellpadding="0" cellspacing="0">\r\n    	<td style="width:28px;height:33px;"><img width="28" height="33" border="0" src="[weburl_url]/image/default/message/l.jpg"></td>\r\n        <td align="center" style="line-height:33px;font-size:14px;font-family:Microsoft YaHei;font-weight:bold;">[menu]</td>\r\n        <td style="width:28px;height:33px;"><img width="28" height="33" border="0" src="[weburl_url]/image/default/message/r.jpg"></td>\r\n    </table>\r\n    \r\n    <table width="630" style="margin:0 auto 0;overflow:hidden;background-color:#FFF;font-family:Verdana;" cellpadding="0" cellspacing="0">\r\n    	<tr>\r\n        	<td style="font-weight:bold;font-size:14px;padding:15px 30px 0;color:#5b5b5b;">尊敬的 <span>[member_name]</span> 您好：</td>\r\n       </tr>\r\n       <tr>\r\n            <td style="padding:5px 30px 10px;color:#5B5B5B;font-size: 12px;line-height: 25px;">\r\n            您于 [time] &nbsp;申请验证邮箱。<br />\r\n            将链接复制到浏览器地址栏访问:<br>[link]<br />\r\n            若您没有申请过验证邮箱 ，请发邮件至：<a target="_blank" href="mailto:[weburl_email]">[weburl_email]</a> 或致电：[weburl_tel] 。\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style="padding:15px 30px 10px;color:#5B5B5B;font-size:12px;line-height:22px;border-top:1px solid #E8E8E8;font-family:Verdana;">\r\n            您之所以收到这封邮件，是因为您曾经注册成为[weburl_name]的用户。<br>\r\n            本邮件由[weburl_name]系统自动发出，请勿直接回复！<br>\r\n            在购物中遇到任何问题，请点击 <a href="[weburl_url]/help.php" target="_blank">帮助中心</a>。<br>\r\n            如果您有任何疑问或建议，请 <a target="_blank" href="[weburl_url]/aboutus.php?msg=1">联系我们</a>。<br>\r\n            [weburl_desc]\r\n            </td>\r\n        </tr>\r\n    	<tr>\r\n            <td valign="middle" align="center" style="font-size:12px;padding:10px 0 20px;color:#5B5B5B;">Copyright © 2004-2014 [weburl_name] 版权所有</td>\r\n        </tr>\r\n    </table>\r\n</td>\r\n</tr>\r\n</table>', 'active', 0),
(2, '[给用户] 欢迎邮件', '感谢您注册[weburl_name]', '<table width="650" style="background-color:#F3F3F2;font-size:12px;margin:auto;" cellpadding="0" cellspacing="0">\r\n<tr>\r\n<td style="padding-bottom:15px;">\r\n	<div style="height:30px;line-height:30px;text-align:center;color:#8c8b8b; font-family:Arial, Helvetica, sans-serif;">为了您能够正常收到来自[weburl_name]邮件，请将<a href="mailto:[weburl_email]" target="_blank">[weburl_email]</a>添加进您的通讯录</div>\r\n   \r\n    <table style="width:630px;background-color:#FFF;margin:0 10px;" cellpadding="0" cellspacing="0">\r\n    	<tr><td valign="middle" style="height:60px;padding-left:25px;">[logo]</td></tr>\r\n    </table>\r\n   \r\n    <table width="650" height="33" style="background-color:#CC0B0B;color:#FFF;" cellpadding="0" cellspacing="0">\r\n    	<td style="width:28px;height:33px;"><img width="28" height="33" border="0" src="[weburl_url]/image/default/message/l.jpg"></td>\r\n        <td align="center" style="line-height:33px;font-size:14px;font-family:Microsoft YaHei;font-weight:bold;">[menu]</td>\r\n        <td style="width:28px;height:33px;"><img width="28" height="33" border="0" src="[weburl_url]/image/default/message/r.jpg"></td>\r\n    </table>\r\n    \r\n    <table width="630" style="margin:0 auto 0;overflow:hidden;background-color:#FFF;font-family:Verdana;" cellpadding="0" cellspacing="0">\r\n        <tr>\r\n        	<td  style="font-weight:bold;font-size:14px;padding:15px 30px 0;color:#5b5b5b;">尊敬的 <span>[member_name]</span> 您好：</td>\r\n        </tr>\r\n        <tr>\r\n            <td align="center" style="padding:20px 30px 15px;font-size:18px;font-weight:bold; color:#005aa0; line-height:35px;">感谢您注册[weburl_name]<br />我们将为您提供最贴心的服务，祝您购物愉快！</td>\r\n        </tr>\r\n        <tr>\r\n            <td style="border-top:1px solid #E8E8E8;font-size:12px;color:#5b5b5b;height:30px;padding:20px 30px 0;">您的用户名：[member_name]&nbsp;&nbsp;&nbsp;&nbsp;您的注册邮箱：[member_email]</td>\r\n        </tr>\r\n        <tr>\r\n        	<td style="font-size:12px;color:#5b5b5b;line-height:30px;height:30px;padding:10px 30px 10px;">邮件中包含您的个人信息，建议您保管好本邮件！如您登陆时<a target="_blank" href="[weburl_url]/lostpass.php">忘记密码</a>。</td>\r\n        </tr>\r\n        <tr>\r\n            <td style="padding:15px 30px 10px;color:#5B5B5B;font-size:12px;line-height:22px;border-top:1px solid #E8E8E8;font-family:Verdana;">\r\n            您之所以收到这封邮件，是因为您曾经注册成为[weburl_name]的用户。<br>\r\n            本邮件由[weburl_name]系统自动发出，请勿直接回复！<br>\r\n            在购物中遇到任何问题，请点击 <a href="[weburl_url]/help.php" target="_blank">帮助中心</a>。<br>\r\n            如果您有任何疑问或建议，请 <a target="_blank" href="[weburl_url]/aboutus.php?msg=1">联系我们</a>。<br>\r\n            [weburl_desc]\r\n            </td>\r\n        </tr>\r\n    	<tr>\r\n            <td valign="middle" align="center" style="font-size:12px;padding:10px 0 20px;color:#5B5B5B;">Copyright © 2004-2014 [weburl_name] 版权所有</td>\r\n        </tr>\r\n    </table>\r\n</td>\r\n</tr>\r\n</table>', 'register', 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_mail_record`
--

CREATE TABLE `mallbuilder_mail_record` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `sendmailname` varchar(20) default NULL COMMENT '发送者',
  `sendtime` datetime default NULL COMMENT '发送时间',
  `sendmailrecord` text COMMENT '发送记录',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件群发日志表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_mail_record`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_member`
--

CREATE TABLE `mallbuilder_member` (
  `userid` int(8) NOT NULL auto_increment COMMENT 'ID',
  `number` varchar(10) default '0',
  `pid` int(11) default NULL COMMENT '父类ID',
  `user` char(20) default NULL COMMENT '会员名',
  `password` char(32) default NULL COMMENT '密码',
  `rand` varchar(5) default NULL COMMENT '排行',
  `name` varchar(30) default NULL COMMENT '真实名字',
  `sex` tinyint(1) default NULL COMMENT '性别',
  `mobile` varchar(30) default NULL COMMENT '手机',
  `email` varchar(50) default NULL COMMENT '邮箱',
  `qq` varchar(50) default NULL COMMENT 'QQ',
  `msn` varchar(50) default NULL COMMENT 'MSN',
  `skype` varchar(50) default NULL COMMENT 'SKYPE',
  `provinceid` int(11) default NULL COMMENT '省ID',
  `cityid` int(11) default NULL COMMENT '市ID',
  `areaid` int(11) default NULL COMMENT '区ID',
  `area` varchar(255) default NULL COMMENT '省市区',
  `logo` varchar(120) default 'image/default/avatar.png' COMMENT 'LOGO',
  `ip` char(15) NOT NULL COMMENT 'IP',
  `point` int(10) default NULL COMMENT '积分',
  `statu` tinyint(1) default NULL COMMENT '状态',
  `regtime` datetime default NULL COMMENT '注册时间',
  `lastLoginTime` int(10) default NULL COMMENT '最后登录时间',
  `invite` varchar(50) default NULL COMMENT '邀请者',
  `sellerpoints` int(10) NOT NULL default '0' COMMENT '卖家信用',
  `buyerpoints` int(10) NOT NULL default '0' COMMENT '买家信用',
  `email_verify` tinyint(1) default '0' COMMENT '邮箱验证',
  `mobile_verify` tinyint(1) default '0' COMMENT '手机验证',
  PRIMARY KEY  (`userid`),
  KEY `user` (`user`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_member_count`
--

CREATE TABLE `mallbuilder_member_count` (
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `blog` int(11) default '0' COMMENT '微博数量',
  `friend` int(11) default '0' COMMENT '好友数量',
  `fan` int(11) default '0' COMMENT '粉丝数量',
  `points` int(11) default '0' COMMENT '积分',
  PRIMARY KEY  (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员信息记录表';

--
-- 导出表中的数据 `mallbuilder_member_count`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_message`
--

CREATE TABLE `mallbuilder_message` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `touserid` int(8) default NULL COMMENT '接收者ID',
  `fromuserid` int(8) default NULL COMMENT '发送者ID',
  `fromInfo` varchar(250) default '0' COMMENT '发送信息',
  `msgtype` tinyint(1) default '1' COMMENT '类型',
  `sub` varchar(50) default NULL COMMENT '主题',
  `con` text COMMENT '内容',
  `iflook` varchar(10) default NULL COMMENT '是否查看',
  `date` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '发送时间',
  `contype` tinyint(4) default NULL,
  `tid` varchar(50) default NULL,
  `receive_type` varchar(200) default NULL,
  `reply_by` int(11) default NULL COMMENT '回复',
  `attachments` varchar(50) default NULL COMMENT '附件',
  `is_save` tinyint(1) default '0' COMMENT '是否保存',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_message`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_nav_menu`
--

CREATE TABLE `mallbuilder_nav_menu` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `sort` int(2) NOT NULL COMMENT '排序',
  `menu_name` varchar(20) NOT NULL COMMENT '导航名称',
  `link_addr` varchar(100) default NULL COMMENT '链接地址',
  `type` int(1) NOT NULL COMMENT '类型',
  `statu` int(1) NOT NULL COMMENT '状态',
  `partent_menu_id` int(20) NOT NULL COMMENT '父类ID',
  `selected_flag` varchar(20) default NULL COMMENT '选中标志',
  `lang` varchar(5) default NULL COMMENT '语',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='导航栏配置表' AUTO_INCREMENT=5 ;

--
-- 导出表中的数据 `mallbuilder_nav_menu`
--

INSERT INTO `mallbuilder_nav_menu` (`id`, `sort`, `menu_name`, `link_addr`, `type`, `statu`, `partent_menu_id`, `selected_flag`, `lang`) VALUES
(1, 0, '首页', '', 2, 1, 0, 'index', 'cn'),
(2, 0, '品牌', '?m=brand', 2, 1, 0, 'brand', 'cn'),
(3, 0, '团购', '?m=tg', 2, 1, 0, 'tg', 'cn'),
(4, 0, '积分商城', '?m=points', 2, 1, 0, 'points', 'cn');

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_page_rec`
--

CREATE TABLE `mallbuilder_page_rec` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `totalurl` int(10) default NULL COMMENT '前一天url总数',
  `mostpopularurl` varchar(100) default NULL COMMENT '最受欢迎 ',
  `pageviews` int(10) default NULL COMMENT '前一天的PV数',
  `totalip` int(10) default '0' COMMENT '前一天ip总数',
  `visitusernum` int(10) default '0' COMMENT '前一天上线的会员数',
  `reguser` int(10) default '0' COMMENT '前一天新注册会员数',
  `pronum` int(10) default '0' COMMENT '前一天发布产品数',
  `newsnum` int(10) default '0' COMMENT '前一天发布资讯数',
  `exhibnum` int(10) default '0' COMMENT '前一天发布展会数',
  `time` datetime default NULL COMMENT '时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站历史流量记录表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_page_rec`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_page_view`
--

CREATE TABLE `mallbuilder_page_view` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `url` varchar(200) default NULL COMMENT '地址',
  `ip` char(20) default NULL COMMENT 'ip',
  `time` datetime default NULL COMMENT '时间',
  `username` char(20) default NULL COMMENT '会员',
  `fileName` char(30) default NULL COMMENT '文件名',
  PRIMARY KEY  (`id`),
  KEY `ip` (`ip`),
  KEY `username` (`username`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台统计表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_page_view`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_banks`
--

CREATE TABLE `mallbuilder_payment_banks` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `pay_uid` int(8) NOT NULL COMMENT '会员支付ID',
  `bank` varchar(255) NOT NULL COMMENT '银行',
  `bank_addr` varchar(200) default NULL COMMENT '开户行',
  `accounts` varchar(255) NOT NULL COMMENT '帐号',
  `active` tinyint(1) NOT NULL default '0' COMMENT '是否有效',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `censor` varchar(50) default NULL COMMENT '检查员',
  `check_time` int(11) default NULL COMMENT '检查时间',
  `testing_cash` decimal(10,2) default NULL COMMENT '现金',
  `master` varchar(225) default NULL COMMENT '管理员',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='银行帐号信息' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_banks`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_card`
--

CREATE TABLE `mallbuilder_payment_card` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `card_num` varchar(30) NOT NULL COMMENT '卡号',
  `total_price` int(11) NOT NULL COMMENT '面额',
  `give_price` float(10,2) default '0.00' COMMENT '赠送',
  `password` varchar(30) NOT NULL COMMENT '密码',
  `statu` tinyint(4) NOT NULL COMMENT '状态',
  `use_name` varchar(20) default NULL COMMENT '使用者',
  `creat_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `stime` int(10) unsigned default NULL COMMENT '有效开始时间',
  `etime` int(10) unsigned default NULL COMMENT '到期时间',
  `pic` varchar(80) default NULL COMMENT '图片',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值卡表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_cashflow`
--

CREATE TABLE `mallbuilder_payment_cashflow` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `pay_uid` int(11) default NULL COMMENT '会员支付ID',
  `member_name` varchar(50) default NULL COMMENT '会员名',
  `buyer_email` varchar(50) default NULL COMMENT '买家账号',
  `seller_email` varchar(50) default NULL COMMENT '卖家账号',
  `price` decimal(10,2) default NULL COMMENT '金钱',
  `flow_id` varchar(50) default NULL COMMENT '流水账号',
  `order_id` varchar(15) default NULL COMMENT '外部订单号',
  `note` varchar(255) default NULL COMMENT '注解',
  `censor` varchar(50) default NULL COMMENT '管理员',
  `time` int(11) unsigned default NULL COMMENT '时间',
  `statu` tinyint(1) default NULL COMMENT '0取消,1待处理,2已付款,3.发货中,4.成功,5.退货中,6.退货成功',
  `return_url` varchar(200) default NULL COMMENT '返回地址',
  `notify_url` varchar(200) default NULL COMMENT '通知地址',
  `extra_param` varchar(100) default NULL COMMENT '额外参数',
  `type` tinyint(1) unsigned default NULL COMMENT '1直接到账 2担保接口',
  `mold` tinyint(2) default '0' COMMENT '类型',
  `display` tinyint(1) unsigned default '1' COMMENT '是否显示',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金明细表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_cashflow`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_cashpickup`
--

CREATE TABLE `mallbuilder_payment_cashpickup` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `pay_uid` int(8) NOT NULL COMMENT '会员支付ID',
  `cashflowid` varchar(50) default NULL COMMENT '流水账号ID',
  `amount` decimal(10,2) NOT NULL COMMENT '总数',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `censor` varchar(50) default NULL COMMENT '管理员',
  `check_time` int(11) default NULL COMMENT '操作时间',
  `is_succeed` tinyint(2) default '0' COMMENT '是否成功',
  `bankflow` varchar(50) default NULL COMMENT '银行流水账号',
  `con` text COMMENT '描述',
  `bank` varchar(50) default NULL COMMENT '银行',
  `cardno` varchar(32) default NULL,
  `cardname` varchar(50) default NULL,
  `supportTime` int(6) default '0',
  `fee` float(10,2) default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现申请表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_cashpickup`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_member`
--

CREATE TABLE `mallbuilder_payment_member` (
  `pay_id` int(11) NOT NULL auto_increment,
  `pay_email` varchar(100) default NULL,
  `pay_mobile` varchar(30) default NULL,
  `login_pass` varchar(32) default NULL,
  `pay_pass` varchar(32) default NULL,
  `real_name` varchar(30) default NULL,
  `identity_card` varchar(30) default NULL,
  `identity_pic` varchar(255) default NULL,
  `profession` varchar(20) default NULL,
  `logo` varchar(100) default NULL,
  `userid` int(11) unsigned default NULL,
  `cash` decimal(10,2) default '0.00',
  `unreachable` decimal(10,2) default '0.00',
  `email_verify` enum('true','false') default 'false',
  `mobile_verify` enum('true','false') default 'false',
  `identity_verify` enum('true','false') default 'false',
  `question` varchar(255) default NULL,
  `answer` varchar(255) default NULL,
  `regtime` int(10) default NULL,
  `lastLoginTime` int(10) default NULL,
  PRIMARY KEY  (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付会员表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_service_fee`
--

CREATE TABLE `mallbuilder_payment_service_fee` (
  `id` int(6) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `fee_rates` float(12,2) default '0.00',
  `fee_min` int(2) default '0',
  `fee_max` int(2) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_payment_service_fee`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_payment_type`
--

CREATE TABLE `mallbuilder_payment_type` (
  `payment_id` tinyint(3) NOT NULL auto_increment COMMENT 'ID',
  `payment_type` varchar(20) default NULL COMMENT '类型',
  `payment_name` varchar(100) default NULL COMMENT '名称',
  `payment_commission` varchar(8) default '0' COMMENT '手续费',
  `payment_desc` text COMMENT '描述',
  `payment_config` text COMMENT '配置',
  `active` tinyint(1) default '0' COMMENT '是否启用',
  `nums` tinyint(3) default '0' COMMENT '排序',
  PRIMARY KEY  (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付方式表' AUTO_INCREMENT=6 ;

--
-- 导出表中的数据 `mallbuilder_payment_type`
--

INSERT INTO `mallbuilder_payment_type` (`payment_id`, `payment_type`, `payment_name`, `payment_commission`, `payment_desc`, `payment_config`, `active`, `nums`) VALUES
(1, 'account', '预存款支付', '', '预存款支付', '', 1, 0),
(2, 'alipay', '支付宝', '', '支付宝网站(www.alipay.com) 是国内先进的网上支付平台。需与支付宝公司签约方可使用。', 'a:3:{i:0;a:3:{s:4:"name";s:12:"seller_email";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:1;a:3:{s:4:"name";s:3:"key";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:2;a:3:{s:4:"name";s:7:"partner";s:4:"type";s:4:"text";s:5:"value";s:0:"";}}', 1, 0),
(3, 'cards', '充值卡', '', '充值卡', '', 1, 0),
(4, 'cbp', '网银在线', '', '网银在线与中国工商银行、招商银行、中国建设银行、农业银行、民生银行等数十家金融机构达成协议。全面支持全国19家银行的信用卡及借记卡实现网上支付。网址:http://www.chinabank.com.cn', 'a:2:{i:0;a:3:{s:4:"name";s:11:"cbp_account";s:4:"type";s:4:"text";s:5:"value";s:4:"1001";}i:1;a:3:{s:4:"name";s:7:"cbp_key";s:4:"type";s:4:"text";s:5:"value";s:4:"test";}}', 1, 0),
(5, 'tenpay', '财付通', '', '', 'a:3:{i:0;a:3:{s:4:"name";s:14:"tenpay_account";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:1;a:3:{s:4:"name";s:10:"tenpay_key";s:4:"type";s:4:"text";s:5:"value";s:0:"";}i:2;a:3:{s:4:"name";s:16:"tenpay_magic_key";s:4:"type";s:4:"text";s:5:"value";s:0:"";}}', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points`
--

CREATE TABLE `mallbuilder_points` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `points` varchar(200) NOT NULL COMMENT '积分',
  `img` varchar(20) NOT NULL COMMENT '图片',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='信誉积分表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_points`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points_cat`
--

CREATE TABLE `mallbuilder_points_cat` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `catname` varchar(30) NOT NULL COMMENT '分类名',
  `parent_id` int(6) default '0' COMMENT '父类ID',
  `displayorder` smallint(8) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼品分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_points_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points_goods`
--

CREATE TABLE `mallbuilder_points_goods` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '产品名称',
  `catid` int(6) default '0' COMMENT '分类ID',
  `price` decimal(10,2) NOT NULL default '0.00' COMMENT '产品原价',
  `points` int(11) NOT NULL COMMENT '兑换所需积分',
  `pic` varchar(100) NOT NULL COMMENT 'Logo',
  `sku` varchar(50) NOT NULL COMMENT '货号',
  `stock` int(11) NOT NULL default '0' COMMENT '库存数',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `content` text COMMENT '详细内容',
  `sell_amount` int(11) NOT NULL default '0' COMMENT '售出数量',
  `hits` int(11) NOT NULL default '0' COMMENT '浏览次数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分商品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_points_goods`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points_log`
--

CREATE TABLE `mallbuilder_points_log` (
  `id` int(11) NOT NULL auto_increment COMMENT '积分日志编号',
  `member_id` int(11) NOT NULL COMMENT '会员编号',
  `member_name` varchar(100) NOT NULL COMMENT '会员名称',
  `points` int(11) NOT NULL default '0' COMMENT '积分数负数表示扣除',
  `type` tinyint(1) default '0',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `desc` varchar(100) NOT NULL COMMENT '操作描述',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员积分日志表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_points_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_points_order`
--

CREATE TABLE `mallbuilder_points_order` (
  `id` int(11) NOT NULL auto_increment COMMENT '兑换订单编号',
  `order_id` varchar(100) NOT NULL COMMENT '兑换订单编号',
  `buyer_id` int(11) NOT NULL COMMENT '兑换会员id',
  `buyer_name` varchar(50) NOT NULL COMMENT '兑换会员姓名',
  `product_id` int(6) default NULL COMMENT '产品ID',
  `product_name` varchar(255) default NULL COMMENT '产品名',
  `pic` varchar(255) default NULL COMMENT '产品图片',
  `contact` varchar(30) default NULL COMMENT '联系人',
  `address` varchar(200) default NULL COMMENT '地址',
  `tel` varchar(20) default NULL COMMENT '电话',
  `create_time` int(11) NOT NULL COMMENT '兑换订单生成时间',
  `shipping_time` int(11) default NULL COMMENT '发货时间',
  `finnshed_time` int(11) default NULL COMMENT '订单完成时间',
  `allpoint` int(11) NOT NULL default '0' COMMENT '兑换总积分',
  `status` int(11) NOT NULL default '10' COMMENT '订单状态：10(默认):待确定;20:已发货;30已完成;0已取消',
  `shipping_name` varchar(50) default NULL COMMENT '发货人',
  `shipping_address` varchar(255) default NULL COMMENT '发货地址',
  `shipping_tel` varchar(20) default NULL COMMENT '联系电话',
  `shipping_company` varchar(50) default NULL COMMENT '快递名',
  `shipping_code` varchar(50) default NULL COMMENT '快递单号',
  `admin_remark` varchar(255) default NULL COMMENT '备注',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='兑换订单表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_points_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_products`
--

CREATE TABLE `mallbuilder_products` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(6) default NULL COMMENT '会员ID',
  `user` varchar(30) default NULL COMMENT '会员名',
  `catid` int(6) default NULL COMMENT '分类ID',
  `ptype` int(1) default '0' COMMENT '类型 全新|二手|闲置|其它',
  `pname` varchar(100) default NULL COMMENT '产品类型 零售|小额批发|大量批发',
  `promotion_tips` varchar(255) default NULL COMMENT '产品名',
  `keywords` varchar(100) default NULL COMMENT '关键字',
  `brand` varchar(60) default NULL COMMENT '品牌',
  `market_price` float(10,2) default NULL COMMENT '市场价',
  `price` float(10,2) default '0.00' COMMENT '价格',
  `amount` int(6) default '1' COMMENT '库存',
  `sell_amount` int(5) default '0' COMMENT '销售量',
  `code` varchar(50) default NULL COMMENT '货号',
  `pic` varchar(200) default NULL COMMENT '图片',
  `pic_more` text COMMENT '图片',
  `maintenance` tinyint(1) default '1' COMMENT '维修',
  `invoice` tinyint(1) default '1' COMMENT '发票',
  `credit` int(3) unsigned default NULL COMMENT '信用',
  `stime_type` tinyint(1) default '1' COMMENT '开始时间类型',
  `stime` int(11) default NULL COMMENT '开始时间',
  `validTime` tinyint(1) default '0' COMMENT '有效期',
  `weight` int(8) unsigned default NULL COMMENT '体积',
  `cubage` int(8) unsigned default NULL COMMENT '重量',
  `freight` smallint(6) unsigned default NULL COMMENT '运费',
  `freight_type` tinyint(1) unsigned default NULL COMMENT '快递方式 卖家承担 买家承担',
  `post_price` float unsigned default NULL COMMENT '平邮',
  `express_price` float unsigned default NULL COMMENT '快递',
  `ems_price` float unsigned default NULL COMMENT 'EMS',
  `province` int(11) default NULL COMMENT '省ID',
  `city` int(11) default NULL COMMENT '市ID',
  `areaid` int(11) default NULL COMMENT '区ID',
  `area` varchar(255) NOT NULL COMMENT '省市区',
  `read_nums` int(6) default '0' COMMENT '点击率',
  `rank` int(5) default '0' COMMENT '排名',
  `uptime` datetime default NULL COMMENT '更新时间',
  `statu` tinyint(1) NOT NULL default '1' COMMENT '-2，-1，0,1,2库存，违规，没审核，审核，推荐',
  `custom_cat_id` int(10) default NULL COMMENT '自定义分类',
  `promotion_id` int(11) default '0' COMMENT '积分',
  `point` int(6) default NULL COMMENT '好坏程度',
  `goodbad` int(6) default '0' COMMENT '促销ID',
  `shop_rec` tinyint(1) unsigned default '0' COMMENT '橱窗推荐',
  `match` text COMMENT '搭配产品ID',
  `rebate` float(10,2) default '0.00',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`,`pname`),
  KEY `pname` (`pname`),
  KEY `keywords` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_products`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_cart`
--

CREATE TABLE `mallbuilder_product_cart` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) unsigned default NULL COMMENT '会员ID',
  `pid` int(11) unsigned default NULL COMMENT '产品ID',
  `sell_userid` int(11) unsigned default NULL COMMENT '卖家ID',
  `price` float unsigned default NULL COMMENT '价格',
  `num` int(5) unsigned default NULL COMMENT '数量',
  `time` int(11) unsigned default NULL COMMENT '创建时间',
  `setmeal` int(11) default '0' COMMENT '套餐',
  `code` varchar(255) default '0' COMMENT '货号',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_cart`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_cat`
--

CREATE TABLE `mallbuilder_product_cat` (
  `catid` int(9) NOT NULL auto_increment COMMENT 'ID',
  `cat` varchar(50) default NULL COMMENT '分类名',
  `title` text COMMENT 'SEO标题',
  `keyword` text COMMENT 'SEO描述',
  `description` text COMMENT 'SEO描述',
  `nums` int(6) default NULL COMMENT '排序',
  `isindex` tinyint(1) default '0' COMMENT '推荐显示',
  `char_index` char(1) default NULL COMMENT '首字母',
  `all_char` varchar(50) default NULL COMMENT '拼音',
  `pic` varchar(150) default NULL COMMENT '图片',
  `brand` text COMMENT '关联品牌',
  `rec_nums` int(10) default '0' COMMENT '点击率',
  `isbuy` tinyint(1) default NULL COMMENT '是否支持购买',
  `ext_table` varchar(30) default NULL COMMENT '关连属性表',
  `ext_field_cat` int(11) default NULL COMMENT '关连属性集',
  `is_setmeal` tinyint(1) unsigned default '0' COMMENT '是否支持套餐',
  `commission` float unsigned default '0' COMMENT '佣金',
  `current` varchar(100) default NULL COMMENT '点击状态',
  `templates` varchar(100) default NULL COMMENT '模版',
  `month` varchar(30) default NULL,
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_comment`
--

CREATE TABLE `mallbuilder_product_comment` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) NOT NULL COMMENT '会员ID',
  `user` char(20) NOT NULL COMMENT '会员名',
  `fromid` int(11) NOT NULL COMMENT '发布者ID',
  `pid` int(11) NOT NULL COMMENT '产品ID',
  `puid` int(11) NOT NULL COMMENT '产品持有者ID',
  `pname` varchar(100) NOT NULL COMMENT '产品名',
  `price` float(30,0) NOT NULL COMMENT '产品名',
  `con` text NOT NULL COMMENT '内容',
  `uptime` int(12) NOT NULL COMMENT '创建时间',
  `goodbad` tinyint(1) NOT NULL default '0' COMMENT '好坏程度',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品评论表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_consult`
--

CREATE TABLE `mallbuilder_product_consult` (
  `id` int(11) NOT NULL auto_increment,
  `catid` int(11) default '0',
  `product_member_id` int(11) default '0',
  `product_id` int(11) default NULL,
  `product_name` varchar(255) default NULL,
  `member_id` int(11) default '0',
  `member_name` varchar(255) default NULL,
  `question` varchar(255) default NULL,
  `answer` varchar(255) default NULL,
  `answer_id` int(11) default '0',
  `question_time` int(10) default NULL,
  `answer_time` int(10) default NULL,
  `status` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_consult`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_consult_cat`
--

CREATE TABLE `mallbuilder_product_consult_cat` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `type` tinyint(1) default '0',
  `con` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 导出表中的数据 `mallbuilder_product_consult_cat`
--

INSERT INTO `mallbuilder_product_consult_cat` (`id`, `name`, `type`, `con`) VALUES
(1, '商品咨询', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_delivery`
--

CREATE TABLE `mallbuilder_product_delivery` (
  `id` int(11) NOT NULL auto_increment,
  `company` varchar(30) character set utf8 NOT NULL COMMENT '快递公司',
  `number` varchar(30) character set utf8 NOT NULL COMMENT '快递单号',
  `time` int(11) NOT NULL COMMENT '发货时间',
  `order_id` varchar(15) character set utf8 NOT NULL,
  `user` varchar(20) character set utf8 NOT NULL,
  `uptime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_delivery`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_detail`
--

CREATE TABLE `mallbuilder_product_detail` (
  `userid` int(11) default NULL COMMENT '会员ID',
  `proid` int(11) default NULL COMMENT '产品ID',
  `detail` text COMMENT '内容',
  KEY `proid` (`proid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品内容表';

--
-- 导出表中的数据 `mallbuilder_product_detail`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_invoice`
--

CREATE TABLE `mallbuilder_product_invoice` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL default '0' COMMENT '发票类型',
  `rise` tinyint(1) NOT NULL default '0' COMMENT '发票抬头',
  `content` tinyint(1) NOT NULL default '0' COMMENT '发票内容',
  `company` varchar(50) default NULL COMMENT '单位名称',
  `number` varchar(30) default NULL COMMENT '纳税人识别号',
  `address` varchar(30) default NULL COMMENT '注册地址',
  `telephone` varchar(30) default NULL COMMENT '注册电话',
  `bank` varchar(30) default NULL COMMENT '开户银行',
  `account` varchar(20) default NULL COMMENT '银行帐户',
  `checked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_invoice`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_order`
--

CREATE TABLE `mallbuilder_product_order` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) unsigned default NULL COMMENT '会员ID',
  `order_id` varchar(15) default NULL COMMENT '订单ID',
  `buyer_id` int(10) default NULL COMMENT '买家ID',
  `seller_id` int(11) unsigned NOT NULL COMMENT '卖家ID',
  `buyer_name` varchar(50) default NULL COMMENT '收货人姓名',
  `buyer_addr` varchar(100) default NULL COMMENT '收货人地址',
  `buyer_tel` varchar(30) default NULL COMMENT '收货人电话',
  `buyer_mobile` varchar(20) default NULL COMMENT '收货人手机',
  `buyer_zip` varchar(6) default NULL COMMENT '收货人邮编',
  `product_price` float default NULL COMMENT '订购价格',
  `logistics_type` varchar(30) default NULL COMMENT '物流类型',
  `logistics_price` float default '0' COMMENT '物流类型',
  `status` int(1) default NULL COMMENT '定单状态',
  `des` varchar(200) default NULL COMMENT '备注',
  `creat_time` int(11) unsigned default NULL COMMENT '下定单时间',
  `uptime` int(11) unsigned default NULL COMMENT '更新时间',
  `buyer_comment` tinyint(1) unsigned default '0' COMMENT '卖家是否评论',
  `seller_comment` tinyint(1) unsigned default '0' COMMENT '买家是否评论',
  `invoice` int(11) NOT NULL default '0' COMMENT '发票',
  `logistics` tinyint(11) NOT NULL default '0' COMMENT '物流',
  `deliver_id` int(11) default '0' COMMENT '快递ID',
  `deliver_name` varchar(30) default NULL COMMENT '快递名',
  `deliver_code` varchar(50) default NULL COMMENT '快递单号',
  `deliver_time` int(10) default '0' COMMENT '配送时间',
  `deliver_addr_id` int(11) default '0' COMMENT '发货地址',
  `time_expand` tinyint(1) default '0' COMMENT '延长时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_order_pro`
--

CREATE TABLE `mallbuilder_product_order_pro` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `order_id` varchar(15) default NULL COMMENT '订单ID',
  `buyer_id` int(11) unsigned NOT NULL COMMENT '买家ID',
  `pid` int(11) unsigned default NULL COMMENT '产品ID',
  `pcatid` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(255) NOT NULL COMMENT '产品名',
  `pic` varchar(100) NOT NULL COMMENT '产品图片',
  `price` float unsigned default NULL COMMENT '价格',
  `num` int(5) unsigned default NULL COMMENT ' 数量',
  `time` int(11) unsigned default NULL COMMENT '创建时间',
  `setmeal` int(11) NOT NULL default '0' COMMENT '套餐',
  `code` varchar(255) default NULL COMMENT '货号',
  `rebate` float(10,2) default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单产品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_order_pro`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_report`
--

CREATE TABLE `mallbuilder_product_report` (
  `id` int(11) NOT NULL auto_increment COMMENT '举报id',
  `userid` int(11) NOT NULL COMMENT '举报人id',
  `user` varchar(50) NOT NULL COMMENT '举报人会员名',
  `pid` int(11) NOT NULL COMMENT '被举报的商品id',
  `pname` varchar(100) NOT NULL COMMENT '被举报的商品名称',
  `subject_id` int(11) NOT NULL COMMENT '举报主题id',
  `subject_name` varchar(50) NOT NULL COMMENT '举报主题',
  `content` varchar(100) NOT NULL COMMENT '举报信息',
  `pic` varchar(100) NOT NULL COMMENT '图片1',
  `datetime` int(11) NOT NULL COMMENT '举报时间',
  `shop_id` int(11) NOT NULL COMMENT '被举报商品的店铺id',
  `state` tinyint(1) NOT NULL COMMENT '举报状态(1未处理/2已处理)',
  `handle_type` tinyint(1) NOT NULL COMMENT '举报处理结果(1无效举报/2恶意举报/3有效举报)',
  `handle_message` varchar(100) NOT NULL COMMENT '举报处理信息',
  `handle_datetime` int(11) NOT NULL default '0' COMMENT '举报处理时间',
  `handle_user` varchar(50) NOT NULL default '0' COMMENT '管理员',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='举报表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_report`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_report_subject`
--

CREATE TABLE `mallbuilder_product_report_subject` (
  `id` int(11) NOT NULL auto_increment COMMENT '举报主题id',
  `content` varchar(100) default NULL COMMENT '举报主题内容',
  `type_id` int(11) NOT NULL default '0' COMMENT '举报类型id',
  `type_name` varchar(50) NOT NULL COMMENT '举报类型名称 ',
  `desc` varchar(100) default NULL COMMENT '举报类型描述',
  `state` tinyint(1) NOT NULL default '0' COMMENT '举报主题状态(1可用/0失效)',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='举报主题表' AUTO_INCREMENT=8 ;

--
-- 导出表中的数据 `mallbuilder_product_report_subject`
--

INSERT INTO `mallbuilder_product_report_subject` (`id`, `content`, `type_id`, `type_name`, `desc`, `state`) VALUES
(1, NULL, 0, '出售禁售品', '销售商城禁止和限制交易规则下所规定的所有商品.', 1),
(2, '管制刀具、弓弩类、其他武器等', 1, '出售禁售品', NULL, 1),
(3, '赌博用具类', 1, '出售禁售品', NULL, 1),
(4, '枪支弹药', 1, '出售禁售品', NULL, 1),
(5, '毒品及吸毒工具', 1, '出售禁售品', NULL, 1),
(6, NULL, 0, '出售假货', NULL, 1),
(7, '出售假货', 6, '出售假货', NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_product_setmeal`
--

CREATE TABLE `mallbuilder_product_setmeal` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL default '0' COMMENT '产品ID',
  `setmeal` varchar(60) NOT NULL COMMENT '套餐名称',
  `price` decimal(10,2) NOT NULL default '0.00' COMMENT '价格',
  `market_price` float(10,2) default '0.00' COMMENT '市场价',
  `cost_price` float(10,2) default '0.00' COMMENT '生产价',
  `stock` int(11) NOT NULL default '0' COMMENT '库存',
  `sku` varchar(60) NOT NULL COMMENT '货号',
  `property_value_id` text COMMENT '规格ID',
  PRIMARY KEY  (`id`),
  KEY `goods_id` (`pid`),
  KEY `price` (`price`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_product_setmeal`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_property`
--

CREATE TABLE `mallbuilder_property` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(100) default NULL COMMENT '类型名',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品属性表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_property`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_property_value`
--

CREATE TABLE `mallbuilder_property_value` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `field` varchar(100) default NULL COMMENT '字段数据库字段名',
  `field_name` varchar(200) default NULL COMMENT '字段名称',
  `field_desc` varchar(100) default NULL COMMENT '字段描述',
  `display_type` int(1) default NULL COMMENT '前台显示类型（单行文本框，多行文件框等）',
  `item` text COMMENT '选项列表',
  `is_search` tinyint(1) default '0' COMMENT '是否被搜索',
  `property_id` int(11) default NULL COMMENT '属性ID',
  `statu` tinyint(1) default '1' COMMENT '状态',
  `type` tinyint(1) default '1' COMMENT '类型',
  `template_id` int(6) default '0' COMMENT '模版ID',
  `displayorder` smallint(6) default '255' COMMENT '排序',
  PRIMARY KEY  (`id`),
  KEY `catid` (`property_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品属性值表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_property_value`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_property_value_template`
--

CREATE TABLE `mallbuilder_property_value_template` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `field` varchar(100) default NULL COMMENT '字段数据库字段名',
  `field_name` varchar(200) default NULL COMMENT '字段名称',
  `field_desc` varchar(100) default NULL COMMENT '字段描述',
  `display_type` int(1) default NULL COMMENT '前台显示类型（单行文本框，多行文件框等）',
  `item` varchar(300) default NULL COMMENT '选项列表',
  `is_search` tinyint(1) default '0' COMMENT '是否被搜索',
  `statu` tinyint(1) default '1' COMMENT '状态',
  `type` tinyint(1) default '1' COMMENT '类型',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品属性值模版表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_property_value_template`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_reg_vercode`
--

CREATE TABLE `mallbuilder_reg_vercode` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `question` varchar(50) default NULL COMMENT '问题',
  `answer` varchar(40) default NULL COMMENT '问题',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='随机问题表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_reg_vercode`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_remind`
--

CREATE TABLE `mallbuilder_remind` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `catid` int(6) default '0',
  `mail` tinyint(1) default '0',
  `mail_template` varchar(255) default NULL,
  `message` tinyint(1) default '0',
  `message_template` varchar(255) default NULL,
  `mobile` tinyint(1) default '0',
  `mobile_template` varchar(255) default NULL,
  `status` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_remind`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_remind_cat`
--

CREATE TABLE `mallbuilder_remind_cat` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `displayorder` smallint(6) default '255',
  `parent_id` int(6) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_remind_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_reserve_username`
--

CREATE TABLE `mallbuilder_reserve_username` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_reserve_username`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_return`
--

CREATE TABLE `mallbuilder_return` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '退货记录ID',
  `oid` varchar(15) NOT NULL COMMENT '订单',
  `return_code` varchar(100) NOT NULL COMMENT '退货编号',
  `seller_id` int(10) unsigned NOT NULL COMMENT '卖家ID',
  `seller_name` varchar(20) NOT NULL COMMENT '店铺名称',
  `buyer_id` int(10) unsigned NOT NULL COMMENT '买家ID',
  `buyer_name` varchar(50) NOT NULL COMMENT '买家会员名',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `message` varchar(300) default NULL COMMENT '退货备注',
  `return_addr_id` int(11) NOT NULL COMMENT '退货ID',
  `return_addr_name` varchar(30) NOT NULL COMMENT '退货人',
  `return_addr` varchar(150) NOT NULL COMMENT '退货人地址',
  `return_post` varchar(20) default NULL COMMENT '退货人邮编',
  `return_tel` varchar(20) default NULL COMMENT '退货人电话',
  `return_mobile` varchar(20) default NULL COMMENT '退货人电话',
  `statu` tinyint(1) NOT NULL default '1' COMMENT '状态',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_return`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_return_goods`
--

CREATE TABLE `mallbuilder_return_goods` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '退货商品记录ID',
  `rid` int(10) unsigned NOT NULL COMMENT '退货记录ID',
  `oid` varchar(15) NOT NULL COMMENT '订单ID',
  `pid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `pname` varchar(100) NOT NULL COMMENT '商品名称',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `returnnum` int(10) unsigned NOT NULL COMMENT '退货数量',
  `pic` varchar(100) default NULL COMMENT '商品图片',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='退货商品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_return_goods`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_search_word`
--

CREATE TABLE `mallbuilder_search_word` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `keyword` varchar(80) default NULL COMMENT 'ID',
  `char_index` varchar(80) default NULL COMMENT '拼音',
  `nums` int(11) default '0' COMMENT '点击率',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关键词表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_search_word`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_session`
--

CREATE TABLE `mallbuilder_session` (
  `sesskey` char(32) NOT NULL,
  `expiry` int(11) unsigned NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`sesskey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 导出表中的数据 `mallbuilder_session`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shipping_address`
--

CREATE TABLE `mallbuilder_shipping_address` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) default NULL COMMENT '会员ID',
  `name` varchar(30) default NULL COMMENT '会员名',
  `provinceid` int(11) default NULL COMMENT '省ID',
  `cityid` int(11) default NULL COMMENT '市ID',
  `areaid` int(11) default NULL COMMENT '区ID',
  `area` varchar(255) character set ucs2 default NULL COMMENT '省市区',
  `addr` varchar(150) default NULL COMMENT '地址',
  `post` varchar(6) default NULL COMMENT '邮编',
  `tel` varchar(20) default NULL COMMENT '电话',
  `mobile` varchar(15) default NULL COMMENT '手机',
  `company` varchar(50) default NULL COMMENT '公司名',
  `con` varchar(200) default NULL COMMENT '描述',
  `default_receipt` tinyint(1) unsigned default NULL COMMENT '默认退货',
  `default_delivery` tinyint(1) unsigned default NULL COMMENT '默认发货',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发货表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shipping_address`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop`
--

CREATE TABLE `mallbuilder_shop` (
  `userid` int(8) NOT NULL default '0' COMMENT 'ID',
  `user` varchar(30) default NULL COMMENT '会员ID',
  `catid` char(10) default NULL COMMENT '分类ID',
  `company` char(60) default NULL COMMENT '店铺名称',
  `tel` varchar(30) default NULL COMMENT '联系电话',
  `addr` varchar(200) default NULL COMMENT '地址',
  `provinceid` int(11) default NULL COMMENT '省ID',
  `cityid` int(11) default NULL COMMENT '市ID',
  `areaid` int(11) default NULL COMMENT '区ID',
  `area` varchar(255) default NULL COMMENT '省市区',
  `main_pro` varchar(100) default NULL COMMENT '主营产品',
  `logo` varchar(100) default NULL COMMENT 'logo',
  `template` varchar(20) default NULL COMMENT '店铺模板',
  `stime` int(11) unsigned default NULL COMMENT '开始时间',
  `etime` int(11) unsigned default NULL COMMENT '结束时间',
  `statu` tinyint(1) default NULL COMMENT '状态',
  `rank` float default '0' COMMENT '排名',
  `view_times` int(5) unsigned default '0' COMMENT '点击率',
  `uptime` int(11) unsigned default NULL COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL COMMENT '创店时间',
  `shop_statu` tinyint(1) NOT NULL default '0' COMMENT '开店状态',
  `credit` int(3) unsigned default NULL COMMENT '信用服务',
  `shop_collect` int(10) NOT NULL default '0' COMMENT '店铺收藏数量',
  `earnest` float(10,2) unsigned default '0.00' COMMENT '保证金',
  `grade` tinyint(1) unsigned default '0' COMMENT '店铺等级',
  `shop_auth` tinyint(1) default '0' COMMENT '店铺认证',
  `shopkeeper_auth` tinyint(1) default '0' COMMENT '店主认证',
  `shop_auth_pic` varchar(100) default NULL COMMENT '店铺认证图片',
  `shopkeeper_auth_pic` varchar(100) default NULL COMMENT '店主认证图片',
  `domin` varchar(255) default NULL COMMENT '顶级域名',
  KEY `company` (`company`,`cityid`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺表';

--
-- 导出表中的数据 `mallbuilder_shop`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_cat`
--

CREATE TABLE `mallbuilder_shop_cat` (
  `id` int(9) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '分类名',
  `parent_id` int(9) NOT NULL default '0' COMMENT '父类I',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_domin`
--

CREATE TABLE `mallbuilder_shop_domin` (
  `id` int(11) NOT NULL auto_increment,
  `domin` varchar(50) NOT NULL,
  `shop_id` int(8) NOT NULL,
  `shop_name` varchar(60) NOT NULL,
  `member_name` varchar(30) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `domin` (`domin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_domin`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_earnest`
--

CREATE TABLE `mallbuilder_shop_earnest` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT 'ID',
  `shop_id` int(11) default NULL COMMENT '会员ID',
  `money` float default NULL COMMENT '保证金',
  `content` text COMMENT '保证金',
  `admin` varchar(30) default NULL COMMENT '管理员',
  `create_time` int(11) unsigned default NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='保证金表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_earnest`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_grade`
--

CREATE TABLE `mallbuilder_shop_grade` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '组名',
  `fee` float NOT NULL default '0' COMMENT '收费标准',
  `desc` text NOT NULL COMMENT '描述',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL default '1' COMMENT '状态 0,1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺等级表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_grade`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_link`
--

CREATE TABLE `mallbuilder_shop_link` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `name` varchar(40) NOT NULL COMMENT '名称',
  `url` varchar(100) NOT NULL COMMENT '地址',
  `desc` varchar(100) default NULL COMMENT '描述',
  `displayorder` smallint(6) default '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺自定义友情链接表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_link`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_navigation`
--

CREATE TABLE `mallbuilder_shop_navigation` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '导航ID',
  `title` varchar(50) NOT NULL COMMENT '导航名称',
  `shop_id` int(10) unsigned NOT NULL default '0' COMMENT '卖家店铺ID',
  `content` text COMMENT '导航内容',
  `sort` tinyint(3) unsigned NOT NULL default '0' COMMENT '导航排序',
  `if_show` tinyint(1) NOT NULL default '0' COMMENT '导航是否显示',
  `add_time` int(10) NOT NULL COMMENT '导航',
  `url` varchar(255) default NULL COMMENT '店铺导航的外链URL',
  `new_open` tinyint(1) unsigned NOT NULL default '0' COMMENT '店铺导航外链是否在新窗口打开：0不开新窗口1开新窗口，默认是0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='卖家店铺导航信息表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_navigation`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_setting`
--

CREATE TABLE `mallbuilder_shop_setting` (
  `shop_id` int(10) NOT NULL COMMENT 'ID',
  `shop_logo` varchar(255) default NULL COMMENT '店铺logo',
  `shop_banner` varchar(255) default NULL COMMENT '店铺横幅',
  `shop_title` varchar(255) default NULL COMMENT 'seo标题',
  `shop_keywords` varchar(255) default NULL COMMENT 'seo关键字',
  `shop_description` varchar(255) default NULL COMMENT 'seo描述',
  `shop_slide` text COMMENT '店铺幻灯片',
  `shop_slideurl` text COMMENT '店铺幻灯片url',
  `common_cat` text COMMENT '常用类别',
  KEY `userid` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺自定义配置表';

--
-- 导出表中的数据 `mallbuilder_shop_setting`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_shop_template`
--

CREATE TABLE `mallbuilder_shop_template` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(60) NOT NULL COMMENT '名称',
  `style` varchar(50) NOT NULL COMMENT '风格',
  `temp_file` varchar(60) NOT NULL COMMENT '模板文件',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺模板表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_shop_template`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_site_spread`
--

CREATE TABLE `mallbuilder_site_spread` (
  `id` int(10) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(10) default NULL COMMENT '会员ID',
  `fromip` varchar(50) default NULL COMMENT '访问IP',
  `access_num` int(10) default '0' COMMENT '点击率',
  `ctime` int(11) default NULL COMMENT '建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站点推广表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_site_spread`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns`
--

CREATE TABLE `mallbuilder_sns` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `original_id` int(11) NOT NULL default '0' COMMENT '原ID',
  `original_member_id` int(11) NOT NULL default '0' COMMENT '原会员ID',
  `original_status` tinyint(1) NOT NULL default '0' COMMENT '原状态',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `member_name` varchar(100) NOT NULL COMMENT '会员名称',
  `title` varchar(500) default NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `img` text COMMENT '图片',
  `type` tinyint(1) default '1' COMMENT '2 pic 3pro 4shop ',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  `comment_count` int(11) NOT NULL default '0' COMMENT '评论数',
  `copy_count` int(11) NOT NULL default '0' COMMENT '转发数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='动态信息表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_comment`
--

CREATE TABLE `mallbuilder_sns_comment` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `member_name` varchar(100) NOT NULL COMMENT '会员名称',
  `original_id` int(11) NOT NULL COMMENT '原帖ID',
  `content` varchar(500) NOT NULL COMMENT '评论内容',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `state` tinyint(1) NOT NULL default '0' COMMENT '状态 0正常 1屏蔽',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_friend`
--

CREATE TABLE `mallbuilder_sns_friend` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `uname` varchar(100) default NULL COMMENT '会员名称',
  `uimg` varchar(100) default NULL COMMENT '会员头像',
  `fuid` int(11) NOT NULL COMMENT '朋友id',
  `funame` varchar(100) NOT NULL COMMENT '好友会员名称',
  `fuimg` varchar(100) default NULL COMMENT '朋友头像',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `state` tinyint(1) NOT NULL default '1' COMMENT '关注状态 1为单方关注 2为双方关注',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_friend`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareproduct`
--

CREATE TABLE `mallbuilder_sns_shareproduct` (
  `id` int(11) NOT NULL auto_increment COMMENT 'D',
  `pid` int(11) NOT NULL COMMENT '商品ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `uname` varchar(100) NOT NULL COMMENT '会员名',
  `content` varchar(500) default NULL COMMENT '描述内容',
  `addtime` int(11) NOT NULL COMMENT '分享操作时间',
  `likeaddtime` int(11) NOT NULL default '0' COMMENT '喜欢操作时间',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  `commentcount` int(11) NOT NULL default '0' COMMENT '评论数',
  `isshare` tinyint(1) NOT NULL default '0' COMMENT '是否分享 0为未分享 1为分享',
  `islike` tinyint(1) NOT NULL default '0' COMMENT '是否喜欢 0为未喜欢 1为喜欢',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享商品表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_shareproduct`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareproduct_info`
--

CREATE TABLE `mallbuilder_sns_shareproduct_info` (
  `pid` int(11) NOT NULL COMMENT '商品ID',
  `pname` varchar(100) NOT NULL COMMENT '商品名',
  `image` varchar(100) default NULL COMMENT '商品图片',
  `price` decimal(10,2) NOT NULL default '0.00' COMMENT '商品价格',
  `shopid` int(11) NOT NULL COMMENT '店铺ID',
  `uname` varchar(100) NOT NULL COMMENT '会员名称',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `likenum` int(11) NOT NULL default '0' COMMENT '喜欢数',
  `likemember` text COMMENT '喜欢过的会员ID，用逗号分隔',
  `collectnum` int(11) NOT NULL default '0' COMMENT '收藏数',
  UNIQUE KEY `snsgoods_goodsid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享商品信息表';

--
-- 导出表中的数据 `mallbuilder_sns_shareproduct_info`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sns_shareshop`
--

CREATE TABLE `mallbuilder_sns_shareshop` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `shopid` int(11) NOT NULL COMMENT '店铺ID',
  `shopname` varchar(100) NOT NULL COMMENT '店铺名',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `uname` varchar(100) NOT NULL COMMENT '会员名',
  `content` varchar(500) default NULL COMMENT '描述内容',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `privacy` tinyint(1) NOT NULL default '0' COMMENT '隐私可见度 0所有人可见 1好友可见 2仅自己可见',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共享店铺表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sns_shareshop`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_stop_ip`
--

CREATE TABLE `mallbuilder_stop_ip` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `ip` varchar(25) NOT NULL COMMENT 'IP',
  `reason` varchar(50) default NULL COMMENT '原因',
  `optime` int(12) unsigned default NULL COMMENT '打开时间',
  `stoptime` int(12) unsigned default NULL COMMENT '关闭时间',
  `autorelease` int(1) default NULL COMMENT '自动打开',
  `statu` tinyint(1) NOT NULL default '1' COMMENT '状态',
  `type` tinyint(1) default '1' COMMENT '类型',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='IP锁定表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_stop_ip`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_sub_domain`
--

CREATE TABLE `mallbuilder_sub_domain` (
  `id` int(4) NOT NULL auto_increment COMMENT 'ID',
  `dtype` int(1) NOT NULL COMMENT '类型',
  `domain` varchar(20) NOT NULL COMMENT '名称',
  `top_domain` varchar(255) default NULL COMMENT '顶级域名',
  `con` varchar(30) NOT NULL COMMENT '省',
  `con2` varchar(20) default NULL COMMENT '市',
  `con3` varchar(20) default NULL COMMENT '区',
  `des` text COMMENT '描述',
  `isopen` int(1) default '1' COMMENT '是否开启',
  `logo` varchar(100) default NULL COMMENT 'logo',
  `web_title` varchar(100) default NULL COMMENT 'SEO标题',
  `web_keyword` varchar(100) default NULL COMMENT 'SEO关键字',
  `web_des` varchar(100) default NULL COMMENT 'SEO描述',
  `copyright` text COMMENT '版权',
  `template` varchar(50) default NULL COMMENT '模板',
  PRIMARY KEY  (`id`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='城市分站表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_sub_domain`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tags`
--

CREATE TABLE `mallbuilder_tags` (
  `tagname` varchar(100) NOT NULL COMMENT '名称',
  `closed` tinyint(1) NOT NULL default '0' COMMENT '关闭',
  `total` mediumint(20) unsigned NOT NULL COMMENT '总数',
  `statu` tinyint(4) NOT NULL COMMENT '0/1  1表示已导入',
  PRIMARY KEY  (`tagname`),
  KEY `total` (`total`),
  KEY `closed` (`closed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';

--
-- 导出表中的数据 `mallbuilder_tags`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_talk`
--

CREATE TABLE `mallbuilder_talk` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `oid` varchar(15) NOT NULL COMMENT '投诉ID',
  `uid` int(11) NOT NULL COMMENT '发言人ID',
  `uname` varchar(50) NOT NULL COMMENT '发言人名称',
  `utype` varchar(10) NOT NULL COMMENT '发言人类型(1-买家/2-卖家)',
  `content` varchar(255) NOT NULL COMMENT '发言内容',
  `add_time` int(11) NOT NULL COMMENT '对话发表时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='对话表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_talk`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg`
--

CREATE TABLE `mallbuilder_tg` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `catid` int(6) NOT NULL COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `market_price` float(10,2) NOT NULL default '0.00' COMMENT '市场价',
  `price` float(10,2) unsigned NOT NULL default '0.00' COMMENT '团购价',
  `hits` int(6) NOT NULL default '0' COMMENT '点击数',
  `sell_amount` int(6) NOT NULL default '0' COMMENT '销售量',
  `limit_quantity` int(6) NOT NULL default '0' COMMENT '限购数量',
  `virtual_quantity` int(6) NOT NULL default '0' COMMENT '虚拟购买数量',
  `status` tinyint(1) NOT NULL default '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `stock` int(10) NOT NULL default '1' COMMENT '库存',
  `provinceid` int(11) NOT NULL COMMENT '省ID',
  `cityid` int(11) NOT NULL COMMENT '市ID',
  `areaid` int(11) NOT NULL COMMENT '区ID',
  `area` varchar(255) NOT NULL COMMENT '省市区',
  `displayorder` smallint(6) NOT NULL default '255' COMMENT '排序',
  `is_virtual` enum('true','false') default 'false' COMMENT '是否虚拟产品',
  `member_id` int(6) default '0' COMMENT '会员ID',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg_cat`
--

CREATE TABLE `mallbuilder_tg_cat` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `parent_id` int(11) NOT NULL default '0' COMMENT '父类ID',
  `catname` varchar(30) NOT NULL COMMENT '分类名',
  `displayorder` smallint(8) NOT NULL default '255' COMMENT '排序',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购产品分类表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg_member`
--

CREATE TABLE `mallbuilder_tg_member` (
  `id` int(6) NOT NULL auto_increment COMMENT 'ID',
  `user` varchar(50) NOT NULL COMMENT '帐号',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `name` varchar(30) default NULL COMMENT '名称',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购会员表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_tg_order`
--

CREATE TABLE `mallbuilder_tg_order` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `order_id` varchar(15) NOT NULL COMMENT '订单号',
  `member_id` int(11) default NULL COMMENT '会员ID',
  `member_name` varchar(50) NOT NULL COMMENT '会员名',
  `tg_id` int(11) NOT NULL COMMENT '产品id',
  `tg_name` varchar(255) NOT NULL COMMENT '产品名',
  `tg_pic` varchar(80) default NULL COMMENT '产品图片',
  `contact` varchar(30) default NULL COMMENT '地址',
  `address` varchar(200) default NULL COMMENT '电话',
  `tel` varchar(20) default NULL COMMENT '手机',
  `remark` varchar(200) default NULL COMMENT '备注',
  `admin_remark` varchar(200) default NULL COMMENT '管理员备注',
  `price` decimal(10,2) default '0.00' COMMENT '价格',
  `quantity` varchar(10) NOT NULL COMMENT '数量',
  `create_time` int(10) default NULL COMMENT '创建时间',
  `payment_time` int(10) default NULL COMMENT '支付时间',
  `shipping_time` int(10) default NULL COMMENT '发货时间',
  `finished_time` int(10) default NULL COMMENT '完成时间',
  `status` tinyint(2) default '20' COMMENT '状态',
  `shipping_name` varchar(50) default NULL COMMENT '发货人',
  `shipping_address` varchar(255) default NULL COMMENT '发货地址',
  `shipping_tel` varchar(20) default NULL COMMENT '联系电话',
  `shipping_company` varchar(50) default NULL COMMENT '快递名',
  `shipping_code` varchar(50) default NULL COMMENT '快递单号',
  `is_virtual` enum('true','false') default 'false' COMMENT '是否虚拟产品',
  `code` varchar(20) default NULL COMMENT '验证码',
  `company_id` int(6) default NULL COMMENT '公司ID',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='团购订单表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_tg_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_comment`
--

CREATE TABLE `mallbuilder_user_comment` (
  `id` int(8) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(8) NOT NULL COMMENT '会员ID',
  `user` char(20) NOT NULL COMMENT '会员名',
  `byid` int(8) NOT NULL COMMENT '被评价者ID',
  `item1` int(8) NOT NULL COMMENT '项目1',
  `item2` int(8) NOT NULL COMMENT '项目2',
  `item3` int(8) NOT NULL COMMENT '项目3',
  `item4` int(8) NOT NULL COMMENT '项目4',
  `uptime` int(12) NOT NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='店铺评价表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_connected`
--

CREATE TABLE `mallbuilder_user_connected` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'ID',
  `userid` int(10) unsigned default NULL COMMENT '会员ID',
  `nickname` varchar(50) default NULL COMMENT '名称',
  `figureurl` varchar(100) default NULL COMMENT '地址',
  `gender` varchar(10) default NULL COMMENT '性别',
  `vip` tinyint(1) unsigned default '0' COMMENT 'vip',
  `level` tinyint(1) unsigned default '0' COMMENT 'level',
  `type` tinyint(1) default '1' COMMENT '类型',
  `access_token` varchar(80) default NULL COMMENT '访问命令',
  `client_id` varchar(80) default NULL COMMENT '客户端',
  `openid` varchar(80) default NULL COMMENT '访问',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员绑定表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_connected`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_group`
--

CREATE TABLE `mallbuilder_user_group` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT '会员组名',
  `des` text COMMENT '会员组描述',
  `con` text,
  `logo` varchar(100) default NULL COMMENT '会员组ＬＯＧＯ',
  `minilogo` varchar(200) default NULL,
  `statu` tinyint(4) default '1' COMMENT '会员组状态 0,1',
  `creat_time` date default NULL COMMENT '创建时间',
  `groupfee` float default '0',
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_user_read_rec`
--

CREATE TABLE `mallbuilder_user_read_rec` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `userid` int(11) default NULL COMMENT '会员ID',
  `tid` int(11) default NULL COMMENT '被访问者ID',
  `type` int(1) default NULL COMMENT '类型',
  `time` int(11) default NULL COMMENT '访问时间',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`,`tid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员访问记录表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_user_read_rec`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_vote`
--

CREATE TABLE `mallbuilder_vote` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `newsid` smallint(6) NOT NULL default '0' COMMENT '新闻ID',
  `title` varchar(120) NOT NULL COMMENT '标题',
  `votetext` text NOT NULL COMMENT '标题',
  `votetype` tinyint(1) NOT NULL default '0' COMMENT '类型 单选 多选',
  `num` int(6) NOT NULL COMMENT '总数',
  `limitip` tinyint(1) NOT NULL default '0' COMMENT '是否限制ID',
  `time` date NOT NULL default '0000-00-00' COMMENT '是否限制时间',
  `tempid` smallint(6) NOT NULL default '0' COMMENT '模板',
  `type` tinyint(4) default NULL COMMENT '类型',
  `uptime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_vote`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_con`
--

CREATE TABLE `mallbuilder_web_con` (
  `con_id` int(5) NOT NULL auto_increment COMMENT 'ID',
  `con_desc` mediumtext COMMENT '描述',
  `con_province` varchar(20) default NULL COMMENT '省',
  `con_city` varchar(20) default NULL COMMENT '市',
  `con_no` int(2) default '0' COMMENT '排序',
  `con_statu` int(1) default '0' COMMENT '状态',
  `con_title` varchar(30) default NULL COMMENT '标题',
  `con_linkaddr` varchar(60) default NULL COMMENT '链接地址',
  `con_group` tinyint(3) NOT NULL COMMENT '组',
  `template` varchar(50) default NULL COMMENT '模板',
  `title` varchar(200) default NULL COMMENT 'SEO标题',
  `keywords` varchar(200) default NULL COMMENT 'SEO关键字',
  `description` varchar(200) default NULL COMMENT 'SEO描述',
  `msg_online` tinyint(1) NOT NULL default '0' COMMENT '是否调用留言板',
  `lang` varchar(5) default NULL COMMENT '语言',
  `type` tinyint(1) default '0' COMMENT '类型',
  PRIMARY KEY  (`con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='网站初始化内容设置' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_web_con`
--


-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_config`
--

CREATE TABLE `mallbuilder_web_config` (
  `id` int(5) unsigned NOT NULL auto_increment COMMENT '主键ＩＤ',
  `index` varchar(30) NOT NULL COMMENT '数组下标',
  `value` text NOT NULL COMMENT '数组值',
  `statu` tinyint(1) NOT NULL default '1' COMMENT '状态值，1可能，0不可用',
  `type` varchar(50) default NULL COMMENT '类型',
  `des` text COMMENT '描述',
  PRIMARY KEY  (`id`),
  KEY `index` (`index`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站配置表' AUTO_INCREMENT=36 ;

--
-- 导出表中的数据 `mallbuilder_web_config`
--

INSERT INTO `mallbuilder_web_config` (`id`, `index`, `value`, `statu`, `type`, `des`) VALUES
(1, 'company', 'Mallbuilder', 1, 'main', NULL),
(2, 'weburl', 'http://democn.mall-builder.com', 1, 'main', NULL),
(3, 'baseurl', 'mall-builder.com', 1, 'main', NULL),
(4, 'logo', '', 1, 'main', NULL),
(5, 'pay_name', '网付宝', 1, 'main', NULL),
(6, 'owntel', '021-64262959', 1, 'main', NULL),
(7, 'email', 'zyfang821115@163.com', 1, 'main', NULL),
(8, 'regname', 'register.php', 1, 'main', NULL),
(9, 'cacheTime', '0', 1, 'main', NULL),
(10, 'money', '￥', 1, 'main', NULL),
(11, 'date_format', '%Y-%m-%d', 1, 'main', NULL),
(12, 'language', 'cn', 1, 'main', NULL),
(13, 'temp', 'default', 1, 'main', NULL),
(14, 'domaincity', '0', 1, 'main', NULL),
(15, 'enable_gzip', '0', 1, 'main', NULL),
(16, 'enable_tranl', '0', 1, 'main', NULL),
(17, 'openstatistics', '0', 1, 'main', NULL),
(18, 'copyright', 'MallBuilder版权所有,正版购买地址http://www.mall-builder.com', 1, 'main', NULL),
(19, 'closetype', '0', 1, 'main', NULL),
(20, 'closecon', '', 1, 'main', NULL),
(21, 'opensuburl', '0', 1, 'seo', NULL),
(22, 'rewrite', '0', 1, 'seo', NULL),
(23, 'title', 'Mallbuilder ', 1, 'seo', NULL),
(24, 'keyword', 'Mallbuilder - 亚洲最大、最安全的网上交易平台，提供各类服饰、美容、家居、数码… 8亿优质特价商品，让你全面安心享受网上购物乐趣！', 1, 'seo', NULL),
(25, 'description', 'Mallbuilder - 亚洲最大、最安全的网上交易平台，提供各类服饰、美容、家居、数码… 8亿优质特价商品，让你全面安心享受网上购物乐趣！', 1, 'seo', NULL),
(26, 'openregemail', '1', 1, 'reg', NULL),
(27, 'user_reg_verf', '0', 1, 'reg', NULL),
(28, 'invite', '0', 1, 'reg', NULL),
(29, 'user_reg', '2', 1, 'reg', NULL),
(30, 'openbbs', '0', 1, 'reg', NULL),
(31, 'inhibit_ip', '0', 1, 'reg', NULL),
(32, 'exception_ip', '127.0.0.1', 1, 'reg', NULL),
(33, 'association', '这里是注册协义，可以在后台注册设置中修改。', 1, 'reg', NULL),
(34, 'closetype', '0', 1, 'reg', NULL),
(35, 'closecon', '', 1, 'reg', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_con_group`
--

CREATE TABLE `mallbuilder_web_con_group` (
  `id` int(11) NOT NULL auto_increment COMMENT 'ID',
  `title` varchar(60) default NULL COMMENT '标题',
  `lang` varchar(5) default NULL COMMENT '语言',
  `sort` smallint(4) default '0' COMMENT '排序',
  `logo` varchar(100) default NULL COMMENT 'logo',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='网站初始化内容分组表' AUTO_INCREMENT=5 ;

--
-- 导出表中的数据 `mallbuilder_web_con_group`
--

INSERT INTO `mallbuilder_web_con_group` (`id`, `title`, `lang`, `sort`, `logo`) VALUES
(1, '消费者保障', 'cn', 255, NULL),
(2, '新手上路', 'cn', 255, NULL),
(3, '付款方式', 'cn', 255, NULL),
(4, '帮助信息', 'cn', 255, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `mallbuilder_web_link`
--

CREATE TABLE `mallbuilder_web_link` (
  `linkid` int(4) NOT NULL auto_increment COMMENT 'ID',
  `name` varchar(20) default NULL COMMENT '名称',
  `url` varchar(200) default NULL COMMENT '地址',
  `statu` tinyint(1) NOT NULL default '0' COMMENT '状态',
  `orderid` int(11) NOT NULL default '0' COMMENT '排序',
  `log` varchar(100) default NULL COMMENT 'logo',
  `province` varchar(15) default NULL COMMENT '省ID',
  `city` varchar(15) default NULL COMMENT '市ID',
  `stime` int(11) default NULL COMMENT '开始时间',
  `etime` int(11) default NULL COMMENT '结束时间',
  `con` text COMMENT '描述',
  PRIMARY KEY  (`linkid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接表' AUTO_INCREMENT=1 ;

--
-- 导出表中的数据 `mallbuilder_web_link`
--

