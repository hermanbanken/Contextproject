<?php

return array(
	"restaurants" => Kohana::$environment == Kohana::STAGING ? true : true,
	"cafes" => Kohana::$environment == Kohana::STAGING ? true : true,
	"recommendations" => true,
);