<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( is_singular() ) { require __DIR__ . '/page.php'; return; }
require __DIR__ . '/home.php';
