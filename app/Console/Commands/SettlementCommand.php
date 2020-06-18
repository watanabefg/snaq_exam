<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use packages\UseCases\Settlement\Create\SettlementCreateRequest;
use packages\UseCases\Settlement\Create\SettlementCreateInteractor;

class SettlementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:setSettlementdate {shipmentdate}';

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
        $shipment_date = $this->argument("shipmentdate");
        $request = new SettlementCreateRequest($shipment_date);

        // NOTE:コマンドラインからだとおそらく具象クラスを呼び出すしかないので厳密なクリーンアーキテクチャではない
        $interactor = new SettlementCreateInteractor();
        
        $response = $interactor->handle($request);
        $message = $response->getCreatedSettlementDate();
        $this->line("決済日について");
        $this->line($message);
    }

}
