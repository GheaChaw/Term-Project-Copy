<?php
session_start();
session_destroy();
header("Location: /roomease/index.html");
exit();