<?php
namespace App\Http\Response;


interface TransformInterface
{
    public function transform($responseData);
}
?>