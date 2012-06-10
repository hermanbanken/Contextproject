<?php

return array(
	"restaurants" => Kohana::$environment == Kohana::STAGING ? false : true,
	"cafes" => Kohana::$environment == Kohana::STAGING ? false : true,
	"recommendations" => true,
);