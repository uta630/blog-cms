<?php

require('function.php');

session_destroy();

header('Location:signin.php');