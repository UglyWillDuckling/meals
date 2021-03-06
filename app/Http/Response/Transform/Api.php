<?php

namespace App\Http\Response\Transform;

use \App\Http\Response\TransformInterface;

class Api implements TransformInterface
{
    public function transform($responseData, $object = true)
    {
//      return json response based on the data given
        return response()->json([
                $responseData
            ],
            200, [
                'Content-Type',
                'application/json'
            ],
            $object ? JSON_FORCE_OBJECT : 0
        );
    }
}
