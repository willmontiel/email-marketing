/*
Navicat MySQL Data Transfer

Source Server         : emarketing_db
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : emarketing_db

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-03-12 10:38:41
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `action`
-- ----------------------------
DROP TABLE IF EXISTS `action`;
CREATE TABLE `action` (
  `idAction` int(11) NOT NULL AUTO_INCREMENT,
  `idResource` int(11) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idAction`),
  KEY `idResource` (`idResource`) USING BTREE,
  CONSTRAINT `action_ibfk_2` FOREIGN KEY (`idResource`) REFERENCES `resource` (`idResource`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of action
-- ----------------------------
INSERT INTO `action` VALUES ('35', '4', 'create');
INSERT INTO `action` VALUES ('36', '4', 'read');
INSERT INTO `action` VALUES ('37', '4', 'update');
INSERT INTO `action` VALUES ('38', '4', 'delete');
INSERT INTO `action` VALUES ('39', '6', 'create');
INSERT INTO `action` VALUES ('40', '6', 'read');
INSERT INTO `action` VALUES ('41', '6', 'update');
INSERT INTO `action` VALUES ('42', '6', 'delete');
INSERT INTO `action` VALUES ('43', '4', 'login on any account');
INSERT INTO `action` VALUES ('44', '10', 'read');
INSERT INTO `action` VALUES ('45', '2', 'read');
INSERT INTO `action` VALUES ('46', '1', 'read');
INSERT INTO `action` VALUES ('47', '3', 'create');
INSERT INTO `action` VALUES ('48', '3', 'read');
INSERT INTO `action` VALUES ('49', '3', 'update');
INSERT INTO `action` VALUES ('50', '3', 'delete');
INSERT INTO `action` VALUES ('51', '1', 'create');
INSERT INTO `action` VALUES ('52', '1', 'update');
INSERT INTO `action` VALUES ('53', '1', 'delete');
INSERT INTO `action` VALUES ('54', '1', 'importbatch');
INSERT INTO `action` VALUES ('56', '1', 'import');
INSERT INTO `action` VALUES ('57', '1', '(un)subscribe');
INSERT INTO `action` VALUES ('58', '2', 'create');
INSERT INTO `action` VALUES ('59', '2', 'update');
INSERT INTO `action` VALUES ('60', '2', 'delete');
INSERT INTO `action` VALUES ('61', '5', 'create');
INSERT INTO `action` VALUES ('62', '5', 'read');
INSERT INTO `action` VALUES ('64', '5', 'update');
INSERT INTO `action` VALUES ('65', '5', 'delete');
INSERT INTO `action` VALUES ('66', '6', 'login how any user');
INSERT INTO `action` VALUES ('67', '7', 'create');
INSERT INTO `action` VALUES ('68', '7', 'read');
INSERT INTO `action` VALUES ('69', '7', 'update');
INSERT INTO `action` VALUES ('70', '7', 'delete');
INSERT INTO `action` VALUES ('71', '8', 'block email');
INSERT INTO `action` VALUES ('72', '8', 'unblock email');
INSERT INTO `action` VALUES ('73', '8', 'read');
INSERT INTO `action` VALUES ('74', '9', 'read');
INSERT INTO `action` VALUES ('75', '9', 'download');
INSERT INTO `action` VALUES ('76', '11', 'read');
INSERT INTO `action` VALUES ('77', '11', 'create');
INSERT INTO `action` VALUES ('78', '11', 'update');
INSERT INTO `action` VALUES ('79', '11', 'delete');
INSERT INTO `action` VALUES ('80', '11', 'send');
INSERT INTO `action` VALUES ('81', '11', 'clone');
INSERT INTO `action` VALUES ('82', '12', 'create');
INSERT INTO `action` VALUES ('83', '12', 'read');
INSERT INTO `action` VALUES ('84', '12', 'delete');
INSERT INTO `action` VALUES ('85', '12', 'update');
INSERT INTO `action` VALUES ('86', '13', 'read');
INSERT INTO `action` VALUES ('88', '13', 'download');
INSERT INTO `action` VALUES ('90', '14', 'read');
INSERT INTO `action` VALUES ('91', '14', 'create');
INSERT INTO `action` VALUES ('92', '14', 'update');
INSERT INTO `action` VALUES ('93', '14', 'delete');
INSERT INTO `action` VALUES ('94', '15', 'read');
INSERT INTO `action` VALUES ('95', '15', 'create');
INSERT INTO `action` VALUES ('96', '15', 'delete');
INSERT INTO `action` VALUES ('97', '15', 'update');
INSERT INTO `action` VALUES ('98', '16', 'read');
INSERT INTO `action` VALUES ('99', '16', 'create');
INSERT INTO `action` VALUES ('100', '16', 'delete');

-- ----------------------------
-- Table structure for `allowed`
-- ----------------------------
DROP TABLE IF EXISTS `allowed`;
CREATE TABLE `allowed` (
  `idAllowed` int(11) NOT NULL AUTO_INCREMENT,
  `idRole` int(11) NOT NULL,
  `idAction` int(11) NOT NULL,
  PRIMARY KEY (`idAllowed`),
  KEY `idRole` (`idAction`) USING BTREE,
  KEY `idAllow` (`idAction`) USING BTREE,
  KEY `idRole_2` (`idRole`) USING BTREE,
  CONSTRAINT `allowed_ibfk_2` FOREIGN KEY (`idAction`) REFERENCES `action` (`idAction`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `allowed_ibfk_3` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of allowed
-- ----------------------------
INSERT INTO `allowed` VALUES ('1', '1', '35');
INSERT INTO `allowed` VALUES ('2', '1', '36');
INSERT INTO `allowed` VALUES ('3', '1', '37');
INSERT INTO `allowed` VALUES ('4', '1', '38');
INSERT INTO `allowed` VALUES ('5', '1', '39');
INSERT INTO `allowed` VALUES ('6', '1', '40');
INSERT INTO `allowed` VALUES ('34', '1', '41');
INSERT INTO `allowed` VALUES ('36', '1', '42');
INSERT INTO `allowed` VALUES ('37', '1', '43');
INSERT INTO `allowed` VALUES ('38', '1', '44');
INSERT INTO `allowed` VALUES ('39', '1', '45');
INSERT INTO `allowed` VALUES ('40', '1', '46');
INSERT INTO `allowed` VALUES ('41', '1', '47');
INSERT INTO `allowed` VALUES ('42', '1', '48');
INSERT INTO `allowed` VALUES ('43', '1', '49');
INSERT INTO `allowed` VALUES ('44', '1', '50');
INSERT INTO `allowed` VALUES ('46', '1', '51');
INSERT INTO `allowed` VALUES ('48', '1', '52');
INSERT INTO `allowed` VALUES ('49', '1', '53');
INSERT INTO `allowed` VALUES ('50', '1', '54');
INSERT INTO `allowed` VALUES ('52', '1', '56');
INSERT INTO `allowed` VALUES ('53', '1', '57');
INSERT INTO `allowed` VALUES ('54', '1', '58');
INSERT INTO `allowed` VALUES ('55', '1', '59');
INSERT INTO `allowed` VALUES ('56', '1', '60');
INSERT INTO `allowed` VALUES ('57', '1', '61');
INSERT INTO `allowed` VALUES ('58', '1', '62');
INSERT INTO `allowed` VALUES ('61', '1', '64');
INSERT INTO `allowed` VALUES ('62', '1', '65');
INSERT INTO `allowed` VALUES ('63', '1', '66');
INSERT INTO `allowed` VALUES ('64', '1', '67');
INSERT INTO `allowed` VALUES ('65', '1', '68');
INSERT INTO `allowed` VALUES ('66', '1', '69');
INSERT INTO `allowed` VALUES ('67', '1', '70');
INSERT INTO `allowed` VALUES ('68', '1', '71');
INSERT INTO `allowed` VALUES ('69', '1', '72');
INSERT INTO `allowed` VALUES ('70', '1', '73');
INSERT INTO `allowed` VALUES ('71', '1', '74');
INSERT INTO `allowed` VALUES ('72', '1', '75');
INSERT INTO `allowed` VALUES ('112', '1', '76');
INSERT INTO `allowed` VALUES ('113', '1', '77');
INSERT INTO `allowed` VALUES ('114', '1', '78');
INSERT INTO `allowed` VALUES ('115', '1', '79');
INSERT INTO `allowed` VALUES ('116', '1', '80');
INSERT INTO `allowed` VALUES ('118', '1', '81');
INSERT INTO `allowed` VALUES ('119', '1', '82');
INSERT INTO `allowed` VALUES ('120', '1', '83');
INSERT INTO `allowed` VALUES ('121', '1', '84');
INSERT INTO `allowed` VALUES ('122', '1', '85');
INSERT INTO `allowed` VALUES ('123', '1', '86');
INSERT INTO `allowed` VALUES ('125', '1', '88');
INSERT INTO `allowed` VALUES ('127', '1', '90');
INSERT INTO `allowed` VALUES ('128', '1', '91');
INSERT INTO `allowed` VALUES ('130', '1', '92');
INSERT INTO `allowed` VALUES ('131', '1', '93');
INSERT INTO `allowed` VALUES ('132', '1', '94');
INSERT INTO `allowed` VALUES ('133', '1', '95');
INSERT INTO `allowed` VALUES ('134', '1', '96');
INSERT INTO `allowed` VALUES ('135', '1', '97');
INSERT INTO `allowed` VALUES ('136', '2', '46');
INSERT INTO `allowed` VALUES ('137', '2', '51');
INSERT INTO `allowed` VALUES ('138', '2', '52');
INSERT INTO `allowed` VALUES ('139', '2', '53');
INSERT INTO `allowed` VALUES ('140', '2', '54');
INSERT INTO `allowed` VALUES ('141', '2', '56');
INSERT INTO `allowed` VALUES ('142', '2', '57');
INSERT INTO `allowed` VALUES ('143', '2', '45');
INSERT INTO `allowed` VALUES ('144', '2', '58');
INSERT INTO `allowed` VALUES ('145', '2', '59');
INSERT INTO `allowed` VALUES ('146', '2', '60');
INSERT INTO `allowed` VALUES ('147', '2', '47');
INSERT INTO `allowed` VALUES ('148', '2', '48');
INSERT INTO `allowed` VALUES ('149', '2', '49');
INSERT INTO `allowed` VALUES ('150', '2', '50');
INSERT INTO `allowed` VALUES ('156', '2', '61');
INSERT INTO `allowed` VALUES ('157', '2', '62');
INSERT INTO `allowed` VALUES ('158', '2', '64');
INSERT INTO `allowed` VALUES ('159', '2', '65');
INSERT INTO `allowed` VALUES ('160', '2', '39');
INSERT INTO `allowed` VALUES ('161', '2', '40');
INSERT INTO `allowed` VALUES ('162', '2', '41');
INSERT INTO `allowed` VALUES ('163', '2', '42');
INSERT INTO `allowed` VALUES ('164', '2', '66');
INSERT INTO `allowed` VALUES ('165', '2', '67');
INSERT INTO `allowed` VALUES ('166', '2', '68');
INSERT INTO `allowed` VALUES ('167', '2', '69');
INSERT INTO `allowed` VALUES ('168', '2', '70');
INSERT INTO `allowed` VALUES ('169', '2', '71');
INSERT INTO `allowed` VALUES ('170', '2', '72');
INSERT INTO `allowed` VALUES ('171', '2', '73');
INSERT INTO `allowed` VALUES ('172', '2', '74');
INSERT INTO `allowed` VALUES ('173', '2', '75');
INSERT INTO `allowed` VALUES ('174', '2', '44');
INSERT INTO `allowed` VALUES ('175', '2', '76');
INSERT INTO `allowed` VALUES ('176', '2', '77');
INSERT INTO `allowed` VALUES ('177', '2', '78');
INSERT INTO `allowed` VALUES ('178', '2', '79');
INSERT INTO `allowed` VALUES ('179', '2', '80');
INSERT INTO `allowed` VALUES ('180', '2', '81');
INSERT INTO `allowed` VALUES ('181', '2', '82');
INSERT INTO `allowed` VALUES ('182', '2', '83');
INSERT INTO `allowed` VALUES ('183', '2', '84');
INSERT INTO `allowed` VALUES ('184', '2', '85');
INSERT INTO `allowed` VALUES ('185', '2', '86');
INSERT INTO `allowed` VALUES ('187', '2', '88');
INSERT INTO `allowed` VALUES ('189', '2', '90');
INSERT INTO `allowed` VALUES ('190', '2', '91');
INSERT INTO `allowed` VALUES ('191', '2', '92');
INSERT INTO `allowed` VALUES ('192', '2', '93');
INSERT INTO `allowed` VALUES ('193', '2', '94');
INSERT INTO `allowed` VALUES ('194', '2', '95');
INSERT INTO `allowed` VALUES ('195', '2', '96');
INSERT INTO `allowed` VALUES ('196', '2', '97');
INSERT INTO `allowed` VALUES ('199', '3', '46');
INSERT INTO `allowed` VALUES ('200', '3', '51');
INSERT INTO `allowed` VALUES ('201', '3', '52');
INSERT INTO `allowed` VALUES ('202', '3', '53');
INSERT INTO `allowed` VALUES ('203', '3', '54');
INSERT INTO `allowed` VALUES ('204', '3', '56');
INSERT INTO `allowed` VALUES ('205', '3', '57');
INSERT INTO `allowed` VALUES ('206', '3', '45');
INSERT INTO `allowed` VALUES ('207', '3', '58');
INSERT INTO `allowed` VALUES ('208', '3', '59');
INSERT INTO `allowed` VALUES ('209', '3', '60');
INSERT INTO `allowed` VALUES ('210', '3', '47');
INSERT INTO `allowed` VALUES ('211', '3', '48');
INSERT INTO `allowed` VALUES ('212', '3', '49');
INSERT INTO `allowed` VALUES ('213', '3', '50');
INSERT INTO `allowed` VALUES ('219', '3', '61');
INSERT INTO `allowed` VALUES ('220', '3', '62');
INSERT INTO `allowed` VALUES ('221', '3', '64');
INSERT INTO `allowed` VALUES ('222', '3', '65');
INSERT INTO `allowed` VALUES ('228', '3', '67');
INSERT INTO `allowed` VALUES ('229', '3', '68');
INSERT INTO `allowed` VALUES ('230', '3', '69');
INSERT INTO `allowed` VALUES ('231', '3', '70');
INSERT INTO `allowed` VALUES ('232', '3', '71');
INSERT INTO `allowed` VALUES ('233', '3', '72');
INSERT INTO `allowed` VALUES ('234', '3', '73');
INSERT INTO `allowed` VALUES ('235', '3', '74');
INSERT INTO `allowed` VALUES ('236', '3', '75');
INSERT INTO `allowed` VALUES ('237', '3', '44');
INSERT INTO `allowed` VALUES ('238', '3', '76');
INSERT INTO `allowed` VALUES ('239', '3', '77');
INSERT INTO `allowed` VALUES ('240', '3', '78');
INSERT INTO `allowed` VALUES ('241', '3', '79');
INSERT INTO `allowed` VALUES ('242', '3', '80');
INSERT INTO `allowed` VALUES ('243', '3', '81');
INSERT INTO `allowed` VALUES ('244', '3', '82');
INSERT INTO `allowed` VALUES ('245', '3', '83');
INSERT INTO `allowed` VALUES ('246', '3', '84');
INSERT INTO `allowed` VALUES ('247', '3', '85');
INSERT INTO `allowed` VALUES ('248', '3', '86');
INSERT INTO `allowed` VALUES ('250', '3', '88');
INSERT INTO `allowed` VALUES ('252', '3', '90');
INSERT INTO `allowed` VALUES ('253', '3', '91');
INSERT INTO `allowed` VALUES ('254', '3', '92');
INSERT INTO `allowed` VALUES ('255', '3', '93');
INSERT INTO `allowed` VALUES ('256', '3', '94');
INSERT INTO `allowed` VALUES ('257', '3', '95');
INSERT INTO `allowed` VALUES ('258', '3', '96');
INSERT INTO `allowed` VALUES ('259', '3', '97');
INSERT INTO `allowed` VALUES ('262', '1', '98');
INSERT INTO `allowed` VALUES ('263', '1', '99');
INSERT INTO `allowed` VALUES ('264', '1', '100');
INSERT INTO `allowed` VALUES ('265', '2', '98');
INSERT INTO `allowed` VALUES ('266', '2', '99');
INSERT INTO `allowed` VALUES ('267', '2', '100');
INSERT INTO `allowed` VALUES ('268', '3', '98');
INSERT INTO `allowed` VALUES ('269', '2', '99');
INSERT INTO `allowed` VALUES ('270', '2', '100');

-- ----------------------------
-- Table structure for `resource`
-- ----------------------------
DROP TABLE IF EXISTS `resource`;
CREATE TABLE `resource` (
  `idResource` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idResource`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of resource
-- ----------------------------
INSERT INTO `resource` VALUES ('1', 'contact');
INSERT INTO `resource` VALUES ('2', 'contactlist');
INSERT INTO `resource` VALUES ('3', 'dbase');
INSERT INTO `resource` VALUES ('4', 'account');
INSERT INTO `resource` VALUES ('5', 'customfield');
INSERT INTO `resource` VALUES ('6', 'user');
INSERT INTO `resource` VALUES ('7', 'segment');
INSERT INTO `resource` VALUES ('8', 'blockemail');
INSERT INTO `resource` VALUES ('9', 'process');
INSERT INTO `resource` VALUES ('10', 'dashboard');
INSERT INTO `resource` VALUES ('11', 'mail');
INSERT INTO `resource` VALUES ('12', 'template');
INSERT INTO `resource` VALUES ('13', 'statistic');
INSERT INTO `resource` VALUES ('14', 'flashmessage');
INSERT INTO `resource` VALUES ('15', 'form');
INSERT INTO `resource` VALUES ('16', 'socialmedia');

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', 'ROLE_SUDO');
INSERT INTO `role` VALUES ('2', 'ROLE_ADMIN');
INSERT INTO `role` VALUES ('3', 'ROLE_USER');
