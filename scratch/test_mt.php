<?php
$props = 'shared-users="1" address-pool="none" session-timeout="1h" comment="test space"';
preg_match_all('/([^\s=]+)=(".*?"|[^\s"]+)/', $props, $pm, PREG_SET_ORDER);
var_export($pm);
