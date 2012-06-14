<?php

return array(
	"restaurants" => Kohana::$environment == Kohana::STAGING ? true : false,
	"cafes" => Kohana::$environment == Kohana::STAGING ? true : false,
	"recommendations" => true,
);