<?php

namespace Eightbitsnl\GezinsbondPhpClient\Requests\V2\Members;

use Eightbitsnl\GezinsbondPhpClient\Requests\BaseRequest;

class GetIsActive extends BaseRequest
{
    public const HTTP_METHOD = self::HTTP_GET;
    public const URI = "/members/v2/{memberNumber}/isactive";
}
