<?php

use Biohazard\GroupChains;

function groupChains(&...$group) {
    return new GroupChains(...$group);
}