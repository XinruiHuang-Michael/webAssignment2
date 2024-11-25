<?php
// 开始会话
session_start();

// 清空会话数据
session_unset();

// 销毁会话
session_destroy();

// 重定向到管理员登录页面
header("Location: ../pages/admin_login.php"); 
exit;
?>