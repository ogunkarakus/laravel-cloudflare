<?php

namespace Karakus\Cloudflare\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Karakus\Cloudflare\TrustProxies;

class Reload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudflare:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload trust proxies IPs and store in cache.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $loader = new TrustProxies();

        Cache::forever('cloudflare.proxies', $loader->load());

        $this->info('Cloudflare\'s IP blocks have been reloaded.');
    }
}
