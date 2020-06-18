<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use packages\UseCases\Shipment\Create\ShipmentCreateRequest;
use packages\UseCases\Shipment\Create\ShipmentCreateInteractor;

class ShipmentCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:setShipmentdate {settlementdate}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $settlement_date = $this->argument("settlementdate");
    $request = new ShipmentCreateRequest($settlement_date);

    // NOTE:コマンドラインからだとおそらく具象クラスを呼び出すしかないので厳密なクリーンアーキテクチャではない
    $interactor = new ShipmentCreateInteractor();

    $response = $interactor->handle($request);
    $messages = $response->getCreatedShipmentDate();

    if (count($messages) == 1) {
      $this->error($messages[0]);
    } else {
      $this->line("発送日について");
      $this->line("初回: $messages[0]");
      $this->line("2回目分(2週間後): $messages[1]");
      $this->line("2回目分(4週間後): $messages[2]");
    }
  }
}
