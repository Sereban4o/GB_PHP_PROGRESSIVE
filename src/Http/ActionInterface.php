<?php
namespace GeekBrains\Http;
use GeekBrains\Http\Request;
use GeekBrains\Http\Response;
interface ActionInterface
{
    public function handle(Request $request): Response;
}
