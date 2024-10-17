/*
 Navicat Premium Dump SQL

 Source Server         : vocaladmin
 Source Server Type    : MySQL
 Source Server Version : 50726 (5.7.26-log)
 Source Host           : localhost:3306
 Source Schema         : vocaladmin

 Target Server Type    : MySQL
 Target Server Version : 50726 (5.7.26-log)
 File Encoding         : 65001

 Date: 15/10/2024 16:33:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品名称',
  `product_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品编号',
  `py_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '助记码',
  `unit_id` int(10) NOT NULL COMMENT '产品单位',
  `type_id` int(10) NOT NULL COMMENT '类型',
  `expiry_days` int(10) NULL DEFAULT NULL COMMENT '0无效期',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product
-- ----------------------------

-- ----------------------------
-- Table structure for product_type
-- ----------------------------
DROP TABLE IF EXISTS `product_type`;
CREATE TABLE `product_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品类型' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_type
-- ----------------------------

-- ----------------------------
-- Table structure for product_unit
-- ----------------------------
DROP TABLE IF EXISTS `product_unit`;
CREATE TABLE `product_unit`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '单位名称',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品单位表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of product_unit
-- ----------------------------

-- ----------------------------
-- Table structure for system_admin
-- ----------------------------
DROP TABLE IF EXISTS `system_admin`;
CREATE TABLE `system_admin`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_sys` tinyint(1) NULL DEFAULT 0 COMMENT '是否超级管理员 0-否 1-是',
  `dept_id` int(3) NULL DEFAULT NULL COMMENT '部门ID',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `account` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `salt` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `login_time` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录IP',
  `disable` tinyint(1) NULL DEFAULT 1 COMMENT '是否禁用：1-是；0-否；',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '修改时间',
  `delete_time` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_admin
-- ----------------------------
INSERT INTO `system_admin` VALUES (1, 1, NULL, '超级管理员', '/static/avatar/avatar.jpg', 'admin', 'dcac4aaa9716674f960363f3b420a93a', 'uJdcHa5D', '2024-10-15 16:26:59', '10.36.115.180', 1, '2024-09-24 09:45:14', '2024-10-15 16:26:59', NULL);
INSERT INTO `system_admin` VALUES (2, 0, 1, '威震天', 'http://10.36.115.110:3101/storage/upload/image/20241010/3b760bb8cc28adb5c2396c0f6b2da93e.png', 'megatron', '0224812472fd80c5489f9cc43f799a09', 'IMWMzr', '2024-10-10 17:12:25', '10.36.115.110', 1, '2024-09-26 14:30:19', '2024-10-10 17:12:26', NULL);
INSERT INTO `system_admin` VALUES (6, 0, 2, '测试', '', 'test', 'd6bd43402a98a458d4eb4f0524f99ef3', 'dxWsBXmC', '2024-10-10 14:28:59', '10.36.115.110', 1, '2024-10-09 15:19:00', '2024-10-10 15:07:11', NULL);
INSERT INTO `system_admin` VALUES (7, 0, 2, '123', '', 'admin111', 'e9e0cb7e62c4b16fe3f31d657c520a6d', 'umSXDhr1', NULL, NULL, 0, '2024-10-10 15:29:28', '2024-10-10 16:18:06', NULL);

-- ----------------------------
-- Table structure for system_admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `system_admin_dept`;
CREATE TABLE `system_admin_dept`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pid` bigint(20) NULL DEFAULT NULL COMMENT '上级部门ID',
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `leader` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '负责人',
  `mobile` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '部门状态（0停用 1正常）',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_admin_dept
-- ----------------------------

-- ----------------------------
-- Table structure for system_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `system_admin_role`;
CREATE TABLE `system_admin_role`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `desc` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色描述',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `menu_ids` json NULL,
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_admin_role
-- ----------------------------
INSERT INTO `system_admin_role` VALUES (1, '超级管理员', '所有权限', 0, '[\"*\"]', '2024-09-23 16:16:40', '2024-09-23 16:16:43', NULL);
INSERT INTO `system_admin_role` VALUES (2, '游客', '无权限', 1, '[1, 2, 3, 6, 9]', '2024-09-24 17:58:40', '2024-10-10 11:14:30', NULL);
INSERT INTO `system_admin_role` VALUES (3, 'ooaa', '', 0, '[1, 2]', '2024-09-26 17:26:54', '2024-09-26 17:27:54', '2024-10-10 14:29:52');
INSERT INTO `system_admin_role` VALUES (4, '测试', '测试', 0, '[1, 2, 3, 6, 9, 10, 11]', '2024-10-10 11:40:44', '2024-10-10 16:17:57', NULL);

-- ----------------------------
-- Table structure for system_admin_role_relation
-- ----------------------------
DROP TABLE IF EXISTS `system_admin_role_relation`;
CREATE TABLE `system_admin_role_relation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '管理员ID',
  `role_id` int(10) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户关联关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_admin_role_relation
-- ----------------------------
INSERT INTO `system_admin_role_relation` VALUES (1, 1, 1);
INSERT INTO `system_admin_role_relation` VALUES (17, 6, 2);
INSERT INTO `system_admin_role_relation` VALUES (26, 7, 4);
INSERT INTO `system_admin_role_relation` VALUES (27, 2, 2);

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '类型',
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '值',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (1, 'admin_login', 'login_restrictions', '1', '登录限制 1 开启 0 关闭', '2024-09-23 16:03:28', NULL);
INSERT INTO `system_config` VALUES (2, 'admin_login', 'password_error_count', '5', '密码输入错误次数', '2024-09-23 16:04:39', NULL);
INSERT INTO `system_config` VALUES (3, 'admin_login', 'limit_login_time', '30', '多少分钟内禁止登录', '2024-09-23 16:05:48', NULL);

-- ----------------------------
-- Table structure for system_dict
-- ----------------------------
DROP TABLE IF EXISTS `system_dict`;
CREATE TABLE `system_dict`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字典名称',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型',
  `create_op` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `update_op` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字典名称' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_dict
-- ----------------------------
INSERT INTO `system_dict` VALUES (1, '单位', NULL, 'basic', NULL, NULL, '2024-09-20 16:28:42', NULL, NULL);

-- ----------------------------
-- Table structure for system_dict_value
-- ----------------------------
DROP TABLE IF EXISTS `system_dict_value`;
CREATE TABLE `system_dict_value`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dict_id` int(11) NOT NULL COMMENT '字典id',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '字典值',
  `create_op` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `update_op` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  `delete_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '字典值' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_dict_value
-- ----------------------------
INSERT INTO `system_dict_value` VALUES (1, 1, '个', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `system_dict_value` VALUES (2, 1, '件', NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT 0 COMMENT '上级菜单',
  `type` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限类型:M=菜单，B=按钮',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规则唯一标识',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单图标',
  `sort` int(10) NULL DEFAULT NULL COMMENT '排序',
  `perms` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限标识（后端）',
  `path` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由地址',
  `component` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '前端组件',
  `is_show` tinyint(1) NULL DEFAULT 1 COMMENT '是否显示: 0=隐藏, 1=展示',
  `is_disable` tinyint(1) NULL DEFAULT 1 COMMENT '是否禁用: 0=禁用, 1=正常',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (1, 0, 'M', 'dashboard', '仪表盘', 'material-symbols:dashboard-outline', 0, '', '/dashboard', NULL, 1, 1, '2024-09-24 15:51:54', '2024-10-09 16:32:16');
INSERT INTO `system_menu` VALUES (2, 1, 'M', 'workbench', '控制台', 'icon-park-outline:workbench', 0, 'index/index', '/dashboard/workbench', 'workbench/workbenchView.vue', 1, 1, '2024-09-24 15:57:09', '2024-10-09 16:35:50');
INSERT INTO `system_menu` VALUES (3, 0, 'M', 'auth', '权限管理', 'fluent-mdl2:permissions-solid', 1, NULL, '/auth', NULL, 1, 1, '2024-09-29 08:34:39', '2024-10-09 16:35:56');
INSERT INTO `system_menu` VALUES (4, 3, 'M', 'menu', '菜单管理', 'gala:menu-left', 3, 'index/index', '/auth/menu', 'auth/menu/indexView.vue', 1, 1, '2024-09-29 08:38:01', '2024-10-10 10:44:16');
INSERT INTO `system_menu` VALUES (5, 3, 'M', 'role', '角色管理', 'carbon:user-role', 2, NULL, '/auth/role', 'auth/role/indexView.vue', 1, 1, '2024-09-29 08:57:05', '2024-10-10 10:44:12');
INSERT INTO `system_menu` VALUES (6, 3, 'M', 'admin', '账户管理', 'grommet-icons:user-admin', 0, NULL, '/auth/admin', 'auth/admin/indexView.vue', 1, 1, '2024-09-29 09:00:47', '2024-10-10 10:45:13');
INSERT INTO `system_menu` VALUES (7, 4, 'B', 'menuAdd', '新增', NULL, 0, NULL, NULL, NULL, 1, 1, '2024-09-29 09:46:07', '2024-10-10 10:32:56');
INSERT INTO `system_menu` VALUES (9, 6, 'B', 'adminAdd', '新增', NULL, 0, 'system.admin/add', NULL, NULL, 1, 1, '2024-10-08 14:42:45', '2024-10-10 10:34:41');
INSERT INTO `system_menu` VALUES (10, 6, 'B', 'adminEdit', '编辑', NULL, 0, 'system.admin/edit', NULL, NULL, 1, 1, '2024-10-08 14:43:15', '2024-10-10 10:34:40');
INSERT INTO `system_menu` VALUES (11, 6, 'B', 'adminDel', '删除', NULL, 0, 'system.admin/delete', NULL, NULL, 1, 1, '2024-10-08 14:43:43', '2024-10-10 10:34:38');
INSERT INTO `system_menu` VALUES (13, 3, 'M', 'dept', '部门管理', 'arcticons:depth-lab', 1, '', '/auth/dept', 'auth/dept/indexView.vue', 1, 1, '2024-10-09 17:24:09', '2024-10-10 10:44:05');
INSERT INTO `system_menu` VALUES (14, 4, 'B', 'menuEdit', '编辑', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:02', '2024-10-10 10:34:15');
INSERT INTO `system_menu` VALUES (15, 4, 'B', 'menuDel', '删除', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:30', '2024-10-10 10:34:30');
INSERT INTO `system_menu` VALUES (16, 13, 'B', 'deptAdd', '新增', NULL, 0, NULL, NULL, NULL, 1, 1, '2024-09-29 09:46:07', '2024-10-10 10:32:56');
INSERT INTO `system_menu` VALUES (17, 13, 'B', 'deptEdit', '编辑', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:02', '2024-10-10 10:34:15');
INSERT INTO `system_menu` VALUES (18, 13, 'B', 'deptDel', '删除', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:30', '2024-10-10 10:34:30');
INSERT INTO `system_menu` VALUES (19, 5, 'B', 'roleAdd', '新增', NULL, 0, NULL, NULL, NULL, 1, 1, '2024-09-29 09:46:07', '2024-10-10 10:32:56');
INSERT INTO `system_menu` VALUES (20, 5, 'B', 'roleEdit', '编辑', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:02', '2024-10-10 10:34:15');
INSERT INTO `system_menu` VALUES (21, 5, 'B', 'roleDel', '删除', '', 0, '', '', '', 1, 1, '2024-10-10 10:34:30', '2024-10-10 10:34:30');
INSERT INTO `system_menu` VALUES (22, 0, 'M', 'system', '系统设置', 'hugeicons:system-update-02', 999, '', '/system', '', 1, 1, '2024-10-10 10:50:18', '2024-10-10 10:50:18');
INSERT INTO `system_menu` VALUES (23, 22, 'M', 'system-config', '参数配置', 'icon-park-outline:config', 0, 'system-config', '/system/config', 'system/config/indexView.vue', 1, 1, '2024-10-10 10:51:34', '2024-10-10 10:52:30');

SET FOREIGN_KEY_CHECKS = 1;
