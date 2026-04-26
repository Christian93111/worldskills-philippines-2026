<?php
session_start();
session_destroy();
header('Location: /stii_module_b/login');