use thinkcmf;

CREATE TABLE IF NOT EXISTS `cmf_final_studio` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '工作室id',
  `title` nvarchar(20) NOT NULL DEFAULT '' COMMENT '工作室名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '工作室简介',
  `max_number` int(10) NOT NULL DEFAULT '10' COMMENT '工作室成员的最大数量，默认最多10人',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建工作室用户id',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  hash_key bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '哈希索引的键，优化通过 title 字符串字段查询数据表，由于 MySql 和 PHP 的限制，由PHP生成MD5 8位十六进制字符串（截取32位前8位），再转换成十进制，哈希碰撞由PHP处理；本案例用不上',
  PRIMARY KEY (`id`),
  KEY hash_key (hash_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 工作室表';



CREATE TABLE IF NOT EXISTS `cmf_final_studio_member` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `studio_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'cmf_final_studio 表主键id，工作室主键id',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'cmf_user 表主键id，成员/成员的主键id',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间，即成员的退出时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间，即成员的加入时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY studio_id_key (studio_id),
  KEY user_id_key (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 工作室成员表，存储工作室成员变动记录，与加入申请和退出申请的业务逻辑有区别，为了查询效率和分离业务逻辑，单独建表';



CREATE TABLE IF NOT EXISTS `cmf_final_studio_application_join` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '申请加入的理由',
  `studio_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'cmf_final_studio 表主键id，工作室主键id',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '申请加入的用户id',
  `result` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '处理结果,0:未处理;1:同意;2:不同意',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间，即处理时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间，即申请时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间，没必要，因为一次申请就是一条记录',
  PRIMARY KEY (`id`),
  KEY studio_id_key  (studio_id),
  KEY user_id_key  (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 工作室加入申请表，存储工作室的加入申请记录，即申请加入的历史记录；加入申请和退出申请分开存储，便于以后分离数据库业务逻辑';


CREATE TABLE IF NOT EXISTS `cmf_final_studio_application_exit` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '申请退出的理由',
  `studio_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'cmf_final_studio 表主键id，工作室主键id',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '申请加入的用户id',
  `result` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '处理结果,0:未处理;1:同意;2:不同意',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间，即处理时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间，即申请时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间，没必要，因为一次申请就是一条记录',
  PRIMARY KEY (`id`),
  KEY studio_id_key  (studio_id),
  KEY user_id_key  (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 工作室退出申请表，存储工作室的退出申请记录，即申请退出的历史记录；加入申请和退出申请分开存储，便于以后分离数据库业务逻辑';


CREATE TABLE IF NOT EXISTS `cmf_final_studio_user_extension` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'cmf_user 表主键id',
  `user_extension_type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'cmf_final_studio_user_extension_type 表主键id，由于本案例没有扩展用户注册的业务逻辑，所以这里其实是手动赋值',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY user_id_key  (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 用户扩展表，由于不想动 cmf_user 的业务逻辑，所以使用扩展表的方式';


CREATE TABLE IF NOT EXISTS `cmf_final_studio_user_extension_type` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_extension_type_name` nvarchar(20) NOT NULL DEFAULT '' COMMENT '用户扩展类型名称',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 用户扩展类型表，可以在 cmf_final_studio_user_extension 表写成枚举字段，或者在 PHP 中写成枚举，但是为了方便强类型语言/gRPC，写一个表去对应枚举类型实体；因为数据较少，可以做缓存';

CREATE TABLE IF NOT EXISTS `cmf_final_studio_term` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` nvarchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `start_time` int NOT NULL DEFAULT '' COMMENT '学期开始时间',
  `end_time` int NOT NULL DEFAULT '' COMMENT '学期结束时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEy start_time_key (start_time),
  KEy end_time_key (end_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='final_studio应用 学期时间记录表，由于时间关系，用不上';
