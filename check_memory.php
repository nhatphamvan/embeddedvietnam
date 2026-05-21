<?php
echo "Memory limit: " . ini_get("memory_limit") . "<br>";
echo "Memory usage now: " . memory_get_usage(true) . "<br>";
echo "Memory peak usage: " . memory_get_peak_usage(true) . "<br>";
