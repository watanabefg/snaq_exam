<?php
namespace packages\UseCases\Settlement\Create;

interface SettlementCreateUseCaseInterface
{
    /**
     * @param SettlementCreateRequest $request
     * @return SettlementCreateResponse
     */
    public function handle(SettlementCreateRequest $request);
}