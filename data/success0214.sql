-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 05 月 09 日 10:59
-- 服务器版本: 5.5.53
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `success0214`
--

-- --------------------------------------------------------

--
-- 表的结构 `ci_account`
--

CREATE TABLE IF NOT EXISTS `ci_account` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `number` varchar(15) COLLATE utf8_unicode_ci DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `amount` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `number` (`number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_account_info`
--

CREATE TABLE IF NOT EXISTS `ci_account_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) DEFAULT '0' COMMENT '关联ID',
  `uid` int(11) DEFAULT '0',
  `buId` smallint(6) DEFAULT '0' COMMENT '客户ID',
  `billNo` varchar(25) DEFAULT '' COMMENT '单号',
  `billType` varchar(20) DEFAULT '',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `accId` int(11) DEFAULT '0' COMMENT '结算账户ID',
  `payment` double DEFAULT '0' COMMENT '收款金额  采购退回为正',
  `wayId` int(11) DEFAULT '0' COMMENT '结算方式ID',
  `settlement` varchar(50) DEFAULT '' COMMENT '结算号',
  `remark` varchar(50) DEFAULT '' COMMENT '备注',
  `transType` int(11) DEFAULT '0',
  `transTypeName` varchar(50) DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `billdate` (`billDate`) USING BTREE,
  KEY `iid` (`iid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_address`
--

CREATE TABLE IF NOT EXISTS `ci_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `shortName` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT ' ',
  `postalcode` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `province` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `area` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `linkman` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `pid` (`postalcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_admin`
--

CREATE TABLE IF NOT EXISTS `ci_admin` (
  `uid` smallint(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '用户名称',
  `userpwd` varchar(32) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '密码',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否锁定',
  `name` varchar(25) COLLATE utf8_unicode_ci DEFAULT '',
  `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `lever` text COLLATE utf8_unicode_ci COMMENT '权限',
  `roleid` tinyint(1) DEFAULT '1' COMMENT '角色ID',
  `righttype1` text COLLATE utf8_unicode_ci COMMENT '加1代表允许数据使用',
  `righttype2` text COLLATE utf8_unicode_ci,
  `righttype8` text COLLATE utf8_unicode_ci,
  `righttype4` text COLLATE utf8_unicode_ci,
  `rightids` varchar(255) COLLATE utf8_unicode_ci DEFAULT '1,2,4,8',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `ci_admin`
--

INSERT INTO `ci_admin` (`uid`, `username`, `userpwd`, `status`, `name`, `mobile`, `lever`, `roleid`, `righttype1`, `righttype2`, `righttype8`, `righttype4`, `rightids`) VALUES
(1, 'admin', '604b02277c0a82ee964e69155ebba491', 1, '超级管理员', '13116139607', NULL, 0, NULL, NULL, NULL, NULL, ''),
(2, 'zcx', '7a19d6ae40fc700a0db6ba99782aeef9', 1, '张常星', '13212348888', '1,2,219,3,5,85,86,11,155,156,163,164,165,167,168,169,180,181,184,185,188,189,190,192,193,202,22,23,24,223,224,225,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,58,59,62,6,10,7,8,88,63,64,65,67,68,69,70,72,73,74,75,77,78,79,97,118,98,121,122', 1, '5,6,7,8,9,10,11,1', '2,3,4', '1,2', '1', '1,2,4,8'),
(3, 'wf', '3891462cc9dbcd80ac1440ed41a0eaa8', 1, '王芳', '13112348888', '1,2,3,5,85,86,11,12,13,155,156,163,164,165,166,167,168,169,170,180,181,182,183,184,185,186,188,189,190,191,192,193,194,195,202,210,212,22,23,24,223,224,225,226,227,228,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,58,59,60,61,62,91,92,6,10,220,7,8,88,89,9,90,63,64,65,66,67,93,94,68,69,70,71,72,95,96,73,74,75,76,77,78,79,80,83,97,118,119,120,98,121,122', 0, '5,6,7,8,9,10,11,1', '2,3,4', '1,2', '1', '1,2,4,8'),
(4, 'wfvip', '3891462cc9dbcd80ac1440ed41a0eaa8', 1, '王芳', '13112348888', NULL, 0, NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- 表的结构 `ci_assistingprop`
--

CREATE TABLE IF NOT EXISTS `ci_assistingprop` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `disable` tinyint(1) DEFAULT '0' COMMENT '状态',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_assistsku`
--

CREATE TABLE IF NOT EXISTS `ci_assistsku` (
  `skuId` int(11) NOT NULL AUTO_INCREMENT,
  `skuClassId` int(11) DEFAULT '0',
  `skuAssistId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skuName` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`skuId`),
  KEY `id` (`skuClassId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_category`
--

CREATE TABLE IF NOT EXISTS `ci_category` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `parentId` smallint(6) DEFAULT '0' COMMENT '上级栏目ID',
  `path` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目路径',
  `level` tinyint(2) DEFAULT '1' COMMENT '层次',
  `ordnum` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `typeNumber` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '区别',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `detail` tinyint(4) DEFAULT '1',
  `sortIndex` smallint(6) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `parentId` (`parentId`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `ci_category`
--

INSERT INTO `ci_category` (`id`, `name`, `parentId`, `path`, `level`, `ordnum`, `status`, `typeNumber`, `remark`, `detail`, `sortIndex`, `isDelete`) VALUES
(4, '大唐隆德电厂', 0, '', 1, 0, 1, 'customertype', '', 1, 0, 1),
(5, '京东商城', 0, '', 1, 0, 1, 'supplytype', '', 1, 0, 1),
(6, '国电', 0, '', 1, 0, 1, 'customertype', '', 1, 0, 0),
(7, '网络平台', 0, '', 1, 0, 1, 'supplytype', '', 1, 0, 0),
(8, '厂商', 0, '', 1, 0, 1, 'supplytype', '', 1, 0, 0),
(9, '原材料', 0, '9', 1, 0, 1, 'trade', '', 1, 0, 0),
(10, '采集卡', 9, '9,10', 2, 0, 1, 'trade', '', 1, 0, 1),
(11, '主板', 9, '9,11', 2, 0, 1, 'trade', '', 1, 0, 1),
(12, '内存', 9, '9,12', 2, 0, 1, 'trade', '', 1, 0, 1),
(13, '硬盘', 9, '9,13', 2, 0, 1, 'trade', '', 1, 0, 1),
(14, '半成品', 0, '14', 1, 0, 1, 'trade', '', 1, 0, 0),
(15, '防护装置pcb', 14, '14,15', 2, 0, 1, 'trade', '', 1, 0, 0),
(16, '产成品', 0, '16', 1, 0, 1, 'trade', '', 1, 0, 0),
(17, '防护装置', 16, '16,17', 2, 0, 1, 'trade', '', 1, 0, 0),
(18, 'U口从标签pcb', 14, '14,18', 2, 0, 1, 'trade', '', 1, 0, 1),
(19, 'U口主标签pcb', 14, '14,19', 2, 0, 1, 'trade', '', 1, 0, 1),
(20, 'U口从标签', 16, '16,20', 2, 0, 1, 'trade', '', 1, 0, 0),
(21, 'U口主标签', 16, '16,21', 2, 0, 1, 'trade', '', 1, 0, 0),
(22, '外购成品', 0, '22', 1, 0, 1, 'trade', '', 1, 0, 0),
(23, '防护装置配线', 22, '22,23', 2, 0, 1, 'trade', '', 1, 0, 0),
(24, '防护装置原材料', 9, '9,24', 2, 0, 1, 'trade', '', 1, 0, 0),
(25, '内部', 0, '', 1, 0, 1, 'customertype', '', 1, 0, 0),
(26, '服务器', 22, '22,26', 2, 0, 1, 'trade', '', 1, 0, 0),
(27, '研华工控机', 26, '22,26,27', 3, 0, 1, 'trade', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_contact`
--

CREATE TABLE IF NOT EXISTS `ci_contact` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '客户名称',
  `number` varchar(50) DEFAULT '0' COMMENT '客户编号',
  `cCategory` smallint(6) DEFAULT '0' COMMENT '客户类别',
  `cCategoryName` varchar(50) DEFAULT '' COMMENT '分类名称',
  `taxRate` double DEFAULT '0' COMMENT '税率',
  `amount` double DEFAULT '0' COMMENT '期初应付款',
  `difMoney` double DEFAULT '0',
  `periodMoney` double DEFAULT '0' COMMENT '期初预付款',
  `beginDate` date DEFAULT NULL COMMENT '余额日期',
  `remark` varchar(100) DEFAULT '' COMMENT '备注',
  `linkMans` text COMMENT '客户联系方式',
  `type` tinyint(1) DEFAULT '-10' COMMENT '-10客户  10供应商',
  `contact` varchar(255) DEFAULT '',
  `cLevel` smallint(5) DEFAULT '1' COMMENT '客户等级ID',
  `cLevelName` varchar(50) DEFAULT '' COMMENT '客户等级',
  `pinYin` varchar(50) DEFAULT '',
  `disable` tinyint(1) DEFAULT '0' COMMENT '0启用   1禁用',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '0正常 1删除',
  PRIMARY KEY (`id`),
  KEY `number` (`number`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `ci_contact`
--

INSERT INTO `ci_contact` (`id`, `name`, `number`, `cCategory`, `cCategoryName`, `taxRate`, `amount`, `difMoney`, `periodMoney`, `beginDate`, `remark`, `linkMans`, `type`, `contact`, `cLevel`, `cLevelName`, `pinYin`, `disable`, `isDelete`) VALUES
(3, '国电大开项目', 'XM009', 6, '国电', 0, 0, 0, 0, '2017-03-02', '', '[]', -10, '', 0, '0', '', 0, 0),
(4, '淘宝商城', 'PT001', 7, '网络平台', 0, 0, 0, 0, '2017-03-02', '', '[]', 10, '', 0, '', '', 0, 0),
(5, '沧州宾海机柜制造有限公司', 'CS002', 8, '厂商', 0, 0, 0, 0, '2017-03-02', '', '[]', 10, '', 0, '', '', 0, 0),
(6, '廊坊特恩普电子科技有限公司', 'CS001', 8, '厂商', 0, 0, 0, 0, '2017-03-02', '', '[]', 10, '', 0, '', '', 0, 0),
(7, '北京韦斯达通科技有限公司', 'CS003', 8, '厂商', 0, 0, 0, 0, '2017-03-02', '', '[]', 10, '', 0, '', '', 0, 0),
(8, '大同二电项目', 'XM001', 6, '国电', 0, 0, 0, 0, '2017-03-02', '', '[]', -10, '', 0, '0', '', 0, 0),
(9, '京东商城', 'PT002', 7, '网络平台', 0, 0, 0, 0, '2017-03-02', '', '[]', 10, '', 0, '', '', 0, 0),
(10, '北京中电瑞铠科技有限公司', '1', 25, '内部', 0, 0, 0, 0, '2017-03-03', '', '[]', -10, '', 0, '0', '', 0, 0),
(11, '北京天开创新技术有限公司', 'CS004', 8, '厂商', 0, 0, 0, 0, '2017-04-26', '', '[]', 10, '', 0, '', '', 0, 0),
(12, '北京完美科学技术研究所', 'CS005', 8, '厂商', 0, 0, 0, 0, '2017-04-26', '', '[]', 10, '', 0, '', '', 0, 0),
(13, '北京金盛达科技有限公司', 'CS006', 8, '厂商', 0, 0, 0, 0, '2017-04-26', '', '[]', 10, '', 0, '', '', 0, 0),
(14, '北京元大兴业科技有限公司', 'CS007', 8, '厂商', 0, 0, 0, 0, '2017-04-26', '', '[]', 10, '', 0, '', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_goods`
--

CREATE TABLE IF NOT EXISTS `ci_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `number` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '商品编号',
  `quantity` double DEFAULT '0' COMMENT '起初数量',
  `spec` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '规格',
  `baseUnitId` smallint(6) DEFAULT '0' COMMENT '单位ID',
  `unitName` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单位名称',
  `categoryId` smallint(6) DEFAULT '0' COMMENT '商品分类ID',
  `categoryName` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '分类名称',
  `purPrice` double DEFAULT '0' COMMENT '预计采购价',
  `salePrice` double DEFAULT '0' COMMENT '预计销售价',
  `unitCost` double DEFAULT '0' COMMENT '单位成本',
  `amount` double DEFAULT '0' COMMENT '期初总价',
  `remark` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `goods` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `propertys` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '初期设置',
  `vipPrice` double DEFAULT '0' COMMENT '会员价',
  `lowQty` double DEFAULT '0',
  `length` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `height` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `highQty` double DEFAULT '0',
  `isSerNum` double DEFAULT '0',
  `barCode` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `discountRate1` double DEFAULT '0' COMMENT '0',
  `discountRate2` double DEFAULT '0',
  `locationId` int(11) DEFAULT '0',
  `locationName` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `wholesalePrice` double DEFAULT '0',
  `width` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `skuAssistId` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '辅助属性分类',
  `pinYin` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `unitId` smallint(6) DEFAULT '0',
  `files` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '图片路径',
  `disable` tinyint(1) DEFAULT '0' COMMENT '0启用   1禁用',
  `unitTypeId` int(11) DEFAULT '0',
  `assistIds` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `assistName` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `assistUnit` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `jianxing` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `josl` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `skuClassId` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `property` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `safeDays` double DEFAULT '0',
  `advanceDay` double DEFAULT '0',
  `isWarranty` double DEFAULT '0',
  `delete` int(11) DEFAULT '0',
  `weight` double DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '0正常  1删除',
  `warehouseWarning` tinyint(1) DEFAULT '0',
  `warehousePropertys` text,
  PRIMARY KEY (`id`),
  KEY `number` (`number`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `ci_goods`
--

INSERT INTO `ci_goods` (`id`, `name`, `number`, `quantity`, `spec`, `baseUnitId`, `unitName`, `categoryId`, `categoryName`, `purPrice`, `salePrice`, `unitCost`, `amount`, `remark`, `status`, `goods`, `propertys`, `vipPrice`, `lowQty`, `length`, `height`, `highQty`, `isSerNum`, `barCode`, `discountRate1`, `discountRate2`, `locationId`, `locationName`, `wholesalePrice`, `width`, `skuAssistId`, `pinYin`, `unitId`, `files`, `disable`, `unitTypeId`, `assistIds`, `assistName`, `assistUnit`, `jianxing`, `josl`, `skuClassId`, `property`, `safeDays`, `advanceDay`, `isWarranty`, `delete`, `weight`, `isDelete`, `warehouseWarning`, `warehousePropertys`) VALUES
(8, '采集卡', '001', 0, 'HDCAP_plus', 2, '块', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'cjk', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(9, '主板', '002', 0, 'DN2008', 2, '块', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'zb', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(10, '内存', '003', 0, '4G', 2, '块', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'nc', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(11, '硬盘', '004', 0, '120G', 2, '块', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'yp', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(12, '防护装置PCB', '005', 0, '', 2, '块', 15, '防护装置pcb', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'fhzzPCB', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(13, '防护装置外壳', '006', 0, '', 3, '台', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'fhzzwk', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(14, 'U盘', '007', 0, '8G', 1, '个', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'Up', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(15, 'DVI-VGA线', '016', 0, '', 1, '个', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'DVI-VGAx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(16, 'DVI-DVI线', '017', 0, '', 1, '个', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'DVI-DVIx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(17, 'USB连接线', '018', 0, '', 1, '个', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'USBljx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(18, 'com线', '011', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'comx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(19, 'USBhub线', '012', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'USBhubx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(20, '安全防护装置', '030', 0, 'RK-3000', 3, '台', 17, '防护装置', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'aqfhzz', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(21, '网口线', '008', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'wkx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(22, '电源2P线', '009', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'dy2Px', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(23, 'USB带帽（短）线', '010', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'USBdmdx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(24, 'PCI-E线', '013', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'PCI-Ex', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(25, 'com数据连接线', '014', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'comsjljx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(26, '网线', '015', 0, '', 4, '根', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'wx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(27, '电源适配器', '019', 0, '', 1, '个', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'dyspq', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(28, '三合一线', '020', 0, '', 4, '根', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'shyx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(29, 'DVI-VGA转接头', '021', 0, '', 4, '根', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'DVI-VGAzjt', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(30, 'VGA-VGA线', '022', 0, '', 4, '根', 23, '防护装置配线', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'VGA-VGAx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(31, '读卡器', '023', 0, '', 3, '台', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'dkq', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(32, '卡', '024', 0, '', 4, '根', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'k', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(33, '风扇', '025', 0, '', 1, '个', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'fs', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(34, '测试', '04111710', 0, '测试', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'cs', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 1, 0, ''),
(35, '鼠标', '026', 0, 'USB', 1, '个', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'sb', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(36, '键盘', '027', 0, 'USB', 1, '个', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'jp', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(37, '交换机', '028', 0, 'H3C 24口', 1, '个', 22, '外购成品', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'jhj', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(38, 'USB带帽（长）线', '029', 0, '', 4, '根', 24, '防护装置原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'USBdmcx', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(39, '硬盘', '031', 0, '60G', 2, '块', 9, '原材料', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'yp', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, ''),
(40, '服务器', '032', 0, '', 3, '台', 27, '研华工控机', 0, 0, 0, 0, '', 1, '', '[]', 0, 0, '', '', 0, 0, '', 0, 0, 1, '总仓库', 0, '', NULL, 'fwq', 0, NULL, 0, 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `ci_goods_img`
--

CREATE TABLE IF NOT EXISTS `ci_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `invId` int(11) DEFAULT '0',
  `type` varchar(100) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  `thumbnailUrl` varchar(255) DEFAULT '',
  `size` int(11) DEFAULT '0',
  `deleteUrl` varchar(255) DEFAULT '',
  `deleteType` varchar(50) DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `invId` (`invId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_invoice`
--

CREATE TABLE IF NOT EXISTS `ci_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buId` smallint(6) DEFAULT '0' COMMENT '供应商ID',
  `billNo` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单据编号',
  `uid` smallint(6) DEFAULT '0',
  `userName` varchar(50) DEFAULT '' COMMENT '制单人',
  `transType` int(11) DEFAULT '0' COMMENT '150501购货 150502退货 150601销售 150602退销 150706其他入库',
  `totalAmount` double DEFAULT '0' COMMENT '购货总金额',
  `amount` double DEFAULT '0' COMMENT '折扣后金额',
  `rpAmount` double DEFAULT '0' COMMENT '本次付款',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `description` varchar(100) DEFAULT '' COMMENT '备注',
  `arrears` double DEFAULT '0' COMMENT '本次欠款',
  `disRate` double DEFAULT '0' COMMENT '折扣率',
  `disAmount` double DEFAULT '0' COMMENT '折扣金额',
  `totalQty` double DEFAULT '0' COMMENT '总数量',
  `totalArrears` double DEFAULT '0',
  `billStatus` tinyint(1) DEFAULT '0' COMMENT '订单状态 ',
  `checkName` varchar(50) DEFAULT '' COMMENT '采购单审核人',
  `totalTax` double DEFAULT '0',
  `totalTaxAmount` double DEFAULT '0',
  `createTime` datetime DEFAULT NULL,
  `checked` tinyint(1) DEFAULT '0' COMMENT '采购单状态',
  `accId` tinyint(4) DEFAULT '0' COMMENT '结算账户ID',
  `billType` varchar(20) DEFAULT '' COMMENT 'PO采购订单 OI其他入库 PUR采购入库 BAL初期余额',
  `modifyTime` datetime DEFAULT NULL COMMENT '更新时间',
  `hxStateCode` tinyint(4) DEFAULT '0' COMMENT '0未付款  1部分付款  2全部付款',
  `transTypeName` varchar(20) DEFAULT '',
  `totalDiscount` double DEFAULT '0',
  `salesId` smallint(6) DEFAULT '0' COMMENT '销售人员ID',
  `customerFree` double DEFAULT '0' COMMENT '客户承担费用',
  `hxAmount` double DEFAULT '0' COMMENT '本次核销金额',
  `payment` double DEFAULT '0' COMMENT '本次预收款',
  `discount` double DEFAULT '0' COMMENT '整单折扣',
  `srcOrderNo` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `srcOrderId` int(11) DEFAULT '0',
  `postData` text COMMENT '提交订单明细 ',
  `locationId` varchar(255) DEFAULT '',
  `inLocationId` varchar(255) DEFAULT '' COMMENT '调入仓库ID多个,分割',
  `outLocationId` varchar(255) DEFAULT '' COMMENT '调出仓库ID多个,分割',
  `paySettacctId` varchar(255) DEFAULT '',
  `recSettacctId` varchar(255) DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '1删除  0正常',
  PRIMARY KEY (`id`),
  KEY `accId` (`accId`),
  KEY `buId` (`buId`),
  KEY `salesId` (`salesId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- 转存表中的数据 `ci_invoice`
--

INSERT INTO `ci_invoice` (`id`, `buId`, `billNo`, `uid`, `userName`, `transType`, `totalAmount`, `amount`, `rpAmount`, `billDate`, `description`, `arrears`, `disRate`, `disAmount`, `totalQty`, `totalArrears`, `billStatus`, `checkName`, `totalTax`, `totalTaxAmount`, `createTime`, `checked`, `accId`, `billType`, `modifyTime`, `hxStateCode`, `transTypeName`, `totalDiscount`, `salesId`, `customerFree`, `hxAmount`, `payment`, `discount`, `srcOrderNo`, `srcOrderId`, `postData`, `locationId`, `inLocationId`, `outLocationId`, `paySettacctId`, `recSettacctId`, `isDelete`) VALUES
(53, 4, 'CG201704271029290', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 5, 0, 0, '', 0, 0, '2017-04-27 10:29:34', 0, 0, 'PUR', '2017-04-27 10:29:34', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271026006', 15, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271029290";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:15:"防护装置PCB";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";i:5;s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:6:"pcb002";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"15";s:10:"srcOrderNo";s:19:"CGDD201704271026006";}}s:8:"totalQty";d:5;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:29:34";s:10:"createTime";s:19:"2017-04-27 10:29:34";s:10:"srcOrderNo";s:19:"CGDD201704271026006";s:10:"srcOrderId";s:2:"15";}', '', '', '', '', '', 0),
(54, 0, 'ZZD201704271030452', 1, '超级管理员', 153301, 0, 0, 0, '2017-04-27', '001', 0, 0, 0, 1, 0, 0, '', 0, 0, '2017-04-27 10:30:45', 0, 0, 'ZZD', '2017-04-27 10:30:45', 0, '组装单', 0, 0, 0, 0, 0, 0, NULL, 0, 'a:16:{s:2:"id";i:-1;s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:18:"ZZD201704271030452";s:7:"entries";a:2:{i:0;a:19:{s:5:"invId";s:2:"20";s:9:"invNumber";s:3:"030";s:7:"invName";s:18:"安全防护装置";s:7:"invSpec";s:7:"RK-3000";s:6:"unitId";s:1:"0";s:8:"mainUnit";s:3:"台";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:3:"qty";s:1:"1";s:9:"assembler";s:0:"";s:14:"version_number";s:0:"";s:7:"checker";s:0:"";s:10:"check_time";s:0:"";s:9:"fire_time";s:0:"";s:5:"firer";s:0:"";s:5:"price";s:1:"0";s:6:"amount";s:4:"0.00";s:10:"locationId";s:1:"1";s:12:"locationName";s:9:"总仓库";}i:1;a:14:{s:5:"invId";s:2:"12";s:9:"invNumber";s:3:"005";s:7:"invName";s:15:"防护装置PCB";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";s:1:"0";s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";i:0;s:6:"amount";i:0;s:10:"locationId";s:1:"1";s:12:"locationName";s:9:"总仓库";s:11:"description";s:6:"PCB007";}}s:8:"totalQty";d:1;s:11:"totalAmount";d:0;s:6:"amount";d:0;s:11:"description";s:3:"001";s:8:"billDate";s:10:"2017-04-27";s:9:"transType";i:153301;s:8:"billType";s:3:"ZZD";s:13:"transTypeName";s:9:"组装单";s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:30:45";s:10:"createTime";s:19:"2017-04-27 10:30:45";}', '', '', '', '', '', 0),
(55, 0, 'ZZD201704271032059', 1, '超级管理员', 153301, 0, 0, 0, '2017-04-27', 'PCBVC', 0, 0, 0, 1, 0, 0, '', 0, 0, '2017-04-27 10:32:05', 0, 0, 'ZZD', '2017-04-27 10:32:05', 0, '组装单', 0, 0, 0, 0, 0, 0, NULL, 0, 'a:16:{s:2:"id";i:-1;s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:18:"ZZD201704271032059";s:7:"entries";a:2:{i:0;a:19:{s:5:"invId";s:2:"20";s:9:"invNumber";s:3:"030";s:7:"invName";s:18:"安全防护装置";s:7:"invSpec";s:7:"RK-3000";s:6:"unitId";s:1:"0";s:8:"mainUnit";s:3:"台";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:3:"qty";s:0:"";s:9:"assembler";s:0:"";s:14:"version_number";s:0:"";s:7:"checker";s:0:"";s:10:"check_time";s:0:"";s:9:"fire_time";s:0:"";s:5:"firer";s:0:"";s:5:"price";s:1:"0";s:6:"amount";s:4:"0.00";s:10:"locationId";s:1:"1";s:12:"locationName";s:9:"总仓库";}i:1;a:14:{s:5:"invId";s:2:"12";s:9:"invNumber";s:3:"005";s:7:"invName";s:15:"防护装置PCB";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";s:1:"0";s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";i:0;s:6:"amount";i:0;s:10:"locationId";s:1:"1";s:12:"locationName";s:9:"总仓库";s:11:"description";s:6:"pcb002";}}s:8:"totalQty";d:1;s:11:"totalAmount";d:0;s:6:"amount";d:0;s:11:"description";s:5:"PCBVC";s:8:"billDate";s:10:"2017-04-27";s:9:"transType";i:153301;s:8:"billType";s:3:"ZZD";s:13:"transTypeName";s:9:"组装单";s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:32:05";s:10:"createTime";s:19:"2017-04-27 10:32:05";}', '', '', '', '', '', 0),
(56, 14, 'CG201704271056449', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 8, 0, 0, '', 0, 0, '2017-04-27 10:56:47', 0, 0, 'PUR', '2017-04-27 10:56:47', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 22, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:14;s:11:"contactName";s:36:"北京元大兴业科技有限公司";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271056449";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:40;s:9:"invNumber";s:3:"032";s:7:"invName";s:9:"服务器";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"台";s:3:"qty";s:1:"8";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427013";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"22";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:8;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:56:47";s:10:"createTime";s:19:"2017-04-27 10:56:47";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"22";}', '', '', '', '', '', 0),
(57, 9, 'CG201704271056596', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 23, 0, 0, '', 0, 0, '2017-04-27 10:57:00', 0, 0, 'PUR', '2017-04-27 10:57:00', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 21, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:9;s:11:"contactName";s:12:"京东商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271056596";s:9:"transType";i:150501;s:7:"entries";a:3:{i:0;a:19:{s:5:"invId";i:10;s:9:"invNumber";s:3:"003";s:7:"invName";s:6:"内存";s:7:"invSpec";s:2:"4G";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"19";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427010";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"21";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:1;a:19:{s:5:"invId";i:11;s:9:"invNumber";s:3:"004";s:7:"invName";s:6:"硬盘";s:7:"invSpec";s:4:"120G";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"3";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427011";s:15:"srcOrderEntryId";s:1:"2";s:10:"srcOrderId";s:2:"21";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:2;a:19:{s:5:"invId";i:39;s:9:"invNumber";s:3:"031";s:7:"invName";s:6:"硬盘";s:7:"invSpec";s:3:"60G";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427012";s:15:"srcOrderEntryId";s:1:"3";s:10:"srcOrderId";s:2:"21";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:23;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:00";s:10:"createTime";s:19:"2017-04-27 10:57:00";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"21";}', '', '', '', '', '', 0),
(58, 11, 'CG201704271057086', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 27, 0, 0, '', 0, 0, '2017-04-27 10:57:10', 0, 0, 'PUR', '2017-04-27 10:57:10', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 20, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:11;s:11:"contactName";s:36:"北京天开创新技术有限公司";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271057086";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:9;s:9:"invNumber";s:3:"002";s:7:"invName";s:6:"主板";s:7:"invSpec";s:6:"DN2008";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"27";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427009";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"20";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:27;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:10";s:10:"createTime";s:19:"2017-04-27 10:57:10";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"20";}', '', '', '', '', '', 0),
(59, 11, 'CG201704271057164', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 27, 0, 0, '', 0, 0, '2017-04-27 10:57:17', 0, 0, 'PUR', '2017-04-27 10:57:17', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 20, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:11;s:11:"contactName";s:36:"北京天开创新技术有限公司";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271057164";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:9;s:9:"invNumber";s:3:"002";s:7:"invName";s:6:"主板";s:7:"invSpec";s:6:"DN2008";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"27";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427009";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"20";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:27;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:17";s:10:"createTime";s:19:"2017-04-27 10:57:17";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"20";}', '', '', '', '', '', 0),
(60, 5, 'CG201704271057242', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 4, 0, 0, '', 0, 0, '2017-04-27 10:57:26', 0, 0, 'PUR', '2017-04-27 10:57:26', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 18, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:5;s:11:"contactName";s:36:"沧州宾海机柜制造有限公司";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271057242";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:13;s:9:"invNumber";s:3:"006";s:7:"invName";s:18:"防护装置外壳";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"台";s:3:"qty";s:1:"4";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427008";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"18";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:4;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:26";s:10:"createTime";s:19:"2017-04-27 10:57:26";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"18";}', '', '', '', '', '', 0),
(61, 4, 'CG201704271057334', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 102, 0, 0, '', 0, 0, '2017-04-27 10:57:35', 0, 0, 'PUR', '2017-04-27 10:57:35', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704261643406', 1, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271057334";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:21;s:9:"invNumber";s:3:"008";s:7:"invName";s:9:"网口线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"102";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:0:"";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:1:"1";s:10:"srcOrderNo";s:19:"CGDD201704261643406";}}s:8:"totalQty";d:102;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:35";s:10:"createTime";s:19:"2017-04-27 10:57:35";s:10:"srcOrderNo";s:19:"CGDD201704261643406";s:10:"srcOrderId";s:1:"1";}', '', '', '', '', '', 0),
(62, 6, 'CG201704271057460', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 15, 0, 0, '', 0, 0, '2017-04-27 10:57:48', 0, 0, 'PUR', '2017-04-27 10:57:48', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 17, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:6;s:11:"contactName";s:39:"廊坊特恩普电子科技有限公司";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271057460";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:15:"防护装置PCB";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"15";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427007";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"17";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:15;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:48";s:10:"createTime";s:19:"2017-04-27 10:57:48";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"17";}', '', '', '', '', '', 0),
(63, 4, 'CG201704271058024', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 462, 0, 0, '', 0, 0, '2017-04-27 10:58:04', 0, 0, 'PUR', '2017-04-27 10:58:04', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 16, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271058024";s:9:"transType";i:150501;s:7:"entries";a:6:{i:0;a:19:{s:5:"invId";i:22;s:9:"invNumber";s:3:"009";s:7:"invName";s:11:"电源2P线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"113";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427001";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:1;a:19:{s:5:"invId";i:19;s:9:"invNumber";s:3:"012";s:7:"invName";s:9:"USBhub线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"105";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427002";s:15:"srcOrderEntryId";s:1:"2";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:2;a:19:{s:5:"invId";i:24;s:9:"invNumber";s:3:"013";s:7:"invName";s:8:"PCI-E线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"67";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427003";s:15:"srcOrderEntryId";s:1:"3";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:3;a:19:{s:5:"invId";i:25;s:9:"invNumber";s:3:"014";s:7:"invName";s:18:"com数据连接线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"65";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427004";s:15:"srcOrderEntryId";s:1:"4";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:4;a:19:{s:5:"invId";i:18;s:9:"invNumber";s:3:"011";s:7:"invName";s:6:"com线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"63";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427005";s:15:"srcOrderEntryId";s:1:"5";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}i:5;a:19:{s:5:"invId";i:33;s:9:"invNumber";s:3:"025";s:7:"invName";s:6:"风扇";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"个";s:3:"qty";s:2:"49";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427006";s:15:"srcOrderEntryId";s:1:"6";s:10:"srcOrderId";s:2:"16";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:462;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:04";s:10:"createTime";s:19:"2017-04-27 10:58:04";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"16";}', '', '', '', '', '', 0),
(64, 4, 'CG201704271058158', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 1, 0, 0, '', 0, 0, '2017-04-27 10:58:16', 0, 0, 'PUR', '2017-04-27 10:58:16', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271026006', 13, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271058158";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:15:"防护装置PCB";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:6:"PCB001";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"13";s:10:"srcOrderNo";s:19:"CGDD201704271026006";}}s:8:"totalQty";d:1;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:16";s:10:"createTime";s:19:"2017-04-27 10:58:16";s:10:"srcOrderNo";s:19:"CGDD201704271026006";s:10:"srcOrderId";s:2:"13";}', '', '', '', '', '', 0),
(65, 4, 'CG201704271058278', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 77, 0, 0, '', 0, 0, '2017-04-27 10:58:28', 0, 0, 'PUR', '2017-04-27 10:58:28', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271018238', 10, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:6:"billNo";s:17:"CG201704271058278";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:38;s:9:"invNumber";s:3:"029";s:7:"invName";s:21:"USB带帽（长）线";s:7:"invSpec";s:0:"";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"77";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:12:"201704271021";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"10";s:10:"srcOrderNo";s:19:"CGDD201704271018238";}}s:8:"totalQty";d:77;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:28";s:10:"createTime";s:19:"2017-04-27 10:58:28";s:10:"srcOrderNo";s:19:"CGDD201704271018238";s:10:"srcOrderId";s:2:"10";}', '', '', '', '', '', 0),
(66, 11, 'CG201704281047110', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-28', '', 0, 0, 0, 27, 0, 0, '', 0, 0, '2017-04-28 10:47:20', 0, 0, 'PUR', '2017-04-28 10:47:20', 2, '购货', 0, 0, 0, 0, 0, 0, 'CGDD201704271043378', 20, 'a:28:{s:2:"id";i:-1;s:4:"buId";i:11;s:11:"contactName";s:36:"北京天开创新技术有限公司";s:4:"date";s:10:"2017-04-28";s:6:"billNo";s:17:"CG201704281047110";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:19:{s:5:"invId";i:9;s:9:"invNumber";s:3:"002";s:7:"invName";s:6:"主板";s:7:"invSpec";s:6:"DN2008";s:5:"skuId";s:2:"-1";s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"27";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";s:11:"description";s:11:"20170427009";s:15:"srcOrderEntryId";s:1:"1";s:10:"srcOrderId";s:2:"20";s:10:"srcOrderNo";s:19:"CGDD201704271043378";}}s:8:"totalQty";d:27;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";i:0;s:8:"rpAmount";i:0;s:7:"arrears";i:0;s:12:"totalArrears";d:0;s:5:"accId";i:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-28";s:8:"accounts";a:0:{}s:11:"hxStateCode";i:2;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-28 10:47:20";s:10:"createTime";s:19:"2017-04-28 10:47:20";s:10:"srcOrderNo";s:19:"CGDD201704271043378";s:10:"srcOrderId";s:2:"20";}', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_invoice_img`
--

CREATE TABLE IF NOT EXISTS `ci_invoice_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `billNo` varchar(50) DEFAULT '',
  `type` varchar(100) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  `thumbnailUrl` varchar(255) DEFAULT '',
  `size` int(11) DEFAULT '0',
  `deleteUrl` varchar(255) DEFAULT '',
  `deleteType` varchar(50) DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `invId` (`billNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_invoice_info`
--

CREATE TABLE IF NOT EXISTS `ci_invoice_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) DEFAULT '0' COMMENT '关联ID',
  `buId` smallint(6) DEFAULT '0' COMMENT '供应商ID',
  `billNo` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单据编号',
  `transType` int(11) DEFAULT '0' COMMENT '150501采购 150502退货',
  `amount` double DEFAULT '0' COMMENT '购货金额',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `description` varchar(50) DEFAULT '' COMMENT '备注',
  `invId` int(11) DEFAULT '0' COMMENT '商品ID',
  `price` double DEFAULT '0' COMMENT '单价',
  `deduction` double DEFAULT '0' COMMENT '折扣额',
  `discountRate` double DEFAULT '0' COMMENT '折扣率',
  `qty` double DEFAULT '0' COMMENT '数量',
  `locationId` smallint(6) DEFAULT '0',
  `tax` double DEFAULT '0',
  `taxRate` double DEFAULT '0',
  `taxAmount` double DEFAULT '0',
  `unitId` smallint(6) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `skuId` int(11) DEFAULT '0',
  `checked` tinyint(1) DEFAULT '0',
  `checkName` varchar(50) DEFAULT '',
  `entryId` tinyint(1) DEFAULT '1' COMMENT '区分调拨单  进和出',
  `transTypeName` varchar(25) DEFAULT '',
  `srcOrderEntryId` int(11) DEFAULT '0',
  `srcOrderId` int(11) DEFAULT '0',
  `srcOrderNo` varchar(25) DEFAULT '',
  `billType` varchar(20) DEFAULT '',
  `salesId` smallint(6) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '1删除 0正常',
  `assembler` varchar(255) NOT NULL COMMENT '组装人',
  `version_number` varchar(255) NOT NULL COMMENT '软件版本号',
  `checker` varchar(255) NOT NULL COMMENT '测试人',
  `check_time` varchar(255) NOT NULL COMMENT '测试时间',
  `fire_time` varchar(255) NOT NULL COMMENT '拷机时间',
  `firer` varchar(255) NOT NULL COMMENT '拷机人',
  PRIMARY KEY (`id`),
  KEY `type` (`transType`),
  KEY `billdate` (`billDate`),
  KEY `invId` (`invId`) USING BTREE,
  KEY `transType` (`transType`),
  KEY `iid` (`iid`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=271 ;

--
-- 转存表中的数据 `ci_invoice_info`
--

INSERT INTO `ci_invoice_info` (`id`, `iid`, `buId`, `billNo`, `transType`, `amount`, `billDate`, `description`, `invId`, `price`, `deduction`, `discountRate`, `qty`, `locationId`, `tax`, `taxRate`, `taxAmount`, `unitId`, `uid`, `skuId`, `checked`, `checkName`, `entryId`, `transTypeName`, `srcOrderEntryId`, `srcOrderId`, `srcOrderNo`, `billType`, `salesId`, `isDelete`, `assembler`, `version_number`, `checker`, `check_time`, `fire_time`, `firer`) VALUES
(249, 53, 4, 'CG201704271029290', 150501, 0, '2017-04-27', 'pcb002', 12, 0, 0, 0, 5, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 15, 'CGDD201704271026006', 'PUR', 0, 0, '', '', '', '', '', ''),
(250, 54, 0, 'ZZD201704271030452', 153301, 0, '2017-04-27', NULL, 20, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, -1, 0, '', 1, '组装单', 0, 0, '', 'ZZD', 0, 0, '', '', '', '', '', ''),
(251, 54, 0, 'ZZD201704271030452', 153301, 0, '2017-04-27', 'PCB007', 12, 0, 0, 0, -1, 1, 0, 0, 0, 0, 0, -1, 0, '', 1, '组装单', 0, 0, '', 'ZZD', 0, 0, '', '', '', '', '', ''),
(252, 55, 0, 'ZZD201704271032059', 153301, 0, '2017-04-27', NULL, 20, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, -1, 0, '', 1, '组装单', 0, 0, '', 'ZZD', 0, 0, '', '', '', '', '', ''),
(253, 55, 0, 'ZZD201704271032059', 153301, 0, '2017-04-27', 'pcb002', 12, 0, 0, 0, -1, 1, 0, 0, 0, 0, 0, -1, 0, '', 1, '组装单', 0, 0, '', 'ZZD', 0, 0, '', '', '', '', '', ''),
(254, 56, 14, 'CG201704271056449', 150501, 0, '2017-04-27', NULL, 40, 0, 0, 0, 8, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 22, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(255, 57, 9, 'CG201704271056596', 150501, 0, '2017-04-27', NULL, 10, 0, 0, 0, 19, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 21, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(256, 57, 9, 'CG201704271056596', 150501, 0, '2017-04-27', NULL, 11, 0, 0, 0, 3, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 2, 21, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(257, 57, 9, 'CG201704271056596', 150501, 0, '2017-04-27', NULL, 39, 0, 0, 0, 1, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 3, 21, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(259, 59, 11, 'CG201704271057164', 150501, 0, '2017-04-27', NULL, 9, 0, 0, 0, 27, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 20, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(260, 60, 5, 'CG201704271057242', 150501, 0, '2017-04-27', NULL, 13, 0, 0, 0, 4, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 18, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(261, 61, 4, 'CG201704271057334', 150501, 0, '2017-04-27', NULL, 21, 0, 0, 0, 102, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 1, 'CGDD201704261643406', 'PUR', 0, 0, '', '', '', '', '', ''),
(262, 62, 6, 'CG201704271057460', 150501, 0, '2017-04-27', NULL, 12, 0, 0, 0, 15, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 17, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(263, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 22, 0, 0, 0, 113, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(264, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 19, 0, 0, 0, 105, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 2, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(265, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 24, 0, 0, 0, 67, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 3, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(266, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 25, 0, 0, 0, 65, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 4, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(267, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 18, 0, 0, 0, 63, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 5, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(268, 63, 4, 'CG201704271058024', 150501, 0, '2017-04-27', NULL, 33, 0, 0, 0, 49, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 6, 16, 'CGDD201704271043378', 'PUR', 0, 0, '', '', '', '', '', ''),
(269, 64, 4, 'CG201704271058158', 150501, 0, '2017-04-27', 'PCB001', 12, 0, 0, 0, 1, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 13, 'CGDD201704271026006', 'PUR', 0, 0, '', '', '', '', '', ''),
(270, 65, 4, 'CG201704271058278', 150501, 0, '2017-04-27', NULL, 38, 0, 0, 0, 77, 1, 0, 0, 0, -1, 1, -1, 0, '', 1, '购货', 1, 10, 'CGDD201704271018238', 'PUR', 0, 0, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ci_invoice_type`
--

CREATE TABLE IF NOT EXISTS `ci_invoice_type` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `inout` tinyint(1) DEFAULT '1' COMMENT '1 入库  -1出库',
  `status` tinyint(1) DEFAULT '1',
  `type` varchar(10) DEFAULT '',
  `default` tinyint(1) DEFAULT '0',
  `number` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `ci_invoice_type`
--

INSERT INTO `ci_invoice_type` (`id`, `name`, `inout`, `status`, `type`, `default`, `number`) VALUES
(1, '其他入库', 1, 1, 'in', 1, 150706),
(2, '盘盈', 1, 1, 'in', 0, 150701),
(3, '其他出库', -1, 1, 'out', 1, 150806),
(4, '盘亏', -1, 1, 'out', 0, 150801);

-- --------------------------------------------------------

--
-- 表的结构 `ci_invtemplate`
--

CREATE TABLE IF NOT EXISTS `ci_invtemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单据编号',
  `billNo` varchar(50) DEFAULT '',
  `uid` smallint(6) DEFAULT '0',
  `userName` varchar(50) DEFAULT '' COMMENT '制单人',
  `totalAmount` double DEFAULT '0' COMMENT '购货总金额',
  `amount` double DEFAULT '0' COMMENT '折扣后金额',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `description` varchar(100) DEFAULT '' COMMENT '备注',
  `totalQty` double DEFAULT '0' COMMENT '总数量',
  `postData` text COMMENT '提交订单明细 ',
  `templateName` varchar(50) DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '1删除  0正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_log`
--

CREATE TABLE IF NOT EXISTS `ci_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` smallint(6) DEFAULT '0' COMMENT '用户ID',
  `ip` varchar(25) DEFAULT '',
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '姓名',
  `log` text COMMENT '日志内容',
  `type` tinyint(1) DEFAULT '1' COMMENT ' ',
  `loginName` varchar(50) DEFAULT '' COMMENT '用户名',
  `modifyTime` datetime DEFAULT NULL COMMENT '写入日期',
  `operateTypeName` varchar(50) DEFAULT '',
  `adddate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `adddate` (`adddate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

--
-- 转存表中的数据 `ci_log`
--

INSERT INTO `ci_log` (`id`, `userId`, `ip`, `name`, `log`, `type`, `loginName`, `modifyTime`, `operateTypeName`, `adddate`) VALUES
(11, NULL, '10.3.10.40', NULL, '登陆成功 用户名：wf', 1, NULL, '2017-04-26 16:27:54', '', '2017-04-26'),
(12, 3, '10.3.10.40', '王芳', '新增商品:USB带帽（长）线', 1, 'wf', '2017-04-26 16:37:24', '', '2017-04-26'),
(13, 3, '10.3.10.40', '王芳', '新增采购订单 单据编号：CGDD201704261643406', 1, 'wf', '2017-04-26 16:44:39', '', '2017-04-26'),
(14, NULL, '10.3.10.40', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-26 16:46:36', '', '2017-04-26'),
(15, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-26 16:53:11', '', '2017-04-26'),
(16, 1, '10.3.10.40', '超级管理员', '修改商品:ID=19名称:USBhub线', 1, 'admin', '2017-04-26 16:55:40', '', '2017-04-26'),
(17, 1, '10.3.10.40', '超级管理员', '修改商品:ID=22名称:电源2P线', 1, 'admin', '2017-04-26 16:55:52', '', '2017-04-26'),
(18, 1, '10.3.10.40', '超级管理员', '修改商品:ID=38名称:USB带帽（长）线', 1, 'admin', '2017-04-26 16:56:02', '', '2017-04-26'),
(19, NULL, '10.3.10.40', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-27 10:18:18', '', '2017-04-27'),
(20, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271018238', 1, 'admin', '2017-04-27 10:19:00', '', '2017-04-27'),
(21, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271026006', 1, 'admin', '2017-04-27 10:27:26', '', '2017-04-27'),
(22, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271026006', 1, 'admin', '2017-04-27 10:29:01', '', '2017-04-27'),
(23, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271026006的单据已被审核！', 1, 'admin', '2017-04-27 10:29:25', '', '2017-04-27'),
(24, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271029290', 1, 'admin', '2017-04-27 10:29:34', '', '2017-04-27'),
(25, 1, '10.3.10.40', '超级管理员', '新增组装单 单据编号：ZZD201704271030452', 1, 'admin', '2017-04-27 10:30:45', '', '2017-04-27'),
(26, 1, '10.3.10.40', '超级管理员', '新增组装单 单据编号：ZZD201704271032059', 1, 'admin', '2017-04-27 10:32:05', '', '2017-04-27'),
(27, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:46:21', '', '2017-04-27'),
(28, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:47:20', '', '2017-04-27'),
(29, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:47:59', '', '2017-04-27'),
(30, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:49:39', '', '2017-04-27'),
(31, 1, '10.3.10.40', '超级管理员', '新增商品:硬盘', 1, 'admin', '2017-04-27 10:52:29', '', '2017-04-27'),
(32, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:53:19', '', '2017-04-27'),
(33, 1, '10.3.10.40', '超级管理员', '新增商品:服务器', 1, 'admin', '2017-04-27 10:55:03', '', '2017-04-27'),
(34, 1, '10.3.10.40', '超级管理员', '新增采购订单 单据编号：CGDD201704271043378', 1, 'admin', '2017-04-27 10:55:30', '', '2017-04-27'),
(35, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:56:43', '', '2017-04-27'),
(36, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271056449', 1, 'admin', '2017-04-27 10:56:47', '', '2017-04-27'),
(37, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:56:58', '', '2017-04-27'),
(38, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271056596', 1, 'admin', '2017-04-27 10:57:00', '', '2017-04-27'),
(39, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:57:07', '', '2017-04-27'),
(40, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271057086', 1, 'admin', '2017-04-27 10:57:10', '', '2017-04-27'),
(41, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271057164', 1, 'admin', '2017-04-27 10:57:17', '', '2017-04-27'),
(42, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:57:23', '', '2017-04-27'),
(43, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271057242', 1, 'admin', '2017-04-27 10:57:26', '', '2017-04-27'),
(44, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704261643406的单据已被审核！', 1, 'admin', '2017-04-27 10:57:32', '', '2017-04-27'),
(45, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271057334', 1, 'admin', '2017-04-27 10:57:35', '', '2017-04-27'),
(46, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:57:45', '', '2017-04-27'),
(47, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271057460', 1, 'admin', '2017-04-27 10:57:48', '', '2017-04-27'),
(48, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271043378的单据已被审核！', 1, 'admin', '2017-04-27 10:58:01', '', '2017-04-27'),
(49, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271058024', 1, 'admin', '2017-04-27 10:58:04', '', '2017-04-27'),
(50, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271026006的单据已被审核！', 1, 'admin', '2017-04-27 10:58:14', '', '2017-04-27'),
(51, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271058158', 1, 'admin', '2017-04-27 10:58:17', '', '2017-04-27'),
(52, 1, '10.3.10.40', '超级管理员', '采购订单 单据编号：CGDD201704271018238的单据已被审核！', 1, 'admin', '2017-04-27 10:58:26', '', '2017-04-27'),
(53, 1, '10.3.10.40', '超级管理员', '新增采购 单据编号：CG201704271058278', 1, 'admin', '2017-04-27 10:58:28', '', '2017-04-27'),
(54, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-27 11:49:53', '', '2017-04-27'),
(55, 1, '10.3.10.40', '超级管理员', '登陆成功 用户名：admin', 1, 'admin', '2017-04-27 17:22:21', '', '2017-04-27'),
(56, NULL, '10.3.10.40', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-28 10:37:51', '', '2017-04-28'),
(57, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-04-28 10:44:15', '', '2017-04-28'),
(58, 1, '10.3.12.50', '超级管理员', '新增采购 单据编号：CG201704281047110', 1, 'admin', '2017-04-28 10:47:20', '', '2017-04-28'),
(59, 1, '10.3.12.50', '超级管理员', '登陆成功 用户名：admin', 1, 'admin', '2017-04-28 11:14:52', '', '2017-04-28'),
(60, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 10:13:56', '', '2017-05-05'),
(61, NULL, '::1', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 10:27:04', '', '2017-05-05'),
(62, NULL, '::1', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 11:40:47', '', '2017-05-05'),
(63, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 11:50:27', '', '2017-05-05'),
(64, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 14:50:05', '', '2017-05-05'),
(65, NULL, '10.3.10.40', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 15:00:14', '', '2017-05-05'),
(66, 1, '10.3.12.50', '超级管理员', '登陆成功 用户名：wf', 1, 'admin', '2017-05-05 15:00:32', '', '2017-05-05'),
(67, NULL, '10.3.10.40', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-05 15:00:44', '', '2017-05-05'),
(68, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-08 10:21:23', '', '2017-05-08'),
(69, NULL, '10.3.12.50', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-08 15:48:13', '', '2017-05-08'),
(70, NULL, '10.3.10.202', NULL, '登陆成功 用户名：admin', 1, NULL, '2017-05-09 10:13:33', '', '2017-05-09');

-- --------------------------------------------------------

--
-- 表的结构 `ci_menu`
--

CREATE TABLE IF NOT EXISTS `ci_menu` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `parentId` smallint(5) DEFAULT '0' COMMENT '上级栏目ID',
  `path` varchar(100) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目路径',
  `level` tinyint(2) DEFAULT '1' COMMENT '层次',
  `ordnum` smallint(6) DEFAULT '0' COMMENT '排序',
  `module` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `typeNumber` varchar(25) COLLATE utf8_unicode_ci DEFAULT '',
  `detail` tinyint(1) DEFAULT '1',
  `sortIndex` smallint(6) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0',
  `remark` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `parentId` (`parentId`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=229 ;

--
-- 转存表中的数据 `ci_menu`
--

INSERT INTO `ci_menu` (`id`, `name`, `parentId`, `path`, `level`, `ordnum`, `module`, `status`, `typeNumber`, `detail`, `sortIndex`, `isDelete`, `remark`) VALUES
(1, '购货单', 0, '1', 1, 99, 'PU_QUERY', 1, 'trade', 1, 0, 0, ''),
(2, '新增', 1, '1,2', 2, 99, 'PU_ADD', 1, 'trade', 1, 0, 0, ''),
(3, '修改', 1, '1,3', 2, 99, 'PU_UPDATE', 1, 'trade', 1, 0, 0, ''),
(4, '删除', 1, '1,4', 2, 99, 'PU_DELETE', 1, 'trade', 1, 0, 0, ''),
(5, '导出', 1, '1,5', 2, 99, 'PU_EXPORT', 1, 'trade', 1, 0, 0, ''),
(6, '销货单', 0, '6', 1, 99, 'SA_QUERY', 1, 'trade', 1, 0, 0, ''),
(7, '新增', 6, '6,7', 2, 99, 'SA_ADD', 1, 'trade', 1, 0, 0, ''),
(8, '修改', 6, '6,8', 2, 99, 'SA_UPDATE', 1, 'trade', 1, 0, 0, ''),
(9, '删除', 6, '6,9', 2, 99, 'SA_DELETE', 1, 'trade', 1, 0, 0, ''),
(10, '导出', 6, '6,10', 2, 99, 'SA_EXPORT', 1, 'trade', 1, 0, 0, ''),
(11, '盘点', 0, '11', 1, 99, 'PD_GENPD', 1, 'trade', 1, 0, 0, ''),
(12, '生成盘点记录', 11, '11,12', 2, 99, 'PD_GENPD', 1, 'trade', 1, 0, 0, ''),
(13, '导出', 11, '11,13', 2, 99, 'PD_EXPORT', 1, 'trade', 1, 0, 0, ''),
(14, '其他入库单', 0, '14', 1, 99, 'IO_QUERY', 1, 'trade', 1, 0, 0, ''),
(15, '新增', 14, '14,15', 2, 99, 'IO_ADD', 1, 'trade', 1, 0, 0, ''),
(16, '修改', 14, '14,16', 2, 99, 'IO_UPDATE', 1, 'trade', 1, 0, 0, ''),
(17, '删除', 14, '14,17', 2, 99, 'IO_DELETE', 1, 'trade', 1, 0, 0, ''),
(18, '其他出库单', 0, '18', 1, 99, 'OO_QUERY', 1, 'trade', 1, 0, 0, ''),
(19, '新增', 18, '18,19', 2, 99, 'OO_ADD', 1, 'trade', 1, 0, 0, ''),
(20, '修改', 18, '18,20', 2, 99, 'OO_UPDATE', 1, 'trade', 1, 0, 0, ''),
(21, '删除', 18, '18,21', 2, 99, 'OO_DELETE', 1, 'trade', 1, 0, 0, ''),
(22, '采购明细表', 0, '22', 1, 99, 'PUREOORTDETAIL_QUERY', 1, 'trade', 1, 0, 0, ''),
(23, '导出', 22, '22,23', 2, 99, 'PUREOORTDETAIL_EXPORT', 1, 'trade', 1, 0, 0, ''),
(24, '打印', 22, '22,24', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(25, '采购汇总表（按商品）', 0, '25', 1, 99, 'PUREPORTINV_QUERY', 1, 'trade', 1, 0, 0, ''),
(26, '导出', 25, '25,26', 2, 99, 'PUREPORTINV_EXPORT', 1, 'trade', 1, 0, 0, ''),
(27, '打印', 25, '25,27', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(28, '采购汇总表（按供应商）', 0, '28', 1, 99, 'PUREPORTPUR_QUERY', 1, 'trade', 1, 0, 0, ''),
(29, '导出', 28, '28,29', 2, 99, 'PUREPORTPUR_EXPORT', 1, 'trade', 1, 0, 0, ''),
(30, '打印', 28, '28,30', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(31, '销售明细表', 0, '31', 1, 99, 'SAREPORTDETAIL_QUERY', 1, 'trade', 1, 0, 0, ''),
(32, '导出', 31, '31,32', 2, 99, 'SAREPORTDETAIL_EXPORT', 1, 'trade', 1, 0, 0, ''),
(33, '打印', 31, '31,33', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(34, '销售汇总表（按商品）', 0, '34', 1, 99, 'SAREPORTINV_QUERY', 1, 'trade', 1, 0, 0, ''),
(35, '导出', 34, '34,35', 2, 99, 'SAREPORTINV_EXPORT', 1, 'trade', 1, 0, 0, ''),
(36, '打印', 34, '34,36', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(37, '销售汇总表（按客户）', 0, '37', 1, 99, 'SAREPORTBU_QUERY', 1, 'trade', 1, 0, 0, ''),
(38, '导出', 37, '37,38', 2, 99, 'SAREPORTBU_EXPORT', 1, 'trade', 1, 0, 0, ''),
(39, '打印', 37, '37,39', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(40, '商品库存余额表', 0, '40', 1, 99, 'InvBalanceReport_QUERY', 1, 'trade', 1, 0, 0, ''),
(41, '导出', 40, '40,41', 2, 99, 'InvBalanceReport_EXPORT', 1, 'trade', 1, 0, 0, ''),
(42, '打印', 40, '40,42', 2, 99, 'InvBalanceReport_PRINT', 0, 'trade', 1, 0, 0, ''),
(43, '商品收发明细表', 0, '43', 1, 99, 'DeliverDetailReport_QUERY', 1, 'trade', 1, 0, 0, ''),
(44, '导出', 43, '43,44', 2, 99, 'DeliverDetailReport_EXPORT', 1, 'trade', 1, 0, 0, ''),
(45, '打印', 43, '43,45', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(46, '商品收发汇总表', 0, '46', 1, 99, 'DeliverSummaryReport_QUERY', 1, 'trade', 1, 0, 0, ''),
(47, '导出', 46, '46,47', 2, 99, 'DeliverSummaryReport_EXPORT', 1, 'trade', 1, 0, 0, ''),
(48, '打印', 46, '46,48', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(49, '往来单位欠款表', 0, '49', 1, 99, 'ContactDebtReport_QUERY', 1, 'trade', 1, 0, 0, ''),
(50, '导出', 49, '49,50', 2, 99, 'ContactDebtReport_EXPORT', 1, 'trade', 1, 0, 0, ''),
(51, '打印', 49, '49,51', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(52, '应付账款明细表', 0, '52', 1, 99, 'PAYMENTDETAIL_QUERY', 1, 'trade', 1, 0, 0, ''),
(53, '导出', 52, '52,53', 2, 99, 'PAYMENTDETAIL_EXPORT', 1, 'trade', 1, 0, 0, ''),
(54, '打印', 52, '52,54', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(55, '应收账款明细表', 0, '55', 1, 99, 'RECEIPTDETAIL_QUERY', 1, 'trade', 1, 0, 0, ''),
(56, '导出', 55, '55,56', 2, 99, 'RECEIPTDETAIL_EXPORT', 1, 'trade', 1, 0, 0, ''),
(57, '打印', 55, '55,57', 2, 99, '', 0, 'trade', 1, 0, 0, ''),
(58, '客户管理', 0, '58', 1, 99, 'BU_QUERY', 1, 'trade', 1, 0, 0, ''),
(59, '新增', 58, '58,59', 2, 99, 'BU_ADD', 1, 'trade', 1, 0, 0, ''),
(60, '修改', 58, '58,60', 2, 99, 'BU_UPDATE', 1, 'trade', 1, 0, 0, ''),
(61, '删除', 58, '58,61', 2, 99, 'BU_DELETE', 1, 'trade', 1, 0, 0, ''),
(62, '导出', 58, '58,62', 2, 99, 'BU_EXPORT', 1, 'trade', 1, 0, 0, ''),
(63, '供应商管理', 0, '63', 1, 99, 'PUR_QUERY', 1, 'trade', 1, 0, 0, ''),
(64, '新增', 63, '63,64', 2, 99, 'PUR_ADD', 1, 'trade', 1, 0, 0, ''),
(65, '修改', 63, '63,65', 2, 99, 'PUR_UPDATE', 1, 'trade', 1, 0, 0, ''),
(66, '删除', 63, '63,66', 2, 99, 'PUR_DELETE', 1, 'trade', 1, 0, 0, ''),
(67, '导出', 63, '63,67', 2, 99, 'PUR_EXPORT', 1, 'trade', 1, 0, 0, ''),
(68, '商品管理', 0, '68', 1, 99, 'INVENTORY_QUERY', 1, 'trade', 1, 0, 0, ''),
(69, '新增', 68, '68,69', 2, 99, 'INVENTORY_ADD', 1, 'trade', 1, 0, 0, ''),
(70, '修改', 68, '68,70', 2, 99, 'INVENTORY_UPDATE', 1, 'trade', 1, 0, 0, ''),
(71, '删除', 68, '68,71', 2, 99, 'INVENTORY_DELETE', 1, 'trade', 1, 0, 0, ''),
(72, '导出', 68, '68,72', 2, 99, 'INVENTORY_EXPORT', 1, 'trade', 1, 0, 0, ''),
(73, '客户类别', 0, '73', 1, 99, 'BUTYPE_QUERY', 1, 'trade', 1, 0, 0, ''),
(74, '新增', 73, '73,74', 2, 99, 'BUTYPE_ADD', 1, 'trade', 1, 0, 0, ''),
(75, '修改', 73, '73,75', 2, 99, 'BUTYPE_UPDATE', 1, 'trade', 1, 0, 0, ''),
(76, '删除', 73, '73,76', 2, 99, 'BUTYPE_DELETE', 1, 'trade', 1, 0, 0, ''),
(77, '计量单位', 0, '77', 1, 99, 'UNIT_QUERY', 1, 'trade', 1, 0, 0, ''),
(78, '新增', 77, '77,78', 2, 99, 'UNIT_ADD', 1, 'trade', 1, 0, 0, ''),
(79, '修改', 77, '77,79', 2, 99, 'UNIT_UPDATE', 1, 'trade', 1, 0, 0, ''),
(80, '删除', 77, '77,80', 2, 99, 'UNIT_DELETE', 1, 'trade', 1, 0, 0, ''),
(81, '系统参数', 0, '81', 1, 99, 'PARAMETER', 1, 'trade', 1, 0, 0, ''),
(82, '权限设置', 0, '82', 1, 99, 'AUTHORITY', 1, 'trade', 1, 0, 0, ''),
(83, '操作日志', 0, '83', 1, 99, 'OPERATE_QUERY', 1, 'trade', 1, 0, 0, ''),
(84, '数据备份', 0, '84', 1, 99, '', 0, 'trade', 1, 0, 0, ''),
(85, '打印', 1, '1,85', 2, 99, 'PU_PRINT', 1, 'trade', 1, 0, 0, ''),
(86, '审核', 1, '1,86', 2, 0, 'PU_CHECK', 1, 'trade', 1, 0, 0, ''),
(87, '反审核', 1, '1,87', 2, 0, 'PU_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(88, '打印', 6, '6,88', 2, 0, 'SA_PRINT', 1, 'trade', 1, 0, 0, ''),
(89, '审核', 6, '6,89', 2, 0, 'SA_CHECK', 1, 'trade', 1, 0, 0, ''),
(90, '反审核', 6, '6,90', 2, 0, 'SA_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(91, '禁用', 58, '58,91', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(92, '启用', 58, '58,92', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(93, '禁用', 63, '63,93', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(94, '启用', 63, '63,94', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(95, '禁用', 68, '68,95', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(96, '启用', 68, '68,96', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(97, '职员管理', 0, '97', 1, 0, 'STAFF_QUERY', 1, 'trade', 1, 0, 0, ''),
(98, '账号管理', 0, '98', 1, 0, 'SettAcct_QUERY', 1, 'trade', 1, 0, 0, ''),
(99, '导入', 11, '11,99', 2, 0, '', 1, 'trade', 1, 0, 0, ''),
(100, '审核', 14, '14,100', 2, 0, 'IO_CHECK', 1, 'trade', 1, 0, 0, ''),
(101, '反审核', 14, '14,101', 2, 0, 'IO_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(102, '导出', 14, '14,102', 2, 0, 'IO_EXPORT', 1, 'trade', 1, 0, 0, ''),
(103, '审核', 18, '18,103', 2, 0, 'OO_CHECK', 1, 'trade', 1, 0, 0, ''),
(104, '反审核', 18, '18,104', 2, 0, 'OO_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(105, '导出', 18, '18,105', 2, 0, 'OO_EXPORT', 1, 'trade', 1, 0, 0, ''),
(106, '现金银行报表', 0, '106', 1, 0, 'SettAcctReport_QUERY', 1, 'trade', 1, 0, 0, ''),
(107, '打印', 106, '106,107', 2, 0, '', 1, 'trade', 1, 0, 0, ''),
(108, '导出', 106, '106,108', 2, 0, 'SettAcctReport_EXPORT', 1, 'trade', 1, 0, 0, ''),
(109, '客户对账单', 0, '109', 1, 0, 'CUSTOMERBALANCE_QUERY', 1, 'trade', 1, 0, 0, ''),
(110, '打印', 109, '109,110', 2, 0, '', 1, 'trade', 1, 0, 0, ''),
(111, '导出', 109, '109,111', 2, 0, 'CUSTOMERBALANCE_EXPORT', 1, 'trade', 1, 0, 0, ''),
(112, '供应商对账单', 0, '112', 1, 0, 'SUPPLIERBALANCE_QUERY', 1, 'trade', 1, 0, 0, ''),
(113, '打印', 112, '112,113', 2, 0, '', 1, 'trade', 1, 0, 0, ''),
(114, '导出', 112, '112,114', 2, 0, 'SUPPLIERBALANCE_EXPORT', 1, 'trade', 1, 0, 0, ''),
(115, '其他收支明细表', 0, '115', 1, 0, 'ORIDETAIL_QUERY', 1, 'trade', 1, 0, 0, ''),
(116, '打印', 115, '115,116', 2, 0, '', 1, 'trade', 1, 0, 0, ''),
(117, '导出', 115, '115,117', 2, 0, 'ORIDETAIL_EXPORT', 1, 'trade', 1, 0, 0, ''),
(118, '新增', 97, '97,118', 2, 0, 'INVLOCTION_ADD', 1, 'trade', 1, 0, 0, ''),
(119, '修改', 97, '97,119', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(120, '删除', 97, '97,120', 2, 0, 'INVLOCTION_DELETE', 1, 'trade', 1, 0, 0, ''),
(121, '新增', 98, '98,121', 2, 0, 'SettAcct_ADD', 1, 'trade', 1, 0, 0, ''),
(122, '修改', 98, '98,122', 2, 0, 'SettAcct_UPDATE', 1, 'trade', 1, 0, 0, ''),
(123, '删除', 98, '98,123', 2, 0, 'SettAcct_DELETE', 1, 'trade', 1, 0, 0, ''),
(124, '收款单', 0, '124', 1, 0, 'RECEIPT_QUERY', 1, 'trade', 1, 0, 0, ''),
(125, '新增', 124, '124,125', 2, 0, 'RECEIPT_ADD', 1, 'trade', 1, 0, 0, ''),
(126, '修改', 124, '124,126', 2, 0, 'RECEIPT_UPDATE', 1, 'trade', 1, 0, 0, ''),
(127, '删除', 124, '124,127', 2, 0, 'RECEIPT_DELETE', 1, 'trade', 1, 0, 0, ''),
(128, '导出', 124, '124,128', 2, 0, 'RECEIPT_EXPORT', 1, 'trade', 1, 0, 0, ''),
(129, '付款单', 0, '129', 1, 0, 'PAYMENT_QUERY', 1, 'trade', 1, 0, 0, ''),
(130, '新增', 129, '129,130', 2, 0, 'PAYMENT_ADD', 1, 'trade', 1, 0, 0, ''),
(131, '修改', 129, '129,131', 2, 0, 'PAYMENT_UPDATE', 1, 'trade', 1, 0, 0, ''),
(132, '删除', 129, '129,132', 2, 0, 'PAYMENT_DELETE', 1, 'trade', 1, 0, 0, ''),
(133, '导出', 129, '129,133', 2, 0, 'PAYMENT_EXPORT', 1, 'trade', 1, 0, 0, ''),
(134, '其他收入单', 0, '134', 1, 0, 'QTSR_QUERY', 1, 'trade', 1, 0, 0, ''),
(135, '新增', 134, '134,135', 2, 0, 'QTSR_ADD', 1, 'trade', 1, 0, 0, ''),
(136, '修改', 134, '134,136', 2, 0, 'QTSR_UPDATE', 1, 'trade', 1, 0, 0, ''),
(137, '删除', 134, '134,137', 2, 0, 'QTSR_DELETE', 1, 'trade', 1, 0, 0, ''),
(138, '导出', 134, '134,138', 2, 0, 'QTSR_EXPORT', 1, 'trade', 1, 0, 0, ''),
(139, '其他支出单', 0, '139', 1, 0, 'QTZC_QUERY', 1, 'trade', 1, 0, 0, ''),
(140, '新增', 139, '139,140', 2, 0, 'QTZC_ADD', 1, 'trade', 1, 0, 0, ''),
(141, '修改', 139, '139,141', 2, 0, 'QTZC_UPDATE', 1, 'trade', 1, 0, 0, ''),
(142, '删除', 139, '139,142', 2, 0, 'QTZC_DELETE', 1, 'trade', 1, 0, 0, ''),
(143, '导出', 139, '139,143', 2, 0, 'QTZC_EXPORT', 1, 'trade', 1, 0, 0, ''),
(144, '调拨单', 0, '144', 1, 0, 'TF_QUERY', 1, 'trade', 1, 0, 0, ''),
(145, '新增', 144, '144,145', 2, 0, 'TF_ADD', 1, 'trade', 1, 0, 0, ''),
(146, '修改', 144, '144,146', 2, 0, 'TF_UPDATE', 1, 'trade', 1, 0, 0, ''),
(147, '删除', 144, '144,147', 2, 0, 'TF_DELETE', 1, 'trade', 1, 0, 0, ''),
(148, '导出', 144, '144,148', 2, 0, 'TF_EXPORT', 1, 'trade', 1, 0, 0, ''),
(149, '重新初始化', 0, '149', 1, 0, '', 0, 'trade', 1, 0, 0, ''),
(151, '成本调整单', 0, '151', 1, 0, 'CADJ_QUERY', 1, 'trade', 1, 0, 0, ''),
(152, '新增', 151, '151,152', 2, 0, 'CADJ_ADD', 1, 'trade', 1, 0, 0, ''),
(153, '修改', 151, '151,153', 2, 0, 'CADJ_UPDATE', 1, 'trade', 1, 0, 0, ''),
(154, '删除', 151, '151,154', 2, 0, 'CADJ_DELETE', 1, 'trade', 1, 0, 0, ''),
(155, '仓库管理', 0, '155', 1, 0, 'INVLOCTION_QUERY', 1, 'trade', 1, 0, 0, ''),
(156, '新增', 155, '155,156', 2, 0, 'INVLOCTION_ADD', 1, 'trade', 1, 0, 0, ''),
(157, '修改', 155, '155,157', 2, 0, 'INVLOCTION_UPDATE', 1, 'trade', 1, 0, 0, ''),
(158, '删除', 155, '155,158', 2, 0, 'INVLOCTION_DELETE', 1, 'trade', 1, 0, 0, ''),
(159, '结算方式', 0, '159', 1, 0, 'Assist_QUERY', 1, 'trade', 1, 0, 0, ''),
(160, '新增', 159, '159,160', 2, 0, 'Assist_ADD', 1, 'trade', 1, 0, 0, ''),
(161, '修改', 159, '159,161', 2, 0, 'Assist_UPDATE', 1, 'trade', 1, 0, 0, ''),
(162, '删除', 159, '159,162', 2, 0, 'Assist_DELETE', 1, 'trade', 1, 0, 0, ''),
(163, '供应商类别', 0, '163', 1, 0, 'SUPPLYTYPE_QUERY', 1, 'trade', 1, 0, 0, ''),
(164, '新增', 163, '163,164', 2, 0, 'SUPPLYTYPE_ADD', 1, 'trade', 1, 0, 0, ''),
(165, '修改', 163, '163,165', 2, 0, 'SUPPLYTYPE_UPDATE', 1, 'trade', 1, 0, 0, ''),
(166, '删除', 163, '163,166', 2, 0, 'SUPPLYTYPE_DELETE', 1, 'trade', 1, 0, 0, ''),
(167, '商品类别', 0, '167', 1, 0, 'TRADETYPE_QUERY', 1, 'trade', 1, 0, 0, ''),
(168, '新增', 167, '167,168', 2, 0, 'TRADETYPE_ADD', 1, 'trade', 1, 0, 0, ''),
(169, '修改', 167, '167,169', 2, 0, 'TRADETYPE_UPDATE', 1, 'trade', 1, 0, 0, ''),
(170, '删除', 167, '167,170', 2, 0, 'TRADETYPE_DELETE', 1, 'trade', 1, 0, 0, ''),
(171, '支出类别', 0, '171', 1, 0, 'PACCTTYPE_QUERY', 1, 'trade', 1, 0, 0, ''),
(172, '新增', 171, '171,172', 2, 0, 'PACCTTYPE_ADD', 1, 'trade', 1, 0, 0, ''),
(173, '修改', 171, '171,173', 2, 0, 'PACCTTYPE_UPDATE', 1, 'trade', 1, 0, 0, ''),
(174, '删除', 171, '171,174', 2, 0, 'PACCTTYPE_DELETE', 1, 'trade', 1, 0, 0, ''),
(175, '收入类别', 0, '175', 1, 0, 'RACCTTYPE_QUERY', 1, 'trade', 1, 0, 0, ''),
(176, '新增', 175, '175,176', 2, 0, 'RACCTTYPE_ADD', 1, 'trade', 1, 0, 0, ''),
(177, '修改', 175, '175,177', 2, 0, 'RACCTTYPE_UPDATE', 1, 'trade', 1, 0, 0, ''),
(178, '删除', 175, '175,178', 2, 0, 'RACCTTYPE_DELETE', 1, 'trade', 1, 0, 0, ''),
(179, '打印', 144, '144,179', 2, 0, 'TF_PRINT', 1, 'trade', 1, 0, 0, ''),
(180, '采购订单', 0, '180', 1, 0, 'PO_QUERY', 1, 'trade', 1, 0, 0, ''),
(181, '新增', 180, '180,181', 2, 0, 'PO_ADD', 1, 'trade', 1, 0, 0, ''),
(182, '修改', 180, '180,182', 2, 0, 'PO_UPDATE', 1, 'trade', 1, 0, 0, ''),
(183, '删除', 180, '180,183', 2, 0, 'PO_DELETE', 1, 'trade', 1, 0, 0, ''),
(184, '导出', 180, '180,184', 2, 0, 'PO_EXPORT', 1, 'trade', 1, 0, 0, ''),
(185, '打印', 180, '180,185', 2, 0, 'PO_PRINT', 1, 'trade', 1, 0, 0, ''),
(186, '审核', 180, '180,186', 2, 0, 'PO_CHECK', 1, 'trade', 1, 0, 0, ''),
(187, '反审核', 180, '180,187', 2, 0, 'PO_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(188, '销售订单', 0, '188', 1, 0, 'SO_QUERY', 1, 'trade', 1, 0, 0, ''),
(189, '新增', 188, '188,189', 2, 0, 'SO_ADD', 1, 'trade', 1, 0, 0, ''),
(190, '修改', 188, '188,190', 2, 0, 'SO_UPDATE', 1, 'trade', 1, 0, 0, ''),
(191, '删除', 188, '188,191', 2, 0, 'SO_DELETE', 1, 'trade', 1, 0, 0, ''),
(192, '导出', 188, '188,192', 2, 0, 'SO_EXPORT', 1, 'trade', 1, 0, 0, ''),
(193, '打印', 188, '188,193', 2, 0, 'SO_PRINT', 1, 'trade', 1, 0, 0, ''),
(194, '审核', 188, '188,194', 2, 0, 'SO_CHECK', 1, 'trade', 1, 0, 0, ''),
(195, '反审核', 188, '188,195', 2, 0, 'SO_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(196, '审核', 144, '144,196', 2, 0, 'TF_CHECK', 1, 'trade', 1, 0, 0, ''),
(197, '反审核', 144, '144,197', 2, 0, 'TF_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(198, '审核', 124, '124,198', 2, 0, 'RECEIPT_CHECK', 1, 'trade', 1, 0, 0, ''),
(199, '反审核', 124, '124,199', 2, 0, 'RECEIPT_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(200, '审核', 129, '129,200', 2, 0, 'PAYMENT_CHECK', 1, 'trade', 1, 0, 0, ''),
(201, '反审核', 129, '129,201', 2, 0, 'PAYMENT_UNCHECK', 1, 'trade', 1, 0, 0, ''),
(202, '库存预警', 0, '202', 1, 0, 'INVENTORY_WARNING', 1, 'trade', 1, 0, 0, ''),
(203, '打印', 14, '14,203', 2, 0, 'IO_PRINT', 1, 'trade', 1, 0, 0, ''),
(204, '打印', 18, '18,204', 2, 0, 'OO_PRINT', 1, 'trade', 1, 0, 0, ''),
(205, '导出', 151, '151,205', 2, 0, 'CADJ_EXPORT', 1, 'trade', 1, 0, 0, ''),
(206, '打印', 151, '151,206', 2, 0, 'CADJ_PRINT', 1, 'trade', 1, 0, 0, ''),
(207, '打印', 124, '124,207', 2, 0, 'RECEIPT_PRINT', 1, 'trade', 1, 0, 0, ''),
(208, '打印', 129, '129,208', 2, 0, 'PAYMENT_PRINT', 1, 'trade', 1, 0, 0, ''),
(209, '打印', 134, '134,209', 2, 0, 'QTSR_PRINT', 1, 'trade', 1, 0, 0, ''),
(210, '采购订单跟踪表', 0, '210', 1, 0, 'PURCHASEORDER_QUERY', 1, 'trade', 1, 0, 0, ''),
(211, '导出', 210, '210,211', 2, 0, 'PURCHASEORDER_EXPORT', 1, 'trade', 1, 0, 0, ''),
(212, '打印', 210, '210,212', 2, 0, 'PURCHASEORDER_PRINT', 1, 'trade', 1, 0, 0, ''),
(213, '销售订单跟踪表', 0, '213', 1, 0, 'SALESORDER_QUERY', 1, 'trade', 1, 0, 0, ''),
(214, '导出', 213, '213,214', 2, 0, 'SALESORDER_EXPORT', 1, 'trade', 1, 0, 0, ''),
(215, '打印', 213, '213,215', 2, 0, 'SALESORDER_PRINT', 1, 'trade', 1, 0, 0, ''),
(216, '合同上传', 180, '180,216', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(217, '合同查看', 180, '180,217', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(218, '合同删除', 180, '180,218', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(219, '价格查看', 1, '1,219', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(220, '价格查看', 6, '6,220', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(221, '价格查看', 180, '180,221', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(222, '价格查看', 188, '188,222', 2, 99, '', 1, 'trade', 1, 0, 0, ''),
(223, '组装单', 0, '223', 1, 0, 'ZZD_QUERY', 1, 'trade', 1, 0, 0, ''),
(224, '新增', 223, '223,224', 2, 0, 'ZZD_ADD', 1, 'trade', 1, 0, 0, ''),
(225, '修改', 223, '223,225', 2, 0, 'ZZD_UPDATE', 1, 'trade', 1, 0, 0, ''),
(226, '删除', 223, '223,226', 2, 0, 'ZZD_DELETE', 1, 'trade', 1, 0, 0, ''),
(227, '导出', 223, '223,227', 2, 0, 'ZZD_EXPORT', 1, 'trade', 1, 0, 0, ''),
(228, '打印', 223, '223,228', 2, 0, 'ZZD_PRINT', 1, 'trade', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `ci_options`
--

CREATE TABLE IF NOT EXISTS `ci_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `ci_options`
--

INSERT INTO `ci_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'system', 'a:10:{s:11:"companyName";s:30:"中电瑞铠科技有限公司";s:11:"companyAddr";s:6:"望京";s:5:"phone";s:11:"13116139607";s:3:"fax";s:11:"13116139607";s:8:"postcode";s:6:"100000";s:9:"qtyPlaces";s:1:"0";s:11:"pricePlaces";s:1:"0";s:12:"amountPlaces";s:1:"2";s:10:"valMethods";s:13:"movingAverage";s:18:"requiredCheckStore";s:1:"1";}', 'yes'),
(2, 'sales', '', 'yes'),
(3, 'purchase', 's:761:"{"grids":{"grid":{"colModel":[["operating"," ",null,60],["goods","商品",null,300],["skuId","属性ID",null,null],["skuName","属性",null,100],["mainUnit","单位",null,80],["unitId","单位Id",null,null],["locationName","仓库",null,100],["batch","批次",null,90],["prodDate","生产日期",null,90],["safeDays","保质期(天)",null,90],["validDate","有效期至",null,90],["qty","数量",null,80],["price","购货单价111",false,100],["discountRate","折扣率(%)",null,70],["deduction","折扣额",null,70],["amount","金额",false,100],["description","备注",null,150],["srcOrderEntryId","源单分录ID",null,0],["srcOrderId","源单ID",null,0],["srcOrderNo","源单号",null,120]],"isReg":true}},"modifyTime":1487928290000,"curTime":1487928290000}";', 'yes'),
(4, 'transfers', 's:2702:"{"grids":{"grid":{"defColModel":[{"name":"operating","label":" ","width":40,"fixed":true,"align":"center","defLabel":" "},{"name":"goods","label":"商品","width":318,"title":false,"classes":"goods","editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"商品"},{"name":"skuId","label":"属性ID","hidden":true,"defLabel":"属性ID","defhidden":true},{"name":"skuName","label":"属性","width":100,"classes":"ui-ellipsis","hidden":true,"defLabel":"属性","defhidden":true},{"name":"mainUnit","label":"单位","width":80,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"单位"},{"name":"unitId","label":"单位Id","hidden":true,"defLabel":"单位Id","defhidden":true},{"name":"batch","label":"批次","width":90,"classes":"ui-ellipsis batch","hidden":true,"title":false,"editable":true,"align":"left","edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"批次","defhidden":true},{"name":"prodDate","label":"生产日期","width":90,"hidden":true,"title":false,"editable":true,"edittype":"custom","editoptions":{},"defLabel":"生产日期","defhidden":true},{"name":"safeDays","label":"保质期(天)","width":90,"hidden":true,"title":false,"align":"left","defLabel":"保质期(天)","defhidden":true},{"name":"validDate","label":"有效期至","width":90,"hidden":true,"title":false,"align":"left","defLabel":"有效期至","defhidden":true},{"name":"qty","label":"数量","width":80,"align":"right","formatter":"number","formatoptions":{"decimalPlaces":1},"editable":true,"defLabel":"数量"},{"name":"outLocationName","label":"调出仓库","nameExt":"<small id=\\"batch-storageA\\">(批量)</small>","sortable":false,"width":100,"title":true,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"调出仓库"},{"name":"inLocationName","label":"调入仓库","nameExt":"<small id=\\"batch-storageB\\">(批量)</small>","width":100,"title":true,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"调入仓库"},{"name":"description","label":"备注","width":150,"title":true,"editable":true,"defLabel":"备注"}],"colModel":[["operating"," ",null,40],["goods","商品",null,318],["skuId","属性ID",true,null],["skuName","属性",true,100],["mainUnit","单位",null,80],["unitId","单位Id",true,null],["batch","批次",true,90],["prodDate","生产日期",true,90],["safeDays","保质期(天)",true,90],["validDate","有效期至",true,90],["qty","数量",null,80],["outLocationName","调出仓库",null,100],["inLocationName","调入仓库",null,100],["description","备注",null,150]],"isReg":true}}}";', 'yes'),
(5, 'otherWarehouse', 's:2906:"{"grids":{"grid":{"defColModel":[{"name":"operating","label":" ","width":40,"fixed":true,"align":"center","defLabel":" "},{"name":"goods","label":"商品","width":320,"title":true,"classes":"goods","editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"商品"},{"name":"skuId","label":"属性ID","hidden":true,"defLabel":"属性ID","defhidden":true},{"name":"skuName","label":"属性","width":100,"classes":"ui-ellipsis","hidden":true,"defLabel":"属性","defhidden":true},{"name":"mainUnit","label":"单位","width":80,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"单位"},{"name":"unitId","label":"单位Id","hidden":true,"defLabel":"单位Id","defhidden":true},{"name":"locationName","label":"仓库","nameExt":"<small id=\\"batchStorage\\">(批量)</small>","width":100,"title":true,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"仓库"},{"name":"batch","label":"批次","width":90,"classes":"ui-ellipsis batch","hidden":true,"title":false,"editable":true,"align":"left","edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"批次","defhidden":true},{"name":"prodDate","label":"生产日期","width":90,"hidden":true,"title":false,"editable":true,"edittype":"custom","editoptions":{},"defLabel":"生产日期","defhidden":true},{"name":"safeDays","label":"保质期(天)","width":90,"hidden":true,"title":false,"align":"left","defLabel":"保质期(天)","defhidden":true},{"name":"validDate","label":"有效期至","width":90,"hidden":true,"title":false,"align":"left","defLabel":"有效期至","defhidden":true},{"name":"qty","label":"数量","width":80,"align":"right","formatter":"number","formatoptions":{"decimalPlaces":1},"editable":true,"defLabel":"数量"},{"name":"price","label":"入库单价","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":1},"editable":true,"defLabel":"入库单价","defhidden":false},{"name":"amount","label":"入库金额","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"入库金额","defhidden":false},{"name":"description","label":"备注","width":150,"title":true,"editable":true,"defLabel":"备注"}],"colModel":[["operating"," ",null,40],["goods","商品",null,320],["skuId","属性ID",true,null],["skuName","属性",true,100],["mainUnit","单位",null,80],["unitId","单位Id",true,null],["locationName","仓库",null,100],["batch","批次",true,90],["prodDate","生产日期",true,90],["safeDays","保质期(天)",true,90],["validDate","有效期至",true,90],["qty","数量",null,80],["price","入库单价",false,100],["amount","入库金额",false,100],["description","备注",null,150]],"isReg":true}}}";', 'yes'),
(6, 'adjustment', 's:1337:"{"grids":{"grid":{"defColModel":[{"name":"operating","label":" ","width":40,"fixed":true,"align":"center","defLabel":" "},{"name":"goods","label":"商品","width":320,"title":true,"classes":"goods","editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis disableSku"},"defLabel":"商品"},{"name":"skuId","label":"属性ID","hidden":true,"defLabel":"属性ID","defhidden":true},{"name":"mainUnit","label":"单位","width":60,"defLabel":"单位"},{"name":"amount","label":"调整金额","hidden":false,"width":100,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"调整金额","defhidden":false},{"name":"locationName","label":"仓库<small id=\\"batchStorage\\">(批量)</small>","width":100,"title":true,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"仓库<small id=\\"batchStorage\\">(批量)</small>"},{"name":"description","label":"备注","width":150,"title":true,"editable":true,"defLabel":"备注"}],"colModel":[["operating"," ",null,40],["goods","商品",null,320],["skuId","属性ID",true,null],["mainUnit","单位",null,60],["amount","调整金额",false,100],["locationName","仓库<small id=\\"batchStorage\\">(批量)</small>",null,100],["description","备注",null,150]],"isReg":true}}}";', 'yes'),
(7, 'purchaseBack', 's:3824:"{"grids":{"grid":{"defColModel":[{"name":"operating","label":" ","width":60,"fixed":true,"align":"center","defLabel":" "},{"name":"goods","label":"商品","nameExt":"<span id=\\"barCodeInsert\\">扫描枪录入</span>","width":300,"classes":"goods","editable":true,"defLabel":"商品"},{"name":"skuId","label":"属性ID","hidden":true,"defLabel":"属性ID","defhidden":true},{"name":"skuName","label":"属性","width":100,"classes":"ui-ellipsis","hidden":true,"defLabel":"属性","defhidden":true},{"name":"mainUnit","label":"单位","width":80,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"单位"},{"name":"unitId","label":"单位Id","hidden":true,"defLabel":"单位Id","defhidden":true},{"name":"locationName","label":"仓库","nameExt":"<small id=\\"batchStorage\\">(批量)</small>","width":100,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"仓库"},{"name":"batch","label":"批次","width":90,"classes":"ui-ellipsis batch","hidden":true,"title":false,"editable":true,"align":"left","edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"批次","defhidden":true},{"name":"prodDate","label":"生产日期","width":90,"hidden":true,"title":false,"editable":true,"edittype":"custom","editoptions":{},"defLabel":"生产日期","defhidden":true},{"name":"safeDays","label":"保质期(天)","width":90,"hidden":true,"title":false,"align":"left","defLabel":"保质期(天)","defhidden":true},{"name":"validDate","label":"有效期至","width":90,"hidden":true,"title":false,"align":"left","defLabel":"有效期至","defhidden":true},{"name":"qty","label":"数量","width":80,"align":"right","formatter":"number","formatoptions":{"decimalPlaces":2},"editable":true,"defLabel":"数量"},{"name":"price","label":"购货单价","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"购货单价test","defhidden":false},{"name":"discountRate","label":"折扣率(%)","hidden":false,"width":70,"fixed":true,"align":"right","formatter":"integer","editable":true,"defLabel":"折扣率(%)","defhidden":false},{"name":"deduction","label":"折扣额","hidden":false,"width":70,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"折扣额","defhidden":false},{"name":"amount","label":"购货金额","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"购货金额","defhidden":false},{"name":"description","label":"备注","width":150,"title":true,"editable":true,"defLabel":"备注"},{"name":"srcOrderEntryId","label":"源单分录ID","width":0,"hidden":true,"defLabel":"源单分录ID","defhidden":true},{"name":"srcOrderId","label":"源单ID","width":0,"hidden":true,"defLabel":"源单ID","defhidden":true},{"name":"srcOrderNo","label":"源单号","width":120,"fixed":true,"hidden":true,"defLabel":"源单号","defhidden":true}],"colModel":[["operating"," ",null,60],["goods","商品",null,300],["skuId","属性ID",true,null],["skuName","属性",true,100],["mainUnit","单位",null,80],["unitId","单位Id",true,null],["locationName","仓库",null,100],["batch","批次",true,90],["prodDate","生产日期",true,90],["safeDays","保质期(天)",true,90],["validDate","有效期至",true,90],["qty","数量",null,80],["price","购货单价",false,100],["discountRate","折扣率(%)",false,70],["deduction","折扣额",false,70],["amount","购货金额",false,100],["description","备注",null,150],["srcOrderEntryId","源单分录ID",true,0],["srcOrderId","源单ID",true,0],["srcOrderNo","源单号",true,120]],"isReg":true}}}";', 'yes'),
(8, 'salesBack', 's:3893:"{"grids":{"grid":{"defColModel":[{"name":"operating","label":" ","width":60,"fixed":true,"align":"center","defLabel":" "},{"name":"goods","label":"商品","nameExt":"<span id=\\"barCodeInsert\\">扫描枪录入</span>","width":300,"classes":"goods","editable":true,"defLabel":"商品"},{"name":"skuId","label":"属性ID","hidden":true,"defLabel":"属性ID","defhidden":true},{"name":"skuName","label":"属性","width":100,"classes":"ui-ellipsis","hidden":true,"defLabel":"属性","defhidden":true},{"name":"mainUnit","label":"单位","width":80,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"单位"},{"name":"unitId","label":"单位Id","hidden":true,"defLabel":"单位Id","defhidden":true},{"name":"locationName","label":"仓库","nameExt":"<small id=\\"batchStorage\\">(批量)</small>","width":100,"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"仓库"},{"name":"batch","label":"批次","width":90,"classes":"ui-ellipsis batch","hidden":true,"title":false,"editable":true,"align":"left","edittype":"custom","editoptions":{"trigger":"ui-icon-ellipsis"},"defLabel":"批次","defhidden":true},{"name":"prodDate","label":"生产日期","width":90,"hidden":true,"title":false,"editable":true,"edittype":"custom","editoptions":{},"defLabel":"生产日期","defhidden":true},{"name":"safeDays","label":"保质期(天)","width":90,"hidden":true,"title":false,"align":"left","defLabel":"保质期(天)","defhidden":true},{"name":"validDate","label":"有效期至","width":90,"hidden":true,"title":false,"align":"left","defLabel":"有效期至","defhidden":true},{"name":"qty","label":"数量","width":80,"align":"right","formatter":"number","formatoptions":{"decimalPlaces":2},"editable":true,"defLabel":"数量"},{"name":"price","label":"销售单价","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"edittype":"custom","editoptions":{"trigger":"ui-icon-triangle-1-s"},"defLabel":"销售单价","defhidden":false},{"name":"discountRate","label":"折扣率(%)","hidden":false,"width":70,"fixed":true,"align":"right","formatter":"integer","editable":true,"defLabel":"折扣率(%)","defhidden":false},{"name":"deduction","label":"折扣额","hidden":false,"width":70,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"折扣额","defhidden":false},{"name":"amount","label":"销售金额","hidden":false,"width":100,"fixed":true,"align":"right","formatter":"currency","formatoptions":{"showZero":true,"decimalPlaces":2},"editable":true,"defLabel":"销售金额","defhidden":false},{"name":"description","label":"备注","width":150,"title":true,"editable":true,"defLabel":"备注"},{"name":"srcOrderEntryId","label":"源单分录ID","width":0,"hidden":true,"defLabel":"源单分录ID","defhidden":true},{"name":"srcOrderId","label":"源单ID","width":0,"hidden":true,"defLabel":"源单ID","defhidden":true},{"name":"srcOrderNo","label":"源单号","width":120,"fixed":true,"hidden":true,"defLabel":"源单号","defhidden":true}],"colModel":[["operating"," ",null,60],["goods","商品",null,300],["skuId","属性ID",true,null],["skuName","属性",true,100],["mainUnit","单位",null,80],["unitId","单位Id",true,null],["locationName","仓库",null,100],["batch","批次",true,90],["prodDate","生产日期",true,90],["safeDays","保质期(天)",true,90],["validDate","有效期至",true,90],["qty","数量",null,80],["price","销售单价",false,100],["discountRate","折扣率(%)",false,70],["deduction","折扣额",false,70],["amount","销售金额",false,100],["description","备注",null,150],["srcOrderEntryId","源单分录ID",true,0],["srcOrderId","源单ID",true,0],["srcOrderNo","源单号",true,120]],"isReg":true}}}";', 'yes'),
(9, 'otherOutbound', 's:583:"{"grids":{"grid":{"colModel":[["operating"," ",null,40],["goods","商品",null,320],["skuId","属性ID",true,null],["skuName","属性",true,100],["mainUnit","单位",null,80],["unitId","单位Id",true,null],["locationName","仓库",null,100],["batch","批次",true,90],["prodDate","生产日期",true,90],["safeDays","保质期(天)",true,90],["validDate","有效期至",true,90],["qty","数量",null,80],["price","出库单位成本",false,100],["amount","出库成本",false,100],["description","备注",null,150]],"isReg":true}},"curTime":1445235745000,"modifyTime":1445235745000}";', 'yes'),
(10, 'purchaseOrder', 's:509:"{"grids":{"grid":{"colModel":[["operating"," ",null,60],["goods","商品",null,300],["skuId","属性ID",null,null],["skuName","属性",null,100],["mainUnit","单位",null,80],["unitId","单位Id",null,null],["locationName","仓库",null,100],["qty","数量",null,80],["price","购货单价11",false,100],["discountRate","折扣率(%)",null,70],["deduction","折扣额",null,70],["amount","金额",false,100],["description","备注",null,150]],"isReg":true}},"curTime":1487928275000,"modifyTime":1487928275000}";', 'yes'),
(11, 'salesOrderList', 's:607:"{"grids":{"grid":{"colModel":[["operating","操作",null,60],["billDate","订单日期",null,100],["billNo","订单编号",null,212],["transType","业务类别",null,100],["salesName","销售人员",null,80],["contactName","客户",null,200],["totalAmount","销售金额",false,100],["totalQty","数量",null,80],["billStatusName","订单状态",null,100],["deliveryDate","交货日期",null,100],["userName","制单人",null,80],["checkName","审核人",false,80],["description","备注",null,200],["disEditable","不可编辑",null,null]],"isReg":true}},"curTime":1446105676000,"modifyTime":1446105676000}";', 'yes'),
(12, 'puDetailNew', '', 'yes'),
(13, 'accountPayDetailNew', 'b:0;', 'yes'),
(14, 'otherIncomeExpenseDetail', 's:368:"{"grids":{"grid":{"colModel":[["date","日期",null,150],["billNo","单据编号",null,110],["transTypeName","收支类别",null,110],["typeName","收支项目",null,110],["amountIn","收入",null,120],["amountOut","支出",null,120],["contactName","往来单位",null,110],["desc","摘要",null,110]],"isReg":true}},"curTime":1440738089000,"modifyTime":1440738089000}";', 'yes'),
(15, 'purchaseOrderList', 's:573:"{"grids":{"grid":{"colModel":[["operating","操作",null,60],["billDate","订单日期",null,100],["billNo","订单编号",null,238],["transType","业务类别",null,100],["contactName","供应商",null,200],["totalAmount","采购金额",false,100],["totalQty","数量",null,80],["billStatusName","订单状态",null,100],["deliveryDate","交货日期",null,100],["userName","制单人",null,80],["checkName","审核人",false,80],["description","备注",null,200],["disEditable","不可编辑",null,null]],"isReg":true}},"curTime":1487571106000,"modifyTime":1487571106000}";', 'yes'),
(16, 'goodsList', 's:580:"{"grids":{"grid":{"colModel":[["operate","操作",null,194],["categoryName","商品类别",null,100],["number","商品编号",null,100],["name","商品名称",null,200],["spec","规格型号",null,60],["unitName","单位",null,40],["currentQty","当前库存",null,80],["quantity","期初数量",null,80],["unitCost","单位成本",false,100],["amount","期初总价",false,100],["purPrice","预计采购价",false,100],["salePrice","零售价",false,100],["remark","备注",null,100],["delete","状态",null,80]],"isReg":true}},"curTime":1447928678000,"modifyTime":1447928678000}";', 'yes'),
(17, 'purchaseList', 's:522:"{"grids":{"grid":{"colModel":[["operating","操作",null,60],["billDate","单据日期",null,100],["billNo","单据编号",null,178],["contactName","供应商",null,200],["totalAmount","购货金额",false,100],["amount","优惠后金额",false,100],["rpAmount","已付款",false,100],["hxStateCode","付款状态",null,80],["userName","制单人",null,80],["checkName","审核人",false,80],["description","备注",null,200],["disEditable","",null,null]],"isReg":true}},"curTime":1447818801000,"modifyTime":1447818801000}";', 'yes'),
(18, 'goodsFlowSummary', 's:769:"{"grids":{"grid":{"colModel":[["assistName","商品类别",null,80],["invNo","商品编号",null,80],["invName","商品名称",null,200],["spec","规格型号",null,60],["unit","单位",null,40],["locationNo","仓库编码",null,0],["location","仓库",null,90],["cost_0","成本",null,80],["qty_1","数量",null,80],["qty_2","数量",null,80],["qty_3","数量",null,80],["qty_4","数量",null,80],["qty_5","数量",null,80],["qty_6","数量",null,80],["qty_7","数量",null,80],["qty_8","数量",null,80],["qty_9","数量",null,80],["qty_10","数量",null,80],["qty_11","数量",null,80],["qty_12","数量",null,80],["qty_13","数量",null,80],["qty_14","数量",null,80],["cost_14","成本",null,80]],"isReg":true}},"curTime":1447914361000,"modifyTime":1447914361000}";', 'yes'),
(19, 'accountProceeDetailNew', 's:432:"{"grids":{"grid":{"colModel":[["buName","客户",null,150],["date","单据日期",null,100],["billNo","单据编号",null,155],["transType","业务类型",null,110],["income","增加应收款",null,120],["expenditure","增加预收款",null,120],["balance","应收款余额",null,120],["description","备注",null,210],["billId","",null,0],["billTypeNo","",null,0]],"isReg":true}},"curTime":1449896005000,"modifyTime":1449896005000}";', 'yes'),
(20, 'goodsBalance', 's:306:"{"grids":{"grid":{"colModel":[["invNo","商品编号",null,80],["invName","商品名称",null,200],["spec","规格型号",null,88],["unit","单位",null,40],["qty_1","数量",null,80],["cost_1","成本",null,80],["qty_2","数量",null,80]],"isReg":true}},"curTime":1452583856000,"modifyTime":1452583856000}";', 'yes'),
(21, 'assemble', 's:1091:"{"grids":{"fixedGrid":{"caption":"组合件","colModel":[["goods","商品",false,370],["skuId","属性ID",null,null],["skuName","属性",null,100],["mainUnit","单位",null,80],["unitId","单位Id",null,null],["locationName","仓库",null,100],["batch","批次",null,90],["prodDate","生产日期",null,90],["safeDays","保质期(天)",null,90],["validDate","有效期至",null,90],["qty","数量",null,80],["price","入库单位成本",false,100],["amount","入库成本",false,100]],"isReg":true},"grid":{"caption":"子件","colModel":[["operating"," ",null,40],["goods","商品",null,330],["skuId","属性ID",null,null],["skuName","属性",null,100],["mainUnit","单位",null,80],["unitId","单位Id",null,null],["locationName","仓库",null,100],["batch","批次",null,90],["prodDate","生产日期",null,90],["safeDays","保质期(天)",null,90],["validDate","有效期至",null,90],["qty","数量",null,80],["price","出库单位成本",false,100],["amount","出库成本",false,100],["description","备注",null,100]],"isReg":true}},"curTime":1469607982000,"modifyTime":1469607982000}";', 'yes'),
(22, 'assembleList', '', 'yes'),
(23, 'salesDetail', '', 'yes'),
(24, 'salesList', '', 'yes');

-- --------------------------------------------------------

--
-- 表的结构 `ci_order`
--

CREATE TABLE IF NOT EXISTS `ci_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buId` smallint(6) DEFAULT '0' COMMENT '供应商ID',
  `billNo` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单据编号',
  `uid` smallint(6) DEFAULT '0',
  `userName` varchar(50) DEFAULT '' COMMENT '制单人',
  `transType` int(11) DEFAULT '0' COMMENT '150501购货 150502退货 150601销售 150602退销 150706其他入库',
  `totalAmount` double DEFAULT '0' COMMENT '购货总金额',
  `amount` double DEFAULT '0' COMMENT '折扣后金额',
  `rpAmount` double DEFAULT '0' COMMENT '本次付款',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `description` varchar(100) DEFAULT '' COMMENT '备注',
  `arrears` double DEFAULT '0' COMMENT '本次欠款',
  `disRate` double DEFAULT '0' COMMENT '折扣率',
  `disAmount` double DEFAULT '0' COMMENT '折扣金额',
  `totalQty` double DEFAULT '0' COMMENT '总数量',
  `totalArrears` double DEFAULT '0',
  `billStatus` tinyint(1) DEFAULT '0' COMMENT '订单状态 ',
  `checkName` varchar(50) DEFAULT '' COMMENT '采购单审核人',
  `totalTax` double DEFAULT '0',
  `totalTaxAmount` double DEFAULT '0',
  `checked` tinyint(1) DEFAULT '0' COMMENT '采购单状态',
  `accId` tinyint(4) DEFAULT '0' COMMENT '结算账户ID',
  `billType` varchar(20) DEFAULT '' COMMENT 'PO采购订单 OI其他入库 PUR采购入库 BAL初期余额',
  `modifyTime` datetime DEFAULT NULL COMMENT '更新时间',
  `hxStateCode` tinyint(4) DEFAULT '0' COMMENT '0未付款  1部分付款  2全部付款',
  `transTypeName` varchar(20) DEFAULT '',
  `totalDiscount` double DEFAULT '0',
  `salesId` smallint(6) DEFAULT '0' COMMENT '销售人员ID',
  `customerFree` double DEFAULT '0' COMMENT '客户承担费用',
  `hxAmount` double DEFAULT '0' COMMENT '本次核销金额',
  `payment` double DEFAULT '0' COMMENT '本次预收款',
  `discount` double DEFAULT '0' COMMENT '整单折扣',
  `postData` text COMMENT '提交订单明细 ',
  `locationId` varchar(255) DEFAULT '',
  `inLocationId` varchar(255) DEFAULT '' COMMENT '调入仓库ID多个,分割',
  `outLocationId` varchar(255) DEFAULT '' COMMENT '调出仓库ID多个,分割',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '1删除  0正常',
  `deliveryDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accId` (`accId`),
  KEY `buId` (`buId`),
  KEY `salesId` (`salesId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `ci_order`
--

INSERT INTO `ci_order` (`id`, `buId`, `billNo`, `uid`, `userName`, `transType`, `totalAmount`, `amount`, `rpAmount`, `billDate`, `description`, `arrears`, `disRate`, `disAmount`, `totalQty`, `totalArrears`, `billStatus`, `checkName`, `totalTax`, `totalTaxAmount`, `checked`, `accId`, `billType`, `modifyTime`, `hxStateCode`, `transTypeName`, `totalDiscount`, `salesId`, `customerFree`, `hxAmount`, `payment`, `discount`, `postData`, `locationId`, `inLocationId`, `outLocationId`, `isDelete`, `deliveryDate`) VALUES
(1, 4, 'CGDD201704261643406', 3, '王芳', 150501, 0, 0, 0, '2017-04-26', '', 0, 0, 0, 102, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:57:32', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:1;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-26";s:12:"deliveryDate";s:10:"2017-04-26";s:6:"billNo";s:19:"CGDD201704261643406";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:21;s:9:"invNumber";s:3:"008";s:7:"invName";s:3:"008";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"102";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:0:"";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:102;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-26";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:32";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-26'),
(10, 4, 'CGDD201704271018238', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 77, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:58:25', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:10;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271018238";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:38;s:9:"invNumber";s:3:"029";s:7:"invName";s:3:"029";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"77";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:12:"201704271021";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:77;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:25";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(13, 4, 'CGDD201704271026006', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 1, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:58:14', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:13;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271026006";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"PCB001";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:1;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:14";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(15, 4, 'CGDD201704271026006', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 5, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:29:25', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:15;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271026006";s:9:"transType";i:150501;s:7:"entries";a:5:{i:0;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"pcb002";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:1;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"pcb003";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:2;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"pcb004";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:3;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"pcb005";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:4;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:6:"pcb006";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:5;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:29:25";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(16, 4, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 462, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:58:01', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:16;s:4:"buId";i:4;s:11:"contactName";s:12:"淘宝商城";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:6:{i:0;a:16:{s:5:"invId";i:22;s:9:"invNumber";s:3:"009";s:7:"invName";s:3:"009";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"113";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427001";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:1;a:16:{s:5:"invId";i:19;s:9:"invNumber";s:3:"012";s:7:"invName";s:3:"012";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:3:"105";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427002";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:2;a:16:{s:5:"invId";i:24;s:9:"invNumber";s:3:"013";s:7:"invName";s:3:"013";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"67";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427003";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:3;a:16:{s:5:"invId";i:25;s:9:"invNumber";s:3:"014";s:7:"invName";s:3:"014";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"65";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427004";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:4;a:16:{s:5:"invId";i:18;s:9:"invNumber";s:3:"011";s:7:"invName";s:3:"011";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"根";s:3:"qty";s:2:"63";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427005";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:5;a:16:{s:5:"invId";i:33;s:9:"invNumber";s:3:"025";s:7:"invName";s:3:"025";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"个";s:3:"qty";s:2:"49";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427006";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:462;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:58:01";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(17, 6, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 15, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:57:45', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:17;s:4:"buId";i:6;s:11:"contactName";s:39:"廊坊特恩普电子科技有限公司";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:12;s:9:"invNumber";s:3:"005";s:7:"invName";s:3:"005";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"15";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427007";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:15;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:45";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(18, 5, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 4, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:57:23', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:18;s:4:"buId";i:5;s:11:"contactName";s:36:"沧州宾海机柜制造有限公司";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:13;s:9:"invNumber";s:3:"006";s:7:"invName";s:3:"006";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"台";s:3:"qty";s:1:"4";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427008";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:4;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:23";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(20, 11, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 27, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:57:07', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:20;s:4:"buId";i:11;s:11:"contactName";s:36:"北京天开创新技术有限公司";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:9;s:9:"invNumber";s:3:"002";s:7:"invName";s:3:"002";s:7:"invSpec";s:6:"DN2008";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"27";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427009";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:27;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:57:07";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(21, 9, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 23, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:56:58', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:21;s:4:"buId";i:9;s:11:"contactName";s:12:"京东商城";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:3:{i:0;a:16:{s:5:"invId";i:10;s:9:"invNumber";s:3:"003";s:7:"invName";s:3:"003";s:7:"invSpec";s:2:"4G";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:2:"19";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427010";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:1;a:16:{s:5:"invId";i:11;s:9:"invNumber";s:3:"004";s:7:"invName";s:3:"004";s:7:"invSpec";s:4:"120G";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"3";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427011";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}i:2;a:16:{s:5:"invId";i:39;s:9:"invNumber";s:3:"031";s:7:"invName";s:3:"031";s:7:"invSpec";s:3:"60G";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"块";s:3:"qty";s:1:"1";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427012";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:23;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:56:58";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27'),
(22, 14, 'CGDD201704271043378', 1, '超级管理员', 150501, 0, 0, 0, '2017-04-27', '', 0, 0, 0, 8, 0, 0, '超级管理员', 0, 0, 1, 0, 'PUR', '2017-04-27 10:56:43', 0, '购货', 0, 0, 0, 0, 0, 0, 'a:23:{s:2:"id";i:22;s:4:"buId";i:14;s:11:"contactName";s:36:"北京元大兴业科技有限公司";s:4:"date";s:10:"2017-04-27";s:12:"deliveryDate";s:10:"2017-04-27";s:6:"billNo";s:19:"CGDD201704271043378";s:9:"transType";i:150501;s:7:"entries";a:1:{i:0;a:16:{s:5:"invId";i:40;s:9:"invNumber";s:3:"032";s:7:"invName";s:3:"032";s:7:"invSpec";s:0:"";s:5:"skuId";i:-1;s:7:"skuName";s:0:"";s:6:"unitId";i:-1;s:8:"mainUnit";s:3:"台";s:3:"qty";s:1:"8";s:5:"price";s:1:"0";s:12:"discountRate";s:1:"0";s:9:"deduction";s:4:"0.00";s:6:"amount";s:4:"0.00";s:11:"description";s:11:"20170427013";s:10:"locationId";i:1;s:12:"locationName";s:9:"总仓库";}}s:8:"totalQty";d:8;s:11:"totalAmount";d:0;s:11:"description";s:0:"";s:7:"disRate";d:0;s:9:"disAmount";d:0;s:6:"amount";d:0;s:8:"billType";s:3:"PUR";s:13:"transTypeName";s:6:"购货";s:8:"billDate";s:10:"2017-04-27";s:10:"billStatus";i:0;s:3:"uid";s:1:"1";s:8:"userName";s:15:"超级管理员";s:10:"modifyTime";s:19:"2017-04-27 10:56:43";s:8:"accounts";a:0:{}s:7:"checked";s:1:"0";}', '', '', '', 0, '2017-04-27');

-- --------------------------------------------------------

--
-- 表的结构 `ci_order_info`
--

CREATE TABLE IF NOT EXISTS `ci_order_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) DEFAULT '0' COMMENT '关联ID',
  `buId` smallint(6) DEFAULT '0' COMMENT '供应商ID',
  `billNo` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单据编号',
  `transType` int(11) DEFAULT '0' COMMENT '150501采购 150502退货',
  `amount` double DEFAULT '0' COMMENT '购货金额',
  `billDate` date DEFAULT NULL COMMENT '单据日期',
  `description` varchar(50) DEFAULT '' COMMENT '备注',
  `deliveryDate` date DEFAULT NULL,
  `invId` int(11) DEFAULT '0' COMMENT '商品ID',
  `price` double DEFAULT '0' COMMENT '单价',
  `deduction` double DEFAULT '0' COMMENT '折扣额',
  `discountRate` double DEFAULT '0' COMMENT '折扣率',
  `qty` double DEFAULT '0' COMMENT '数量',
  `locationId` smallint(6) DEFAULT '0',
  `tax` double DEFAULT '0',
  `taxRate` double DEFAULT '0',
  `taxAmount` double DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '1部分  2已入库',
  `unitId` smallint(6) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `skuId` int(11) DEFAULT '0',
  `entryId` tinyint(1) DEFAULT '1' COMMENT '区分调拨单  进和出',
  `transTypeName` varchar(25) DEFAULT '',
  `srcOrderEntryId` int(11) DEFAULT '0',
  `srcOrderId` int(11) DEFAULT '0',
  `srcOrderNo` varchar(25) DEFAULT '',
  `billType` varchar(20) DEFAULT '',
  `salesId` smallint(6) DEFAULT '0',
  `isDelete` tinyint(1) DEFAULT '0' COMMENT '1删除 0正常',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`),
  KEY `type` (`transType`),
  KEY `billdate` (`billDate`),
  KEY `invId` (`invId`) USING BTREE,
  KEY `transType` (`transType`),
  KEY `iid` (`iid`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- 转存表中的数据 `ci_order_info`
--

INSERT INTO `ci_order_info` (`id`, `iid`, `buId`, `billNo`, `transType`, `amount`, `billDate`, `description`, `deliveryDate`, `invId`, `price`, `deduction`, `discountRate`, `qty`, `locationId`, `tax`, `taxRate`, `taxAmount`, `status`, `unitId`, `uid`, `skuId`, `entryId`, `transTypeName`, `srcOrderEntryId`, `srcOrderId`, `srcOrderNo`, `billType`, `salesId`, `isDelete`) VALUES
(23, 15, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'pcb002', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(24, 15, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'pcb003', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 2, 0, '', 'PUR', 0, 0),
(25, 15, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'pcb004', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 3, 0, '', 'PUR', 0, 0),
(26, 15, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'pcb005', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 4, 0, '', 'PUR', 0, 0),
(27, 15, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'pcb006', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 5, 0, '', 'PUR', 0, 0),
(42, 22, 14, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 40, 0, 0, 0, 8, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(43, 21, 9, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 10, 0, 0, 0, 19, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(44, 21, 9, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 11, 0, 0, 0, 3, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 2, 0, '', 'PUR', 0, 0),
(45, 21, 9, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 39, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 3, 0, '', 'PUR', 0, 0),
(46, 20, 11, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 9, 0, 0, 0, 27, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(47, 18, 5, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 13, 0, 0, 0, 4, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(48, 1, 4, 'CGDD201704261643406', 150501, 0, '2017-04-26', NULL, '2017-04-26', 21, 0, 0, 0, 102, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(49, 17, 6, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 12, 0, 0, 0, 15, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(50, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 22, 0, 0, 0, 113, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(51, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 19, 0, 0, 0, 105, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 2, 0, '', 'PUR', 0, 0),
(52, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 24, 0, 0, 0, 67, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 3, 0, '', 'PUR', 0, 0),
(53, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 25, 0, 0, 0, 65, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 4, 0, '', 'PUR', 0, 0),
(54, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 18, 0, 0, 0, 63, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 5, 0, '', 'PUR', 0, 0),
(55, 16, 4, 'CGDD201704271043378', 150501, 0, '2017-04-27', NULL, '2017-04-27', 33, 0, 0, 0, 49, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 6, 0, '', 'PUR', 0, 0),
(56, 13, 4, 'CGDD201704271026006', 150501, 0, '2017-04-27', 'PCB001', '2017-04-27', 12, 0, 0, 0, 1, 1, 0, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0),
(57, 10, 4, 'CGDD201704271018238', 150501, 0, '2017-04-27', NULL, '2017-04-27', 38, 0, 0, 0, 77, 1, 1, 0, 0, 0, -1, 1, -1, 1, '购货', 1, 0, '', 'PUR', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_staff`
--

CREATE TABLE IF NOT EXISTS `ci_staff` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `number` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `disable` tinyint(1) DEFAULT '0' COMMENT '0启用  1禁用',
  `allowsms` tinyint(4) DEFAULT '0',
  `birthday` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `commissionrate` tinyint(4) DEFAULT '0',
  `creatorId` int(11) DEFAULT '0',
  `deptId` int(11) DEFAULT '0',
  `description` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `empId` int(11) DEFAULT '0',
  `empType` tinyint(4) DEFAULT '1',
  `fullId` int(11) DEFAULT '0',
  `leftDate` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `mobile` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `parentId` smallint(6) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `userName` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `number` (`number`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ci_staff`
--

INSERT INTO `ci_staff` (`id`, `name`, `number`, `disable`, `allowsms`, `birthday`, `commissionrate`, `creatorId`, `deptId`, `description`, `email`, `empId`, `empType`, `fullId`, `leftDate`, `mobile`, `parentId`, `sex`, `userName`, `isDelete`) VALUES
(1, '销售员工', '001', 0, 0, '', 0, 0, 0, '', '', 0, 1, 0, '', '', NULL, NULL, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_storage`
--

CREATE TABLE IF NOT EXISTS `ci_storage` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '导航栏目',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '栏目名称',
  `locationNo` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `disable` tinyint(1) DEFAULT '0' COMMENT '状态 0正常  1锁定',
  `allowNeg` tinyint(4) DEFAULT '0',
  `deptId` int(11) DEFAULT '0',
  `empId` int(11) DEFAULT '0',
  `groupx` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `type` tinyint(4) DEFAULT '0',
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `isDelete` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `locationNo` (`locationNo`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ci_storage`
--

INSERT INTO `ci_storage` (`id`, `name`, `locationNo`, `disable`, `allowNeg`, `deptId`, `empId`, `groupx`, `phone`, `type`, `address`, `isDelete`) VALUES
(1, '总仓库', '1', 0, 0, 0, 0, '', '', 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_unit`
--

CREATE TABLE IF NOT EXISTS `ci_unit` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '客户名称',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `unitTypeId` smallint(6) DEFAULT '0',
  `default` tinyint(1) DEFAULT '0',
  `rate` tinyint(1) DEFAULT '0',
  `guid` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `ci_unit`
--

INSERT INTO `ci_unit` (`id`, `name`, `status`, `unitTypeId`, `default`, `rate`, `guid`, `isDelete`) VALUES
(1, '个', 1, 0, 0, 0, '', 0),
(2, '块', 1, 0, 0, 0, '', 0),
(3, '台', 1, 0, 0, 0, '', 0),
(4, '根', 1, 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ci_unittype`
--

CREATE TABLE IF NOT EXISTS `ci_unittype` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '客户名称',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isDelete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_verifica_info`
--

CREATE TABLE IF NOT EXISTS `ci_verifica_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) DEFAULT '0' COMMENT '关联ID',
  `buId` smallint(6) DEFAULT '0' COMMENT '客户ID',
  `billId` int(11) DEFAULT '0' COMMENT '销售单号ID',
  `billNo` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '单编号',
  `billType` varchar(20) DEFAULT '',
  `transType` varchar(50) DEFAULT '',
  `billDate` date DEFAULT NULL,
  `billPrice` double DEFAULT NULL,
  `nowCheck` double DEFAULT '0' COMMENT '本次核销',
  `hasCheck` double DEFAULT '0' COMMENT '已核销',
  `notCheck` double DEFAULT '0' COMMENT '未核销',
  `isDelete` tinyint(1) DEFAULT '0',
  `checked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ci_warehouse`
--

CREATE TABLE IF NOT EXISTS `ci_warehouse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invId` int(11) DEFAULT '0' COMMENT '商品ID',
  `highQty` double DEFAULT '0' COMMENT '供应商ID',
  `lowQty` double DEFAULT '0',
  `locationId` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `invId` (`invId`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
