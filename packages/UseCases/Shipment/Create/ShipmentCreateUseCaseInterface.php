<?php
namespace packages\UseCases\Shipment\Create;

interface ShipmentCreateUseCaseInterface
{
    /**
     * @param ShipmentCreateRequest $request
     * @return ShipmentCreateResponse
     */
    public function handle(ShipmentCreateRequest $request);
}
