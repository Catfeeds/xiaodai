-- MySQL dump 10.16  Distrib 10.1.33-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: youyiqianbao
-- ------------------------------------------------------
-- Server version	10.1.33-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `my_access`
--

DROP TABLE IF EXISTS `my_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='授权表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_access`
--

LOCK TABLES `my_access` WRITE;
/*!40000 ALTER TABLE `my_access` DISABLE KEYS */;
INSERT INTO `my_access` VALUES (1,1,1,NULL),(1,2,2,NULL),(1,9,3,NULL),(1,10,3,NULL),(1,11,3,NULL),(1,12,3,NULL),(1,13,3,NULL),(1,14,3,NULL),(1,15,3,NULL),(1,16,3,NULL),(1,17,3,NULL),(1,18,3,NULL),(1,19,3,NULL),(1,20,3,NULL),(1,32,3,NULL),(1,33,3,NULL),(1,34,3,NULL),(1,35,3,NULL),(1,3,2,NULL),(1,21,3,NULL),(1,22,3,NULL),(1,23,3,NULL),(1,24,3,NULL),(1,25,3,NULL),(1,26,3,NULL),(1,27,3,NULL),(1,6,2,NULL),(1,66,3,NULL),(1,49,3,NULL),(1,50,3,NULL),(1,51,3,NULL),(1,52,3,NULL),(1,53,3,NULL),(1,54,3,NULL),(1,55,3,NULL),(1,56,3,NULL),(1,67,3,NULL),(1,68,3,NULL),(1,69,3,NULL),(1,82,3,NULL),(1,83,3,NULL),(1,84,3,NULL),(1,85,3,NULL),(1,95,3,NULL),(1,96,3,NULL),(1,97,3,NULL),(1,98,3,NULL),(1,119,3,NULL),(1,120,3,NULL),(1,121,3,NULL),(1,122,3,NULL),(1,123,3,NULL),(1,124,3,NULL),(1,125,3,NULL),(1,126,3,NULL),(1,127,3,NULL),(1,128,3,NULL),(1,129,3,NULL),(1,130,3,NULL),(1,4,2,NULL),(1,57,3,NULL),(1,58,3,NULL),(1,59,3,NULL),(1,60,3,NULL),(1,70,3,NULL),(1,61,3,NULL),(1,62,3,NULL),(1,63,3,NULL),(1,64,3,NULL),(1,65,3,NULL),(1,71,3,NULL),(1,72,3,NULL),(1,73,3,NULL),(1,91,3,NULL),(1,92,3,NULL),(1,93,3,NULL),(1,94,3,NULL),(1,104,3,NULL),(1,105,3,NULL),(1,106,3,NULL),(1,111,3,NULL),(1,112,3,NULL),(1,113,3,NULL),(1,115,3,NULL),(1,131,3,NULL),(1,132,3,NULL),(1,133,3,NULL),(1,134,3,NULL),(1,162,3,NULL),(1,163,3,NULL),(1,164,3,NULL),(1,165,3,NULL),(1,166,3,NULL),(1,167,3,NULL),(1,168,3,NULL),(1,169,3,NULL),(1,107,3,NULL),(1,108,3,NULL),(1,109,3,NULL),(1,110,3,NULL),(1,248,3,NULL),(1,249,3,NULL),(1,250,3,NULL),(1,251,3,NULL),(1,5,2,NULL),(1,28,3,NULL),(1,29,3,NULL),(1,30,3,NULL),(1,31,3,NULL),(1,155,2,NULL),(1,156,3,NULL),(1,157,3,NULL),(1,158,3,NULL),(1,159,3,NULL),(1,7,2,NULL),(1,74,3,NULL),(1,75,3,NULL),(1,76,3,NULL),(1,77,3,NULL),(1,78,3,NULL),(1,79,3,NULL),(1,80,3,NULL),(1,81,3,NULL),(1,86,3,NULL),(1,87,3,NULL),(1,88,3,NULL),(1,89,3,NULL),(1,90,3,NULL),(1,99,3,NULL),(1,100,3,NULL),(1,101,3,NULL),(1,102,3,NULL),(1,103,3,NULL),(1,116,3,NULL),(1,118,3,NULL),(1,160,3,NULL),(1,161,3,NULL),(1,252,3,NULL),(1,253,3,NULL),(1,254,3,NULL),(1,255,3,NULL),(1,256,3,NULL),(1,261,3,NULL),(1,262,3,NULL),(1,263,3,NULL),(1,264,3,NULL),(1,269,3,NULL),(1,270,3,NULL),(1,271,3,NULL),(1,272,3,NULL),(1,273,3,NULL),(1,274,3,NULL),(1,265,2,NULL),(1,266,3,NULL),(1,267,3,NULL),(1,268,3,NULL),(1,197,2,NULL),(1,198,3,NULL),(1,199,3,NULL),(1,200,3,NULL),(1,201,3,NULL),(1,209,3,NULL),(1,210,3,NULL),(1,211,3,NULL),(1,212,3,NULL),(1,213,3,NULL),(1,214,3,NULL),(1,257,3,NULL),(1,258,3,NULL),(1,259,3,NULL),(1,260,3,NULL),(1,8,2,NULL),(1,36,3,NULL),(1,37,3,NULL),(1,38,3,NULL),(1,39,3,NULL),(1,40,3,NULL),(1,41,3,NULL),(1,42,3,NULL),(1,43,3,NULL),(1,44,3,NULL),(1,45,3,NULL),(1,46,3,NULL),(1,47,3,NULL),(1,144,3,NULL),(1,149,4,NULL),(1,153,4,NULL),(1,145,4,NULL),(1,150,4,NULL),(1,154,4,NULL),(1,146,4,NULL),(1,148,4,NULL),(1,152,4,NULL),(1,147,4,NULL),(1,151,4,NULL),(1,48,3,NULL),(1,215,3,NULL),(1,216,4,NULL),(1,217,4,NULL),(1,218,4,NULL),(1,219,4,NULL),(1,220,4,NULL),(1,221,4,NULL),(1,222,4,NULL),(1,223,4,NULL),(1,224,4,NULL),(1,225,4,NULL),(1,114,3,NULL),(1,117,3,NULL),(1,170,2,NULL),(1,226,3,NULL),(1,227,3,NULL),(1,228,3,NULL),(1,229,3,NULL),(1,171,3,NULL),(1,172,3,NULL),(1,173,3,NULL),(1,174,3,NULL),(1,175,3,NULL),(1,176,3,NULL),(1,177,3,NULL),(1,178,3,NULL),(1,203,3,NULL),(1,202,3,NULL),(1,179,3,NULL),(1,180,3,NULL),(1,181,3,NULL),(1,182,3,NULL),(1,183,3,NULL),(1,184,3,NULL),(1,185,3,NULL),(1,186,3,NULL),(1,187,3,NULL),(1,188,3,NULL),(1,189,3,NULL),(1,190,3,NULL),(1,191,3,NULL),(1,192,3,NULL),(1,193,3,NULL),(1,194,3,NULL),(1,195,3,NULL),(1,196,3,NULL),(1,204,3,NULL),(1,205,3,NULL),(1,206,3,NULL),(1,207,3,NULL),(1,208,3,NULL),(1,244,3,NULL),(1,245,3,NULL),(1,246,3,NULL),(1,247,3,NULL),(1,230,2,NULL),(1,231,3,NULL),(1,232,3,NULL),(1,233,3,NULL),(1,234,3,NULL),(1,235,3,NULL),(1,236,3,NULL),(1,237,3,NULL),(1,238,3,NULL),(1,239,3,NULL),(1,240,3,NULL),(1,241,3,NULL),(1,242,3,NULL),(1,243,3,NULL);
/*!40000 ALTER TABLE `my_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_account_log`
--

DROP TABLE IF EXISTS `my_account_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_account_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL COMMENT '金额',
  `act` varchar(255) DEFAULT NULL COMMENT '动作',
  `type` int(11) DEFAULT NULL COMMENT '类型 5:推广返利.1,在线充值,4:用户提现,2:订单消费，3：订单退款',
  `addtime` datetime DEFAULT NULL,
  `orderno` varchar(45) DEFAULT NULL COMMENT '在线充值订单号',
  `status` int(11) DEFAULT '0' COMMENT '在线充值状态  0:失败,1:成功',
  `paytime` datetime DEFAULT NULL COMMENT '在线充值支付时间',
  `payinfo` longtext COMMENT '在线充值结果信息',
  `payid` int(11) DEFAULT NULL COMMENT '支付者会员id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户变动记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_account_log`
--

LOCK TABLES `my_account_log` WRITE;
/*!40000 ALTER TABLE `my_account_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_account_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_address`
--

DROP TABLE IF EXISTS `my_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agentid` int(11) DEFAULT '0' COMMENT '代理商ID',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isdefault` tinyint(3) DEFAULT '0' COMMENT '0-否，1-默认',
  `username` varchar(255) DEFAULT NULL COMMENT '用户填写的真实姓名',
  `telephone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `provinceid` int(11) DEFAULT '0',
  `cityid` int(11) DEFAULT '0',
  `districtid` int(11) DEFAULT '0',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `memberid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收货地址表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_address`
--

LOCK TABLES `my_address` WRITE;
/*!40000 ALTER TABLE `my_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_article_praise`
--

DROP TABLE IF EXISTS `my_article_praise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_article_praise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) DEFAULT '0' COMMENT '文章id',
  `memberid` int(11) DEFAULT '0' COMMENT '会员id',
  `addtime` datetime DEFAULT NULL COMMENT '点赞时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章点赞记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_article_praise`
--

LOCK TABLES `my_article_praise` WRITE;
/*!40000 ALTER TABLE `my_article_praise` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_article_praise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_article_share`
--

DROP TABLE IF EXISTS `my_article_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_article_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) DEFAULT '0' COMMENT '文章id',
  `memberid` int(11) DEFAULT '0' COMMENT '会员id',
  `addtime` datetime DEFAULT NULL COMMENT '分享时间',
  `num` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章分享记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_article_share`
--

LOCK TABLES `my_article_share` WRITE;
/*!40000 ALTER TABLE `my_article_share` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_article_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_article_view`
--

DROP TABLE IF EXISTS `my_article_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_article_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleid` int(11) DEFAULT '0' COMMENT '文章id',
  `memberid` int(11) DEFAULT '0' COMMENT '会员id',
  `from` int(11) DEFAULT '0' COMMENT '0-公众号，1-朋友圈，2-微信群，3-好友分享',
  `frommemberid` int(11) DEFAULT '0' COMMENT '若是通过分享浏览，则记录分享人',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '阅读时间',
  `endtime` timestamp NULL DEFAULT NULL COMMENT '结束阅读时间',
  `phonemodel` varchar(255) DEFAULT NULL COMMENT '手机型号',
  `readtime` decimal(10,2) DEFAULT '1.00' COMMENT '阅读时长',
  `num` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章阅读记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_article_view`
--

LOCK TABLES `my_article_view` WRITE;
/*!40000 ALTER TABLE `my_article_view` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_article_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_bankinfo`
--

DROP TABLE IF EXISTS `my_bankinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_bankinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `telephone` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `bankname` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `bankno` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_bankinfo`
--

LOCK TABLES `my_bankinfo` WRITE;
/*!40000 ALTER TABLE `my_bankinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_bankinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_cart`
--

DROP TABLE IF EXISTS `my_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `memberid` varchar(255) DEFAULT NULL COMMENT '保存用户的openid',
  `attr` longtext COMMENT '属性',
  `num` int(11) DEFAULT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_cart`
--

LOCK TABLES `my_cart` WRITE;
/*!40000 ALTER TABLE `my_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_classes`
--

DROP TABLE IF EXISTS `my_category_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='课程分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_classes`
--

LOCK TABLES `my_category_classes` WRITE;
/*!40000 ALTER TABLE `my_category_classes` DISABLE KEYS */;
INSERT INTO `my_category_classes` VALUES (1,'2017-09-11 02:11:00',1,1,'企业管理类','企业管理类课程',1,0,'0,1,',0,'/Public/uploadfile/file/2017-09-11/59b5f0a57927a.jpg'),(2,'2017-09-11 02:12:15',1,2,'财税类','财税联盟',1,0,'0,2,',0,'/Public/uploadfile/file/2017-09-11/59b5f0f0ef691.png'),(3,'2017-09-11 02:13:53',1,3,'财务软件培训','财务管理软件培训',1,0,'0,3,',0,'/Public/uploadfile/file/2017-09-11/59b5f14b8c3a7.jpg'),(4,'2017-01-14 14:17:00',1,4,'沐兰学财税','',1,0,'0,4,',0,''),(5,'2017-09-08 09:33:09',1,5,'财务软件宣传类','财务软件宣传',1,0,'0,5,',0,'/Public/uploadfile/file/2017-09-08/59b263d3ac9d7.jpg'),(6,'2017-09-11 02:14:54',1,6,'国学类','国学大师授课',1,0,'0,6,',0,'/Public/uploadfile/file/2017-09-11/59b5f19cba013.png'),(8,'2017-11-18 14:39:09',1,8,'产品学习资料','',1,0,'0,8,',0,'/Public/uploadfile/file/2017-11-18/5a103a2f5dfff.jpg');
/*!40000 ALTER TABLE `my_category_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_company`
--

DROP TABLE IF EXISTS `my_category_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='单位表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_company`
--

LOCK TABLES `my_category_company` WRITE;
/*!40000 ALTER TABLE `my_category_company` DISABLE KEYS */;
INSERT INTO `my_category_company` VALUES (1,'2017-09-08 07:32:30',1,1,'中欧国际管理学院','中欧国际管理学院（China  Europe  Int’L  Management  College）是一所由中欧国际长江商学院投资集团（CHINA  DIRECT  DISTRIBUTION  MANAGEMENT  COLLEGE  LIMITED）和世界商务策划师联合会（WORLD  BUSINESS  STRATEGIST   ASSOCIATION）等大型国际学术教育机构共同创办的、专门培养国际化高级管理人才的非赢利性高等学府。中欧国际管理学院（CEIMC）以国际水平为中国培养具有全球竞争及合作',1,0,'0,1,',1,'/Public/uploadfile/file/2017-09-07/59b143aea074a.jpg'),(5,'2017-09-08 07:50:53',1,5,'天润华邦会计师事务所','四川天润华邦财税服务有限公司是专注于企业财税健康，助推企业成长的专业会计服务机构，我们通过专业知识为客户提供：股份制改组与上市的审计、验资、盈利预测审核、内部控制评价；各类企业的会计报表审计、审阅、内部审计；企业清算会计报表审计；经济责任审计、资产核资审计；对舞弊或差错的特种调查；参与调解经济纠纷，协助鉴别经济案件证据；商定程序及其他各种特定目的的审计；承担企业上市前的可行性研究\n',1,0,'0,5,',1,'/Public/uploadfile/file/2017-09-08/59b24bda998aa.jpg'),(6,'2017-09-08 07:35:33',1,6,'四川航天金穗高技术有限公司','四川航天金穗高技术有限公司成立于1999年7月，是知名上市企业航天信息股份有限公司（股票代码：600271）在四川的子公司。公司致力于政府信息化和企业信息化服务，是金税工程重要组成部分——“增值税防伪税控系统”在四川省唯一的服务单位。公司主要业务涉足“金税”、“金盾”、“金卡”等国家重点工程,是我省规模最大的IT服务企业。 通过9年多的艰苦奋斗，目前公司已设立了19个分子公司，并建立起覆盖全省21个地市服务网点，形成一个立体化服务体系。',1,0,'0,6,',1,'/Public/uploadfile/file/2017-09-08/59b2483b8e18f.jpg'),(8,'2017-09-11 03:48:10',1,7,'四川世纪中税软件系统有限公司','四川一般纳税人、小规模纳税人税务申报服务企业！',1,0,'0,8,',1,'/Public/uploadfile/file/2017-09-08/59b248060c65d.jpg'),(9,'2017-09-08 07:52:35',1,2,'财税专家—孟峰','汇智云讲堂特聘讲师。\n    注册会计师，畅捷通信息技术股份有限公司小微企业研究院税务专家。 著作：《别交冤枉税—企业所得税汇算清缴一本通》 主要课程： \n小企业会计准则讲解及财税差异分析系列 企业所得税汇缴实务及填报系列 营转增帐务处理实务系列 企业经营全过程财税问题分析  中级会计实务',1,0,'0,9,',1,'/Public/uploadfile/file/2017-09-07/59b14f6e81f02.jpg'),(10,'2017-09-11 03:48:12',1,8,'财税联盟','川渝两地财务、税务信息化交流平台',1,0,'0,10,',1,'/Public/uploadfile/file/2017-09-08/59b24088d2c31.jpg'),(11,'2017-09-08 07:53:15',1,2,'国际贸易专家讲堂—曹屹','汇智云讲堂特聘讲师、中国总会计师协会特聘讲师。四川省某高校副教授、会计师，20多年丰富的教学经验，主讲《基础会计》、《财务会计》、《国际贸易会计》等专业课程。',1,0,'0,11,',1,'/Public/uploadfile/file/2017-09-07/59b14f6e81f02.jpg'),(13,'2017-09-11 03:48:14',1,9,'四川百旺金赋科技有限公司','四川百旺金赋科技有限公司是经四川省国家税务局批准,国家信息安全工程技术研究中心唯一授权的，在四川省内从事增值税发票系统升级版推广和服务的企业。\n      目前公司已在全省二十一个市州设立了服务网点，拥有专业的服务团队。我们秉承“与客户一同成长、做企业满意的财税伙伴”的服务方针，努力为客户提供更加标准化、专业化的服务。',1,0,'0,13,',1,'/Public/uploadfile/file/2017-09-11/59b60764c9437.jpg');
/*!40000 ALTER TABLE `my_category_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_coupon`
--

DROP TABLE IF EXISTS `my_category_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `template` varchar(255) DEFAULT NULL COMMENT '模板名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_coupon`
--

LOCK TABLES `my_category_coupon` WRITE;
/*!40000 ALTER TABLE `my_category_coupon` DISABLE KEYS */;
INSERT INTO `my_category_coupon` VALUES (1,'2016-01-26 03:34:05',1,1,'5元券','',1,0,'0,1,',0,'',NULL),(2,'2016-01-26 03:34:05',1,2,'10元券','',1,0,'0,2,',0,'',NULL),(3,'2018-07-07 11:30:40',1,3,'123','1111111111111',1,0,'0,3,',0,'',NULL);
/*!40000 ALTER TABLE `my_category_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_info`
--

DROP TABLE IF EXISTS `my_category_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `template` varchar(255) DEFAULT NULL COMMENT '模板名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='单页分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_info`
--

LOCK TABLES `my_category_info` WRITE;
/*!40000 ALTER TABLE `my_category_info` DISABLE KEYS */;
INSERT INTO `my_category_info` VALUES (1,'2016-11-03 08:32:24',1,1,'关于我们','',1,0,'0,1,',0,'',NULL);
/*!40000 ALTER TABLE `my_category_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_label`
--

DROP TABLE IF EXISTS `my_category_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` text COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='标签分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_label`
--

LOCK TABLES `my_category_label` WRITE;
/*!40000 ALTER TABLE `my_category_label` DISABLE KEYS */;
INSERT INTO `my_category_label` VALUES (1,'2015-07-21 14:16:30',1,1,'基础信息','',1,0,'0,1,',0,''),(2,'2015-07-21 14:16:30',1,2,'广告幻灯','',1,0,'0,2,',0,'');
/*!40000 ALTER TABLE `my_category_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_member`
--

DROP TABLE IF EXISTS `my_category_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `template` varchar(255) DEFAULT NULL COMMENT '模板名',
  `discount` int(11) DEFAULT '100' COMMENT '会员购物折扣',
  `praviteper` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_member`
--

LOCK TABLES `my_category_member` WRITE;
/*!40000 ALTER TABLE `my_category_member` DISABLE KEYS */;
INSERT INTO `my_category_member` VALUES (1,'2016-01-23 03:18:30',1,1,'普通会员','',1,0,'0,1,',0,'',NULL,100,NULL),(2,'2017-05-20 07:17:22',1,2,'VIP会员','',1,0,'0,2,',0,'',NULL,90,'94,119'),(3,'2017-05-18 02:19:45',1,3,'黄金会员','',1,0,'0,3,',0,'',NULL,80,NULL),(4,'2017-05-18 02:19:56',1,4,'钻石会员','',1,0,'0,4,',0,'',NULL,70,NULL);
/*!40000 ALTER TABLE `my_category_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_news`
--

DROP TABLE IF EXISTS `my_category_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `template` varchar(255) DEFAULT NULL COMMENT '模板名',
  `inner` int(11) DEFAULT '0' COMMENT '1-neibu',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='资讯分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_news`
--

LOCK TABLES `my_category_news` WRITE;
/*!40000 ALTER TABLE `my_category_news` DISABLE KEYS */;
INSERT INTO `my_category_news` VALUES (1,'2016-12-27 03:33:42',1,1,'新闻中心','',1,0,'0,1,',0,'',NULL,0);
/*!40000 ALTER TABLE `my_category_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_product`
--

DROP TABLE IF EXISTS `my_category_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `template` varchar(255) DEFAULT NULL COMMENT '模板名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='产品分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_product`
--

LOCK TABLES `my_category_product` WRITE;
/*!40000 ALTER TABLE `my_category_product` DISABLE KEYS */;
INSERT INTO `my_category_product` VALUES (1,'2017-09-08 06:43:46',1,5,'财务软件','用友云服务产品',1,0,'0,1,',0,'/Public/uploadfile/file/2017-09-08/59b23c1f5c81a.jpg',NULL),(2,'2017-09-08 03:53:29',1,4,'培训考试','财税职称培训考试资料',1,0,'0,2,',0,'',NULL),(3,'2017-09-08 04:08:02',1,2,'教材书籍','中欧国际管理学院讲师教材',1,0,'0,3,',0,'/Public/uploadfile/file/2017-09-08/59b217a117206.jpg',NULL),(4,'2017-09-08 04:00:17',1,3,'财务耗材','财务软件配套打印耗材',1,0,'0,4,',0,'/Public/uploadfile/file/2017-09-08/59b215cedd7da.jpg',NULL),(7,'2017-09-08 06:44:39',1,1,'在线课程','在线直播、录播类课程',1,0,'0,7,',0,'/Public/uploadfile/file/2017-09-07/59b14f6e81f02.jpg',NULL),(14,'2017-12-01 12:58:16',1,14,'微信开发','',1,0,'0,14,',0,'/Public/uploadfile/file/2017-12-01/5a2151e5dbc6b.jpg',NULL);
/*!40000 ALTER TABLE `my_category_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_teacher`
--

DROP TABLE IF EXISTS `my_category_teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  `sort` tinyint(3) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `depth` tinyint(3) DEFAULT '0' COMMENT '层级',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `sortpath` varchar(100) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '推荐：0-否，1-是',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='老师表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_teacher`
--

LOCK TABLES `my_category_teacher` WRITE;
/*!40000 ALTER TABLE `my_category_teacher` DISABLE KEYS */;
INSERT INTO `my_category_teacher` VALUES (1,'2017-09-08 08:00:00',1,1,'刘鹰','中欧国际管理学院院长，中欧MBA硕士总裁班、中欧DBA博士总裁班课程主讲教授。',1,0,'0,1,',0,'/Public/uploadfile/file/2017-09-08/59b24dfc6422c.jpg'),(2,'2016-12-30 13:33:38',1,2,'王文君','中欧管理学院著名讲师，西华大学教授！',1,0,'0,2,',0,'/Public/uploadfile/file/2016-12-29/5864a36d43775.jpg'),(6,'2016-12-30 13:59:34',1,6,'王文','',1,0,'0,6,',0,''),(7,'2017-01-14 14:17:22',1,7,'沐兰','',1,0,'0,7,',0,''),(8,'2017-09-09 13:05:43',1,8,'孟峰','用友公司税务专家，有10多年的财税服务经验，中国注册税务师，会计师',1,0,'0,8,',0,'/Public/uploadfile/file/2017-09-09/59b3e720f1479.jpg'),(9,'2017-02-23 07:32:24',1,9,'唐心智','中欧国际管理学院特聘DBA博士班特聘讲师！曾在美国加州大学做过游学学者，对国际经贸问题有很客观独到的见解！',1,0,'0,9,',0,''),(10,'2017-09-09 14:08:40',1,10,'畅捷通','畅捷通信息技术股份有限公司',1,0,'0,10,',0,'/Public/uploadfile/file/2017-09-09/59b3f5e667f35.gif'),(11,'2017-03-15 11:54:19',1,11,'熊平','中欧国际管理学院特聘DBA博士班特聘讲师！',1,0,'0,11,',0,'/Public/uploadfile/file/2017-03-15/58c928b437bdc.jpg'),(12,'2017-03-24 02:43:29',1,12,'曹屹','老师简介：四川省某高校副教授、会计师，20多年丰富的教学经验，主讲《基础会计》、《财务会计》、《国际贸易会计》等专业课程。中国总会计师协会、汇智共创特聘讲师。',1,0,'0,12,',0,'/Public/uploadfile/file/2017-03-24/58d4868ed9ad1.jpg'),(13,'2017-03-27 06:30:03',1,13,'赵永杰','成都卓强知识产权代理有限公司总经理、中欧国际管理学院讲师。',1,0,'0,13,',0,'/Public/uploadfile/file/2017-03-15/58c896a49a46a.jpg'),(14,'2017-03-27 07:54:48',1,14,'邱绍群','中欧国际管理学院特聘讲师',1,0,'0,14,',0,'/Public/uploadfile/file/2017-03-15/58c896a49a46a.jpg'),(15,'2017-03-29 03:05:05',1,15,'黄成鹏','中欧国际管理学院特聘讲师',1,0,'0,15,',0,'/Public/uploadfile/file/2017-03-15/58c896a49a46a.jpg'),(16,'2017-09-09 13:06:07',1,16,'吕涛','中欧国际管理学院秘书处成员，拥有20多年ERP管理软件实施经验，企业信息化建设指导专家！',1,0,'0,16,',0,'/Public/uploadfile/file/2017-09-09/59b3e73de5d5e.png'),(17,'2017-04-20 01:42:01',1,17,'汤佛平','',1,0,'0,17,',0,'/Public/uploadfile/file/2017-03-15/58c896a49a46a.jpg'),(18,'2017-06-12 09:08:06',1,18,'叶彬','中欧国际管理学院著名讲师',1,0,'0,18,',0,''),(19,'2017-08-25 07:35:25',1,19,'张恩泽','中欧国际管理学院 DBA博士总裁班特聘教授，西南财经大学金融管理学教授。对中国股市有多年研究',1,0,'0,19,',0,'/Public/uploadfile/file/2017-03-15/58c896a49a46a.jpg'),(20,'2017-09-11 03:48:54',1,20,'百旺金赋','',1,0,'0,20,',0,'/Public/uploadfile/file/2017-09-11/59b60764c9437.jpg'),(23,'2017-09-19 07:09:00',1,23,'邱庆剑','中国著名财税专家，中国最早注册会计师之一。财税联盟合作讲师。主讲课程：顶层财税设计',1,0,'0,23,',0,'/Public/uploadfile/file/2017-09-19/59c0c22a5d057.jpg'),(24,'2017-09-29 01:06:45',1,24,'赵亚川','中欧国际管理学院DBA博士总裁班组织战略设计专题讲师，四川西南财经大学特聘教授。',1,0,'0,24,',0,'');
/*!40000 ALTER TABLE `my_category_teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_category_turntable`
--

DROP TABLE IF EXISTS `my_category_turntable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_category_turntable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL COMMENT '开始时间',
  `endtime` datetime DEFAULT NULL COMMENT '结束时间',
  `times` int(11) DEFAULT NULL COMMENT '每人抽奖次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='大转盘活动列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_category_turntable`
--

LOCK TABLES `my_category_turntable` WRITE;
/*!40000 ALTER TABLE `my_category_turntable` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_category_turntable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_class_order`
--

DROP TABLE IF EXISTS `my_class_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_class_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL,
  `memberid` int(11) DEFAULT NULL,
  `classid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0:未支付，1：已支付',
  `payinfo` longtext COMMENT '支付详细信息',
  `paytime` datetime DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_class_order`
--

LOCK TABLES `my_class_order` WRITE;
/*!40000 ALTER TABLE `my_class_order` DISABLE KEYS */;
INSERT INTO `my_class_order` VALUES (2,'1612277D50',187,11,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"vsldxl4jqcp9vjegyopcm28kg8hryh8b\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms2pZkhkHvUIIQGoSIyJDdFk\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612277D50_1482831319\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"5A6170132053F4F466C12CE34660D432\\\";s:8:\\\"time_end\\\";s:14:\\\"20161227173532\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4008882001201612274178781120\\\";}\"','2016-12-27 17:35:32',NULL,0.01,'课程视频'),(3,'1111111',188,11,1,NULL,NULL,NULL,NULL,'课程视频'),(4,'1612277D50',187,12,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"vsldxl4jqcp9vjegyopcm28kg8hryh8b\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms2pZkhkHvUIIQGoSIyJDdFk\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612277D50_1482831319\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"5A6170132053F4F466C12CE34660D432\\\";s:8:\\\"time_end\\\";s:14:\\\"20161227173532\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4008882001201612274178781120\\\";}\"','2016-12-27 17:35:32','1899-12-29 00:00:00',0.01,'课程视频'),(5,'161227AB6C',189,11,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:4:\\\"1000\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"ri7djj9qb9an0gyoe9v4gvgklgj1tz8s\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"161227AB6C_1483104924\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"C2E58E645F59194D1209563E01DA8771\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230213532\\\";s:9:\\\"total_fee\\\";s:4:\\\"1000\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612304540736221\\\";}\"','2016-12-30 21:35:33',NULL,10.00,'课程视频'),(6,'1612278685',189,8,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"qaungnq6dcgsdcx7mnsf5qq1cvvtgfq6\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612278685_1482848570\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"E07DA46A66365B5286A5BDD53A99B84D\\\";s:8:\\\"time_end\\\";s:14:\\\"20161227222259\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612274212794118\\\";}\"','2016-12-27 22:23:02',NULL,0.01,'资料课程12'),(7,'16122738D2',189,10,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"kbwmvh9lhxr4io5re2le8zsiljej9o9q\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"16122738D2_1482848649\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"227C103DCE43C6E8591D75827BE19A3A\\\";s:8:\\\"time_end\\\";s:14:\\\"20161227222416\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612274212804588\\\";}\"','2016-12-27 22:24:16',NULL,0.01,'资料课程12'),(8,'161228A08D',191,11,0,NULL,NULL,NULL,10.00,'课程视频'),(9,'161228B93A',192,6,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"h2eopspso83jezkqvsrppc5bdofhp66e\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms-wXwz890vCiebZ__Ukz_g8\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"161228B93A_1482929974\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"32C415E69338048515DA0FB1146431AB\\\";s:8:\\\"time_end\\\";s:14:\\\"20161228205940\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4003522001201612284311484822\\\";}\"','2016-12-28 20:59:40',NULL,0.01,'资料课程12'),(10,'1612286705',195,6,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"8bzqlv4c1ug2225koxkkezt7ibo1u50s\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612286705_1482930169\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"2CAF684EC9DD11FAA34922C3251A9A88\\\";s:8:\\\"time_end\\\";s:14:\\\"20161228210258\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201612284313173102\\\";}\"','2016-12-28 21:03:01',NULL,0.01,'资料课程12'),(11,'1612297050',189,6,0,NULL,NULL,NULL,0.01,'资料课程12'),(12,'1612307372',204,9,0,NULL,NULL,NULL,0.01,'人与文化（下）—刘鹰'),(13,'16123023BF',192,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"1kefc43ih65sop440ppo9vnt8zig2ec1\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms-wXwz890vCiebZ__Ukz_g8\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"16123023BF_1483101018\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"16538889F51E8D9AA828E5BFC9DE1580\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230203638\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4003522001201612304532614759\\\";}\"','2016-12-30 20:36:39',NULL,0.01,'人与文化（下）—刘鹰'),(14,'16123002FB',189,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"tv9b7uza2xjel1wqnpefmylv0xzbswpv\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"16123002FB_1483101488\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"8EFBC9A33F5A24A481C5298C3194CB56\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230203817\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612304533160534\\\";}\"','2016-12-30 20:38:20',NULL,0.01,'人与文化（下）—刘鹰'),(15,'1612301222',192,11,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"1ebotzw4vwyizwmvwdw0k1y8fb9r44v8\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms-wXwz890vCiebZ__Ukz_g8\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612301222_1483104627\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"B390615F97C7175F51E948426B5E2E46\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230213033\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4003522001201612304540447406\\\";}\"','2016-12-30 21:30:41',NULL,0.01,'人与文化（下）—刘鹰'),(16,'1612307930',189,14,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"lnrsc3uud2e7wwo274entg2zxwmzfy6f\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612307930_1483106461\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"0B8DB6C35C1E23598772F093BF3BBC86\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230220109\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612304542608416\\\";}\"','2016-12-30 22:01:09',NULL,0.01,'四川百旺金赋科技有限公司税控盘（版）操作手册'),(17,'1612300CA2',191,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"f6s35n2k7flthe7oyjqlrub9413ls3g6\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms4kcnGLJq4k_muruEa5qA_M\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612300CA2_1483107190\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"646D7CE66E91529F821C8121FADF1127\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230221328\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4006392001201612304544830516\\\";}\"','2016-12-30 22:13:28',NULL,0.01,'人与文化（上）—刘鹰'),(18,'161230CDD9',189,13,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"87tubdkto80z500mibsl1rv9riovrui3\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"161230CDD9_1483107953\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"D5A19B1550FA29C1547C1290CC75A956\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230222601\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201612304543755139\\\";}\"','2016-12-30 22:26:03',NULL,0.01,'税控发票开票软件（金税盘版）V2.1用户手册'),(19,'161230519D',195,13,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"1g4fxfqbx82nnsicyz05wj6o59i0wdq1\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"161230519D_1483108092\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"7881BDC384DF051EFD33E487D38858A3\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230222822\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201612304545624743\\\";}\"','2016-12-30 22:28:23',NULL,0.01,'税控发票开票软件（金税盘版）V2.1用户手册'),(20,'1612301D60',192,14,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"cckdlln549e7m1w8d2bf4r9x3ugskxxn\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms-wXwz890vCiebZ__Ukz_g8\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1612301D60_1483109106\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"2A396642C1FE10C6065401433B2CD510\\\";s:8:\\\"time_end\\\";s:14:\\\"20161230224514\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4003522001201612304548451924\\\";}\"','2016-12-30 22:45:14',NULL,0.01,'四川百旺金赋科技有限公司税控盘（版）操作手册'),(21,'1701058F83',223,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"6af5ezspr9ntbadbyd1die2dgod4v5px\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms6i68m_7P2H9R1BKJ9mjySc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1701058F83_1483588699\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"59D811A0CAFCE73180EA52451A5FB1BE\\\";s:8:\\\"time_end\\\";s:14:\\\"20170105115832\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4008832001201701055255926919\\\";}\"','2017-01-05 11:58:33',NULL,0.01,'人与文化（上）—刘鹰'),(22,'170107DD64',227,11,0,NULL,NULL,NULL,0.01,'人与文化（下）—刘鹰'),(23,'170107C703',208,9,0,NULL,NULL,NULL,0.01,'人与文化（上）—刘鹰'),(24,'1701146F5D',187,14,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"dlqhgrsowgj163u44nseseexsbzgm53v\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms2pZkhkHvUIIQGoSIyJDdFk\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1701146F5D_1484373407\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"48BB64B5262B4F3A3140D5F673DBFDAC\\\";s:8:\\\"time_end\\\";s:14:\\\"20170114135656\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4008882001201701146284593427\\\";}\"','2017-01-14 13:56:56',NULL,0.01,'四川百旺金赋科技有限公司税控盘（版）操作手册'),(25,'170115A5B2',321,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"ybv8aplty5xvyr7znm0t08j38yo5pcp3\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGmsw3OYud6pd8kjcPQu9k1FIM\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170115A5B2_1484486870\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"A24C929D1FAB142DA120F2183CB32415\\\";s:8:\\\"time_end\\\";s:14:\\\"20170115212759\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4000832001201701156441973575\\\";}\"','2017-01-15 21:27:59',NULL,0.01,'人与文化（上）—刘鹰'),(26,'170120F514',189,20,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"hazn05p7sejts17egmpu04up0kanmwgp\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170120F514_1484893852\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"7D2E9DA609CEE7B49F90203B4441B4A0\\\";s:8:\\\"time_end\\\";s:14:\\\"20170120143101\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201701206957659313\\\";}\"','2017-01-20 14:31:02',NULL,0.01,'2015所得税申报表填制及政策变化'),(27,'170120839A',189,19,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"hewstfv8qlu4hjms5nkjkzth3s4646wr\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170120839A_1484893914\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"C18729DD12B2771938A15698813754A3\\\";s:8:\\\"time_end\\\";s:14:\\\"20170120143202\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201701206959741295\\\";}\"','2017-01-20 14:32:04',NULL,0.01,'所得税预缴表填制2014'),(28,'1701202F3C',189,18,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"l3nac5iftdp0rc8cmu7ndvmxqcr84isp\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1701202F3C_1484893975\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"94058C5E6D2CD2086946C1C3FD16EAD8\\\";s:8:\\\"time_end\\\";s:14:\\\"20170120143303\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201701206957734841\\\";}\"','2017-01-20 14:33:04',NULL,0.01,'新版小规模纳税人申报表填写'),(29,'170120B912',191,18,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"9hk49ni8emqdx4bti0o8il49iplqgvvr\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms4kcnGLJq4k_muruEa5qA_M\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170120B912_1484897392\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"837787340091634FD5CE28168FC4FD1C\\\";s:8:\\\"time_end\\\";s:14:\\\"20170120153004\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4006392001201701206965274459\\\";}\"','2017-01-20 15:30:05',NULL,0.01,'新版小规模纳税人申报表填写'),(30,'170204F19D',195,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"uvtfcdj3i7e99emj2bih99wwcn57hm25\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170204F19D_1488705180\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"D63FD02505BB67D24BD51BC8CD1A55E0\\\";s:8:\\\"time_end\\\";s:14:\\\"20170305171309\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201703052329850707\\\";}\"','2017-03-05 17:13:10',NULL,0.01,'人与文化（上）—刘鹰'),(31,'1702051600',191,19,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"08d13fj2xjwlr6cv46ofjzdngh492mdm\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms4kcnGLJq4k_muruEa5qA_M\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1702051600_1486285080\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"CAF14D7D24D05CD3507FCF94DEF08792\\\";s:8:\\\"time_end\\\";s:14:\\\"20170205165817\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4006392001201702058827104027\\\";}\"','2017-02-05 16:58:22',NULL,0.01,'所得税预缴表填制2014'),(32,'1702087F93',195,19,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"gv5y273738ki4n6lr6x9tjvr5rr3zqtz\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1702087F93_1486514859\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"CF23699DFB9930271CC6D46A8AFE23D5\\\";s:8:\\\"time_end\\\";s:14:\\\"20170208084753\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201702089140628358\\\";}\"','2017-02-08 08:47:53',NULL,0.01,'所得税预缴表填制2014'),(33,'17020858F4',390,22,0,NULL,NULL,NULL,0.01,'财税（2016）36号文件之营业税改征增值税试点实施办法'),(34,'1702158B52',195,55,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:9:\\\"BOC_DEBIT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"vb5qd78ow471mmv7zou0dbtcyl2k5mbm\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1702158B52_1487137811\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"3DBCCFF023E8366971300A99E6DF13DD\\\";s:8:\\\"time_end\\\";s:14:\\\"20170215135030\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201702150018222759\\\";}\"','2017-02-15 13:50:31',NULL,5.00,'金税三期上线涉税风险分析（下）'),(35,'170219ABAA',436,38,0,NULL,NULL,NULL,5.00,'2016财税新政—股东投资涉税问题分析（下）'),(36,'1702196D57',376,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:10:\\\"ABC_CREDIT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"r6ex7j67q9ebewi3wrhfpbau4gp57xaw\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms0vX9S4f0k6CdWtuGyDsiUA\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1702196D57_1487507126\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"4ABDBE5A92A66C5B13434D09A7045200\\\";s:8:\\\"time_end\\\";s:14:\\\"20170219202537\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4007122001201702190566236218\\\";}\"','2017-02-19 20:25:38',NULL,0.01,'人与文化（上）—刘鹰'),(37,'17022136A2',189,37,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"fbzslhcv6f90e0qz6jaezfujww5jty73\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"17022136A2_1487671841\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"179DFFD16F2F26347EF7AAB7A625F932\\\";s:8:\\\"time_end\\\";s:14:\\\"20170221181049\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201702210790105089\\\";}\"','2017-02-21 18:10:49',NULL,5.00,'2016财税新政股东投资涉税问题分析（上）'),(38,'1702257852',189,41,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴年结前风险自查(中）'),(39,'170225C57B',534,9,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"vysmpwufhzb321zlvo1e7go6rj924qiw\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms6nnAuAnZVJ6pBFM_kqjbR4\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170225C57B_1488031758\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"DC6AC089D288AF67C121F75E4764C7E5\\\";s:8:\\\"time_end\\\";s:14:\\\"20170225220928\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4009212001201702251325033563\\\";}\"','2017-02-25 22:09:28',NULL,0.01,'人与文化（上）—刘鹰'),(40,'170305CCC2',195,97,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:9:\\\"BOC_DEBIT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"j5m0ka22m6a3xpepdwgob7c30p459c1q\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170305CCC2_1488705857\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"25DFF1D8522AA8A1A640C022CDFA12D4\\\";s:8:\\\"time_end\\\";s:14:\\\"20170305172435\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201703052335237463\\\";}\"','2017-03-05 17:24:35',NULL,5.00,'2016财税新政'),(41,'1703054B43',534,10,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:1:\\\"1\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"e48tmdon0n4nd7iq7sd9q6t0o7vu5bda\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms6nnAuAnZVJ6pBFM_kqjbR4\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1703054B43_1488713357\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"B123037609F6E3CAE8A027DAEFF705E4\\\";s:8:\\\"time_end\\\";s:14:\\\"20170305192927\\\";s:9:\\\"total_fee\\\";s:1:\\\"1\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4009212001201703052350099772\\\";}\"','2017-03-05 19:29:28',NULL,0.01,'企业业绩评价体系（下）—王文君'),(42,'1703051546',195,102,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:9:\\\"BOC_DEBIT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"9lk785io7v9o4e9tr9rp7z59w2zlm5ox\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1703051546_1488720144\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"5B58BCBE7E692B70BF0F89E5C10290BA\\\";s:8:\\\"time_end\\\";s:14:\\\"20170305212234\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201703052365609157\\\";}\"','2017-03-05 21:22:34',NULL,5.00,'2016企业所得税汇算清缴'),(43,'170308D376',189,98,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴'),(44,'170313FF30',376,10,0,NULL,NULL,NULL,0.01,'企业业绩评价体系（下）—王文君'),(45,'170315BD24',189,109,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"x0qlr67ihnbcncjvb43z3mnp0gz5og2l\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170315BD24_1489534174\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"1C8F788C75413B9215A3FB1E8174276D\\\";s:8:\\\"time_end\\\";s:14:\\\"20170315072951\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201703153407492508\\\";}\"','2017-03-15 07:29:52',NULL,5.00,'2016企业所得税汇算清缴'),(46,'1703214AA4',189,119,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十一）'),(47,'1703211179',190,132,0,NULL,NULL,NULL,5.00,'唐心智：国际经贸热点问题探讨（七）'),(48,'170322F7DF',189,154,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"mg9l8qfezx780lcn4b7hhtxpiz6hjt9j\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170322F7DF_1490137665\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"24E5785A7EDA9AE1C19FE9CEB4C19DC0\\\";s:8:\\\"time_end\\\";s:14:\\\"20170322071645\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201703224203852086\\\";}\"','2017-03-22 07:07:52',NULL,5.00,'刘鹰：企业资源运作及融资规划（十四）'),(49,'1704261A73',693,119,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十一）'),(50,'170426A773',754,119,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十一）'),(51,'1704262125',764,119,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十一）'),(52,'1705030A57',764,252,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（八）'),(53,'1705101771',195,120,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:3:\\\"500\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"92n5yx7zibyfe54twb8korjhgjpdor8m\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1705101771_1494400511\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"D64171033523B8EB135DC51F545CD53A\\\";s:8:\\\"time_end\\\";s:14:\\\"20170510152603\\\";s:9:\\\"total_fee\\\";s:3:\\\"500\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201705100295043242\\\";}\"','2017-05-10 15:15:23',NULL,5.00,'2016企业所得税汇算清缴（十二）'),(54,'17051791C5',764,123,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十五）'),(55,'1705179886',764,124,0,NULL,NULL,NULL,5.00,'2016企业所得税汇算清缴（十六）'),(56,'170616CA03',1037,316,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:2:\\\"11\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"70j1qfz0vlb80oqfe2nwf33swqo7ssto\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms9sRfm7erKVwm3kMA8CP3AQ\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"170616CA03_1497575569\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"1F15338E2CA0BE1CCB6116C38D925D89\\\";s:8:\\\"time_end\\\";s:14:\\\"20170616091256\\\";s:9:\\\"total_fee\\\";s:2:\\\"11\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4001152001201706165933086722\\\";}\"','2017-06-16 09:12:58',NULL,0.11,'初创企业CEO财务必修课一'),(57,'1706229798',1063,316,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:2:\\\"11\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"6cfwvi8vscki9c3nt7gxolnw2r6c37zb\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms__WNnvZloP82GFj6AcaKwc\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1706229798_1498094195\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"DD9EAFC1FBE9DBCFCD82AC884528D3ED\\\";s:8:\\\"time_end\\\";s:14:\\\"20170622091641\\\";s:9:\\\"total_fee\\\";s:2:\\\"11\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4005552001201706226844227833\\\";}\"','2017-06-22 09:16:43',NULL,0.11,'初创企业CEO财务必修课一'),(58,'1706223057',818,316,1,'\"a:16:{s:5:\\\"appid\\\";s:18:\\\"wxd4b348526ef8250b\\\";s:9:\\\"bank_type\\\";s:3:\\\"CFT\\\";s:8:\\\"cash_fee\\\";s:2:\\\"11\\\";s:8:\\\"fee_type\\\";s:3:\\\"CNY\\\";s:12:\\\"is_subscribe\\\";s:1:\\\"Y\\\";s:6:\\\"mch_id\\\";s:10:\\\"1335852501\\\";s:9:\\\"nonce_str\\\";s:32:\\\"255t1260ttcgofsb42n9sdee3bdhtwf2\\\";s:6:\\\"openid\\\";s:28:\\\"oxmGms6ivKt3_sx_rvOxrMu0JnZg\\\";s:12:\\\"out_trade_no\\\";s:21:\\\"1706223057_1498094643\\\";s:11:\\\"result_code\\\";s:7:\\\"SUCCESS\\\";s:11:\\\"return_code\\\";s:7:\\\"SUCCESS\\\";s:4:\\\"sign\\\";s:32:\\\"C621A2EFD92B944CB0C16B2FCFC8D7D7\\\";s:8:\\\"time_end\\\";s:14:\\\"20170622092407\\\";s:9:\\\"total_fee\\\";s:2:\\\"11\\\";s:10:\\\"trade_type\\\";s:5:\\\"JSAPI\\\";s:14:\\\"transaction_id\\\";s:28:\\\"4007282001201706226850393965\\\";}\"','2017-06-22 09:24:09',NULL,0.11,'初创企业CEO财务必修课一'),(59,'1707070108',801,316,0,NULL,NULL,NULL,0.11,'初创企业CEO财务必修课一'),(60,'1708258355',764,316,0,NULL,NULL,NULL,0.11,'初创企业CEO财务必修课一');
/*!40000 ALTER TABLE `my_class_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_config`
--

DROP TABLE IF EXISTS `my_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置项';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_config`
--

LOCK TABLES `my_config` WRITE;
/*!40000 ALTER TABLE `my_config` DISABLE KEYS */;
INSERT INTO `my_config` VALUES (1,'WEB_SITE_TITLE',1,'网站标题',1,'','网站标题前台显示标题',1,'贷款服务平台',1),(2,'WEB_SITE_DESCRIPTION',2,'网站描述',1,'','网站搜索引擎描述',1,'贷款服务平台',2),(3,'WEB_SITE_KEYWORD',2,'网站关键字',1,'','网站搜索引擎关键字',1,'贷款服务平台',3),(4,'WEB_SITE_CLOSE',4,'关闭站点',1,'0:关闭,1:开启','站点关闭后其他用户不能访问，管理员可以正常访问',1,'1',4),(5,'WEB_SITE_ICP',1,'备案信息',1,'','备案信息',1,'蜀ICP备1000000号',5),(6,'DATA_BACKUP_PATH',1,'数据库备份根路径',2,'','路径必须以 / 结尾',1,'./Public/data/',6),(7,'DATA_BACKUP_PART_SIZE',0,'数据库备份卷大小',2,'','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M',1,'20971520',7),(8,'DATA_BACKUP_COMPRESS',4,'数据库备份文件是否启用压缩',2,'0:不压缩\r\n1:启用压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数',1,'1',8),(9,'DATA_BACKUP_COMPRESS_LEVEL',4,'数据库备份文件压缩级别',2,'1:普通\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效',1,'9',9),(10,'ALLOW_VISIT',3,'不受限控制器方法',0,'','不受限控制器方法',1,'5',10),(11,'DENY_VISIT',3,'超管专限控制器方法',0,'44','仅超级管理员可访问的控制器方法',1,'6',11),(12,'CONFIG_TYPE_LIST',3,'配置类型列表',0,'','主要用于数据解析和页面表单的生成',1,'0:数字\n1:字符\n2:文本\n3:数组\n4:枚举\n5:分割',12),(13,'CONFIG_GROUP_LIST',3,'配置分组',0,'1:基本\n2:系统\n3:接口','配置分组',1,'1:基本\n2:系统\n3:接口',13),(14,'CONFIG_STATUS_LIST',3,'订单状态',0,'0:订单提交\n1:系统确认\n2:已发货\n3:已完成\n4:已取消','状态分组',1,'0:订单提交\n1:系统确认\n2:已发货\n3:已完成\n4:已取消',14),(15,'CONFIG_PAYMETHOD_LIST',3,'支付方式配置',0,'1:微信支付\r\n4:货到付款','支付方式配置',1,'1:微信支付\n4:货到付款',15),(16,'CONFIG_BOOKTIME_LIST',3,'可预定时段分组',0,'','可预定时段分组',1,'',16),(17,'POINT_RATE',0,'积分比例',4,'','如1，表示：消费1元积1分',0,'1',17),(19,'WEB_SITE_COPYRIGHT',1,'版权信息',1,'','版权信息',1,'贷款服务平台',19),(20,'WEB_SITE_TEMPLATE',4,'模板设置',1,'default:默认','选择前台显示模板',1,'default',4),(21,'SMTP_SERVER',1,'STMP服务器',3,'','发送邮件的SMTP服务器地址',0,'smtp.163.com',21),(22,'SMTP_PORT',0,'SMTP端口',3,'','SMTP服务器的端口',0,'25',22),(23,'SMTP_EMAIL',1,'发件人名称',3,'','一般是email地址或公司名称',0,'',23),(24,'SMTP_USERNAME',1,'邮箱账号',3,'','同上',0,'',24),(25,'SMTP_USERPWD',1,'邮箱密码',3,'','发件箱密码',0,'',25),(26,'SMTP_LINE',5,'邮件接口设置',3,'','短信接口设置',1,'',20),(27,'SMS_LINE',5,'短信接口设置',3,'','短信接口设置',1,'',27),(28,'SMS_API_URL',1,'短信接口API',3,'','短信接口网关地址',1,'https://sdk2.028lk.com/sdk2/BatchSend2.aspx',28),(29,'SMS_USERNAME',1,'用户名',3,'','短信接口用户名',1,'cdjs001820',29),(30,'SMS_USERPWD',1,'密码',3,'','短信接口密码',1,'112233@',30),(31,'WEB_SITE_EMAIL',4,'邮件系统',2,'0:禁用\r\n1:启用','启用后才能发送邮件，需要配置邮件接口',0,'1',36),(32,'WEB_SITE_SMS',4,'短信系统',2,'0:禁用\r\n1:启用','启用后才能发送手机短信，需要配置短信接口',1,'1',37),(33,'WEB_SITE_WECHAT',4,'微信接口',2,'0:禁用\r\n1:启用','启用后才能接管微信平台，需要配置微信接口',0,'1',38),(37,'VOTE_DAY',0,'投票总数',2,'','一个用户每天投票总数',0,'3',37),(38,'VOTE_PLAYER',0,'对选手投票总数',2,'','用户每天对同一个选手的投票总数',0,'1',38),(39,'ALIPAY',4,'启用支付宝支付',2,'0:禁用\n1:启用','1：启用，0：禁用【支付宝支付需要在程序中设置账号密码】',0,'0',39),(40,'ACCOUNTPAY',4,'启用消费积分支付',2,'0:禁用\n1:启用','0：禁用，1：启用',0,'1',40),(44,'FIRST_FIRST_REFEE',0,'首次购买上一级返利',4,'','首次购买上一级返利【元】',0,'150',44),(45,'FIRST_SECOND_REFEE',0,'首次购买向上第二级返利',4,'','首次购买向上第二级返利【元】',0,'30',45),(46,'FIRST_THIRD_REFEE',0,'首次购买向上第三级返利',4,'','首次购买向上第三级返利【元】',0,'10',46),(47,'OTHER_FIRST_REFEE',0,'非首次购买向上一级返利',4,'','非首次购买向上一级返利百分比【%】',0,'1',47),(48,'OTHER_SECOND_REFEE',0,'非首次购买向上第二级返利',4,'','非首次购买向上第二级返利百分比【%】',0,'0.5',48),(49,'OTHER_THIRD_REFEE',0,'非首次购买向上第三级返利',4,'','非首次购买向上第三级返利百分比【%】',0,'0.3',49),(50,'SIGNUOP_SCOPE',0,'签到范围【米】',1,'','签到范围【米】',0,'1000',50),(51,'WEB_TPL_CODE',1,'短信模板',3,'','短信模板',1,'您的验证码：{$code}，如非本人操作，请忽略此短信。【U易钱包】',51),(52,'PRAISE_POINT',0,'点赞赠送积分',2,'','点赞文章赠送积分',0,'2',52),(53,'UCHANGPAY_APIURL',1,'U畅支付接口地址',3,'','U畅支付接口地址【需要最后一条斜线‘/’】',0,'http://api.cmbxm.mbcloud.com/',53),(54,'UCHANGPAY_MCHID',1,'U畅支付接口商户号',3,'','U畅支付接口商户号',0,'100006347881',54),(55,'UCHANGPAY_PASS',1,'优畅支付商户秘钥',3,'','优畅支付商户秘钥',0,'0E4AB999BFD8A05A0C50E0976B389F23',55),(56,'SHARE_POINT',1,'分享赠送积分',2,'','分享文章赠送积分',0,'3',56),(57,'NO_FOLLOW_DAYS',0,'超过此天数未跟进的客户自动进入公海 ',2,'','超过此天数未跟进的客户自动进入公海 ',0,'60',57),(58,'NO_ORDER_DAYS',0,'未下单客户进入公海',2,'','未下单客户进入公海天数',0,'60',58),(59,'QUESTION_NUM',0,'题库随机考试数量',2,'','题库随机考试数量',0,'20',59),(60,'QUESTION_TIME',0,'答题时间',2,'','答题时间【分钟】',0,'30',60),(61,'CONTRACT_TIMEOUT',0,'合同到期提醒时间',3,'','合同到期提醒时间，天。小于配置天数的合同将会提醒',0,'330',61),(62,'ALTER_ADDRESS',4,'系统定位地址修改',2,'0:不能修改\n1:可以修改','crm系统跟进记录，工作计划定位地址修改',0,'1',62),(63,'LOAN_DAOQI',0,'贷款到期预警天数',2,'','贷款到期小于X天，则进行预警',1,'6',63),(64,'DELAY_CONFIG',2,'延期配置',1,'','延期配置',1,'{\n\"1000\":\"300\",\n\"1500\":\"450\",\n\"2000\":\"600\",\n\"2990\":\"890\",\n\"3990\":\"990\"\n}',1);
/*!40000 ALTER TABLE `my_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_active`
--

DROP TABLE IF EXISTS `my_content_active`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_active` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT '',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT '',
  `content` longtext,
  `source` varchar(250) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `linkurl` varchar(250) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT '0' COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `isresume` tinyint(3) DEFAULT '0',
  `sortpath` varchar(250) DEFAULT NULL,
  `images` varchar(1024) DEFAULT NULL,
  `parentname` varchar(255) DEFAULT NULL,
  `start` date DEFAULT NULL COMMENT '开始时间',
  `end` date DEFAULT NULL COMMENT '结束时间',
  `explain` longtext COMMENT '规则说明',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '活动报名经费',
  `sold` int(11) DEFAULT '0' COMMENT '报名数',
  `hits` int(11) DEFAULT '0' COMMENT '浏览数',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `longitude` varchar(255) DEFAULT NULL COMMENT '经度',
  `latitude` varchar(255) DEFAULT NULL COMMENT '纬度',
  `first_refee` decimal(10,2) DEFAULT '0.00',
  `second_refee` decimal(10,2) DEFAULT '0.00',
  `third_refee` decimal(10,2) DEFAULT '0.00',
  `type` mediumint(9) DEFAULT '1' COMMENT '1-报名收费活动,2-亲子投票活动',
  `activetime` date DEFAULT NULL COMMENT '活动开始时间',
  `limit` int(11) DEFAULT '0' COMMENT '报名人数限制,0为不限制',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票活动';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_active`
--

LOCK TABLES `my_content_active` WRITE;
/*!40000 ALTER TABLE `my_content_active` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_active` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_active_member`
--

DROP TABLE IF EXISTS `my_content_active_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_active_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activeid` int(11) NOT NULL DEFAULT '0' COMMENT '参加的活动id',
  `activename` varchar(255) DEFAULT NULL,
  `no` varchar(255) DEFAULT '0' COMMENT '支付订单编号',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `telephone` varchar(255) DEFAULT NULL,
  `memberid` int(11) DEFAULT NULL COMMENT '选手id',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `headimg` varchar(255) DEFAULT NULL COMMENT '头像',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0:未支付，1：已支付',
  `addtime` datetime DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '支付金额',
  `payinfo` text COMMENT '支付信息',
  `paytime` datetime DEFAULT NULL COMMENT '支付时间',
  `other` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动参加人员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_active_member`
--

LOCK TABLES `my_content_active_member` WRITE;
/*!40000 ALTER TABLE `my_content_active_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_active_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_active_memberp`
--

DROP TABLE IF EXISTS `my_content_active_memberp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_active_memberp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activeid` int(11) NOT NULL DEFAULT '0' COMMENT '参加的活动id',
  `no` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `headimg` varchar(255) DEFAULT NULL COMMENT '头像',
  `photo` longtext COMMENT '照片',
  `tickets` int(11) NOT NULL DEFAULT '0' COMMENT '票数',
  `content` longtext COMMENT '详细介绍',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0:待审核，1：已通过，2：不通过',
  `qq` varchar(255) DEFAULT NULL COMMENT 'qq',
  `mobile` varchar(255) DEFAULT NULL COMMENT '手机',
  `fav` varchar(255) DEFAULT NULL COMMENT '爱好',
  `memberid` int(11) DEFAULT NULL COMMENT '选手id',
  `addtime` datetime DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `orderno` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='亲子活动报名表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_active_memberp`
--

LOCK TABLES `my_content_active_memberp` WRITE;
/*!40000 ALTER TABLE `my_content_active_memberp` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_active_memberp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_active_vote`
--

DROP TABLE IF EXISTS `my_content_active_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_active_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` int(11) DEFAULT NULL COMMENT '选手id',
  `voteid` int(11) DEFAULT NULL COMMENT '投票id',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '投票次数',
  `votetime` date DEFAULT NULL COMMENT '投票时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票备注表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_active_vote`
--

LOCK TABLES `my_content_active_vote` WRITE;
/*!40000 ALTER TABLE `my_content_active_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_active_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_classes`
--

DROP TABLE IF EXISTS `my_content_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `content` longtext,
  `source` varchar(250) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL COMMENT '0',
  `linkurl` varchar(250) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `isresume` tinyint(3) DEFAULT '0',
  `sortpath` varchar(250) DEFAULT NULL,
  `images` varchar(1024) DEFAULT NULL,
  `parentname` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `price1` decimal(10,2) DEFAULT '0.00' COMMENT '市场价',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `sold` int(11) DEFAULT '0' COMMENT '销量',
  `unit` varchar(250) DEFAULT NULL COMMENT '单位',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '产品编号',
  `point` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1-资料，2-视频',
  `companyid` int(11) DEFAULT '0' COMMENT '单位id',
  `teacherid` int(11) DEFAULT '0' COMMENT '老师id',
  `attachment` varchar(255) DEFAULT NULL COMMENT '附件url',
  `isdownload` int(11) DEFAULT '0' COMMENT '是否下载',
  `attachdetail` text COMMENT '附件详细',
  `questime` int(11) DEFAULT '0' COMMENT '问卷时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_classes`
--

LOCK TABLES `my_content_classes` WRITE;
/*!40000 ALTER TABLE `my_content_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_express`
--

DROP TABLE IF EXISTS `my_content_express`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `indexpic` varchar(250) DEFAULT NULL COMMENT '形象图',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `siteurl` varchar(255) DEFAULT NULL COMMENT '查询网址',
  `content` varchar(255) DEFAULT '' COMMENT '公司备注',
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(3) DEFAULT NULL COMMENT '状态：0-待审核，1-已审核，2-审核未通过',
  `isresume` tinyint(3) DEFAULT '0',
  `description` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='物流快递表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_express`
--

LOCK TABLES `my_content_express` WRITE;
/*!40000 ALTER TABLE `my_content_express` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_express` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_info`
--

DROP TABLE IF EXISTS `my_content_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT '',
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT '',
  `content` longtext,
  `source` varchar(250) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL COMMENT '0',
  `linkurl` varchar(250) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT '0' COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `isresume` tinyint(3) DEFAULT '0',
  `sortpath` varchar(250) DEFAULT NULL,
  `images` varchar(1024) DEFAULT NULL,
  `parentname` varchar(255) DEFAULT NULL,
  `star1` int(11) DEFAULT '0' COMMENT '体验指数',
  `star2` int(11) DEFAULT '0' COMMENT '刺激指数',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='单页信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_info`
--

LOCK TABLES `my_content_info` WRITE;
/*!40000 ALTER TABLE `my_content_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_news`
--

DROP TABLE IF EXISTS `my_content_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `content` longtext,
  `source` varchar(250) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `linkurl` varchar(250) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `isresume` tinyint(3) DEFAULT '0',
  `sortpath` varchar(250) DEFAULT NULL,
  `images` varchar(1024) DEFAULT NULL,
  `parentname` varchar(255) DEFAULT NULL,
  `hits` int(11) DEFAULT '0' COMMENT '浏览数',
  `shares` int(11) DEFAULT '0' COMMENT '分享数',
  `praises` int(11) DEFAULT '0' COMMENT '点赞数',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='基础内容表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_news`
--

LOCK TABLES `my_content_news` WRITE;
/*!40000 ALTER TABLE `my_content_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_product`
--

DROP TABLE IF EXISTS `my_content_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `content` longtext,
  `source` varchar(250) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL COMMENT '0',
  `linkurl` varchar(250) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `isresume` tinyint(3) DEFAULT '0',
  `sortpath` varchar(250) DEFAULT NULL,
  `images` varchar(1024) DEFAULT NULL,
  `parentname` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `price1` decimal(10,2) DEFAULT '0.00' COMMENT '市场价',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `sold` int(11) DEFAULT '0' COMMENT '销量',
  `unit` varchar(250) DEFAULT NULL COMMENT '单位',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '产品编号',
  `weight` int(11) DEFAULT '0' COMMENT '重量（克）',
  `type` varchar(255) NOT NULL DEFAULT '1' COMMENT '1:商品；2：积分商品',
  `attr` longtext,
  `point` int(11) DEFAULT NULL,
  `first_refee` decimal(10,2) DEFAULT '0.00' COMMENT '第一级返利',
  `second_refee` decimal(10,2) DEFAULT '0.00' COMMENT '第二级返利',
  `third_refee` decimal(10,2) DEFAULT '0.00' COMMENT '第三级返利',
  `is_shipfee` int(11) DEFAULT '0' COMMENT '0:不包邮，1：包邮',
  `onlycredit` int(11) DEFAULT '0' COMMENT '是否仅限积分支付',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='产品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_product`
--

LOCK TABLES `my_content_product` WRITE;
/*!40000 ALTER TABLE `my_content_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_signup`
--

DROP TABLE IF EXISTS `my_content_signup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_signup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `indexpic` varchar(250) DEFAULT NULL,
  `keywords` varchar(250) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `content` longtext,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `long` varchar(255) DEFAULT NULL COMMENT '经度',
  `lat` varchar(255) DEFAULT NULL COMMENT '纬度',
  `address` varchar(255) DEFAULT NULL COMMENT '上课地点',
  `ewm` varchar(255) DEFAULT NULL COMMENT '签到二维码图片地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_signup`
--

LOCK TABLES `my_content_signup` WRITE;
/*!40000 ALTER TABLE `my_content_signup` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_signup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_signup_record`
--

DROP TABLE IF EXISTS `my_content_signup_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_signup_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signupid` int(11) DEFAULT NULL COMMENT '签到课程id',
  `memberid` int(11) DEFAULT NULL COMMENT '会员id',
  `addtime` datetime DEFAULT NULL COMMENT '签到时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_signup_record`
--

LOCK TABLES `my_content_signup_record` WRITE;
/*!40000 ALTER TABLE `my_content_signup_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_signup_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_content_turntable`
--

DROP TABLE IF EXISTS `my_content_turntable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_content_turntable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `indexpic` varchar(255) DEFAULT NULL,
  `chance` int(11) DEFAULT '0' COMMENT '概率',
  `remark` varchar(255) DEFAULT NULL,
  `num` int(11) DEFAULT '0' COMMENT '奖品数量',
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='大转盘奖品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_content_turntable`
--

LOCK TABLES `my_content_turntable` WRITE;
/*!40000 ALTER TABLE `my_content_turntable` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_content_turntable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_contract`
--

DROP TABLE IF EXISTS `my_contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customid` int(11) DEFAULT '0' COMMENT '客户名称',
  `masterid` int(11) DEFAULT '0' COMMENT '签约员工',
  `mastername` varchar(255) DEFAULT NULL COMMENT '签约员工姓名',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '添加日期',
  `no` varchar(255) DEFAULT NULL COMMENT '合同编号',
  `name` varchar(255) DEFAULT NULL COMMENT '合同名称',
  `signtime` date DEFAULT NULL COMMENT '签约日期',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '合同金额',
  `getamount` decimal(10,2) DEFAULT '0.00' COMMENT '已收款',
  `start` date DEFAULT NULL COMMENT '开始服务时间',
  `end` date DEFAULT NULL COMMENT '服务结束时间',
  `status` int(11) DEFAULT '0' COMMENT '0-正常，1-服务到期，2-撤回',
  `remark` varchar(255) DEFAULT NULL,
  `contractimages` text COMMENT '合同附件照片',
  `type` int(11) DEFAULT '0' COMMENT '1-销售类，2-服务类',
  `notice` int(11) DEFAULT '0' COMMENT '1-已提醒',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='合同表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_contract`
--

LOCK TABLES `my_contract` WRITE;
/*!40000 ALTER TABLE `my_contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_coupon`
--

DROP TABLE IF EXISTS `my_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `no` varchar(255) DEFAULT NULL COMMENT '优惠券号',
  `title` varchar(255) DEFAULT NULL COMMENT '优惠券名称',
  `cost` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '优惠金额',
  `pid` int(11) DEFAULT '0' COMMENT '优惠券类型',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '0' COMMENT '消费：0-未消费，1-已消费',
  `usetime` timestamp NULL DEFAULT NULL COMMENT '消费时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '消费备注（订单号）',
  `timefrom` timestamp NULL DEFAULT NULL COMMENT '开始日期',
  `timeto` timestamp NULL DEFAULT NULL COMMENT '结束日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_coupon`
--

LOCK TABLES `my_coupon` WRITE;
/*!40000 ALTER TABLE `my_coupon` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_delay`
--

DROP TABLE IF EXISTS `my_delay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_delay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addtime` datetime DEFAULT NULL,
  `orderno` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `money` decimal(9,2) DEFAULT NULL,
  `refundtime` datetime DEFAULT NULL,
  `refundinfo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `dealno` varchar(255) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_delay`
--

LOCK TABLES `my_delay` WRITE;
/*!40000 ALTER TABLE `my_delay` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_delay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_department`
--

DROP TABLE IF EXISTS `my_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '部门名',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='部门表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_department`
--

LOCK TABLES `my_department` WRITE;
/*!40000 ALTER TABLE `my_department` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_feedback`
--

DROP TABLE IF EXISTS `my_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `sort` int(11) DEFAULT '0' COMMENT '0',
  `status` tinyint(3) DEFAULT '0' COMMENT '是否录取，0=>否；1=>是',
  `username` varchar(255) DEFAULT NULL COMMENT '姓名',
  `telephone` varchar(255) DEFAULT NULL COMMENT '电话',
  `email` varchar(255) DEFAULT NULL COMMENT '邮件',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `content` text COMMENT '评论内容',
  `idcard` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL COMMENT '专业',
  `qq` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `replay` text,
  `isresume` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT NULL,
  `replaytime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='反馈表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_feedback`
--

LOCK TABLES `my_feedback` WRITE;
/*!40000 ALTER TABLE `my_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_file`
--

DROP TABLE IF EXISTS `my_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  `fullpath` varchar(255) DEFAULT '' COMMENT '全路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微信-上传记录表*';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_file`
--

LOCK TABLES `my_file` WRITE;
/*!40000 ALTER TABLE `my_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_item`
--

DROP TABLE IF EXISTS `my_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `masterid` int(11) DEFAULT '0' COMMENT '创建人id',
  `member` text COMMENT '成员',
  `customid` int(11) DEFAULT '0' COMMENT '客户id',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` int(11) DEFAULT '0' COMMENT '0-跟进中，1-已签约，2-已放弃，3-服务中，4-服务结束',
  `name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '项目描述',
  `mastername` varchar(255) DEFAULT NULL COMMENT '创建人姓名',
  `contractid` varchar(255) DEFAULT NULL COMMENT '合同id',
  `updatetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='项目表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_item`
--

LOCK TABLES `my_item` WRITE;
/*!40000 ALTER TABLE `my_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_label`
--

DROP TABLE IF EXISTS `my_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `info` text,
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(4) DEFAULT NULL COMMENT '1',
  `pid` int(11) DEFAULT NULL COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_label`
--

LOCK TABLES `my_label` WRITE;
/*!40000 ALTER TABLE `my_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan`
--

DROP TABLE IF EXISTS `my_loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '贷款订单号',
  `memberid` int(11) DEFAULT '0' COMMENT '会员id',
  `idcard` varchar(255) DEFAULT NULL COMMENT '身份证号',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `productid` int(11) DEFAULT '0' COMMENT '贷款产品id',
  `productinfo` text COMMENT '申请时产品信息',
  `damount` decimal(10,2) DEFAULT '0.00' COMMENT '贷款金额',
  `interest` decimal(10,2) DEFAULT '0.00' COMMENT '利息，金额',
  `interestrate` decimal(10,2) DEFAULT '0.00' COMMENT '利率，百分比',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '应还金额，本金+利息+逾期费',
  `handcharge` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `days` int(11) DEFAULT '0' COMMENT '贷款天数',
  `deadline` timestamp NULL DEFAULT NULL COMMENT '贷款还款期限',
  `status` int(11) DEFAULT '0' COMMENT '0-待审核，1-已审核，2-已放款，3-已逾期，4-已还款',
  `overduefee` decimal(10,2) DEFAULT '0.00' COMMENT '逾期费',
  `refundtime` timestamp NULL DEFAULT NULL COMMENT '还款时间',
  `refundinfo` text COMMENT '还款信息',
  `refundamount` decimal(10,2) DEFAULT '0.00' COMMENT '还款总额',
  `step` text COMMENT '贷款过程记录',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `paiedtime` timestamp NULL DEFAULT NULL COMMENT '放款时间',
  `shenhestatus` int(11) DEFAULT '0' COMMENT '0-未通过，1-已通过',
  `refusereason` text COMMENT '拒绝理由',
  `no` varchar(255) DEFAULT NULL COMMENT '优惠劵号',
  `discount` decimal(10,2) DEFAULT '0.00',
  `url` varchar(255) DEFAULT NULL COMMENT '合同url',
  `status1` int(1) unsigned DEFAULT '0' COMMENT '客户确认',
  `daozhang` decimal(10,2) DEFAULT NULL COMMENT '到账金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='贷款信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan`
--

LOCK TABLES `my_loan` WRITE;
/*!40000 ALTER TABLE `my_loan` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan_interest`
--

DROP TABLE IF EXISTS `my_loan_interest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan_interest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单号',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '利息金额',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `memberid` int(11) DEFAULT '0',
  `interestrate` decimal(10,2) DEFAULT '0.00' COMMENT '利率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='利息记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan_interest`
--

LOCK TABLES `my_loan_interest` WRITE;
/*!40000 ALTER TABLE `my_loan_interest` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan_interest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan_notice`
--

DROP TABLE IF EXISTS `my_loan_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '逾期订单号',
  `memberid` int(11) DEFAULT '0' COMMENT '逾期会员id',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提醒时间',
  `act` text COMMENT '操作',
  `type` int(11) DEFAULT '0' COMMENT '1-提醒',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='逾期提醒记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan_notice`
--

LOCK TABLES `my_loan_notice` WRITE;
/*!40000 ALTER TABLE `my_loan_notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan_overdue`
--

DROP TABLE IF EXISTS `my_loan_overdue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan_overdue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL,
  `memberid` int(11) DEFAULT '0',
  `amount` decimal(10,2) DEFAULT '0.00',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `days` int(11) DEFAULT '0' COMMENT '逾期天数',
  `overduefee` decimal(10,2) DEFAULT '0.00' COMMENT '逾期费率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='逾期记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan_overdue`
--

LOCK TABLES `my_loan_overdue` WRITE;
/*!40000 ALTER TABLE `my_loan_overdue` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan_overdue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan_product`
--

DROP TABLE IF EXISTS `my_loan_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '名称',
  `indexpic` varchar(255) DEFAULT NULL COMMENT '形象图',
  `interest` decimal(10,2) DEFAULT '0.00' COMMENT '贷款利率',
  `days` text COMMENT '贷款期限，‘，’分割的天数',
  `limitstart` decimal(10,2) DEFAULT '0.00' COMMENT '额度起始值',
  `limitend` decimal(10,2) DEFAULT '0.00' COMMENT '额度最大值',
  `remark` text COMMENT '产品描述',
  `status` int(11) DEFAULT '0',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `handcharge` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `overdue` decimal(10,2) DEFAULT '0.00' COMMENT '逾期费，本金的百分比',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='贷款产品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan_product`
--

LOCK TABLES `my_loan_product` WRITE;
/*!40000 ALTER TABLE `my_loan_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_loan_record`
--

DROP TABLE IF EXISTS `my_loan_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_loan_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL,
  `memberid` int(11) DEFAULT '0',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `type` int(11) DEFAULT '1' COMMENT '1-放款，2-收款',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收放款记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_loan_record`
--

LOCK TABLES `my_loan_record` WRITE;
/*!40000 ALTER TABLE `my_loan_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_loan_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_lottery_activity`
--

DROP TABLE IF EXISTS `my_lottery_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_lottery_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '活动名称',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `timefrom` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `timeto` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `times` int(11) DEFAULT '0' COMMENT '允许中奖次数',
  `issubscribe` tinyint(3) DEFAULT '0' COMMENT '是否关注才能参与',
  `iseveryday` tinyint(3) DEFAULT '0' COMMENT '是否允许每天抽奖',
  `remark` varchar(255) DEFAULT NULL COMMENT '活动描述',
  `content` text COMMENT '兑奖说明',
  `pwd` varchar(255) DEFAULT NULL COMMENT '兑奖密码',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享获得次数',
  `sharelogo` varchar(255) DEFAULT NULL COMMENT '分享显示的图片',
  `sharetitle` varchar(255) DEFAULT NULL COMMENT '分享显示的标题',
  `shareintro` varchar(255) DEFAULT NULL COMMENT '分享显示的描述',
  `shareurl` varchar(255) DEFAULT NULL COMMENT '分享URL，不填写则为当前页面',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '0-未开启，1-开启',
  `isdefault` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='刮刮卡活动列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_lottery_activity`
--

LOCK TABLES `my_lottery_activity` WRITE;
/*!40000 ALTER TABLE `my_lottery_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_lottery_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_lottery_history`
--

DROP TABLE IF EXISTS `my_lottery_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_lottery_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `num` int(11) DEFAULT '0' COMMENT '抽奖次数',
  `date` date DEFAULT NULL COMMENT '抽奖日期',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='刮刮卡参与记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_lottery_history`
--

LOCK TABLES `my_lottery_history` WRITE;
/*!40000 ALTER TABLE `my_lottery_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_lottery_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_lottery_prize`
--

DROP TABLE IF EXISTS `my_lottery_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_lottery_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `showtitle` varchar(255) DEFAULT NULL COMMENT '奖项名称',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `type` tinyint(3) DEFAULT '1' COMMENT '奖品类型：0-实物，1-红包，2-余额，3-积分',
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `probability` decimal(10,2) DEFAULT '0.00' COMMENT '中奖概率',
  `content` text COMMENT '兑奖说明',
  `num` int(11) DEFAULT '0' COMMENT '奖品数量',
  `getnum` int(11) DEFAULT '0' COMMENT '奖品已抽取数量',
  `shareurl` varchar(255) DEFAULT NULL COMMENT '分享URL，不填写则为当前页面',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '0-未开启，1-开启',
  `sort` int(11) DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='刮刮卡奖品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_lottery_prize`
--

LOCK TABLES `my_lottery_prize` WRITE;
/*!40000 ALTER TABLE `my_lottery_prize` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_lottery_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_lottery_record`
--

DROP TABLE IF EXISTS `my_lottery_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_lottery_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '兑换码',
  `prize` varchar(1024) NOT NULL DEFAULT '' COMMENT '奖品信息',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '领取状态：0-未领取，1-已领取，2-放弃',
  `type` int(11) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `gettime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='刮刮卡中奖纪录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_lottery_record`
--

LOCK TABLES `my_lottery_record` WRITE;
/*!40000 ALTER TABLE `my_lottery_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_lottery_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_lottery_share`
--

DROP TABLE IF EXISTS `my_lottery_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_lottery_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `actid` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='刮刮卡分享表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_lottery_share`
--

LOCK TABLES `my_lottery_share` WRITE;
/*!40000 ALTER TABLE `my_lottery_share` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_lottery_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member`
--

DROP TABLE IF EXISTS `my_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cominfo` int(11) DEFAULT '0' COMMENT '1-已完善资料',
  `contacts` int(11) DEFAULT '0' COMMENT '1-联系人信息完成',
  `tmobile` int(11) DEFAULT '0' COMMENT '1-已完善运营商信息',
  `zmf` int(11) DEFAULT NULL COMMENT '芝麻分',
  `qqsync` int(11) DEFAULT '0' COMMENT '1-qq同步信息已完成',
  `taobao` int(11) DEFAULT '0' COMMENT '淘宝认证',
  `bank` int(11) DEFAULT '0' COMMENT '银行信息是否填写',
  `zfb` int(11) DEFAULT '0' COMMENT '支付宝是否认证',
  `gz` int(11) DEFAULT '0' COMMENT '京东信息是否完成',
  `applystatus` int(11) DEFAULT '0' COMMENT '0-未申请，1-申请中，2-私有客户',
  `openid` varchar(255) DEFAULT '',
  `username` varchar(255) DEFAULT NULL COMMENT '用户填写的真实姓名',
  `userpwd` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `userreal` varchar(255) DEFAULT NULL COMMENT '用户填写的真实姓名',
  `idcard` varchar(255) DEFAULT NULL,
  `idcardimg1` varchar(255) DEFAULT NULL COMMENT '身份证正面',
  `idcardimg2` varchar(255) DEFAULT NULL COMMENT '身份证反面',
  `idcardimg3` varchar(255) DEFAULT NULL COMMENT '手持照片',
  `telephone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `contactinfo` text COMMENT '联系人信息',
  `tmobileinfo` text,
  `taobaoinfo` text COMMENT 'taobaoinfo',
  `zfbinfo` text COMMENT '支付宝同步信息',
  `qqsyncinfo` text COMMENT 'qq同步信息',
  `bankinfo` text COMMENT '银行信息',
  `jdinfo` text COMMENT '京东信息',
  `qq` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL COMMENT '生日',
  `provinceid` int(11) DEFAULT '0',
  `cityid` int(11) DEFAULT '0',
  `districtid` int(11) DEFAULT '0',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `info` varchar(255) DEFAULT NULL COMMENT '自我简介',
  `subscribe` tinyint(3) DEFAULT '0',
  `nickname` varchar(255) DEFAULT NULL,
  `sex` tinyint(3) DEFAULT '0',
  `language` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT '0',
  `unionid` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '备注名',
  `status` tinyint(3) DEFAULT '1' COMMENT '状态，启用禁用',
  `credit` decimal(10,2) DEFAULT '0.00' COMMENT '消费积分（在线充值，消费返积分，可以消费，账户无变动36个月之后可以提现）',
  `balance` decimal(10,2) DEFAULT '0.00' COMMENT '佣金积分（下级消费返利，可以提现，可以充值成为消费积分）',
  `mobile` varchar(255) DEFAULT NULL COMMENT '手机号',
  `ismobilevalid` tinyint(3) DEFAULT '0' COMMENT '手机号是否验证：0-未验证，1-已验证',
  `paypwd` varchar(255) DEFAULT '' COMMENT '支付密码',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后交互时间',
  `unsubscribetime` int(11) DEFAULT NULL COMMENT '最后取消关注时间',
  `fid` int(11) DEFAULT '0' COMMENT '推广人ID',
  `pid` int(11) DEFAULT '1' COMMENT '用户类别（1:普通会员，2:推广会员）默认为普通会员，消费之后自动成为推广会员拥有推广二维码',
  `sortpath` varchar(45) DEFAULT NULL,
  `point` int(11) DEFAULT '0',
  `alipay` varchar(45) DEFAULT NULL COMMENT '支付宝账号',
  `industry` varchar(45) DEFAULT NULL,
  `bought` text COMMENT '已经购买过的大类',
  `seelower` tinyint(1) DEFAULT '1' COMMENT '是否可以查看下级',
  `company` varchar(255) DEFAULT NULL,
  `zhiwei` varchar(45) DEFAULT NULL,
  `need` text,
  `have` text,
  `extendinfo` text,
  `staffid` int(11) DEFAULT '0',
  `source` int(11) DEFAULT '0' COMMENT '客户来源',
  `producttype` int(11) DEFAULT '0' COMMENT '产品类别',
  `level` int(11) DEFAULT '0' COMMENT '客户分级',
  `memberstatus` int(11) DEFAULT '0' COMMENT '客户状态',
  `lastfollowtime` datetime DEFAULT NULL,
  `cardno` varchar(255) DEFAULT NULL COMMENT '会员卡号',
  `career` int(11) DEFAULT '0' COMMENT '职业',
  `age` varchar(255) DEFAULT NULL COMMENT '年龄',
  `education` int(11) DEFAULT '0' COMMENT '学历',
  `income` int(11) DEFAULT '0' COMMENT '收入',
  `complatetime` timestamp NULL DEFAULT NULL COMMENT '客户成交时间',
  `targetamount` varchar(255) DEFAULT NULL COMMENT '预估金额',
  `creater` int(11) DEFAULT '0' COMMENT '创建人',
  `work` text COMMENT '工作信息',
  `invitationcode` varchar(255) DEFAULT NULL COMMENT '邀请码',
  `registercode` varchar(255) DEFAULT NULL COMMENT 'zhucema',
  `zmfinfo` varchar(255) DEFAULT NULL COMMENT '芝麻分信息',
  `update_time` datetime DEFAULT NULL,
  `update_info` varchar(64) DEFAULT '' COMMENT '更新信息',
  `token` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='会员-有openid的访客信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member`
--

LOCK TABLES `my_member` WRITE;
/*!40000 ALTER TABLE `my_member` DISABLE KEYS */;
INSERT INTO `my_member` VALUES (1,1,0,0,NULL,0,0,0,0,0,0,'','Mac','96e79218965eb72c92a549dd5a330112',NULL,'36082219750711058X','http://pbeapl0kv.bkt.clouddn.com/11531635322124d521dc3e734ea5347411453b07077.jpg','http://pbeapl0kv.bkt.clouddn.com/1153163532283245670ab6a4e66df3f00ed7d5b2465.jpg','http://pbeapl0kv.bkt.clouddn.com/11531635322966f5476526250febf536ae397811647.png','17713413684',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,0,NULL,0,NULL,NULL,NULL,NULL,'http://pbeapl0kv.bkt.clouddn.com/11531635322966f5476526250febf536ae397811647.png',0,NULL,NULL,1,0.00,0.00,NULL,0,'','2018-07-15 06:14:58',0,NULL,0,1,NULL,0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,NULL,0,NULL,0,0,NULL,NULL,0,NULL,NULL,'',NULL,'2018-07-15 14:19:22','更新了个人信息','');
/*!40000 ALTER TABLE `my_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_apply`
--

DROP TABLE IF EXISTS `my_member_apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL COMMENT '会员id',
  `staffid` int(11) DEFAULT NULL COMMENT '员工id',
  `status` int(11) DEFAULT '0' COMMENT '0-申请中，1-已同意，2-已拒绝，3-成为私有后进入公海',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `updatetime` timestamp NULL DEFAULT NULL COMMENT '操作时间（同意，拒绝）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户申请表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_apply`
--

LOCK TABLES `my_member_apply` WRITE;
/*!40000 ALTER TABLE `my_member_apply` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_apply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_balance`
--

DROP TABLE IF EXISTS `my_member_balance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID-标识字段',
  `username` varchar(255) DEFAULT NULL,
  `prebalance` int(11) DEFAULT '0' COMMENT '之前余额',
  `amount` int(11) DEFAULT '0' COMMENT '收支金额',
  `balance` int(11) DEFAULT '0' COMMENT '本次余额',
  `balancetype` varchar(1) DEFAULT NULL COMMENT '0-支出，1-收入',
  `balancetypeid` tinyint(3) DEFAULT '0' COMMENT '收支类型ID，决定是收还是支',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '1',
  `remark` varchar(1024) DEFAULT NULL COMMENT '收支备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员-用户余额表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_balance`
--

LOCK TABLES `my_member_balance` WRITE;
/*!40000 ALTER TABLE `my_member_balance` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_balance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_balance_type`
--

DROP TABLE IF EXISTS `my_member_balance_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_balance_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `depth` tinyint(4) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `sortpath` varchar(100) DEFAULT NULL,
  `sorttype` tinyint(4) DEFAULT NULL,
  `indexpic` varchar(100) DEFAULT NULL,
  `type` tinyint(3) DEFAULT '0' COMMENT '0-支出，1-收入',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员-用户余额收支类型表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_balance_type`
--

LOCK TABLES `my_member_balance_type` WRITE;
/*!40000 ALTER TABLE `my_member_balance_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_balance_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_cash`
--

DROP TABLE IF EXISTS `my_member_cash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID-标识字段',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '0-待审，1-已审，2-失败',
  `remark` varchar(1024) DEFAULT NULL COMMENT '提现备注',
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员-用户提现表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_cash`
--

LOCK TABLES `my_member_cash` WRITE;
/*!40000 ALTER TABLE `my_member_cash` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_cash` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_custom`
--

DROP TABLE IF EXISTS `my_member_custom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `customid` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关注表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_custom`
--

LOCK TABLES `my_member_custom` WRITE;
/*!40000 ALTER TABLE `my_member_custom` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_custom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_dic`
--

DROP TABLE IF EXISTS `my_member_dic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_dic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `pid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户字典表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_dic`
--

LOCK TABLES `my_member_dic` WRITE;
/*!40000 ALTER TABLE `my_member_dic` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_dic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_duotou`
--

DROP TABLE IF EXISTS `my_member_duotou`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_duotou` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8,
  `taskno` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isget` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_duotou`
--

LOCK TABLES `my_member_duotou` WRITE;
/*!40000 ALTER TABLE `my_member_duotou` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_duotou` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_info`
--

DROP TABLE IF EXISTS `my_member_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT '0' COMMENT '会员 id',
  `tmobilereal` text COMMENT '运营商实名认证信息',
  `tmobileinfo` longtext COMMENT '运营商数据',
  `bankreal` text COMMENT '银行卡实名',
  `idcardinfo` text COMMENT '身份证信息',
  `idcardcompare` text COMMENT '身份证对比',
  `creditannounce` text COMMENT '征信信息',
  `blackloan` text COMMENT '网贷黑名单',
  `blackcrime` text COMMENT '犯罪记录',
  `blackcourt` text COMMENT '高法黑名单',
  `jd` text COMMENT '京东数据',
  `taobao` longtext COMMENT '淘宝数据',
  `alipay` longtext COMMENT '支付宝数据',
  `tmobilereport` longtext COMMENT '联系人报告',
  `squad` text COMMENT '反欺诈',
  `list` text COMMENT '多头信息',
  `bull` text COMMENT '多头',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员认证信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_info`
--

LOCK TABLES `my_member_info` WRITE;
/*!40000 ALTER TABLE `my_member_info` DISABLE KEYS */;
INSERT INTO `my_member_info` VALUES (1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `my_member_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_level`
--

DROP TABLE IF EXISTS `my_member_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户分级表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_level`
--

LOCK TABLES `my_member_level` WRITE;
/*!40000 ALTER TABLE `my_member_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_prize`
--

DROP TABLE IF EXISTS `my_member_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `prizeid` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '是否领取',
  `prizename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户中奖纪录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_prize`
--

LOCK TABLES `my_member_prize` WRITE;
/*!40000 ALTER TABLE `my_member_prize` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_producttype`
--

DROP TABLE IF EXISTS `my_member_producttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_producttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户意向产品类别';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_producttype`
--

LOCK TABLES `my_member_producttype` WRITE;
/*!40000 ALTER TABLE `my_member_producttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_producttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_source`
--

DROP TABLE IF EXISTS `my_member_source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户来源表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_source`
--

LOCK TABLES `my_member_source` WRITE;
/*!40000 ALTER TABLE `my_member_source` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_status`
--

DROP TABLE IF EXISTS `my_member_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `complate` int(11) DEFAULT '0' COMMENT '1-已完成客户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户状态表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_status`
--

LOCK TABLES `my_member_status` WRITE;
/*!40000 ALTER TABLE `my_member_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_taobao`
--

DROP TABLE IF EXISTS `my_member_taobao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_taobao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8,
  `taskno` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isget` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_taobao`
--

LOCK TABLES `my_member_taobao` WRITE;
/*!40000 ALTER TABLE `my_member_taobao` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_taobao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_taobao_record`
--

DROP TABLE IF EXISTS `my_member_taobao_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_taobao_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT '0',
  `img` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'erweima',
  `taskno` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_taobao_record`
--

LOCK TABLES `my_member_taobao_record` WRITE;
/*!40000 ALTER TABLE `my_member_taobao_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_taobao_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_tmobile_data`
--

DROP TABLE IF EXISTS `my_member_tmobile_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_tmobile_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datadetail` longtext COMMENT '数据详细',
  `taskno` varchar(255) DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isget` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='接口获取运营商数据表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_tmobile_data`
--

LOCK TABLES `my_member_tmobile_data` WRITE;
/*!40000 ALTER TABLE `my_member_tmobile_data` DISABLE KEYS */;
INSERT INTO `my_member_tmobile_data` VALUES (1,'{\"taskNo\":\"216d7e3e5f9d4cc98366a8f6058c65471531636190398\",\"code\":\"carrier_4\",\"taskStatus\":\"pending\",\"acceptPatchCode\":[2000],\"message\":\"短信已发送，请输入验证码\"}','216d7e3e5f9d4cc98366a8f6058c65471531636190398','2018-07-15 06:29:58',1);
/*!40000 ALTER TABLE `my_member_tmobile_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_tmobile_record`
--

DROP TABLE IF EXISTS `my_member_tmobile_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_tmobile_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskno` varchar(255) DEFAULT NULL COMMENT '任务号',
  `memberid` int(11) DEFAULT '0' COMMENT '会员id',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `telephone` varchar(255) DEFAULT NULL,
  `servicepwd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='获取运营商信息任务号记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_tmobile_record`
--

LOCK TABLES `my_member_tmobile_record` WRITE;
/*!40000 ALTER TABLE `my_member_tmobile_record` DISABLE KEYS */;
INSERT INTO `my_member_tmobile_record` VALUES (1,'216d7e3e5f9d4cc98366a8f6058c65471531636190398',1,'2018-07-15 06:29:49','17713413684','538534');
/*!40000 ALTER TABLE `my_member_tmobile_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_zfb`
--

DROP TABLE IF EXISTS `my_member_zfb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_zfb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` longtext CHARACTER SET utf8,
  `taskno` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isget` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_zfb`
--

LOCK TABLES `my_member_zfb` WRITE;
/*!40000 ALTER TABLE `my_member_zfb` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_zfb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_member_zfb_record`
--

DROP TABLE IF EXISTS `my_member_zfb_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_member_zfb_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskno` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `img` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `memberid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_member_zfb_record`
--

LOCK TABLES `my_member_zfb_record` WRITE;
/*!40000 ALTER TABLE `my_member_zfb_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_member_zfb_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_message`
--

DROP TABLE IF EXISTS `my_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `content` text,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `replytime` timestamp NULL DEFAULT NULL,
  `isreplynew` int(11) DEFAULT '1' COMMENT '1-新，0-否',
  `isquesnew` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='在线咨询表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_message`
--

LOCK TABLES `my_message` WRITE;
/*!40000 ALTER TABLE `my_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_message_record`
--

DROP TABLE IF EXISTS `my_message_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_message_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsid` int(11) DEFAULT '0' COMMENT '消息id',
  `staffid` int(11) DEFAULT '0' COMMENT '员工id',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息阅读记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_message_record`
--

LOCK TABLES `my_message_record` WRITE;
/*!40000 ALTER TABLE `my_message_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_message_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_msg`
--

DROP TABLE IF EXISTS `my_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loanid` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_msg`
--

LOCK TABLES `my_msg` WRITE;
/*!40000 ALTER TABLE `my_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_node`
--

DROP TABLE IF EXISTS `my_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `super` tinyint(3) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `isresume` tinyint(3) DEFAULT '0' COMMENT '常用菜单',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `name` (`name`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=275 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_node`
--

LOCK TABLES `my_node` WRITE;
/*!40000 ALTER TABLE `my_node` DISABLE KEYS */;
INSERT INTO `my_node` VALUES (1,'Admin','后台管理',1,'',1,0,1,'',0,'',0),(2,'Rbac','组织架构',1,'',2,1,2,'',0,'fa fa-key',0),(3,'System','系统设置',1,'',3,1,2,'',0,'fa fa-cogs',0),(4,'Content','内容管理',1,'',4,1,2,'',0,'fa fa-folder-open',0),(5,'Label','标签管理',1,'',5,1,2,'',0,'fa fa-bookmark',0),(6,'Setting','分类管理',1,'',3,1,2,'',0,'fa fa-reorder',0),(7,'Member','会员订单',1,'',7,1,2,'',0,'fa fa-user',0),(8,'Wechat','微信平台',1,'',10,1,2,'',0,'fa fa-wechat',0),(9,'role','角色管理',1,'',9,2,3,'',0,'',0),(10,'addRole','添加角色',0,'',10,2,3,'',0,'',0),(11,'editRole','修改角色',0,'',11,2,3,'',0,'',0),(12,'deleteRole','删除角色',0,'',12,2,3,'',0,'',0),(13,'user','用户管理',1,'',13,2,3,'',0,'',0),(14,'addUser','添加用户',0,'',14,2,3,'',0,'',0),(15,'editUser','修改用户',0,'',15,2,3,'',0,'',0),(16,'deleteUser','删除用户',0,'',16,2,3,'',0,'',0),(17,'node','节点管理',1,'',17,2,3,'',1,'',0),(18,'addNode','添加节点',0,'',18,2,3,'',0,'',0),(19,'editNode','修改节点',0,'',19,2,3,'',0,'',0),(20,'deleteNode','删除节点',0,'',20,2,3,'',0,'',0),(21,'config','配置管理',1,'',21,3,3,'',1,'',0),(22,'addConfig','添加配置',0,'',22,3,3,'',0,'',0),(23,'editConfig','修改配置',0,'',23,3,3,'',0,'',0),(24,'deleteConfig','删除配置',0,'',24,3,3,'',0,'',0),(25,'setting','参数设置',1,'',25,3,3,'',0,'',1),(26,'database','数据备份',1,'',26,3,3,'',0,'',0),(27,'pwd','修改密码',1,'',27,3,3,'',0,'',0),(28,'label','标签管理',1,'',28,5,3,'',0,'',0),(29,'addLabel','添加标签',1,'',29,5,3,'',0,'',0),(30,'editLabel','修改标签',0,'',30,5,3,'',0,'',0),(31,'deleteLabel','删除标签',0,'',31,5,3,'',0,'',0),(32,'access','授权操作',0,'',32,2,3,'',0,'',0),(33,'batch','批量操作',0,'',33,2,3,'',0,'',0),(34,'ajax','Ajax操作',0,'',34,2,3,'',0,'',0),(35,'index','后台首页',0,'',35,2,3,'Index/index',0,'',0),(36,'setting','公众号设置',1,'',36,8,3,'',0,'',0),(37,'material','素材管理',1,'',37,8,3,'',0,'',0),(38,'keyword','关键词回复',1,'',38,8,3,'',0,'',0),(39,'menu','自定义菜单',1,'',39,8,3,'',0,'',0),(40,'message','信息群发',0,'',40,8,3,'',0,'',0),(41,'getMenu','获取菜单',0,'',41,8,3,'',0,'',0),(42,'setMenu','设置菜单',0,'',42,8,3,'',0,'',0),(43,'news','图文素材管理',0,'',43,8,3,'',0,'',0),(44,'single','单图文消息',0,'',44,8,3,'',0,'',0),(45,'multi','多图文消息',0,'',45,8,3,'',0,'',0),(46,'editKeyword','修改关键词',0,'',46,8,3,'',0,'',0),(47,'deleteMaterial','删除素材',0,'',47,8,3,'',0,'',0),(48,'modal','选择素材',0,'',48,8,3,'',0,'',0),(49,'news','资讯分类',1,'',49,6,3,'',0,'',0),(50,'addNews','添加资讯分类',0,'',50,6,3,'',0,'',0),(51,'editNews','修改资讯分类',0,'',51,6,3,'',0,'',0),(52,'deleteNews','删除资讯分类',0,'',52,6,3,'',0,'',0),(53,'info','单页分类',1,'',53,6,3,'',0,'',0),(54,'addInfo','添加单页分类',0,'',54,6,3,'',0,'',0),(55,'editInfo','修改单页分类',0,'',55,6,3,'',0,'',0),(56,'deleteInfo','删除单页分类',0,'',56,6,3,'',0,'',0),(57,'news','资讯列表',1,'',57,4,3,'',0,'',0),(58,'addNews','添加资讯',0,'',58,4,3,'',0,'',0),(59,'editNews','修改资讯',0,'',59,4,3,'',0,'',0),(60,'deleteNews','删除资讯',0,'',60,4,3,'',0,'',0),(61,'info','单页列表',1,'',61,4,3,'',0,'',0),(62,'addInfo','添加单页',0,'',62,4,3,'',0,'',0),(63,'editInfo','修改单页',0,'',63,4,3,'',0,'',0),(64,'deleteInfo','删除单页',0,'',64,4,3,'',0,'',0),(65,'modal','素材选择',0,'',65,4,3,'',0,'',0),(66,'product','产品分类',0,'',48,6,3,'',0,'',0),(67,'addProduct','添加产品分类',0,'',67,6,3,'',0,'',0),(68,'editProduct','修改产品分类',0,'',68,6,3,'',0,'',0),(69,'deleteProduct','删除产品分类',0,'',69,6,3,'',0,'',0),(70,'product','产品列表',0,'',60,4,3,'',0,'',0),(71,'addProduct','添加产品',0,'',71,4,3,'',0,'',0),(72,'editProduct','修改产品',0,'',72,4,3,'',0,'',0),(73,'deleteProduct','删除产品',0,'',73,4,3,'',0,'',0),(74,'member','客户管理',1,'',74,7,3,'',0,'',0),(75,'addMember','添加会员',0,'',75,7,3,'',0,'',0),(76,'editMember','修改会员',0,'',76,7,3,'',0,'',0),(77,'deleteMember','删除会员',0,'',77,7,3,'',0,'',0),(78,'order','订单列表',0,'',78,7,3,'',0,'',0),(79,'addOrder','添加订单',0,'',79,7,3,'',0,'',0),(80,'editOrder','修改订单',0,'',80,7,3,'',0,'',0),(81,'deleteOrder','删除订单',0,'',81,7,3,'',0,'',0),(82,'member','会员分类',0,'',82,6,3,'',0,'',0),(83,'addMember','添加会员分类',0,'',83,6,3,'',0,'',0),(84,'editMember','修改会员分类',0,'',84,6,3,'',0,'',0),(85,'deleteMember','删除会员分类',0,'',85,6,3,'',0,'',0),(86,'express','快递列表',0,'',86,7,3,'',0,'',0),(87,'addExpress','添加快递',0,'',87,7,3,'',0,'',0),(88,'editExpress','修改快递',0,'',88,7,3,'',0,'',0),(89,'deleteExpress','删除快递',0,'',89,7,3,'',0,'',0),(90,'balance','余额记录',0,'',90,7,3,'',0,'',0),(91,'coupon','优惠券列表',1,'',91,4,3,'',0,'',0),(92,'addCoupon','添加优惠券',1,'',92,4,3,'',0,'',0),(93,'editCoupon','修改优惠券',1,'',93,4,3,'',0,'',0),(94,'deleteCoupon','删除优惠券',1,'',94,4,3,'',0,'',0),(95,'coupon','优惠券分类',1,'',95,6,3,'',0,'',0),(96,'addCoupon','添加优惠券分类',1,'',96,6,3,'',0,'',0),(97,'editCoupon','修改优惠券分类',1,'',97,6,3,'',0,'',0),(98,'deleteCoupon','删除优惠券分类',1,'',98,6,3,'',0,'',0),(99,'cash','提现列表',0,'',99,7,3,'',0,'',0),(100,'addCash','添加提现',0,'',100,7,3,'',0,'',0),(101,'editCash','修改提现',0,'',101,7,3,'',0,'',0),(102,'deleteCash','删除提现',0,'',102,7,3,'',0,'',0),(103,'statistic','订单统计',0,'',103,7,3,'',0,'',0),(104,'pointproduct','积分产品',0,'积分产品',104,4,3,NULL,0,'',0),(105,'addpointProduct','添加积分产品',0,'',105,4,3,NULL,0,'',0),(106,'editpointProduct','编辑积分产品',0,'',106,4,3,NULL,0,'',0),(107,'active','活动列表',0,'',170,4,3,NULL,0,'',0),(108,'addactive','添加活动',0,'',171,4,3,NULL,0,'',0),(109,'editactive','编辑活动',0,'',172,4,3,NULL,0,'',0),(110,'deleteactive','删除活动',0,'',173,4,3,NULL,0,'',0),(111,'activemember','活动参加选手',0,'',111,4,3,NULL,0,'',0),(112,'memberview','参赛选手详细',0,'',112,4,3,NULL,0,'',0),(113,'deleteactivemember','删除参赛选手',0,'',113,4,3,NULL,0,'',0),(114,'addKeyword','添加关键词',0,'',114,8,3,NULL,0,'',0),(115,'deletepointProduct','删除积分产品',0,'',115,4,3,NULL,0,'',0),(116,'exportorder','导出订单',0,'',116,7,3,NULL,0,'',0),(117,'deleteKeyword','删除关键词',0,'',117,8,3,NULL,0,'',0),(118,'getChildren','获取会员下级',0,'',118,7,3,NULL,0,'',0),(119,'teacher','教师列表',0,'教师列表',119,6,3,NULL,0,'',0),(120,'addteacher','添加教师',0,'添加教师',120,6,3,NULL,0,'',0),(121,'editteacher','编辑教师',0,'编辑教师',121,6,3,NULL,0,'',0),(122,'deleteteacher','删除教师',0,'',122,6,3,NULL,0,'',0),(123,'company','单位列表',0,'单位列表',123,6,3,NULL,0,'',0),(124,'addcompany','添加单位',0,'添加单位',124,6,3,NULL,0,'',0),(125,'editcompany','编辑单位',0,'编辑单位',125,6,3,NULL,0,'',0),(126,'deletecompany','删除单位',0,'删除单位',126,6,3,NULL,0,'',0),(127,'classes','课程分类',0,'',127,6,3,NULL,0,'',0),(128,'addclasses','添加课程分类',0,'',128,6,3,NULL,0,'',0),(129,'editclasses','编辑课程分类',0,'',129,6,3,NULL,0,'',0),(130,'deleteclasses','删除课程分类',0,'',130,6,3,NULL,0,'',0),(131,'classes','课程列表',0,'',131,4,3,NULL,0,'',0),(132,'editclasses','编辑课程',0,'',132,4,3,NULL,0,'',0),(133,'addclasses','添加课程',0,'',133,4,3,NULL,0,'',0),(134,'deleteclasses','删除课程',0,'',134,4,3,NULL,0,'',0),(144,'wheel','大转盘',1,'',48,8,3,'',0,'',0),(145,'activity','活动列表',1,'',114,144,4,'',0,'',0),(146,'addActivity','添加活动',0,'',115,144,4,'',0,'',0),(147,'editActivity','修改活动',0,'',116,144,4,'',0,'',0),(148,'deleteActvity','删除活动',0,'',116,144,4,'',0,'',0),(149,'prize','奖项列表',1,'',114,144,4,'',0,'',0),(150,'addPrice','添加奖项',0,'',115,144,4,'',0,'',0),(151,'editPrize','修改奖项',0,'',116,144,4,'',0,'',0),(152,'deletePrize','删除奖项',0,'',116,144,4,'',0,'',0),(153,'history','参与列表',1,'',114,144,4,'',0,'',0),(154,'record','中奖记录',1,'',115,144,4,'',0,'',0),(155,'Form','留言管理',0,'',6,1,2,'',0,'',0),(156,'feedback','留言列表',1,'',72,155,3,'',0,'',0),(157,'addFeedback','添加反馈',0,'',73,155,3,'',0,'',0),(158,'editFeedback','修改反馈',0,'',74,155,3,'',0,'',0),(159,'deleteFeedback','删除反馈',0,'',75,155,3,'',0,'',0),(160,'expmember','导出会员',0,'',160,7,3,NULL,0,'',0),(161,'exportExcel','输出excel工作簿',0,'',161,7,3,NULL,0,'',0),(162,'signup','签到课程列表',0,'',162,4,3,NULL,0,'',0),(163,'addsignup','添加签到课程',0,'',163,4,3,NULL,0,'',0),(164,'editsignup','编辑签到课程',0,'',164,4,3,NULL,0,'',0),(165,'deletesignup','删除签到课程',0,'',165,4,3,NULL,0,'',0),(166,'expsignuprecord','导出签到记录操作',0,'',166,4,3,NULL,0,'',0),(167,'exportExcel','输出excel方法',0,'',167,4,3,NULL,0,'',0),(168,'getqrcode','获取课程二维码',0,'',168,4,3,NULL,0,'',0),(169,'signuprecord','查看签到记录',0,'',169,4,3,NULL,0,'',0),(170,'Crm','CRM系统',0,NULL,166,1,2,NULL,0,NULL,0),(171,'staff','员工列表',1,NULL,167,170,3,NULL,0,NULL,0),(172,'addstaff','添加员工',0,NULL,168,170,3,NULL,0,NULL,0),(173,'editstaff','编辑员工',0,NULL,169,170,3,NULL,0,NULL,0),(174,'deletestaff','删除员工',0,NULL,170,170,3,NULL,0,NULL,0),(175,'member','客户管理',1,NULL,171,170,3,NULL,0,NULL,0),(176,'addmember','添加客户',0,NULL,172,170,3,NULL,0,NULL,0),(177,'editmember','编辑客户',0,NULL,173,170,3,NULL,0,NULL,0),(178,'deletemember','删除客户',0,NULL,174,170,3,NULL,0,NULL,0),(179,'applymember','查看私有会员申请',0,NULL,175,170,3,NULL,0,NULL,0),(180,'import','导入客户',1,NULL,176,170,3,NULL,0,NULL,0),(181,'source','客户来源',1,NULL,177,170,3,NULL,0,NULL,0),(182,'addsource','添加客户来源',0,NULL,178,170,3,NULL,0,NULL,0),(183,'editsource','编辑来源',0,NULL,179,170,3,NULL,0,NULL,0),(184,'deletesource','删除来源',0,NULL,180,170,3,NULL,0,NULL,0),(185,'producttype','产品类别',1,NULL,181,170,3,NULL,0,NULL,0),(186,'addproducttype','添加产品类别',0,NULL,182,170,3,NULL,0,NULL,0),(187,'editproducttype','编辑产品类别',0,NULL,183,170,3,NULL,0,NULL,0),(188,'deleteproducttype','删除产品类别',0,NULL,184,170,3,NULL,0,NULL,0),(189,'level','客户分级',1,NULL,185,170,3,NULL,0,NULL,0),(190,'addlevel','添加客户分级',0,NULL,186,170,3,NULL,0,NULL,0),(191,'editlevel','编辑客户分级',0,NULL,187,170,3,NULL,0,NULL,0),(192,'deletelevel','删除客户分级',0,NULL,188,170,3,NULL,0,NULL,0),(193,'memberstatus','跟进状态',1,'',189,170,3,NULL,0,'',0),(194,'addmemberstatus','添加客户状态',0,NULL,190,170,3,NULL,0,NULL,0),(195,'editmemberstatus','编辑客户状态',0,NULL,191,170,3,NULL,0,NULL,0),(196,'deletememberstatus','删除客户状态',0,NULL,192,170,3,NULL,0,NULL,0),(197,'Finance','财务中心',1,'',9,1,2,NULL,0,'',0),(198,'order','订单统计',0,NULL,131,197,3,NULL,0,NULL,0),(199,'Member','会员统计',0,NULL,132,197,3,NULL,0,NULL,0),(200,'product','单品统计',0,NULL,133,197,3,NULL,0,NULL,0),(201,'balance','佣金统计',0,NULL,134,197,3,NULL,0,NULL,0),(202,'stafffollow','客户跟进记录',1,'',175,170,3,NULL,0,'',0),(203,'deletestafffollow','删除客户跟进记录',0,'',175,170,3,NULL,0,'',0),(204,'editstafffollow','查看客户跟进详细',0,'',204,170,3,NULL,0,'',0),(205,'customdic','客户字典',1,'',205,170,3,NULL,0,'',0),(206,'editcustomdic','编辑客户字典',0,'',206,170,3,NULL,0,'',0),(207,'addcustomdic','添加客户字典',0,'',207,170,3,NULL,0,'',0),(208,'deletecustomdic','删除客户字典',0,'',208,170,3,NULL,0,'',0),(209,'articleanas','文章分析',0,'',209,197,3,'',0,'',0),(210,'detail','文章详细概览',0,'',210,197,3,'',0,'',0),(211,'article','单篇文章分析',0,'',211,197,3,'',0,'',0),(212,'articlemember','单篇文章传播节点',0,'',212,197,3,'',0,'',0),(213,'memberanas','用户分析',0,'',213,197,3,'',0,'',0),(214,'memberdetail','单个用户分析',0,'',214,197,3,'',0,'',0),(215,'lottery','刮刮卡',1,NULL,48,8,3,NULL,0,NULL,0),(216,'activity','活动列表',1,NULL,215,215,4,NULL,0,NULL,0),(217,'addActivity','添加活动',0,NULL,216,215,4,NULL,0,NULL,0),(218,'editActivity','修改活动',0,NULL,217,215,4,NULL,0,NULL,0),(219,'deleteActvity','删除活动',0,NULL,218,215,4,NULL,0,NULL,0),(220,'prize','奖项列表',1,NULL,219,215,4,NULL,0,NULL,0),(221,'addPrice','添加奖项',0,NULL,220,215,4,NULL,0,NULL,0),(222,'editPrize','修改奖项',0,NULL,221,215,4,NULL,0,NULL,0),(223,'deletePrize','删除奖项',0,NULL,222,215,4,NULL,0,NULL,0),(224,'history','参与列表',1,NULL,223,215,4,NULL,0,NULL,0),(225,'record','中奖记录',1,NULL,224,215,4,NULL,0,NULL,0),(226,'department','部门管理',1,NULL,150,170,3,NULL,0,NULL,0),(227,'adddepartment','添加部门',0,NULL,151,170,3,NULL,0,NULL,0),(228,'editdepartment','编辑部门',0,NULL,152,170,3,NULL,0,NULL,0),(229,'deletedepartment','删除部门',0,NULL,153,170,3,NULL,0,NULL,0),(230,'Exam','考试系统',0,'',230,1,2,NULL,0,'',0),(231,'tiku','题库管理',1,'',231,230,3,NULL,0,'',0),(232,'addtiku','添加题目',0,'',232,230,3,NULL,0,'',0),(233,'edittiku','编辑题目',0,'',233,230,3,NULL,0,'',0),(234,'deletetiku','删除题库',0,'',234,230,3,NULL,0,'',0),(235,'student','考生管理',1,'',235,230,3,NULL,0,'',0),(236,'addstudent','添加考生',0,'',236,230,3,NULL,0,'',0),(237,'editstudent','编辑考生',0,'',237,230,3,NULL,0,'',0),(238,'deletestudent','删除考生',0,'',238,230,3,NULL,0,'',0),(239,'importstudent','导入考生',1,'',239,230,3,NULL,0,'',0),(240,'exam','考试管理',0,'',240,230,3,NULL,0,'',0),(241,'addexam','添加考试',0,'',241,230,3,NULL,0,'',0),(242,'editexam','编辑考试',0,'',242,230,3,NULL,0,'',0),(243,'deleteexam','删除考试',0,'',243,230,3,NULL,0,'',0),(244,'saletongji','销售统计',1,'',244,170,3,NULL,0,'',0),(245,'itemtongji','项目统计',0,'',245,170,3,NULL,0,'',0),(246,'hetongtongji','合同统计',0,'',246,170,3,NULL,0,'',0),(247,'showrankdetail','销售统计详细',0,'',247,170,3,NULL,0,'',0),(248,'loan','贷款产品',1,'',248,4,3,NULL,0,'',0),(249,'addloan','添加贷款产品',0,'',249,4,3,NULL,0,'',0),(250,'editloan','编辑贷款产品',0,'',250,4,3,NULL,0,'',0),(251,'deleteloan','删除贷款产品',0,'',251,4,3,NULL,0,'',0),(252,'getinterfacedata','征信查询接口操作',0,'',252,7,3,NULL,0,'',0),(253,'loan','贷款订单',1,'',253,7,3,NULL,0,'',0),(254,'editloan','编辑贷款订单',0,'',254,7,3,NULL,0,'',0),(255,'addloan','添加贷款订单',0,'',255,7,3,NULL,0,'',0),(256,'deleteloan','删除贷款订单',0,'',256,7,3,NULL,0,'',0),(257,'fagnkuan','本金放款台账',1,'',257,197,3,NULL,0,'',0),(258,'shoukuan','本金收款台账',1,'',258,197,3,NULL,0,'',0),(259,'lixi','利息台账',1,'',259,197,3,NULL,0,'',0),(260,'yuqi','逾期罚金收款台账',1,'',260,197,3,NULL,0,'',0),(261,'loanahead','贷款初审',1,'',261,7,3,'/Admin/Member/loan/status/0',0,'',0),(262,'loansecond','贷款再审放款',1,'',262,7,3,'/Admin/Member/loan/status/1',0,'',0),(263,'loannotice','逾期提醒',1,'',263,7,3,'/Admin/Member/loan/status/3',0,'',0),(264,'loan','审核员查看贷款订单',0,'',264,7,3,NULL,0,'',0),(265,'Member1','贷后管理',1,'',8,1,2,NULL,0,'',0),(266,'loannotice','催收管理',1,'',266,265,3,'/Admin/Member/loan/status/3',0,'',0),(267,'loan','贷后管理员查看贷款订单',0,'贷后管理员查看贷款订单',267,265,3,NULL,0,'',0),(268,'loanyujin','贷后预警',1,'',268,265,3,'/Admin/Member/loan?status=2',0,'',0),(269,'getContract','合同',0,'合同',269,7,3,NULL,0,'',0),(270,'getpdf','生成pdf文档',0,'生成pdf文档',270,7,3,NULL,0,'',0),(271,'staff','员工列表',1,'',271,7,3,NULL,0,'',0),(272,'addstaff','添加员工',0,'',272,7,3,NULL,0,'',0),(273,'editstaff','修改员工',0,'',273,7,3,NULL,0,'',0),(274,'deletestaff','删除员工',0,'',274,7,3,NULL,0,'',0);
/*!40000 ALTER TABLE `my_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_order`
--

DROP TABLE IF EXISTS `my_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '订单名称',
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '0' COMMENT '0-未付款,1-待发货,2-已发货,3-已完成,4-已取消,5-已退款',
  `ordertimes` varchar(255) DEFAULT NULL COMMENT '订单状态时间',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '订购数量',
  `username` varchar(255) DEFAULT NULL COMMENT '用户填写的真实姓名',
  `telephone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `addressid` int(11) DEFAULT '0' COMMENT '收货地址ID',
  `provinceid` int(11) DEFAULT '0',
  `cityid` int(11) DEFAULT '0',
  `districtid` int(11) DEFAULT '0',
  `address` varchar(255) DEFAULT '' COMMENT '详细地址',
  `remark` text COMMENT '订单备注',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '订单实付金额',
  `total` decimal(10,2) DEFAULT '0.00' COMMENT '商品总额',
  `shipfee` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `discount` decimal(10,2) DEFAULT '0.00' COMMENT '折扣金额',
  `paystatus` tinyint(3) DEFAULT '0' COMMENT '支付状态：0-未付款，1-已付款',
  `paytime` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `paymethod` tinyint(3) DEFAULT '0' COMMENT '支付方式：0-到店支付，1-微信支付',
  `tradeno` varchar(255) DEFAULT '' COMMENT '交易流水号',
  `payinfo` longtext COMMENT '支付详细',
  `refundinfo` varchar(1024) DEFAULT '' COMMENT '退款详细',
  `refundqueryinfo` varchar(1024) DEFAULT '' COMMENT '退款查询详细',
  `replyinfo` varchar(255) DEFAULT NULL COMMENT '处理意见',
  `replytime` timestamp NULL DEFAULT NULL COMMENT '处理时间',
  `guid` varchar(255) DEFAULT NULL COMMENT '会员标识',
  `expressid` int(11) DEFAULT '0' COMMENT '物流公司ID',
  `expressno` varchar(255) DEFAULT NULL COMMENT '物流单号',
  `couponno` varchar(255) DEFAULT NULL COMMENT '优惠券号',
  `type` int(11) DEFAULT '1' COMMENT '1：商品订单，2：积分订单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_order`
--

LOCK TABLES `my_order` WRITE;
/*!40000 ALTER TABLE `my_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_order_detail`
--

DROP TABLE IF EXISTS `my_order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单编号',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `productid` int(11) DEFAULT '0' COMMENT '产品ID',
  `firstpid` int(11) DEFAULT '0',
  `specification` varchar(255) NOT NULL DEFAULT '' COMMENT '产品规格',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '产品名称',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '订购数量',
  `status` tinyint(3) DEFAULT '0' COMMENT '0-未付款,1-待发货,2-已发货,3-已完成,4-已取消,5-已退款',
  `weight` int(11) DEFAULT '0' COMMENT '重量（克）',
  `attrs` longtext COMMENT '属性',
  `secondbuy` int(11) DEFAULT '0' COMMENT '0:第一次购买该大类下产品，1：第二次购买该大类下产品',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='订单详细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_order_detail`
--

LOCK TABLES `my_order_detail` WRITE;
/*!40000 ALTER TABLE `my_order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_order_history`
--

DROP TABLE IF EXISTS `my_order_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_order_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单号',
  `status` tinyint(3) DEFAULT '0' COMMENT '订单状态',
  `content` text COMMENT '订单内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单修改记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_order_history`
--

LOCK TABLES `my_order_history` WRITE;
/*!40000 ALTER TABLE `my_order_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_order_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_pcd`
--

DROP TABLE IF EXISTS `my_pcd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_pcd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `level` tinyint(1) DEFAULT '0',
  `status` int(10) DEFAULT '0',
  `ext` int(10) DEFAULT '0',
  `sort` int(10) DEFAULT '0',
  `en` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='全国省市区街道';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_pcd`
--

LOCK TABLES `my_pcd` WRITE;
/*!40000 ALTER TABLE `my_pcd` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_pcd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_point_log`
--

DROP TABLE IF EXISTS `my_point_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_point_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `act` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_point_log`
--

LOCK TABLES `my_point_log` WRITE;
/*!40000 ALTER TABLE `my_point_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_point_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_question`
--

DROP TABLE IF EXISTS `my_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `itemtitle` text COMMENT '题号如：A，B,C,',
  `items` text,
  `point` int(11) DEFAULT '5' COMMENT '默认5分',
  `answer` text COMMENT '答案',
  `status` int(11) DEFAULT '0',
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='题库表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_question`
--

LOCK TABLES `my_question` WRITE;
/*!40000 ALTER TABLE `my_question` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_role`
--

DROP TABLE IF EXISTS `my_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `sort` tinyint(3) DEFAULT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `shop` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_role`
--

LOCK TABLES `my_role` WRITE;
/*!40000 ALTER TABLE `my_role` DISABLE KEYS */;
INSERT INTO `my_role` VALUES (1,'admin',0,1,'管理员',1,'','');
/*!40000 ALTER TABLE `my_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_role_user`
--

DROP TABLE IF EXISTS `my_role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_role_user`
--

LOCK TABLES `my_role_user` WRITE;
/*!40000 ALTER TABLE `my_role_user` DISABLE KEYS */;
INSERT INTO `my_role_user` VALUES (1,'2'),(3,'8'),(4,'9'),(5,'10'),(6,'11'),(7,'12'),(6,'13'),(1,'14'),(1,'15'),(11,'16'),(11,'17');
/*!40000 ALTER TABLE `my_role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_sms`
--

DROP TABLE IF EXISTS `my_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) DEFAULT '0' COMMENT '1-手机绑定',
  `telephone` varchar(255) DEFAULT NULL COMMENT '手机号',
  `code` int(10) DEFAULT NULL COMMENT '验证码',
  `status` tinyint(3) DEFAULT '0' COMMENT '0-未验证，1-已验证',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verifytime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='短信记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_sms`
--

LOCK TABLES `my_sms` WRITE;
/*!40000 ALTER TABLE `my_sms` DISABLE KEYS */;
INSERT INTO `my_sms` VALUES (1,1,'17713413684',143852,0,'2018-07-15 06:03:19',NULL),(2,1,'17713413684',468730,0,'2018-07-15 06:07:16',NULL),(3,1,'17713413684',75256,1,'2018-07-15 06:13:07','2018-07-15 06:14:58');
/*!40000 ALTER TABLE `my_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_staff`
--

DROP TABLE IF EXISTS `my_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '登录名',
  `userpwd` varchar(255) DEFAULT NULL COMMENT '登录密码',
  `name` varchar(255) DEFAULT NULL COMMENT '员工姓名',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `position` varchar(255) DEFAULT NULL COMMENT '职位',
  `status` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT NULL,
  `workdate` date DEFAULT NULL,
  `noticedate` date DEFAULT NULL COMMENT '是否已通知',
  `praisenum` int(11) DEFAULT '0',
  `ismaster` int(11) DEFAULT '0' COMMENT '0-普通员工，1-领导',
  `headimgurl` varchar(255) DEFAULT NULL,
  `departmentid` int(11) DEFAULT '0',
  `departlimit` text COMMENT '部门权限',
  `registercode` varchar(255) DEFAULT NULL COMMENT '注册码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='员工表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_staff`
--

LOCK TABLES `my_staff` WRITE;
/*!40000 ALTER TABLE `my_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_staff_follow`
--

DROP TABLE IF EXISTS `my_staff_follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_staff_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `images` text COMMENT '图片',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `remark` text COMMENT '跟进详细',
  `staffid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `type` int(11) DEFAULT '0',
  `method` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='员工跟进记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_staff_follow`
--

LOCK TABLES `my_staff_follow` WRITE;
/*!40000 ALTER TABLE `my_staff_follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_staff_follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_student`
--

DROP TABLE IF EXISTS `my_student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '姓名',
  `telephone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `no` varchar(255) DEFAULT NULL COMMENT '学号',
  `userpwd` varchar(255) DEFAULT NULL COMMENT '密码',
  `major` varchar(255) DEFAULT NULL COMMENT '专业',
  `grade` varchar(255) DEFAULT NULL COMMENT '年级',
  `class` varchar(255) DEFAULT NULL COMMENT '班级',
  `qq` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考生表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_student`
--

LOCK TABLES `my_student` WRITE;
/*!40000 ALTER TABLE `my_student` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_student_exam`
--

DROP TABLE IF EXISTS `my_student_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_student_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentid` int(11) DEFAULT '0',
  `examid` int(11) DEFAULT '0' COMMENT '考试id',
  `question` text COMMENT '考试题目',
  `answer` text COMMENT '答案',
  `score` int(11) DEFAULT NULL COMMENT '得分',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '考试时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考生考试记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_student_exam`
--

LOCK TABLES `my_student_exam` WRITE;
/*!40000 ALTER TABLE `my_student_exam` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_student_exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_user`
--

DROP TABLE IF EXISTS `my_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT '',
  `userpwd` varchar(50) DEFAULT '',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remark` varchar(255) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `sort` tinyint(3) DEFAULT NULL,
  `logtimes` int(11) DEFAULT '0',
  `lastlogtime` timestamp NULL DEFAULT NULL,
  `lastlogip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_user`
--

LOCK TABLES `my_user` WRITE;
/*!40000 ALTER TABLE `my_user` DISABLE KEYS */;
INSERT INTO `my_user` VALUES (1,'administrator','21232f297a57a5a743894a0e4a801fc3','2018-07-15 07:16:24','超级管理员',1,1,877,'2018-07-15 07:16:24','211.149.153.13'),(2,'admin','25d55ad283aa400af464c76d713c07ad','2018-07-15 07:18:14','系统管理员',1,1,695,'2018-07-15 07:18:14','211.149.153.14');
/*!40000 ALTER TABLE `my_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_keyword`
--

DROP TABLE IF EXISTS `my_wechat_keyword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '关键词',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isfull` tinyint(3) DEFAULT '0' COMMENT '0-模糊查询，1-全字匹配',
  `isall` tinyint(3) DEFAULT '0' COMMENT '0-随机回复，1-全部回复',
  `ismenu` tinyint(3) DEFAULT '0' COMMENT '0-否，1-是',
  `keyword_rule_id` int(11) DEFAULT '0' COMMENT '规则ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微信-关键词回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_keyword`
--

LOCK TABLES `my_wechat_keyword` WRITE;
/*!40000 ALTER TABLE `my_wechat_keyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_keyword` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_keyword_reply`
--

DROP TABLE IF EXISTS `my_wechat_keyword_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_keyword_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword_id` int(11) DEFAULT '0' COMMENT '关键词ID',
  `type` tinyint(3) DEFAULT '1' COMMENT '0-文字，1-图片，2-图文，3-语音，4-视频，5-app',
  `info` varchar(2048) DEFAULT NULL COMMENT '回复内容',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sort` int(11) DEFAULT NULL COMMENT '0',
  `status` tinyint(3) DEFAULT '1' COMMENT '可否购买',
  `keyword_ids` varchar(255) DEFAULT ',' COMMENT '关键词ID列表，允许多个关键词回复同一内容',
  `keyword_rule_id` int(11) DEFAULT '0' COMMENT '规则ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微信-关键词回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_keyword_reply`
--

LOCK TABLES `my_wechat_keyword_reply` WRITE;
/*!40000 ALTER TABLE `my_wechat_keyword_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_keyword_reply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_keyword_rule`
--

DROP TABLE IF EXISTS `my_wechat_keyword_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_keyword_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '规则名称',
  `title` varchar(1024) DEFAULT NULL COMMENT '关键词列表',
  `num` varchar(255) DEFAULT NULL COMMENT '统计数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微信-关键词回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_keyword_rule`
--

LOCK TABLES `my_wechat_keyword_rule` WRITE;
/*!40000 ALTER TABLE `my_wechat_keyword_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_keyword_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_material`
--

DROP TABLE IF EXISTS `my_wechat_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) DEFAULT '1' COMMENT '1-图片，2-图文，3-语音，4-视频，5-app',
  `remark` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL COMMENT '图片地址，语音地址，视频地址',
  `size` varchar(255) DEFAULT NULL COMMENT '素材尺寸',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微信-素材表：1-图片，2-图文，3-语音，4-视频';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_material`
--

LOCK TABLES `my_wechat_material` WRITE;
/*!40000 ALTER TABLE `my_wechat_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_material_comment`
--

DROP TABLE IF EXISTS `my_wechat_material_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_material_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_detail_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `indexpic` varchar(255) DEFAULT NULL,
  `info` varchar(1000) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `up` int(11) NOT NULL DEFAULT '0' COMMENT '顶',
  `down` int(11) NOT NULL DEFAULT '0' COMMENT '踩',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信-素材评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_material_comment`
--

LOCK TABLES `my_wechat_material_comment` WRITE;
/*!40000 ALTER TABLE `my_wechat_material_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_material_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_material_detail`
--

DROP TABLE IF EXISTS `my_wechat_material_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_material_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) DEFAULT '0' COMMENT '素材ID',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `remark` varchar(255) DEFAULT NULL COMMENT '标题',
  `info` text COMMENT '描述',
  `indexpic` varchar(255) DEFAULT NULL COMMENT '形象图',
  `panel_name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `isshowpic` tinyint(3) DEFAULT '0' COMMENT '0-封面不显示在正文，1-显示',
  `iscomment` tinyint(3) DEFAULT '0' COMMENT '评论：0-不允许，1-允许',
  `islink` tinyint(3) DEFAULT '0' COMMENT '0-内容，1-外链',
  `url` varchar(1000) DEFAULT NULL COMMENT '外部链接',
  `ourl` varchar(1000) DEFAULT NULL COMMENT '原文链接',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `hits` int(11) DEFAULT '0' COMMENT '点击量',
  `praise` int(11) NOT NULL DEFAULT '0' COMMENT '赞',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微信-素材-详细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_material_detail`
--

LOCK TABLES `my_wechat_material_detail` WRITE;
/*!40000 ALTER TABLE `my_wechat_material_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_material_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_mp`
--

DROP TABLE IF EXISTS `my_wechat_mp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_mp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '微信后台用户名',
  `userpwd` varchar(255) DEFAULT NULL COMMENT '微信后台密码',
  `panel_edition` tinyint(3) DEFAULT '0' COMMENT '0-微信，1-易信',
  `panel_name` varchar(255) DEFAULT NULL COMMENT '账号名称',
  `panel_username` varchar(255) DEFAULT NULL COMMENT '公众号',
  `panel_originid` varchar(255) DEFAULT NULL COMMENT '原始ID',
  `panel_avatar` varchar(255) DEFAULT NULL COMMENT '公众号头像',
  `panel_fans` int(11) DEFAULT NULL COMMENT '粉丝数量',
  `panel_code` varchar(255) DEFAULT NULL COMMENT '公众号二维码',
  `panel_cert` tinyint(3) DEFAULT '0' COMMENT '是否认证',
  `panel_type` tinyint(3) DEFAULT '0' COMMENT '0-订阅号，1-服务号',
  `panel_fakeid` varchar(255) DEFAULT NULL COMMENT 'fakeid',
  `provinceid` int(11) DEFAULT '0',
  `cityid` int(11) DEFAULT '0',
  `districtid` int(11) DEFAULT '0',
  `proname` varchar(255) DEFAULT NULL,
  `cityname` varchar(255) DEFAULT NULL,
  `disname` varchar(255) DEFAULT NULL,
  `app_secret` varchar(255) DEFAULT NULL COMMENT '微信-App Secret',
  `app_id` varchar(255) DEFAULT NULL COMMENT '微信-App Id',
  `app_token` varchar(255) DEFAULT NULL COMMENT '微信-token',
  `app_mchid` varchar(255) DEFAULT NULL COMMENT '微信-支付-受理商ID，身份标识',
  `app_key` varchar(255) DEFAULT NULL COMMENT '微信-支付-商户支付密钥Key',
  `app_aeskey` varchar(255) DEFAULT NULL COMMENT '微信-消息加密密钥',
  `app_url` varchar(255) DEFAULT NULL COMMENT '公众号接口地址',
  `is_default` tinyint(3) DEFAULT '0' COMMENT '是否默认账号：0-否，1-是',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `addip` varchar(50) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0',
  `ourl` varchar(1000) DEFAULT NULL COMMENT '原文链接',
  `couponid` int(11) DEFAULT '0' COMMENT '关注返券类型',
  `quesjson` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='平台用户添加的公众号表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_mp`
--

LOCK TABLES `my_wechat_mp` WRITE;
/*!40000 ALTER TABLE `my_wechat_mp` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_mp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wechat_setting`
--

DROP TABLE IF EXISTS `my_wechat_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wechat_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL COMMENT '接收通知的邮箱',
  `mobile` varchar(255) DEFAULT NULL COMMENT '接收通知的手机号',
  `app_response` tinyint(3) DEFAULT '0' COMMENT '响应方式：0-图文，1-链接',
  `app_title` varchar(255) DEFAULT NULL COMMENT '图文标题',
  `app_intro` varchar(255) DEFAULT NULL COMMENT '图文简介',
  `app_cover` varchar(255) DEFAULT NULL COMMENT '图文封面',
  `menu` varchar(10240) DEFAULT NULL COMMENT '用一个字段来存-微信自定义菜单',
  `items` varchar(10240) DEFAULT NULL COMMENT '用一个字段来存-微信自定义菜单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='应用-基础设置表*';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wechat_setting`
--

LOCK TABLES `my_wechat_setting` WRITE;
/*!40000 ALTER TABLE `my_wechat_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wechat_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wheel_activity`
--

DROP TABLE IF EXISTS `my_wheel_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wheel_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '活动名称',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `timefrom` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `timeto` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `times` int(11) DEFAULT '0' COMMENT '允许中奖次数',
  `issubscribe` tinyint(3) DEFAULT '0' COMMENT '是否关注才能参与',
  `iseveryday` tinyint(3) DEFAULT '0' COMMENT '是否允许每天抽奖',
  `remark` varchar(255) DEFAULT NULL COMMENT '活动描述',
  `content` text COMMENT '兑奖说明',
  `pwd` varchar(255) DEFAULT NULL COMMENT '兑奖密码',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享获得次数',
  `sharelogo` varchar(255) DEFAULT NULL COMMENT '分享显示的图片',
  `sharetitle` varchar(255) DEFAULT NULL COMMENT '分享显示的标题',
  `shareintro` varchar(255) DEFAULT NULL COMMENT '分享显示的描述',
  `shareurl` varchar(255) DEFAULT NULL COMMENT '分享URL，不填写则为当前页面',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '0-未开启，1-开启',
  `isdefault` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='抽奖活动';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wheel_activity`
--

LOCK TABLES `my_wheel_activity` WRITE;
/*!40000 ALTER TABLE `my_wheel_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wheel_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wheel_history`
--

DROP TABLE IF EXISTS `my_wheel_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wheel_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `num` int(11) DEFAULT '0' COMMENT '抽奖次数',
  `date` date DEFAULT NULL COMMENT '抽奖日期',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='抽奖参与记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wheel_history`
--

LOCK TABLES `my_wheel_history` WRITE;
/*!40000 ALTER TABLE `my_wheel_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wheel_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wheel_prize`
--

DROP TABLE IF EXISTS `my_wheel_prize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wheel_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `showtitle` varchar(255) DEFAULT NULL COMMENT '奖项名称',
  `indexpic` varchar(255) NOT NULL DEFAULT '' COMMENT '形象图',
  `type` tinyint(3) DEFAULT '1' COMMENT '奖品类型：0-实物，1-红包，2-余额，3-积分',
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `probability` decimal(10,2) DEFAULT '0.00' COMMENT '中奖概率',
  `content` text COMMENT '兑奖说明',
  `num` int(11) DEFAULT '0' COMMENT '奖品数量',
  `getnum` int(11) DEFAULT '0' COMMENT '奖品已抽取数量',
  `shareurl` varchar(255) DEFAULT NULL COMMENT '分享URL，不填写则为当前页面',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '0-未开启，1-开启',
  `sort` int(11) DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='抽奖奖品';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wheel_prize`
--

LOCK TABLES `my_wheel_prize` WRITE;
/*!40000 ALTER TABLE `my_wheel_prize` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wheel_prize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wheel_record`
--

DROP TABLE IF EXISTS `my_wheel_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wheel_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actid` int(11) DEFAULT '0' COMMENT '活动ID',
  `memberid` int(11) DEFAULT '0' COMMENT '会员ID',
  `sn` varchar(255) NOT NULL DEFAULT '' COMMENT '兑换码',
  `prize` varchar(1024) NOT NULL DEFAULT '' COMMENT '奖品信息',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(3) DEFAULT '1' COMMENT '领取状态：0-未领取，1-已领取，2-放弃',
  `type` int(11) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `gettime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='抽奖中奖记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wheel_record`
--

LOCK TABLES `my_wheel_record` WRITE;
/*!40000 ALTER TABLE `my_wheel_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wheel_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_wheel_share`
--

DROP TABLE IF EXISTS `my_wheel_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_wheel_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `memberid` int(11) DEFAULT NULL,
  `actid` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_wheel_share`
--

LOCK TABLES `my_wheel_share` WRITE;
/*!40000 ALTER TABLE `my_wheel_share` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_wheel_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_work`
--

DROP TABLE IF EXISTS `my_work`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staffid` int(11) DEFAULT '0' COMMENT '员工id',
  `content` text COMMENT '内容、',
  `date` date DEFAULT NULL COMMENT '日期',
  `status` int(11) DEFAULT '0' COMMENT '0-执行中，1-已完成，2-未完成，3-不能完成',
  `remark` text COMMENT '备注',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `notice` int(11) DEFAULT '0' COMMENT '是否到期提醒',
  `type` int(11) DEFAULT '1' COMMENT '1-短期工作计划，2-周工作计划，3-月工作计划',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='工作计划安排表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_work`
--

LOCK TABLES `my_work` WRITE;
/*!40000 ALTER TABLE `my_work` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_work` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_work_comment`
--

DROP TABLE IF EXISTS `my_work_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_work_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) DEFAULT '0' COMMENT '工作记录id',
  `staffid` int(11) DEFAULT '0' COMMENT '评论id',
  `content` text COMMENT '评论内容',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='工作评论';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_work_comment`
--

LOCK TABLES `my_work_comment` WRITE;
/*!40000 ALTER TABLE `my_work_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_work_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `my_work_praise`
--

DROP TABLE IF EXISTS `my_work_praise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `my_work_praise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workid` int(11) DEFAULT '0',
  `staffid` int(11) DEFAULT '0',
  `addtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='工作记录点赞表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `my_work_praise`
--

LOCK TABLES `my_work_praise` WRITE;
/*!40000 ALTER TABLE `my_work_praise` DISABLE KEYS */;
/*!40000 ALTER TABLE `my_work_praise` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-15  3:30:07
